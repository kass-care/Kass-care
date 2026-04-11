<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProviderAlertController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedFacilityId = session('facility_id');
        $effectiveFacilityId = $selectedFacilityId ?: ($user->facility_id ?? null);

        $logsQuery = CareLog::query()
            ->with([
                'visit.client.facility',
                'visit.caregiver',
                'visit.provider',
            ])
            ->whereNull('alerts_reviewed_at');

        if ($user && $user->role === 'super_admin') {
            if ($selectedFacilityId) {
                $logsQuery->whereHas('visit', function ($q) use ($selectedFacilityId) {
                    $q->where('facility_id', $selectedFacilityId);
                });
            }
        } else {
            if ($effectiveFacilityId) {
                $logsQuery->whereHas('visit', function ($q) use ($effectiveFacilityId) {
                    $q->where('facility_id', $effectiveFacilityId);
                });
            }
        }

        $logs = $logsQuery->latest()->get();

        $alertRows = collect();

        foreach ($logs as $log) {
            $visit = $log->visit;
            $client = $visit?->client;

            if (!$visit || !$client) {
                continue;
            }

            $alertsForThisLog = $this->extractAlertsFromLog($log);

            foreach ($alertsForThisLog as $alert) {
                $alertRows->push([
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'room' => $client->room,
                    'facility_name' => $client->facility->name ?? '-',
                    'visit_id' => $visit->id,
                    'care_log_id' => $log->id,
                    'type' => $alert['type'],
                    'severity' => $alert['severity'],
                    'message' => $alert['message'],
                    'flagged_at' => $log->created_at,
                ]);
            }
        }

        $latestPatientAlerts = $alertRows
            ->sortByDesc('flagged_at')
            ->groupBy('client_id')
            ->map(function (Collection $items) {
                return $items->sortByDesc('flagged_at')->first();
            })
            ->values()
            ->sortByDesc('flagged_at');

        $totalActiveAlerts = $alertRows->count();
        $criticalAlerts = $alertRows->where('severity', 'critical')->count();
        $highAlerts = $alertRows->where('severity', 'high')->count();
        $mediumAlerts = $alertRows->where('severity', 'medium')->count();

        $alertTypeSummary = $alertRows
            ->groupBy('type')
            ->map(function (Collection $items, $type) {
                return [
                    'type' => $type,
                    'count' => $items->count(),
                    'critical_count' => $items->where('severity', 'critical')->count(),
                    'high_count' => $items->where('severity', 'high')->count(),
                    'medium_count' => $items->where('severity', 'medium')->count(),
                ];
            })
            ->sortByDesc('count')
            ->values();

        return view('provider.alerts.index', [
            'latestPatientAlerts' => $latestPatientAlerts,
            'totalActiveAlerts' => $totalActiveAlerts,
            'criticalAlerts' => $criticalAlerts,
            'highAlerts' => $highAlerts,
            'mediumAlerts' => $mediumAlerts,
            'alertTypeSummary' => $alertTypeSummary,
        ]);
    }

    public function markReviewed(CareLog $careLog)
    {
        $user = auth()->user();
        $selectedFacilityId = session('facility_id');
        $effectiveFacilityId = $selectedFacilityId ?: ($user->facility_id ?? null);

        if ($user->role !== 'super_admin' && $effectiveFacilityId) {
            abort_if(
                (int) optional($careLog->visit)->facility_id !== (int) $effectiveFacilityId,
                403,
                'Unauthorized alert review.'
            );
        }

        if ($user->role === 'super_admin' && $selectedFacilityId) {
            abort_if(
                (int) optional($careLog->visit)->facility_id !== (int) $selectedFacilityId,
                403,
                'Unauthorized alert review.'
            );
        }

        $careLog->update([
            'alerts_reviewed_at' => now(),
            'alerts_reviewed_by' => $user->id,
        ]);

        return redirect()
            ->route('provider.alerts')
            ->with('success', 'Alert marked as reviewed and removed from active queue.');
    }

    private function extractAlertsFromLog(CareLog $log): array
    {
        $alerts = [];

        if ((bool) ($log->high_bp_alert ?? false)) {
            $alerts[] = [
                'type' => 'High Blood Pressure',
                'severity' => 'high',
                'message' => 'Blood pressure flagged above normal range.',
            ];
        }

        if ((bool) ($log->low_oxygen_alert ?? false)) {
            $alerts[] = [
                'type' => 'Low Oxygen',
                'severity' => 'critical',
                'message' => 'Oxygen saturation flagged below safe range.',
            ];
        }

        if ((bool) ($log->fever_alert ?? false)) {
            $alerts[] = [
                'type' => 'Fever',
                'severity' => 'medium',
                'message' => 'Temperature flagged above expected range.',
            ];
        }

        if ((bool) ($log->low_pulse_alert ?? false)) {
            $alerts[] = [
                'type' => 'Low Pulse',
                'severity' => 'high',
                'message' => 'Pulse flagged below expected range.',
            ];
        }

        if ((bool) ($log->high_pulse_alert ?? false)) {
            $alerts[] = [
                'type' => 'High Pulse',
                'severity' => 'high',
                'message' => 'Pulse flagged above expected range.',
            ];
        }

        return $alerts;
    }
}
