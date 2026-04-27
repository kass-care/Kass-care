<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Caregiver;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CareLogController extends Controller
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
                ->when(
                    Schema::hasColumn('caregivers', 'facility_id'),
                    fn ($q) => $q->where('facility_id', $facilityId)
                )
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
                ->when(
                    Schema::hasColumn('caregivers', 'facility_id'),
                    fn ($q) => $q->where('facility_id', $facilityId)
                )
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

    private function caregiverIds(): array
    {
        $user = $this->currentUser();
        $caregiver = $this->caregiverRecord();

        $ids = [$user->id];

        if ($caregiver) {
            $ids[] = $caregiver->id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    private function visitBaseQuery()
    {
        $facilityId = $this->currentFacilityId();

        $query = Visit::query()->with(['client', 'caregiver', 'facility', 'provider']);

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            $query->where('facility_id', $facilityId);
        }

        return $query;
    }

    private function caregiverVisitQuery()
    {
        $query = $this->visitBaseQuery();

        if (Schema::hasColumn('visits', 'caregiver_id')) {
            return $query->whereIn('caregiver_id', $this->caregiverIds());
        }

        return $query->whereRaw('1 = 0');
    }

    private function careLogBaseQuery()
    {
        $facilityId = $this->currentFacilityId();

        $query = CareLog::query()->with(['visit.client', 'visit.caregiver', 'visit.facility', 'visit.provider']);

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            $query->whereHas('visit', function ($visitQuery) use ($facilityId) {
                $visitQuery->where('facility_id', $facilityId);
            });
        }

        return $query;
    }

    private function indexView(): string
    {
        $user = $this->currentUser();

        if (in_array($user->role, ['provider', 'super_admin'], true) && view()->exists('provider.care-logs.index')) {
            return 'provider.care-logs.index';
        }

        return 'caregiver.care-logs.index';
    }

    private function showView(): string
    {
        $user = $this->currentUser();

        if (in_array($user->role, ['provider', 'super_admin'], true) && view()->exists('provider.care-logs.show')) {
            return 'provider.care-logs.show';
        }

        return 'caregiver.care-logs.show';
    }

    private function redirectAfterSave()
    {
        $user = $this->currentUser();

        return match ($user->role) {
            'caregiver' => redirect()->route('caregiver.care-logs.index'),
            'provider', 'super_admin' => redirect()->route('provider.care-logs.index'),
            default => redirect()->back(),
        };
    }

    public function index()
    {
        $user = $this->currentUser();

        if ($user->role === 'caregiver') {
            $caregiverIds = $this->caregiverIds();

            $careLogs = $this->careLogBaseQuery()
                ->whereHas('visit', function ($query) use ($caregiverIds) {
                    $query->whereIn('caregiver_id', $caregiverIds);
                })
                ->latest()
                ->get();

            return view($this->indexView(), compact('careLogs'));
        }

        $careLogs = $this->careLogBaseQuery()
            ->latest()
            ->get();

        return view($this->indexView(), compact('careLogs'));
    }

    public function create(Request $request)
    {
        $user = $this->currentUser();
        $selectedVisit = null;

        if ($user->role === 'caregiver') {
            $visits = $this->caregiverVisitQuery()
                ->orderBy('visit_date')
                ->latest('id')
                ->get();

            if ($request->filled('visit_id')) {
                $selectedVisit = $visits->firstWhere('id', (int) $request->visit_id);
            }

            return view('caregiver.care-logs.create', compact('visits', 'selectedVisit'));
        }

        $visits = $this->visitBaseQuery()
            ->orderBy('visit_date')
            ->latest('id')
            ->get();

        if ($request->filled('visit_id')) {
            $selectedVisit = $visits->firstWhere('id', (int) $request->visit_id);
        }

        return view('provider.care-logs.create', compact('visits', 'selectedVisit'));
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();
        $facilityId = $this->currentFacilityId();

        $validated = $request->validate([
            'visit_id' => ['required', 'exists:visits,id'],
            'adl_status' => ['nullable', 'string'],
            'bathroom_assistance' => ['nullable', 'string'],
            'mobility_support' => ['nullable', 'string'],
            'meal_notes' => ['nullable', 'string'],
            'medication_notes' => ['nullable', 'string'],
            'charting_notes' => ['nullable', 'string'],
            'care_notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string'],
            'pulse' => ['nullable', 'string'],
            'temperature' => ['nullable', 'string'],
            'respiratory_rate' => ['nullable', 'string'],
            'oxygen_saturation' => ['nullable', 'string'],
            'weight' => ['nullable', 'string'],
            'blood_sugar' => ['nullable', 'string'],
            'check_in_time' => ['nullable', 'string'],
            'check_out_time' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
        ]);

        $visit = Visit::query()->findOrFail($validated['visit_id']);

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            abort_if((int) $visit->facility_id !== (int) $facilityId, 403, 'This visit is outside the selected facility.');
        }

        if ($user->role === 'caregiver') {
            abort_if(
                !in_array((int) ($visit->caregiver_id ?? 0), array_map('intval', $this->caregiverIds()), true),
                403,
                'This visit is not assigned to you.'
            );
        }

        $adlPayload = array_filter([
            'adl_status' => $validated['adl_status'] ?? null,
            'bathroom_assistance' => $validated['bathroom_assistance'] ?? null,
            'mobility_support' => $validated['mobility_support'] ?? null,
            'meal_notes' => $validated['meal_notes'] ?? null,
            'medication_notes' => $validated['medication_notes'] ?? null,
            'charting_notes' => $validated['charting_notes'] ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $vitalsPayload = array_filter([
            'blood_pressure' => $validated['blood_pressure'] ?? null,
            'pulse' => $validated['pulse'] ?? null,
            'temperature' => $validated['temperature'] ?? null,
            'respiratory_rate' => $validated['respiratory_rate'] ?? null,
            'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'blood_sugar' => $validated['blood_sugar'] ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $payload = [
            'visit_id' => $visit->id,
            'notes' => $validated['care_notes'] ?? null,
            'adls' => !empty($adlPayload) ? $adlPayload : null,
            'vitals' => !empty($vitalsPayload) ? $vitalsPayload : null,
        ];

        if (Schema::hasColumn('care_logs', 'caregiver_id')) {
            $caregiver = $this->caregiverRecord();
            $payload['caregiver_id'] = $caregiver?->id ?? auth()->id();
        }

        if (Schema::hasColumn('care_logs', 'client_id')) {
            $payload['client_id'] = $visit->client_id;
        }

        if (Schema::hasColumn('care_logs', 'facility_id')) {
            $payload['facility_id'] = $visit->facility_id;
        }

        CareLog::create($payload);

        if (!empty($validated['check_in_time']) && Schema::hasColumn('visits', 'check_in_time')) {
            $visit->check_in_time = $validated['check_in_time'];
        }

        if (!empty($validated['check_out_time']) && Schema::hasColumn('visits', 'check_out_time')) {
            $visit->check_out_time = $validated['check_out_time'];
        }

        if (!empty($validated['latitude']) && Schema::hasColumn('visits', 'check_in_latitude')) {
            $visit->check_in_latitude = $validated['latitude'];
        }

        if (!empty($validated['longitude']) && Schema::hasColumn('visits', 'check_in_longitude')) {
            $visit->check_in_longitude = $validated['longitude'];
        }

        if (!empty($validated['check_in_time']) && empty($validated['check_out_time'])) {
            if (Schema::hasColumn('visits', 'status')) {
                $visit->status = 'in_progress';
            }

            if (Schema::hasColumn('visits', 'visit_started')) {
                $visit->visit_started = true;
            }

            if (Schema::hasColumn('visits', 'visit_started_at')) {
                $visit->visit_started_at = now();
            }
        }

        if (!empty($validated['check_out_time'])) {
            if (Schema::hasColumn('visits', 'status')) {
                $visit->status = 'completed';
            }

            if (Schema::hasColumn('visits', 'visit_completed')) {
                $visit->visit_completed = true;
            }

            if (Schema::hasColumn('visits', 'visit_completed_at')) {
                $visit->visit_completed_at = now();
            }
        }

        $visit->save();

        return $this->redirectAfterSave()
            ->with('success', 'Care log saved successfully.');
    }

    public function show(CareLog $careLog)
    {
        $user = $this->currentUser();
        $facilityId = $this->currentFacilityId();

        $careLog->load(['visit.client', 'visit.caregiver', 'visit.facility', 'visit.provider']);

        if ($facilityId && !empty($careLog->visit?->facility_id)) {
            abort_if((int) $careLog->visit->facility_id !== (int) $facilityId, 403, 'Unauthorized.');
        }

        if ($user->role === 'caregiver') {
            $ownsVisit = in_array(
                (int) ($careLog->visit->caregiver_id ?? 0),
                array_map('intval', $this->caregiverIds()),
                true
            );

            abort_if(!$ownsVisit, 403, 'Unauthorized.');
        }

        return view($this->showView(), compact('careLog'));
    }
}
