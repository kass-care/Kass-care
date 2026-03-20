<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        abort_if(!$user || !$user->facility_id, 403, 'No facility assigned.');

        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('visits.create', compact('clients', 'caregivers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        abort_if(!$user || !$user->facility_id, 403, 'No facility assigned.');

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'activity' => 'required|string|max:255',
            'visit_date' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        Visit::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'activity' => $request->activity,
            'visit_date' => $request->visit_date,
            'status' => $request->status,
            'facility_id' => $user->facility_id,
        ]);

        return redirect()->route('caregiver.dashboard')
            ->with('success', 'Visit created successfully!');
    }
}
