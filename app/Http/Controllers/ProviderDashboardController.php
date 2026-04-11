<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Client;
use App\Models\Task;
use App\Models\Visit;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $patients = $this->buildRecentPatients($facilityId);
        $dashboard = $this->alertDashboardData($facilityId);

        return view('provider.dashboard', array_merge($dashboard, [
            'patients' => $patients,
        ]));
    }

    public function alerts(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;
        $dashboard = $this->alertDashboardData($facilityId);

        $type = $request->get('type', 'all');
        $flaggedCareLogs = collect($dashboard['flaggedCareLogs'] ?? []);

        $filteredLogs = $flaggedCareLogs->filter(function ($log) use ($type) {
            if ($type === 'all') {
                return true;
            }

            $alerts = collect($log->clinical_alerts ?? [])
                ->pluck('label')
                ->map(fn ($value) => strtolower((string) $value));

            return match ($type) {
                'high_bp'    => $alerts->contains('high bp') || $alerts->contains('borderline bp'),
                'low_oxygen' => $alerts->contains('low oxygen'),
                'fever'      => $alerts->contains('fever') || $alerts->contains('mild temp elevation'),
                'pulse'      => $alerts->contains('high pulse') || $alerts->contains('elevated pulse'),
                default      => true,
            };
        })->values();

        return view('provider.alerts', [
            'type' => $type,
            'careLogs' => $filteredLogs,
            'scheduledCount' => $dashboard['scheduledCount'] ?? 0,
            'completedCount' => $dashboard['completedCount'] ?? 0,
            'inProgressCount' => $dashboard['inProgressCount'] ?? 0,
            'flaggedCount' => $dashboard['flaggedCount'] ?? 0,
            'highBpCount' => $dashboard['highBpCount'] ?? 0,
            'lowOxygenCount' => $dashboard['lowOxygenCount'] ?? 0,
            'feverCount' => $dashboard['feverCount'] ?? 0,
            'pulseIssueCount' => $dashboard['pulseIssueCount'] ?? 0,
        ]);
    }

    public function compliance()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $visits = Visit::with(['client', 'caregiver', 'careLogs', 'providerNote'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->get();

        $missedVisits = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'missed';
        });

        $missingCareLogs = $visits->filter(function ($visit) {
            return $visit->careLogs->isEmpty();
        });

        $missingNotes = $visits->filter(function ($visit) {
            return !$visit->providerNote;
        });

        return view('provider.compliance', [
            'visits' => $visits,
            'missedVisits' => $missedVisits,
            'missingCareLogs' => $missingCareLogs,
            'missingNotes' => $missingNotes,
        ]);
    }

    public function summary()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $visits = Visit::with(['client', 'caregiver', 'careLogs', 'providerNote'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->latest('visit_date')
            ->get();

        $totalVisits = $visits->count();

        $completedVisits = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'completed';
        })->count();

        $scheduledVisits = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'scheduled';
        })->count();

        $missedVisits = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'missed';
        })->count();

        return view('provider.summary', [
            'visits' => $visits,
            'totalVisits' => $totalVisits,
            'completedVisits' => $completedVisits,
            'scheduledVisits' => $scheduledVisits,
            'missedVisits' => $missedVisits,
        ]);
    }

    public function markReviewed($id)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $careLog = CareLog::with('visit')->findOrFail($id);

        if ($facilityId && $careLog->visit && (int) $careLog->visit->facility_id !== (int) $facilityId) {
            abort(403, 'Unauthorized.');
        }

        $careLog->reviewed = true;
        $careLog->reviewed_at = now();
        $careLog->save();

        return back()->with('success', 'Alert marked reviewed successfully.');
    }

    private function buildRecentPatients($facilityId = null)
    {
        $patients = Client::query()
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->with([
                'latestVisit.careLogs' => function ($query) {
                    $query->latest();
                },
            ])
            ->withCount(['diagnoses', 'medications', 'visits'])
            ->latest()
            ->take(10)
            ->get();

        return $patients->map(function ($patient) {
            $latestVisit = $patient->latestVisit;
            $latestCareLog = $latestVisit?->careLogs?->sortByDesc('created_at')->first();

            $alerts = $this->extractClinicalAlerts($latestCareLog);

            $patient->dashboard_alert_count = count($alerts);
            $patient->dashboard_medication_count = $patient->medications_count ?? 0;
            $patient->dashboard_diagnosis_count = $patient->diagnoses_count ?? 0;
            $patient->dashboard_last_visit = $latestVisit?->visit_date;

            return $patient;
        });
    }

    private function alertDashboardData($facilityId = null): array
    {
        $visits = Visit::with(['client', 'caregiver'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->get();

        $scheduledCount = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'scheduled';
        })->count();

        $completedCount = $visits->filter(function ($visit) {
            return strtolower((string) ($visit->status ?? '')) === 'completed';
        })->count();

        $inProgressCount = $visits->filter(function ($visit) {
            $status = strtolower((string) ($visit->status ?? ''));
            return in_array($status, ['in progress', 'in_progress']);
        })->count();

        $careLogs = CareLog::with(['visit.client', 'visit.caregiver'])
            ->when($facilityId, function ($query) use ($facilityId) {
                $query->whereHas('visit', function ($visitQuery) use ($facilityId) {
                    $visitQuery->where('facility_id', $facilityId);
                });
            })
            ->where(function ($query) {
                $query->whereNull('reviewed')
                    ->orWhere('reviewed', false)
                    ->orWhere('reviewed', 0);
            })
            ->latest()
            ->get();

        $flaggedCareLogs = $careLogs->map(function ($careLog) {
            $alerts = $this->extractClinicalAlerts($careLog);

            $riskScore = count($alerts);

            if ($riskScore >= 3) {
                $careLog->risk_level = 'HIGH';
            } elseif ($riskScore >= 2) {
                $careLog->risk_level = 'MODERATE';
            } else {
                $careLog->risk_level = 'LOW';
            }

            $careLog->client = $careLog->visit->client ?? null;
            $careLog->caregiver = $careLog->visit->caregiver ?? null;
            $careLog->client_id = $careLog->visit->client_id ?? null;
            $careLog->clinical_alerts = $alerts;

            return $careLog;
        })->filter(function ($careLog) {
            return !empty($careLog->clinical_alerts);
        })->values();

        foreach ($flaggedCareLogs as $log) {
            $client = $log->visit->client ?? null;

            if (!$client) {
                continue;
            }

            $title = 'Follow up - ' . ($client->name ?? 'Patient');

            Task::firstOrCreate(
                [
                    'visit_id' => $log->visit_id,
                    'title' => $title,
                ],
                [
                    'description' => 'Clinical alert detected from caregiver documentation.',
                    'priority' => 'urgent',
                    'status' => 'open',
                    'assigned_to' => auth()->id(),
                    'created_by' => auth()->id(),
                    'client_id' => $client->id,
                    'facility_id' => $client->facility_id,
                ]
            );
        }

        $highBpCount = $flaggedCareLogs->filter(function ($careLog) {
            return collect($careLog->clinical_alerts)->contains(fn ($alert) => $alert['label'] === 'High BP');
        })->count();

        $lowOxygenCount = $flaggedCareLogs->filter(function ($careLog) {
            return collect($careLog->clinical_alerts)->contains(fn ($alert) => $alert['label'] === 'Low Oxygen');
        })->count();

        $feverCount = $flaggedCareLogs->filter(function ($careLog) {
            return collect($careLog->clinical_alerts)->contains(
                fn ($alert) => in_array($alert['label'], ['Fever', 'Mild Temp Elevation'])
            );
        })->count();

        $pulseIssueCount = $flaggedCareLogs->filter(function ($careLog) {
            return collect($careLog->clinical_alerts)->contains(
                fn ($alert) => in_array($alert['label'], ['High Pulse', 'Elevated Pulse'])
            );
        })->count();

        return [
            'scheduledCount' => $scheduledCount,
            'completedCount' => $completedCount,
            'inProgressCount' => $inProgressCount,
            'flaggedCount' => $flaggedCareLogs->count(),
            'highBpCount' => $highBpCount,
            'lowOxygenCount' => $lowOxygenCount,
            'feverCount' => $feverCount,
            'pulseIssueCount' => $pulseIssueCount,
            'flaggedCareLogs' => $flaggedCareLogs,
        ];
    }

    private function extractClinicalAlerts($careLog): array
    {
        if (!$careLog) {
            return [];
        }

        $vitals = is_array($careLog->vitals) ? $careLog->vitals : [];
        $alerts = [];

        $bp = $vitals['blood_pressure'] ?? $vitals['bp'] ?? null;
        if (!empty($bp) && str_contains((string) $bp, '/')) {
            [$systolic, $diastolic] = array_pad(explode('/', (string) $bp, 2), 2, null);
            $systolic = (int) trim((string) $systolic);
            $diastolic = (int) trim((string) $diastolic);

            if ($systolic >= 140 || $diastolic >= 90) {
                $alerts[] = [
                    'level' => 'red',
                    'label' => 'High BP',
                    'message' => 'Blood pressure is above normal range.',
                ];
            } elseif (($systolic >= 130 && $systolic < 140) || ($diastolic >= 80 && $diastolic < 90)) {
                $alerts[] = [
                    'level' => 'yellow',
                    'label' => 'Borderline BP',
                    'message' => 'Blood pressure is mildly elevated.',
                ];
            }
        }

        $pulse = $vitals['pulse'] ?? null;
        if (!empty($pulse)) {
            $pulse = (int) $pulse;

            if ($pulse < 60 || $pulse > 120) {
                $alerts[] = [
                    'level' => 'purple',
                    'label' => 'High Pulse',
                    'message' => 'Pulse is outside the preferred range.',
                ];
            } elseif ($pulse > 100) {
                $alerts[] = [
                    'level' => 'purple',
                    'label' => 'Elevated Pulse',
                    'message' => 'Pulse is slightly elevated.',
                ];
            }
        }

        $oxygen = $vitals['oxygen'] ?? $vitals['oxygen_saturation'] ?? null;
        if (!empty($oxygen)) {
            $oxygen = (int) $oxygen;

            if ($oxygen < 90) {
                $alerts[] = [
                    'level' => 'red',
                    'label' => 'Low Oxygen',
                    'message' => 'Oxygen saturation is below safe range.',
                ];
            }
        }

        $temperature = $vitals['temperature'] ?? $vitals['temp'] ?? null;
        if (!empty($temperature)) {
            $temperature = (float) $temperature;

            if ($temperature >= 100.4) {
                $alerts[] = [
                    'level' => 'red',
                    'label' => 'Fever',
                    'message' => 'Temperature is above normal range.',
                ];
            } elseif ($temperature >= 99.5) {
                $alerts[] = [
                    'level' => 'yellow',
                    'label' => 'Mild Temp Elevation',
                    'message' => 'Temperature is slightly elevated.',
                ];
            }
        }

        return $alerts;
    }
}
