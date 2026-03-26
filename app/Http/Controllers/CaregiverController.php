<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Visit;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INTERNAL HELPER
    |--------------------------------------------------------------------------
    */
    private function caregiverVisitQuery()
    {
        $user = auth()->user();

        return Visit::query()->when($user->role !== 'super_admin', function ($query) use ($user) {
            $query->where('facility_id', $user->facility_id);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-IN
    |--------------------------------------------------------------------------
    */
    public function checkIn($id)
    {
        $visit = $this->caregiverVisitQuery()
            ->with('client')
            ->findOrFail($id);

        return view('caregiver.check-in', compact('visit'));
    }

    public function storeCheckIn(Request $request, $id)
    {
        $request->validate([
            'check_in_time' => ['required', 'date'],
        ]);

        $visit = $this->caregiverVisitQuery()->findOrFail($id);

        $visit->update([
            'check_in_time' => $request->check_in_time,
            'status' => 'in_progress',
        ]);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Visit checked in successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CARE LOG
    |--------------------------------------------------------------------------
    */
    public function showCareLog($visit)
    {
        $visit = $this->caregiverVisitQuery()
            ->with('client')
            ->findOrFail($visit);

        return view('caregiver.carelog', compact('visit'));
    }

    public function storeCareLog(Request $request, $visit)
    {
        $request->validate([
            'notes' => ['nullable', 'string'],
            'adls' => ['nullable', 'array'],
            'vitals' => ['nullable', 'array'],
        ]);

        $visit = $this->caregiverVisitQuery()->findOrFail($visit);

        CareLog::create([
            'visit_id' => $visit->id,
            'caregiver_id' => $visit->caregiver_id ?? null,
            'notes' => $request->notes,
            'adls' => $request->adls ?? [],
            'vitals' => $request->vitals ?? [],
        ]);

        return redirect()
            ->route('caregiver.care-logs.index')
            ->with('success', 'Care log saved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-OUT
    |--------------------------------------------------------------------------
    */
    public function checkOut($id)
    {
        $visit = $this->caregiverVisitQuery()
            ->with('client')
            ->findOrFail($id);

        return view('caregiver.check-out', compact('visit'));
    }

    public function storeCheckOut(Request $request, $id)
    {
        $request->validate([
            'visit_summary' => ['nullable', 'string'],
            'client_condition' => ['nullable', 'string'],
            'tasks_completed' => ['nullable', 'string'],
            'follow_up_concerns' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $visit = $this->caregiverVisitQuery()->findOrFail($id);

        $visit->update([
            'check_out_time' => now(),
            'status' => 'completed',
        ]);

        CareLog::create([
            'visit_id' => $visit->id,
            'caregiver_id' => $visit->caregiver_id ?? null,
            'notes' => $request->notes ?: $request->visit_summary,
            'adls' => [
                'tasks_completed' => $request->tasks_completed,
                'client_condition' => $request->client_condition,
                'follow_up_concerns' => $request->follow_up_concerns,
            ],
            'vitals' => [],
        ]);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Visit completed successfully.');
    }
}
