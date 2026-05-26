<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaregiverShiftController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        abort_if(!$user, 403);

        $shifts = Shift::with(['clients', 'facility'])
            ->where('caregiver_id', $user->id)
            ->latest('shift_date')
            ->latest()
            ->get();

        return view('caregiver.shifts.index', compact('shifts'));
    }

    public function clockIn(Request $request, Shift $shift): RedirectResponse
    {
        $user = auth()->user();

        abort_if(!$user, 403);

        $this->authorizeShift($shift, $user->id);

        $validated = $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $this->ensureInsideFacilityGeofence(
            $shift,
            (float) $validated['latitude'],
            (float) $validated['longitude']
        );

        if (!$shift->clock_in_at) {
            $shift->update([
                'clock_in_at' => now(),
                'clock_in_latitude' => $validated['latitude'],
                'clock_in_longitude' => $validated['longitude'],
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'Shift clock-in recorded successfully.');
    }

    public function clockOut(Request $request, Shift $shift): RedirectResponse
    {
        $user = auth()->user();

        abort_if(!$user, 403);

        $this->authorizeShift($shift, $user->id);

        abort_if(
            !$shift->clock_in_at,
            422,
            'You must clock in before clocking out.'
        );

        $validated = $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $this->ensureInsideFacilityGeofence(
            $shift,
            (float) $validated['latitude'],
            (float) $validated['longitude']
        );

        if (!$shift->clock_out_at) {
            $shift->update([
                'clock_out_at' => now(),
                'clock_out_latitude' => $validated['latitude'],
                'clock_out_longitude' => $validated['longitude'],
                'status' => 'completed',
            ]);
        }

        return back()->with('success', 'Shift clock-out recorded successfully.');
    }

    private function authorizeShift(Shift $shift, int $userId): void
    {
        abort_if(
            (int) $shift->caregiver_id !== (int) $userId,
            403,
            'Unauthorized shift access.'
        );
    }

    private function ensureInsideFacilityGeofence(Shift $shift, float $latitude, float $longitude): void
    {
        $facility = $shift->facility;

        abort_if(!$facility, 422, 'Facility not found for this shift.');

        abort_if(
            !$facility->latitude || !$facility->longitude,
            422,
            'Facility GPS location is not configured.'
        );

        $distance = $this->distanceInMeters(
            (float) $facility->latitude,
            (float) $facility->longitude,
            $latitude,
            $longitude
        );

        $allowedRadius = (int) ($facility->allowed_radius_meters ?? 150);

        abort_if(
            $distance > $allowedRadius,
            403,
            'Clock-in/out denied. You are outside the approved facility GPS radius.'
        );
    }

    private function distanceInMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $earthRadius * $angle;
    }
}
