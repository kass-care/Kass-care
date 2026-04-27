<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\CareLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProviderAlertController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $type = $request->get('type');

        $alertsQuery = Alert::with(['visit.client.facility', 'visit.caregiver', 'visit.provider'])
            ->where('resolved', false);

        if ($type && $type !== 'all') {
            $alertsQuery->where('type', $type);
        }

        $alerts = $alertsQuery
            ->latest('id')
            ->get();

        $alertRows = $alerts->map(function (Alert $alert) {
            $visit = $alert->visit;
            $client = $visit?->client;

            return [
                'alert_id' => $alert->id,
                'client_id' => $alert->client_id ?? $client?->id,
                'client_name' => $client?->name ?? 'Unknown Patient',
                'room' => $client?->room ?? 'N/A',
                'facility_name' => $visit?->facility?->name ?? $client?->facility?->name ?? '-',
                'visit_id' => $alert->visit_id,
                'care_log_id' => null,
                'type' => ucwords(str_replace('_', ' ', $alert->type)),
                'raw_type' => $alert->type,
                'severity' => $alert->severity ?? 'info',
                'message' => $alert->message ?? $alert->title ?? 'Clinical alert requires review.',
                'flagged_at' => $alert->created_at,
            ];
        });

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
            ->groupBy('raw_type')
            ->map(function (Collection $items, $type) {
                return [
                    'type' => ucwords(str_replace('_', ' ', $type)),
                    'raw_type' => $type,
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
        abort_if(!$user, 403, 'Unauthorized.');

        $careLog->update([
            'alerts_reviewed_at' => now(),
            'alerts_reviewed_by' => $user->id,
        ]);

        return redirect()
            ->route('provider.alerts.index')
            ->with('success', 'Care log alert marked as reviewed.');
    }

    public function resolveAlert(Alert $alert)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        if ($user->role !== 'super_admin' && $facilityId) {
            abort_if((int) $alert->facility_id !== (int) $facilityId, 403, 'Unauthorized alert review.');
        }

        $alert->update([
            'resolved' => true,
            'read_at' => now(),
        ]);

        return redirect()
            ->route('provider.alerts.index')
            ->with('success', 'Alert marked as reviewed.');
    }
}
