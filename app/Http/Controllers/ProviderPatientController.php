<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Client;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
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

    private function patientModelClass(): string
    {
        return class_exists(Patient::class) ? Patient::class : Client::class;
    }

    private function patientBaseQuery()
    {
        $modelClass = $this->patientModelClass();
        $query = $modelClass::query();

        $facilityId = $this->currentFacilityId();
        if ($facilityId && Schema::hasColumn($query->getModel()->getTable(), 'facility_id')) {
            $query->where('facility_id', $facilityId);
        }

        return $query;
    }

    private function patientDisplayName($patient): string
    {
        $firstName = $patient->first_name ?? null;
        $lastName = $patient->last_name ?? null;
        $fullName = trim(($firstName ? $firstName . ' ' : '') . ($lastName ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        if (!empty($patient->name)) {
            return $patient->name;
        }

        return 'Patient';
    }

    public function index($id)
    {
        $patient = $this->patientBaseQuery()->findOrFail($id);

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

        $alertCount = 0;

        if (!empty($latestVitals['blood_pressure'])) {
            $bp = (string) $latestVitals['blood_pressure'];
            preg_match('/(\d+)\s*\/\s*(\d+)/', $bp, $matches);

            if (!empty($matches[1]) && (int) $matches[1] >= 140) {
                $alertCount++;
            }
        }

        if (!empty($latestVitals['oxygen_saturation']) && is_numeric($latestVitals['oxygen_saturation'])) {
            if ((float) $latestVitals['oxygen_saturation'] < 92) {
                $alertCount++;
            }
        }

        if (!empty($latestVitals['temperature']) && is_numeric($latestVitals['temperature'])) {
            if ((float) $latestVitals['temperature'] >= 100.4) {
                $alertCount++;
            }
        }

        return view('provider.patients.workspace', [
            'patient' => $patient,
            'patientName' => $this->patientDisplayName($patient),
            'visits' => $visits,
            'careLogs' => $careLogs,
            'latestCareLog' => $latestCareLog,
            'latestVitals' => $latestVitals,
            'latestAdls' => $latestAdls,
            'alertCount' => $alertCount,
        ]);
    }

    public function summary($id)
    {
        return $this->index($id);
    }
}
