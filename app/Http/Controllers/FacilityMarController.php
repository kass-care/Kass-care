<?php

namespace App\Http\Controllers;

use App\Models\EmarAdministration;
use App\Models\Medication;
use Illuminate\Http\Request;

class FacilityMarController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $medications = Medication::with(['client', 'provider'])
            ->whereHas('client', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->where('approval_status', 'approved')
            ->orderBy('medication_name')
            ->get();

        $administrations = EmarAdministration::with(['client', 'medication', 'caregiver', 'correctedBy'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->take(50)
            ->get();

        $flaggedAdministrations = $administrations
            ->whereIn('status', ['missed', 'refused', 'held', 'side_effects']);

        return view('facility.mar.index', compact(
            'medications',
            'administrations',
            'flaggedAdministrations'
        ));
    }

    public function correct(Request $request, EmarAdministration $administration)
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if((int) $administration->facility_id !== (int) $facilityId, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:given,refused,held,missed,side_effects'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'correction_reason' => ['required', 'string', 'max:2000'],
        ]);

        $administration->update([
            'is_corrected' => true,
            'previous_status' => $administration->status,
            'previous_notes' => $administration->notes,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $administration->notes,
            'correction_reason' => $validated['correction_reason'],
            'corrected_by' => $user->id,
            'corrected_at' => now(),
        ]);

        return back()->with('success', 'Medication record corrected and audit trail saved.');
    }
}
