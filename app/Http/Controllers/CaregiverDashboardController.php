<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class CaregiverDashboardController extends Controller
{
    private function currentUser()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        return $user;
    }

    private function currentFacilityId(): ?int
    {
        $user = $this->currentUser();

        return session('facility_id') ?? $user->facility_id ?? null;
    }

    private function caregiverRecord(): ?Caregiver
    {
        $user = $this->currentUser();
        $facilityId = $this->currentFacilityId();

        $query = Caregiver::query();

        if (Schema::hasColumn('caregivers', 'user_id')) {
            $byUser = (clone $query)->where('user_id', $user->id)->first();
            if ($byUser) {
                return $byUser;
            }
        }

        if ($facilityId && !empty($user->email) && Schema::hasColumn('caregivers', 'email')) {
            $byEmail = (clone $query)
                ->when(Schema::hasColumn('caregivers', 'facility_id'), fn ($q) => $q->where('facility_id', $facilityId))
                ->where('email', $user->email)
                ->first();

            if ($byEmail) {
                if (Schema::hasColumn('caregivers', 'user_id') && empty($byEmail->user_id)) {
                    $byEmail->user_id = $user->id;
                    $byEmail->save();
                }

                return $byEmail;
            }
        }

        if ($facilityId && Schema::hasColumn('caregivers', 'name')) {
            $byName = (clone $query)
                ->when(Schema::hasColumn('caregivers', 'facility_id'), fn ($q) => $q->where('facility_id', $facilityId))
                ->where('name', $user->name)
                ->first();

            if ($byName) {
                if (Schema::hasColumn('caregivers', 'user_id') && empty($byName->user_id)) {
                    $byName->user_id = $user->id;
                    $byName->save();
                }

                return $byName;
            }
        }

        return null;
    }

    public function index()
    {
        $user = $this->currentUser();
        $caregiver = $this->caregiverRecord();
        $facilityId = $this->currentFacilityId();

        $caregiverIds = [$user->id];

        if ($caregiver) {
            $caregiverIds[] = $caregiver->id;
        }

        $caregiverIds = array_unique(array_filter($caregiverIds));

        $visitsQuery = Visit::with(['client', 'caregiver']);

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            $visitsQuery->where('facility_id', $facilityId);
        }

        if (Schema::hasColumn('visits', 'caregiver_id')) {
            $visitsQuery->whereIn('caregiver_id', $caregiverIds);
        } else {
            $visitsQuery->whereRaw('1 = 0');
        }

        $visits = $visitsQuery
            ->latest('visit_date')
            ->latest('id')
            ->get();

        $todayVisits = $visits->filter(function ($visit) {
            if (!empty($visit->visit_date) && Carbon::parse($visit->visit_date)->isToday()) {
                return true;
            }

            if (!empty($visit->start_time) && Carbon::parse($visit->start_time)->isToday()) {
                return true;
            }

            return false;
        })->values();

        $activeVisit = $visits->first(function ($visit) {
            return in_array(strtolower($visit->status ?? ''), ['in_progress', 'in progress']);
        });

        $assignedClients = $visits
            ->filter(fn ($visit) => !is_null($visit->client))
            ->groupBy('client_id')
            ->map(function ($clientVisits) {
                $latestVisit = $clientVisits->sortByDesc('updated_at')->first();
                $client = $latestVisit->client;

                return (object) [
                    'id' => $client->id ?? null,
                    'name' => $client->name ?? 'Unknown Client',
                    'latest_status' => $latestVisit->status ?? 'N/A',
                    'latest_visit_date' => $latestVisit->visit_date ?? $latestVisit->updated_at,
                    'visit_id' => $latestVisit->id,
                ];
            })
            ->values();

        $completedVisitsCount = $visits->filter(function ($visit) {
            return strtolower($visit->status ?? '') === 'completed';
        })->count();

        $pendingVisitsCount = $visits->filter(function ($visit) {
            return in_array(strtolower($visit->status ?? ''), ['scheduled', 'pending', 'in_progress', 'in progress']);
        })->count();

        return view('caregiver.dashboard', [
            'visits' => $visits,
            'todayVisits' => $todayVisits,
            'activeVisit' => $activeVisit,
            'assignedClients' => $assignedClients,
            'completedVisitsCount' => $completedVisitsCount,
            'pendingVisitsCount' => $pendingVisitsCount,
        ]);
    }
}
