<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Caregiver;
use App\Models\ProviderMessage;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CaregiverController extends Controller
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

        if ($facilityId && !empty($user->email) && Schema::hasColumn('caregivers', 'email')) {
            $byEmail = (clone $query)
                ->when(Schema::hasColumn('caregivers', 'facility_id'), fn ($q) => $q->where('facility_id', $facilityId))
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
                ->when(Schema::hasColumn('caregivers', 'facility_id'), fn ($q) => $q->where('facility_id', $facilityId))
                ->where('name', $user->name)
                ->first();

            if ($byName) {
                if (Schema::hasColumn('caregivers', 'user_id') && empty($byName->user_id)) {
                    $byName->user_id = $user->id;
                }

                if (Schema::hasColumn('caregivers', 'email') && empty($byName->email)) {
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
    $user = auth()->user();
    $facilityId = $this->currentFacilityId();

    $query = Visit::query();

    if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
        $query->where('facility_id', $facilityId);
    }

    $ids = [];

    // user id (THIS is what your visits are using)
    if ($user) {
        $ids[] = $user->id;
    }

    // caregiver profile id (fallback)
    if ($caregiver) {
        $ids[] = $caregiver->id;
    }

    $ids = array_unique(array_filter($ids));

    return $query->whereIn('caregiver_id', $ids);
}
    private function setVisitValueIfColumnExists(Visit $visit, string $column, $value): void
    {
        if (Schema::hasColumn('visits', $column)) {
            $visit->{$column} = $value;
        }
    }

    public function visits()
    {
        $visits = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->orderByDesc('visit_date')
            ->orderBy('start_time')
            ->get();

        return view('caregiver.visits', compact('visits'));
    }

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

    public function checkOut($id)
{
    $visit = $this->caregiverVisitQuery()
        ->with(['client', 'facility', 'provider'])
        ->findOrFail($id);

    // 🚨 CHECK: must have care log before checkout
    $hasCareLog = \App\Models\CareLog::where('visit_id', $visit->id)->exists();

    if (!$hasCareLog) {
        return redirect()
            ->route('caregiver.care-logs.create', ['visit_id' => $visit->id])
            ->with('error', 'You must complete a care log before checking out.');
    }

    return view('caregiver.check-out', compact('visit'));
}
    public function storeCheckOut(Request $request, $id)
    {
// 🚨 HARD BLOCK
$hasCareLog = \App\Models\CareLog::where('visit_id', $id)->exists();

if (!$hasCareLog) {
    return redirect()
        ->route('caregiver.care-logs.create', ['visit_id' => $id])
        ->with('error', 'Care log required before checkout.');
}

        $request->validate([
            'visit_summary' => ['nullable', 'string'],
            'client_condition' => ['nullable', 'string'],
            'tasks_completed' => ['nullable', 'string'],
            'follow_up_concerns' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
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
                $visit->duration_minutes = (int) round($checkIn->diffInSeconds($now) / 60);
            } catch (\Throwable $e) {
                //
            }
        }

        if ($request->filled('latitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_out_latitude', $request->latitude);
        }

        if ($request->filled('longitude')) {
            $this->setVisitValueIfColumnExists($visit, 'check_out_longitude', $request->longitude);
        }

        $visit->save();

        $adls = array_filter([
            'tasks_completed' => $request->tasks_completed,
            'client_condition' => $request->client_condition,
            'follow_up_concerns' => $request->follow_up_concerns,
        ]);

        $payload = [
            'visit_id' => $visit->id,
            'notes' => $request->notes ?: $request->visit_summary,
            'adls' => !empty($adls) ? $adls : null,
            'vitals' => null,
        ];

        if ($caregiver && Schema::hasColumn('care_logs', 'caregiver_id')) {
            $payload['caregiver_id'] = $caregiver->id;
        }

        if (Schema::hasColumn('care_logs', 'client_id')) {
            $payload['client_id'] = $visit->client_id;
        }

        if (Schema::hasColumn('care_logs', 'facility_id')) {
            $payload['facility_id'] = $visit->facility_id;
        }

        CareLog::create($payload);

        return redirect()
            ->route('caregiver.visits')
            ->with('success', 'Visit completed successfully.');
    }

    public function reportIssue(Visit $visit)
    {
        $visit = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->findOrFail($visit->id);

        return view('caregiver.report-issue', compact('visit'));
    }

    public function storeReportIssue(Request $request, Visit $visit)
    {
        $visit = $this->caregiverVisitQuery()
            ->with(['client', 'facility', 'provider'])
            ->findOrFail($visit->id);

        $data = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $providerId = $visit->provider_id
            ?? $visit->client?->provider_id
            ?? User::where('role', 'provider')
                ->where('facility_id', $visit->facility_id)
                ->value('id');

        if (!$providerId) {
            return back()->with('error', 'No provider is linked to this patient or facility yet.');
        }

        ProviderMessage::create([
            'facility_id' => $visit->facility_id,
            'client_id' => $visit->client_id,
            'sender_id' => auth()->id(),
            'provider_id' => $providerId,
            'subject' => $data['subject'] ?: 'Caregiver clinical concern',
            'message' => "Caregiver reported an issue during Visit #{$visit->id}:\n\n" . $data['message'],
            'priority' => 'urgent',
        ]);

        return redirect()
            ->route('caregiver.visits')
            ->with('success', 'Clinical issue reported to provider successfully.');
    }

    public function showCareLog($visit)
    {
        return redirect()->route('caregiver.care-logs.create', [
            'visit_id' => $visit,
        ]);
    }
}
