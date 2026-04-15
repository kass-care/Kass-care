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
        $caregiver = $this->caregiverRecord();
        $query = $this->visitBaseQuery();

        if (!$caregiver) {
            return $query->whereRaw('1 = 0');
        }

        if (Schema::hasColumn('visits', 'caregiver_id')) {
            $query->where('caregiver_id', $caregiver->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query;
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

        if ($user->role === 'caregiver' && view()->exists('caregiver.care-logs.index')) {
            return 'caregiver.care-logs.index';
        }

        if (in_array($user->role, ['provider', 'super_admin'], true) && view()->exists('provider.care-logs.index')) {
            return 'provider.care-logs.index';
        }

        return 'caregiver.care-logs.index';
    }

    private function showView(): string
    {
        $user = $this->currentUser();

        if ($user->role === 'caregiver' && view()->exists('caregiver.care-logs.show')) {
            return 'caregiver.care-logs.show';
        }

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
            $caregiver = $this->caregiverRecord();
            abort_if(!$caregiver, 403, 'Caregiver profile not found.');

            $careLogs = $this->careLogBaseQuery()
                ->whereHas('visit', function ($query) use ($caregiver) {
                    $query->where('caregiver_id', $caregiver->id);
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
            $caregiver = $this->caregiverRecord();
            abort_if(!$caregiver, 403, 'Caregiver profile not found.');

            $visits = $this->caregiverVisitQuery()
                ->orderBy('visit_date')
                ->get();

            if ($request->filled('visit_id')) {
                $selectedVisit = $visits->firstWhere('id', (int) $request->visit_id);
            }

            return view('care-logs.create', compact('visits', 'selectedVisit'));
        }

        $visits = $this->visitBaseQuery()
            ->orderBy('visit_date')
            ->get();

        if ($request->filled('visit_id')) {
            $selectedVisit = $visits->firstWhere('id', (int) $request->visit_id);
        }

        return view('care-logs.create', compact('visits', 'selectedVisit'));
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();
        $facilityId = $this->currentFacilityId();

        $validated = $request->validate([
            'visit_id'             => ['required', 'exists:visits,id'],
            'adl_status'           => ['nullable', 'string'],
            'bathroom_assistance'  => ['nullable', 'string'],
            'mobility_support'     => ['nullable', 'string'],
            'meal_notes'           => ['nullable', 'string'],
            'medication_notes'     => ['nullable', 'string'],
            'charting_notes'       => ['nullable', 'string'],
            'care_notes'           => ['nullable', 'string'],
            'blood_pressure'       => ['nullable', 'string'],
            'pulse'                => ['nullable', 'string'],
            'temperature'          => ['nullable', 'string'],
            'respiratory_rate'     => ['nullable', 'string'],
            'oxygen_saturation'    => ['nullable', 'string'],
            'weight'               => ['nullable', 'string'],
            'blood_sugar'          => ['nullable', 'string'],
            'check_in_time'        => ['nullable', 'string'],
            'check_out_time'       => ['nullable', 'string'],
            'latitude'             => ['nullable', 'string'],
            'longitude'            => ['nullable', 'string'],
        ]);

        $visit = Visit::query()->findOrFail($validated['visit_id']);

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            abort_if((int) $visit->facility_id !== (int) $facilityId, 403, 'This visit is outside the selected facility.');
        }

        if ($user->role === 'caregiver') {
            $caregiver = $this->caregiverRecord();
            abort_if(!$caregiver, 403, 'Caregiver profile not found.');
            abort_if((int) ($visit->caregiver_id ?? 0) !== (int) $caregiver->id, 403, 'This visit is not assigned to you.');
        }

        $adlPayload = array_filter([
            'adl_status'          => $validated['adl_status'] ?? null,
            'bathroom_assistance' => $validated['bathroom_assistance'] ?? null,
            'mobility_support'    => $validated['mobility_support'] ?? null,
            'meal_notes'          => $validated['meal_notes'] ?? null,
            'medication_notes'    => $validated['medication_notes'] ?? null,
            'charting_notes'      => $validated['charting_notes'] ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $vitalsPayload = array_filter([
            'blood_pressure'    => $validated['blood_pressure'] ?? null,
            'pulse'             => $validated['pulse'] ?? null,
            'temperature'       => $validated['temperature'] ?? null,
            'respiratory_rate'  => $validated['respiratory_rate'] ?? null,
            'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
            'weight'            => $validated['weight'] ?? null,
            'blood_sugar'       => $validated['blood_sugar'] ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');

        $payload = [
            'visit_id' => $visit->id,
            'notes'    => $validated['care_notes'] ?? null,
            'adls'     => !empty($adlPayload) ? $adlPayload : null,
            'vitals'   => !empty($vitalsPayload) ? $vitalsPayload : null,
        ];

        if (Schema::hasColumn('care_logs', 'caregiver_id')) {
            $caregiver = $this->caregiverRecord();
            if ($caregiver) {
                $payload['caregiver_id'] = $caregiver->id;
            }
        }

        CareLog::create($payload);

        if (!empty($validated['check_in_time']) && Schema::hasColumn('visits', 'check_in_time')) {
            $visit->check_in_time = $validated['check_in_time'];
        }

        if (!empty($validated['check_in_time']) && Schema::hasColumn('visits', 'check_in')) {
            $visit->check_in = $validated['check_in_time'];
        }

        if (!empty($validated['check_out_time']) && Schema::hasColumn('visits', 'check_out_time')) {
            $visit->check_out_time = $validated['check_out_time'];
        }

        if (!empty($validated['check_out_time']) && Schema::hasColumn('visits', 'check_out')) {
            $visit->check_out = $validated['check_out_time'];
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
            $caregiver = $this->caregiverRecord();
            abort_if(!$caregiver, 403, 'Caregiver profile not found.');

            $ownsVisit = (int) ($careLog->visit->caregiver_id ?? 0) === (int) $caregiver->id;
            abort_if(!$ownsVisit, 403, 'Unauthorized.');
        }

        return view($this->showView(), compact('careLog'));
    }
}
