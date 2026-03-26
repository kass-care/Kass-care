<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        // 🔥 DEBUG — DO NOT REMOVE YET
         $facilityId = auth()->user()->facility_id;

if (!$facilityId) {
    abort(403, 'No facility assigned.');
}    
    

        $facilityId = session('facility_id') ?? $user->facility_id;

        if ($user->role === 'super_admin') {
            if ($facilityId) {
                $visits = Visit::with(['client', 'caregiver'])
                    ->where('facility_id', $facilityId)
                    ->latest()
                    ->get();
            } else {
                $visits = Visit::with(['client', 'caregiver'])
                    ->latest()
                    ->get();
            }
        } else {
            abort_if(!$facilityId, 403, 'No facility assigned.');

            $visits = Visit::with(['client', 'caregiver'])
                ->where('facility_id', $facilityId)
                ->latest()
                ->get();
        }

        return view('visits.index', compact('visits'));
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first');
        }

        $facilityId = session('facility_id') ?? $user->facility_id;

        if ($user->role === 'super_admin') {
            if ($facilityId) {
                $clients = Client::where('facility_id', $facilityId)->latest()->get();
                $caregivers = Caregiver::where('facility_id', $facilityId)->latest()->get();
            } else {
                $clients = Client::latest()->get();
                $caregivers = Caregiver::latest()->get();
            }
        } else {
            abort_if(!$facilityId, 403, 'No facility assigned.');

            $clients = Client::where('facility_id', $facilityId)->latest()->get();
            $caregivers = Caregiver::where('facility_id', $facilityId)->latest()->get();
        }

        return view('visits.create', compact('clients', 'caregivers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'activity' => 'required|string|max:255',
            'visit_date' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        $client = Client::findOrFail($request->client_id);
        $caregiver = Caregiver::findOrFail($request->caregiver_id);

        $facilityId = session('facility_id')
            ?? ($user->role === 'super_admin'
                ? ($client->facility_id ?? $caregiver->facility_id)
                : $user->facility_id);

        abort_if(!$facilityId, 403, 'No facility assigned.');

        Visit::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'activity' => $request->activity,
            'visit_date' => $request->visit_date,
            'status' => $request->status,
            'facility_id' => $facilityId,
        ]);

        return redirect()->route('admin.visits.index')
            ->with('success', 'Visit created successfully!');
    }

    public function show(Visit $visit)
    {
        return view('visits.show', compact('visit'));
    }

    public function edit(Visit $visit)
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if(!$facilityId, 403, 'No facility assigned.');

        $clients = Client::where('facility_id', $facilityId)->latest()->get();
        $caregivers = Caregiver::where('facility_id', $facilityId)->latest()->get();

        return view('visits.edit', compact('visit', 'clients', 'caregivers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'activity' => 'required|string|max:255',
            'visit_date' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        $visit->update($request->all());

        return redirect()->route('admin.visits.index')
            ->with('success', 'Visit updated successfully!');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()->route('admin.visits.index')
            ->with('success', 'Visit deleted successfully!');
    }
}
