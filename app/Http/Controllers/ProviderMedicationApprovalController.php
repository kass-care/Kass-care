<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class ProviderMedicationApprovalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        $medications = Medication::with(['client', 'provider'])
            ->whereHas('client', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->where('approval_status', 'pending')
            ->latest()
            ->get();

        return view('provider.medication-approvals.index', compact('medications'));
    }

    public function approve(Request $request, Medication $medication)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if((int) $medication->client->facility_id !== (int) $facilityId, 403, 'Unauthorized facility access.');

        $medication->update([
            'approval_status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'provider_note' => $request->input('provider_note'),
            'status' => 'active',
        ]);

        return back()->with('success', 'Medication approved successfully.');
    }

    public function reject(Request $request, Medication $medication)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if((int) $medication->client->facility_id !== (int) $facilityId, 403, 'Unauthorized facility access.');

        $request->validate([
            'provider_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $medication->update([
            'approval_status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'provider_note' => $request->input('provider_note'),
            'status' => 'paused',
        ]);

        return back()->with('success', 'Medication rejected.');
    }
}
