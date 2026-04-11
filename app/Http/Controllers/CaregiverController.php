<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Caregiver;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CaregiverController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

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

    private function resolveCaregiverRecord(): ?Caregiver
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

        return null;
    }

    private function caregiverVisitQuery()
    {
        $caregiver = $this->resolveCaregiverRecord();
        $facilityId = $this->currentFacilityId();

        $query = Visit::query();

        if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
            $query->where('facility_id', $facilityId);
        }

        if (!$caregiver) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('caregiver_id', $caregiver->id);
    }

    private function setVisitValueIfColumnExists(Visit $visit, string $column, $value): void
    {
        if (Schema::hasColumn('visits', $column)) {
            $visit->{$column} = $value;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Visits Page
    |--------------------------------------------------------------------------
    */

    public function visits()
    {
        $visits = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->orderByDesc('visit_date')
            ->orderBy('start_time')
            ->get();

        return view('caregiver.visits', compact('visits'));
    }

    /*
    |--------------------------------------------------------------------------
    | Check In
    |--------------------------------------------------------------------------
    */

    public function checkIn($id)
    {
        $visit = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->findOrFail($id);

        return view('caregiver.check-in', compact('visit'));
    }

    public function storeCheckIn(Request $request, $id)
    {
        $request->validate([
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $visit = $this->caregiverVisitQuery()->findOrFail($id);
        $now = now();

        $this->setVisitValueIfColumnExists($visit, 'check_in_time', $now);
        $this->setVisitValueIfColumnExists($visit, 'check_in', $now);
        $this->setVisitValueIfColumnExists($visit, 'status', 'in_progress');
        $this->setVisitValueIfColumnExists($visit, 'visit_started', true);
        $this->setVisitValueIfColumnExists($visit, 'visit_started_at', $now);

        if ($request->filled('latitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_in_latitude', $request->latitude);
        }

        if ($request->filled('longitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_in_longitude', $request->longitude);
        }

        $visit->save();

        return redirect()
            ->route('caregiver.visits')
            ->with('success', 'Visit checked in successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Check Out
    |--------------------------------------------------------------------------
    */

    public function checkOut($id)
    {
        $visit = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->findOrFail($id);

        return view('caregiver.check-out', compact('visit'));
    }

    public function storeCheckOut(Request $request, $id)
    {
        $request->validate([
            'visit_summary'      => ['nullable', 'string'],
            'client_condition'   => ['nullable', 'string'],
            'tasks_completed'    => ['nullable', 'string'],
            'follow_up_concerns' => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
            'latitude'           => ['nullable', 'numeric'],
            'longitude'          => ['nullable', 'numeric'],
        ]);

        $visit = $this->caregiverVisitQuery()->findOrFail($id);
        $caregiver = $this->resolveCaregiverRecord();
        $now = now();

        $this->setVisitValueIfColumnExists($visit, 'check_out_time', $now);
        $this->setVisitValueIfColumnExists($visit, 'check_out', $now);
        $this->setVisitValueIfColumnExists($visit, 'status', 'completed');
        $this->setVisitValueIfColumnExists($visit, 'visit_completed', true);
        $this->setVisitValueIfColumnExists($visit, 'visit_completed_at', $now);

        if (
            Schema::hasColumn('visits', 'check_in_time') &&
            Schema::hasColumn('visits', 'duration_minutes') &&
            !empty($visit->check_in_time)
        ) {
            try {
                $checkIn = Carbon::parse($visit->check_in_time);
                $durationMinutes = (int) round($checkIn->diffInSeconds($now) / 60);
                $visit->duration_minutes = $durationMinutes;
            } catch (\Throwable $e) {
                // keep going safely
            }
        }

        if ($request->filled('latitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_out_latitude', $request->latitude);
        }

        if ($request->filled('longitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_out_longitude', $request->longitude);
        }

        $visit->save();

        if (class_exists(CareLog::class)) {
            $adls = array_filter([
                'tasks_completed'    => $request->tasks_completed,
                'client_condition'   => $request->client_condition,
                'follow_up_concerns' => $request->follow_up_concerns,
            ], fn ($value) => !is_null($value) && $value !== '');

            $payload = [
                'visit_id' => $visit->id,
                'notes'    => $request->notes ?: $request->visit_summary,
                'adls'     => !empty($adls) ? $adls : null,
                'vitals'   => null,
            ];

            if ($caregiver && Schema::hasColumn('care_logs', 'caregiver_id')) {
                $payload['caregiver_id'] = $caregiver->id;
            }

            CareLog::create($payload);
        }

        return redirect()
            ->route('caregiver.visits')
            ->with('success', 'Visit completed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Care Log Shortcut
    |--------------------------------------------------------------------------
    */

    public function showCareLog($visit)
    {
        return redirect()->route('caregiver.care-logs.create', [
            'visit_id' => $visit,
        ]);
    }
}
