<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Visit;
use Carbon\Carbon;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $caregiver = Caregiver::where('user_id', $user->id)->first();

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

        $visits = Visit::with(['client', 'caregiver'])
            ->where('caregiver_id', $caregiver->id)
            ->when(
                $user->role !== 'super_admin' && !empty($user->facility_id),
                function ($query) use ($user) {
                    $query->where('facility_id', $user->facility_id);
                }
            )
            ->latest()
            ->get();

        $todayVisits = $visits->filter(function ($visit) {
            if (!empty($visit->visit_date) && Carbon::parse($visit->visit_date)->isToday()) {
                return true;
            }

            if (!empty($visit->scheduled_at) && Carbon::parse($visit->scheduled_at)->isToday()) {
                return true;
            }

            return false;
        })->values();

        $activeVisit = $visits->first(function ($visit) {
            return strtolower($visit->status ?? '') === 'in_progress';
        });

        $assignedClients = $visits
            ->filter(fn ($visit) => !is_null($visit->client))
            ->groupBy('client_id')
            ->map(function ($clientVisits) {
                $latestVisit = $clientVisits->sortByDesc('updated_at')->first();

                return (object) [
                    'id' => $latestVisit->client->id ?? null,
                    'name' => $latestVisit->client->name ?? 'Unknown Client',
                    'latest_status' => $latestVisit->status ?? 'N/A',
                    'latest_visit_date' => $latestVisit->visit_date
                        ?? $latestVisit->scheduled_at
                        ?? $latestVisit->updated_at,
                    'visit_id' => $latestVisit->id,
                ];
            })
            ->values();

        $completedVisitsCount = $visits->filter(function ($visit) {
            return strtolower($visit->status ?? '') === 'completed';
        })->count();

        $pendingVisitsCount = $visits->filter(function ($visit) {
            return in_array(strtolower($visit->status ?? ''), ['scheduled', 'pending', 'in_progress']);
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
