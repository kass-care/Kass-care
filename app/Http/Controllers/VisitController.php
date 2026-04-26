<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Client;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get provider facilities
        $facilityIds = DB::table('facility_provider')
            ->where('provider_id', $user->id)
            ->pluck('facility_id');

        $visits = Visit::with(['client', 'caregiver'])
            ->whereIn('facility_id', $facilityIds)
            ->latest()
            ->get();

        return view('visits.index', compact('visits'));
    }

    public function create()
    {
        $user = auth()->user();

        $facilityIds = DB::table('facility_provider')
            ->where('provider_id', $user->id)
            ->pluck('facility_id');

        $clients = Client::whereIn('facility_id', $facilityIds)
            ->orderBy('name')
            ->get();

        $caregivers = Caregiver::whereIn('facility_id', $facilityIds)
            ->orderBy('name')
            ->get();

        return view('visits.create', compact('clients', 'caregivers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $facilityIds = DB::table('facility_provider')
            ->where('provider_id', $user->id)
            ->pluck('facility_id');

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'caregiver_id' => ['required', 'exists:caregivers,id'],
            'activity' => ['required', 'string', 'max:255'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $caregiver = Caregiver::findOrFail($validated['caregiver_id']);

        // SECURITY CHECK
        abort_if(
            !$facilityIds->contains($client->facility_id),
            403,
            'Client not in your facilities.'
        );

        abort_if(
            !$facilityIds->contains($caregiver->facility_id),
            403,
            'Caregiver not in your facilities.'
        );

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

        $facilityIds = DB::table('facility_provider')
            ->where('provider_id', $user->id)
            ->pluck('facility_id');

        $clients = Client::whereIn('facility_id', $facilityIds)->get();
        $caregivers = Caregiver::whereIn('facility_id', $facilityIds)->get();

        return view('visits.edit', compact('visit', 'clients', 'caregivers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $this->authorizeVisit($visit);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'caregiver_id' => ['required', 'exists:caregivers,id'],
            'activity' => ['required', 'string', 'max:255'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $client = Client::findOrFail($validated['client_id']);
        $caregiver = Caregiver::findOrFail($validated['caregiver_id']);

        abort_if(
            $client->facility_id != $visit->facility_id,
            403,
            'Client mismatch.'
        );

        abort_if(
            $caregiver->facility_id != $visit->facility_id,
            403,
            'Caregiver mismatch.'
        );

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

        if ($user->role === 'super_admin') {
            return;
        }

        $facilityIds = DB::table('facility_provider')
            ->where('provider_id', $user->id)
            ->pluck('facility_id');

        abort_if(
            !$facilityIds->contains($visit->facility_id),
            403,
            'Unauthorized visit access.'
        );
    }
}
