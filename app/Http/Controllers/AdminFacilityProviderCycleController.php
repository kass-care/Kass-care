<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\FacilityProviderCycle;
use Illuminate\Http\Request;

class AdminFacilityProviderCycleController extends Controller
{
    public function index()
    {
        $cycles = FacilityProviderCycle::with(['facility', 'provider'])
            ->latest()
            ->get();

        return view('admin.facility_provider_cycles.index', compact('cycles'));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('admin.facility_provider_cycles.create', compact('facilities', 'providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'provider_id' => 'required|exists:users,id',
            'review_interval_days' => 'required|integer|min:1',
            'next_due_at' => 'nullable|date',
            'scheduled_for' => 'nullable|date',
            'status' => 'required|string',
            'priority' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        FacilityProviderCycle::create([
            'facility_id' => $request->facility_id,
            'provider_id' => $request->provider_id,
            'review_interval_days' => $request->review_interval_days,
            'last_completed_at' => null,
            'next_due_at' => $request->next_due_at,
            'scheduled_for' => $request->scheduled_for,
            'completed_for_cycle_at' => null,
            'status' => $request->status,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('admin.facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle created successfully.');
    }

    public function edit(FacilityProviderCycle $facilityProviderCycle)
    {
        $facilities = Facility::orderBy('name')->get();
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('admin.facility_provider_cycles.edit', compact('facilityProviderCycle', 'facilities', 'providers'));
    }

    public function update(Request $request, FacilityProviderCycle $facilityProviderCycle)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'provider_id' => 'required|exists:users,id',
            'review_interval_days' => 'required|integer|min:1',
            'last_completed_at' => 'nullable|date',
            'next_due_at' => 'nullable|date',
            'scheduled_for' => 'nullable|date',
            'completed_for_cycle_at' => 'nullable|date',
            'status' => 'required|string',
            'priority' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $facilityProviderCycle->update([
            'facility_id' => $request->facility_id,
            'provider_id' => $request->provider_id,
            'review_interval_days' => $request->review_interval_days,
            'last_completed_at' => $request->last_completed_at,
            'next_due_at' => $request->next_due_at,
            'scheduled_for' => $request->scheduled_for,
            'completed_for_cycle_at' => $request->completed_for_cycle_at,
            'status' => $request->status,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('admin.facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle updated successfully.');
    }

    public function destroy(FacilityProviderCycle $facilityProviderCycle)
    {
        $facilityProviderCycle->delete();

        return redirect()
            ->route('admin.facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle deleted successfully.');
    }
}
