<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Caregiver;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CaregiverActionController extends Controller
{
    protected function getAuthorizedVisit($id)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $caregiver = Caregiver::where('user_id', $user->id)->first();

        abort_if(!$caregiver, 403, 'No caregiver profile linked to this account.');

        $visit = Visit::with(['client', 'caregiver'])->findOrFail($id);

        abort_if((int) $visit->caregiver_id !== (int) $caregiver->id, 403, 'Unauthorized caregiver access.');

        return $visit;
    }

    public function checkIn($id)
    {
        $visit = $this->getAuthorizedVisit($id);

        return view('caregiver.check-in', compact('visit'));
    }

    public function saveCheckIn(Request $request, $id)
    {
        $visit = $this->getAuthorizedVisit($id);

        $visit->update([
            'check_in_time' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Check-in saved successfully!');
    }

    public function checkOut($id)
    {
        $visit = $this->getAuthorizedVisit($id);

        return view('caregiver.check-out', compact('visit'));
    }

    public function saveCheckOut(Request $request, $id)
    {
        $visit = $this->getAuthorizedVisit($id);

        $checkOutTime = now();
        $duration = null;

        if ($visit->check_in_time) {
            $start = Carbon::parse($visit->check_in_time);
            $end = Carbon::parse($checkOutTime);
            $duration = (int) $start->diffInMinutes($end);
        }

        $visit->update([
            'check_out_time' => $checkOutTime,
            'duration_minutes' => $duration,
            'status' => 'completed',
        ]);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Visit completed successfully!');
    }

    public function showCareLog($id)
    {
        $visit = $this->getAuthorizedVisit($id);

        return view('caregiver.care-log', compact('visit'));
    }

    public function storeCareLog(Request $request, $id)
    {
        $visit = $this->getAuthorizedVisit($id);

        $request->validate([
            'notes' => 'nullable|string',
            'adl_bathing' => 'nullable|string|max:255',
            'adl_dressing' => 'nullable|string|max:255',
            'adl_toileting' => 'nullable|string|max:255',
            'adl_feeding' => 'nullable|string|max:255',
            'adl_mobility' => 'nullable|string|max:255',
            'temperature' => 'nullable|string|max:50',
            'heart_rate' => 'nullable|string|max:50',
            'respiratory_rate' => 'nullable|string|max:50',
            'oxygen_saturation' => 'nullable|string|max:50',
            'blood_pressure_systolic' => 'nullable|string|max:50',
            'blood_pressure_diastolic' => 'nullable|string|max:50',
        ]);

        $vitals = collect([
            'Temperature' => $request->temperature,
            'Heart Rate' => $request->heart_rate,
            'Respiratory Rate' => $request->respiratory_rate,
            'Oxygen Saturation' => $request->oxygen_saturation,
            'BP Systolic' => $request->blood_pressure_systolic,
            'BP Diastolic' => $request->blood_pressure_diastolic,
        ])->filter(fn ($value) => !is_null($value) && $value !== '')
          ->map(fn ($value, $label) => $label . ': ' . $value)
          ->implode(', ');

        $adlNotes = collect([
            'Bathing' => $request->adl_bathing,
            'Dressing' => $request->adl_dressing,
            'Toileting' => $request->adl_toileting,
            'Feeding' => $request->adl_feeding,
            'Mobility' => $request->adl_mobility,
        ])->filter(fn ($value) => !is_null($value) && $value !== '')
          ->map(fn ($value, $label) => $label . ': ' . $value)
          ->implode(', ');

        $combinedNotes = collect([
            $request->notes,
            $adlNotes ? 'ADLs - ' . $adlNotes : null,
            $vitals ? 'Vitals - ' . $vitals : null,
        ])->filter(fn ($value) => !is_null($value) && trim($value) !== '')
          ->implode(' | ');

        $visit->update([
            'notes' => $combinedNotes,
        ]);

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Care log saved successfully!');
    }
}
