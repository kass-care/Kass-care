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

        if (
            $facilityId &&
            Schema::hasColumn('caregivers', 'facility_id') &&
            !empty($user->email) &&
            Schema::hasColumn('caregivers', 'email')
        ) {
            $byEmail = (clone $query)
                ->where('facility_id', $facilityId)
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

        if (
            $facilityId &&
            Schema::hasColumn('caregivers', 'facility_id') &&
            Schema::hasColumn('caregivers', 'name')
        ) {
            $byName = (clone $query)
                ->where('facility_id', $facilityId)
                ->where('name', $user->name)
                ->first();

            if ($byName) {
                if (Schema::hasColumn('caregivers', 'user_id') && empty($byName->user_id)) {
                    $byName->user_id = $user->id;
                }

                if (
                    Schema::hasColumn('caregivers', 'email') &&
                    empty($byName->email) &&
                    !empty($user->email)
                ) {
                    $byName->email = $user->email;
                }

                $byName->save();

                return $byName;
            }
        }

        if (!empty($user->email) && Schema::hasColumn('caregivers', 'email')) {
            $byEmail = (clone $query)->where('email', $user->email)->first();
            if ($byEmail) {
                return $byEmail;
            }
        }

        if (Schema::hasColumn('caregivers', 'name')) {
            $byName = (clone $query)->where('name', $user->name)->first();
            if ($byName) {
                return $byName;
            }
        }

        return null;
    }

        public function index()
{
    $caregiver = $this->caregiverRecord();

    if (!$caregiver) {
        return view('caregiver.dashboard', [
            'visits' => collect(),
            'todayVisits' => collect(),
            'activeVisit' => null,
            'assignedClients' => collect(),
            'completedVisitsCount' => 0,
            'pendingVisitsCount' => 0,
        ]);
    }

    $facilityId = $caregiver->facility_id;

    $visitsQuery = Visit::with(['client','caregiver']);
          
        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            $visitsQuery->where('facility_id', $facilityId);
        }

        if (Schema::hasColumn('visits', 'caregiver_id')) {
            $visitsQuery->where('caregiver_id', $caregiver->id);
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

                $clientName = trim(
                    (($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
                );

                if ($clientName === '') {
                    $clientName = $client->name ?? 'Unknown Client';
                }

                return (object) [
                    'id' => $client->id ?? null,
                    'name' => $clientName,
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
