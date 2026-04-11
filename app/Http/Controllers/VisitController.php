<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $selectedFacilityId = session('facility_id');
        $facilityId = $selectedFacilityId ?? $user->facility_id;

        $query = Visit::with(['client', 'caregiver'])->latest();

        if ($user->role === 'super_admin') {
            if ($facilityId) {
                $query->where('facility_id', $facilityId);
            }
        } else {
            abort_if(!$facilityId, 403, 'No facility assigned.');
            $query->where('facility_id', $facilityId);
        }

        $visits = $query->get();

        return view('visits.index', compact('visits'));
    }

    public function create()
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $selectedFacilityId = session('facility_id');
        $facilityId = $selectedFacilityId ?? $user->facility_id;

        if ($user->role !== 'super_admin') {
            abort_if(!$facilityId, 403, 'No facility assigned.');
        }

        $clients = $facilityId
            ? Client::where('facility_id', $facilityId)->orderBy('name')->get()
            : Client::orderBy('name')->get();

        $caregivers = $facilityId
            ? User::where('facility_id', $facilityId)
                ->where('role', 'caregiver')
                ->orderBy('name')
                ->get()
            : User::where('role', 'caregiver')
                ->orderBy('name')
                ->get();

        return view('visits.create', compact('clients', 'caregivers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user, 403, 'Unauthorized.');

        $selectedFacilityId = session('facility_id');
        $facilityId = $selectedFacilityId ?? $user->facility_id;

        if ($user->role !== 'super_admin') {
            abort_if(!$facilityId, 403, 'No facility assigned.');
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'caregiver_id' => ['required', 'exists:users,id'],
            'activity' => ['required', 'string', 'max:255'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $client = Client::findOrFail($validated['client_id']);

        if ($facilityId) {
            abort_if((int) $client->facility_id !== (int) $facilityId, 403, 'Selected client does not belong to this facility.');
        }

        $caregiver = User::where('role', 'caregiver')
            ->findOrFail($validated['caregiver_id']);

        if ($facilityId) {
            abort_if((int) $caregiver->facility_id !== (int) $facilityId, 403, 'Selected caregiver does not belong to this facility.');
        }

        Visit::create([
            'client_id' => $client->id,
            'caregiver_id' => $caregiver->id,
            'activity' => $validated['activity'],
            'visit_date' => $validated['visit_date'],
            'status' => $validated['status'],
            'facility_id' => $client->facility_id,
        ]);

        return redirect()
            ->route('visits.index')
            ->with('success', 'Visit created successfully!');
    }

    public function edit(Visit $visit)
    {
        $this->authorizeVisit($visit);

        $user = auth()->user();
        $facilityId = session('facility_id') ?? $user->facility_id;

        $clients = Client::where('facility_id', $visit->facility_id)
            ->orderBy('name')
            ->get();

        $caregivers = User::where('facility_id', $visit->facility_id)
            ->where('role', 'caregiver')
            ->orderBy('name')
            ->get();

        return view('visits.edit', compact('visit', 'clients', 'caregivers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $this->authorizeVisit($visit);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'caregiver_id' => ['required', 'exists:users,id'],
            'activity' => ['required', 'string', 'max:255'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $client = Client::findOrFail($validated['client_id']);
        abort_if((int) $client->facility_id !== (int) $visit->facility_id, 403, 'Selected client does not belong to this facility.');

        $caregiver = User::where('role', 'caregiver')
            ->findOrFail($validated['caregiver_id']);
        abort_if((int) $caregiver->facility_id !== (int) $visit->facility_id, 403, 'Selected caregiver does not belong to this facility.');

        $visit->update([
            'client_id' => $client->id,
            'caregiver_id' => $caregiver->id,
            'activity' => $validated['activity'],
            'visit_date' => $validated['visit_date'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('visits.index')
            ->with('success', 'Visit updated successfully!');
    }

    public function destroy(Visit $visit)
    {
        $this->authorizeVisit($visit);

        $visit->delete();

        return redirect()
            ->route('visits.index')
            ->with('success', 'Visit deleted successfully!');
    }

    private function authorizeVisit(Visit $visit): void
    {
        $user = auth()->user();
        $facilityId = session('facility_id') ?? $user->facility_id;

        if ($user->role !== 'super_admin') {
            abort_if((int) $visit->facility_id !== (int) $facilityId, 403, 'Unauthorized visit access.');
        }
    }
}
