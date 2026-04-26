<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Client;
use App\Models\ProviderMessage;
use App\Models\ProviderNote;
use App\Models\Visit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class ProviderPatientController extends Controller
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

    private function patientBaseQuery()
    {
        $query = Client::query();
        $facilityId = $this->currentFacilityId();

        if ($facilityId && Schema::hasColumn('clients', 'facility_id')) {
            $query->where('facility_id', $facilityId);
        }

        return $query;
    }

    private function patientDisplayName(Client $patient): string
    {
        if (!empty($patient->name)) {
            return $patient->name;
        }

        $fullName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));

        return $fullName !== '' ? $fullName : 'Patient';
    }

    private function buildAlertCount(array $latestVitals): int
    {
        $alertCount = 0;

        $bp = $latestVitals['blood_pressure'] ?? $latestVitals['bp'] ?? null;
        if (!empty($bp)) {
            preg_match('/(\d+)\s*\/\s*(\d+)/', (string) $bp, $matches);
            if (!empty($matches[1]) && (int) $matches[1] >= 140) {
                $alertCount++;
            }
        }

        $oxygen = $latestVitals['oxygen_saturation'] ?? $latestVitals['oxygen'] ?? null;
        if ($oxygen !== null && $oxygen !== '' && is_numeric($oxygen) && (float) $oxygen < 92) {
            $alertCount++;
        }

        $temperature = $latestVitals['temperature'] ?? $latestVitals['temp'] ?? null;
        if ($temperature !== null && $temperature !== '' && is_numeric($temperature) && (float) $temperature >= 100.4) {
            $alertCount++;
        }

        return $alertCount;
    }

    private function buildSnapshot(Client $patient, $visits): array
    {
        $age = null;

        if (!empty($patient->date_of_birth)) {
            try {
                $age = Carbon::parse($patient->date_of_birth)->age;
            } catch (\Throwable $e) {
                $age = null;
            }
        }

        $lastVisit = $visits->first();

        return [
            'age' => $age,
            'diagnosisCount' => method_exists($patient, 'diagnoses') ? $patient->diagnoses->count() : 0,
            'medicationCount' => method_exists($patient, 'medications') ? $patient->medications->count() : 0,
            'visitCount' => $visits->count(),
            'last_visit' => $lastVisit && !empty($lastVisit->visit_date)
                ? Carbon::parse($lastVisit->visit_date)->format('M d, Y')
                : null,
            'lastVisit' => $lastVisit,
        ];
    }

    private function buildIntelligence(array $latestVitals): array
    {
        $alerts = [];

        $bp = $latestVitals['blood_pressure'] ?? $latestVitals['bp'] ?? null;
        if (!empty($bp)) {
            preg_match('/(\d+)\s*\/\s*(\d+)/', (string) $bp, $matches);
            if (!empty($matches[1]) && (int) $matches[1] >= 140) {
                $alerts[] = 'High blood pressure detected';
            }
        }

        $oxygen = $latestVitals['oxygen_saturation'] ?? $latestVitals['oxygen'] ?? null;
        if ($oxygen !== null && $oxygen !== '' && is_numeric($oxygen) && (float) $oxygen < 92) {
            $alerts[] = 'Low oxygen saturation detected';
        }

        $temperature = $latestVitals['temperature'] ?? $latestVitals['temp'] ?? null;
        if ($temperature !== null && $temperature !== '' && is_numeric($temperature) && (float) $temperature >= 100.4) {
            $alerts[] = 'Elevated temperature detected';
        }

        $riskLevel = 'LOW';

        if (count($alerts) >= 2) {
            $riskLevel = 'HIGH';
        } elseif (count($alerts) === 1) {
            $riskLevel = 'MODERATE';
        }

        return [
            'risk_level' => $riskLevel,
            'alerts' => $alerts,
        ];
    }

    private function patientMessages(Client $patient)
    {
        return ProviderMessage::with(['facility', 'sender', 'provider'])
            ->where('client_id', $patient->id)
            ->latest()
            ->get();
    }

    public function index($id)
    {
        $patient = $this->patientBaseQuery()
            ->with(['facility', 'provider', 'diagnoses', 'medications', 'visits'])
            ->findOrFail($id);

        $visits = Visit::query()
            ->with(['caregiver', 'provider'])
            ->where('client_id', $patient->id)
            ->latest('visit_date')
            ->latest('id')
            ->get();

        $careLogs = CareLog::query()
            ->with(['visit'])
            ->whereHas('visit', function ($query) use ($patient) {
                $query->where('client_id', $patient->id);
            })
            ->latest()
            ->get();

        $latestCareLog = $careLogs->first();
        $latestVitals = is_array($latestCareLog?->vitals) ? $latestCareLog->vitals : [];
        $latestAdls = is_array($latestCareLog?->adls) ? $latestCareLog->adls : [];
        $alertCount = $this->buildAlertCount($latestVitals);

        return view('provider.patients.workspace', [
            'patient' => $patient,
            'patientName' => $this->patientDisplayName($patient),
            'visits' => $visits,
            'careLogs' => $careLogs,
            'providerMessages' => $this->patientMessages($patient),
            'latestCareLog' => $latestCareLog,
            'latestVitals' => $latestVitals,
            'latestAdls' => $latestAdls,
            'alertCount' => $alertCount,
        ]);
    }

    public function summary($id)
    {
        $patient = $this->patientBaseQuery()
            ->with(['facility', 'provider', 'diagnoses', 'medications', 'visits'])
            ->findOrFail($id);

        $visits = Visit::query()
            ->with(['caregiver', 'provider'])
            ->where('client_id', $patient->id)
            ->latest('visit_date')
            ->latest('id')
            ->get();

        $careLogs = CareLog::query()
            ->with(['visit'])
            ->whereHas('visit', function ($query) use ($patient) {
                $query->where('client_id', $patient->id);
            })
            ->latest()
            ->get();

        $providerNotes = ProviderNote::query()
            ->where('client_id', $patient->id)
            ->latest()
            ->get();

        $latestCareLog = $careLogs->first();
        $latestVitals = is_array($latestCareLog?->vitals) ? $latestCareLog->vitals : [];
        $latestAdls = is_array($latestCareLog?->adls) ? $latestCareLog->adls : [];

        return view('provider.patients.summary', [
            'patient' => $patient,
            'patientName' => $this->patientDisplayName($patient),
            'visits' => $visits,
            'careLogs' => $careLogs,
            'providerNotes' => $providerNotes,
            'providerMessages' => $this->patientMessages($patient),
            'latestCareLog' => $latestCareLog,
            'latestVitals' => $latestVitals,
            'latestAdls' => $latestAdls,
            'snapshot' => $this->buildSnapshot($patient, $visits),
            'intelligence' => $this->buildIntelligence($latestVitals),
            'lastVisitDate' => optional($visits->first())->visit_date
                ? Carbon::parse(optional($visits->first())->visit_date)
                : null,
        ]);
    }
}
