<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Support\AuditLogger;

class ProviderRoundsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?? ($user->facility_id ?? null);

        $clientsQuery = Client::with([
            'facility',
            'visits.careLogs',
            'diagnoses',
            'medications',
        ]);

        if ($facilityId) {
            $clientsQuery->where('facility_id', $facilityId);
        }

        $clients = $clientsQuery->get();

        $rounds = collect();

        foreach ($clients as $client) {
            $lastVisit = $client->visits
                ->sortByDesc(fn ($visit) => optional($visit->visit_date)->timestamp ?? 0)
                ->first();

            $latestCareLog = $client->visits
                ->flatMap(function ($visit) {
                    return $visit->careLogs ?? collect();
                })
                ->sortByDesc(fn ($log) => optional($log->created_at)->timestamp ?? 0)
                ->first();

            $alerts = [];
            $priorityScore = 0;
            $daysSinceVisit = null;
            $complianceStatus = 'no_visits';

            if ($latestCareLog) {
                $vitals = is_array($latestCareLog->vitals ?? null) ? $latestCareLog->vitals : [];

                $bp = $vitals['bp'] ?? $vitals['blood_pressure'] ?? null;
                $pulse = $vitals['pulse'] ?? null;
                $temp = $vitals['temperature'] ?? $vitals['temp'] ?? null;
                $oxygen = $vitals['oxygen'] ?? $vitals['oxygen_saturation'] ?? null;

                $systolic = null;
                $diastolic = null;

                if ($bp && str_contains((string) $bp, '/')) {
                    [$systolic, $diastolic] = array_pad(explode('/', (string) $bp, 2), 2, null);
                    $systolic = is_numeric(trim((string) $systolic)) ? (float) trim((string) $systolic) : null;
                    $diastolic = is_numeric(trim((string) $diastolic)) ? (float) trim((string) $diastolic) : null;
                }

                $pulse = is_numeric((string) $pulse) ? (float) $pulse : null;
                $temp = is_numeric((string) $temp) ? (float) $temp : null;
                $oxygen = is_numeric((string) $oxygen) ? (float) $oxygen : null;

                if (($systolic !== null && $systolic >= 160) || ($diastolic !== null && $diastolic >= 100)) {
                    $alerts[] = 'High blood pressure';
                    $priorityScore += 3;
                }

                if ($oxygen !== null && $oxygen < 90) {
                    $alerts[] = 'Low oxygen';
                    $priorityScore += 3;
                }

                if ($temp !== null && $temp >= 100.4) {
                    $alerts[] = 'Fever';
                    $priorityScore += 2;
                }

                if ($pulse !== null && ($pulse < 50 || $pulse > 110)) {
                    $alerts[] = 'Abnormal pulse';
                    $priorityScore += 1;
                }
            }

            if ($lastVisit && $lastVisit->visit_date) {
                $daysSinceVisit = Carbon::parse($lastVisit->visit_date)
                    ->startOfDay()
                    ->diffInDays(now()->startOfDay());

                if ($daysSinceVisit > 60) {
                    $alerts[] = 'Visit overdue';
                    $priorityScore += 3;
                    $complianceStatus = 'overdue';
                } elseif ($daysSinceVisit >= 46) {
                    $alerts[] = 'Visit due soon';
                    $priorityScore += 1;
                    $complianceStatus = 'due_soon';
                } else {
                    $complianceStatus = 'current';
                }
            } else {
                $alerts[] = 'No visit recorded';
                $priorityScore += 2;
                $complianceStatus = 'no_visits';
            }

            if ($client->medications->count() >= 8) {
                $alerts[] = 'Medication review needed';
                $priorityScore += 1;
            }

            if ($client->diagnoses->count() >= 5) {
                $alerts[] = 'Complex diagnosis profile';
                $priorityScore += 1;
            }

            $priority = 'LOW';

            if ($priorityScore >= 5) {
                $priority = 'HIGH';
            } elseif ($priorityScore >= 2) {
                $priority = 'MODERATE';
            }

            $isRounded = (bool) ($lastVisit?->is_rounded ?? false);

            if (! $isRounded) {
                $rounds->push([
                    'client' => $client,
                    'facility_name' => $client->facility->name ?? 'Unassigned Facility',
                    'priority' => $priority,
                    'priority_score' => $priorityScore,
                    'alerts' => collect($alerts)->unique()->values(),
                    'last_visit' => $lastVisit,
                    'days_since_visit' => $daysSinceVisit,
                    'compliance_status' => $complianceStatus,
                    'is_rounded' => $isRounded,
                ]);
            }
        }

        $groupedRounds = $rounds
            ->sortByDesc('priority_score')
            ->groupBy('facility_name');

        $stats = [
            'facilities' => $groupedRounds->count(),
            'patients' => $rounds->count(),
            'high' => $rounds->where('priority', 'HIGH')->count(),
            'moderate' => $rounds->where('priority', 'MODERATE')->count(),
            'overdue' => $rounds->where('compliance_status', 'overdue')->count(),
            'dueSoon' => $rounds->where('compliance_status', 'due_soon')->count(),
        ];

        return view('provider.rounds.index', compact('groupedRounds', 'stats'));
    }
public function markRounded($visitId = null)
{
    if (!$visitId) {
        return back()->with('error', 'Visit ID missing.');
    }

    $visit = Visit::with('client')->findOrFail($visitId);

    $visit->is_rounded = true;
    $visit->rounded_at = now();
    $visit->save();

    AuditLogger::log(
        action: 'visit_marked_rounded',
        description: 'Provider marked patient as rounded during facility rounds',
        targetType: 'visit',
        targetId: $visit->id,
        clientId: $visit->client_id,
        clientName: optional($visit->client)->name,
        meta: [
            'visit_date' => $visit->visit_date,
            'rounded_at' => now()->toDateTimeString(),
        ]
    );

    return redirect()
        ->route('provider.rounds.index')
        ->with('success', 'Patient marked rounded successfully.');
}

}
