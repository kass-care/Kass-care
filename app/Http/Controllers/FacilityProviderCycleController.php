<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityProviderCycle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacilityProviderCycleController extends Controller
{
    public function index()
    {
        $cycles = FacilityProviderCycle::with(['facility', 'provider'])
            ->orderBy('next_due_at')
            ->get();

        $currentCount = $cycles->filter(fn ($cycle) => $cycle->computed_status === 'current')->count();
        $dueSoonCount = $cycles->filter(fn ($cycle) => $cycle->computed_status === 'due_soon')->count();
        $dueCount = $cycles->filter(fn ($cycle) => $cycle->computed_status === 'due')->count();
        $overdueCount = $cycles->filter(fn ($cycle) => $cycle->computed_status === 'overdue')->count();

        return view('facility-provider-cycles.index', compact(
            'cycles',
            'currentCount',
            'dueSoonCount',
            'dueCount',
            'overdueCount'
        ));
    }

    public function create()
    {
        $facilities = Facility::orderBy('name')->get();
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('facility-provider-cycles.create', compact('facilities', 'providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'review_interval_days' => ['required', 'integer', 'min:1'],
            'last_completed_at' => ['nullable', 'date'],
            'scheduled_for' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $lastCompletedAt = !empty($validated['last_completed_at'])
            ? Carbon::parse($validated['last_completed_at'])
            : null;

        $nextDueAt = $lastCompletedAt
            ? (clone $lastCompletedAt)->addDays((int) $validated['review_interval_days'])
            : null;

        FacilityProviderCycle::create([
            'facility_id' => $validated['facility_id'],
            'provider_id' => $validated['provider_id'] ?? null,
            'review_interval_days' => (int) $validated['review_interval_days'],
            'last_completed_at' => $lastCompletedAt,
            'next_due_at' => $nextDueAt,
            'scheduled_for' => !empty($validated['scheduled_for']) ? Carbon::parse($validated['scheduled_for']) : null,
            'completed_for_cycle_at' => null,
            'status' => 'active',
            'priority' => $validated['priority'] ?? 'normal',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle created successfully.');
    }

    public function edit(FacilityProviderCycle $facilityProviderCycle)
    {
        $facilities = Facility::orderBy('name')->get();
        $providers = User::where('role', 'provider')->orderBy('name')->get();

        return view('facility-provider-cycles.edit', [
            'cycle' => $facilityProviderCycle,
            'facilities' => $facilities,
            'providers' => $providers,
        ]);
    }

    public function update(Request $request, FacilityProviderCycle $facilityProviderCycle)
    {
        $validated = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'provider_id' => ['nullable', 'exists:users,id'],
            'review_interval_days' => ['required', 'integer', 'min:1'],
            'last_completed_at' => ['nullable', 'date'],
            'scheduled_for' => ['nullable', 'date'],
            'completed_for_cycle_at' => ['nullable', 'date'],
            'priority' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $lastCompletedAt = !empty($validated['last_completed_at'])
            ? Carbon::parse($validated['last_completed_at'])
            : null;

        $nextDueAt = $lastCompletedAt
            ? (clone $lastCompletedAt)->addDays((int) $validated['review_interval_days'])
            : null;

        $facilityProviderCycle->update([
            'facility_id' => $validated['facility_id'],
            'provider_id' => $validated['provider_id'] ?? null,
            'review_interval_days' => (int) $validated['review_interval_days'],
            'last_completed_at' => $lastCompletedAt,
            'next_due_at' => $nextDueAt,
            'scheduled_for' => !empty($validated['scheduled_for']) ? Carbon::parse($validated['scheduled_for']) : null,
            'completed_for_cycle_at' => !empty($validated['completed_for_cycle_at']) ? Carbon::parse($validated['completed_for_cycle_at']) : null,
            'priority' => $validated['priority'] ?? 'normal',
            'status' => $validated['status'] ?? $facilityProviderCycle->status,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle updated successfully.');
    }

    public function destroy(FacilityProviderCycle $facilityProviderCycle)
    {
        $facilityProviderCycle->delete();

        return redirect()
            ->route('facility-provider-cycles.index')
            ->with('success', 'Facility provider cycle deleted successfully.');
    }
}
