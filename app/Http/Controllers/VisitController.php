<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Client;
use App\Models\Caregiver;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['client', 'caregiver'])->latest()->get();
        return view('visits.index', compact('visits'));
    }
public function create()
{
    $clients = Client::all();
    $caregivers = Caregiver::all();
    
    // Add this line so the table at the bottom of your 'create' page has data!
    $visits = Visit::with(['client', 'caregiver'])->latest()->get();

    return view('visits.create', compact('clients', 'caregivers', 'visits'));
}

public function store(Request $request)
{
    // 1. We change 'scheduled_at' to 'visit_date' to match your DB and form
    $request->validate([
        'client_id'    => 'required|exists:clients,id',
        'caregiver_id' => 'required|exists:caregivers,id',
        'activity'     => 'required|string|max:255',
        'visit_date'   => 'required|date', 
        'status'       => 'nullable|string|max:50',
    ]);

    // 2. We map the data and include the mandatory organization_id
    \App\Models\Visit::create([
        'client_id'       => $request->client_id,
        'caregiver_id'    => $request->caregiver_id,
        'activity'        => $request->activity,
        'visit_date'      => $request->visit_date,
        'visit_time' => $request->visit_time,
        'status'          => $request->status ?? 'Scheduled',
        'organization_id' => 1, 
    ]);

    return redirect()->route('visits.index')->with('success', 'Visit recorded successfully!');
} // <--- THIS BRACKET FIXES THE SYNTAX ERROR IN PHOTO 17
    public function edit(Visit $visit)
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();
        return view('visits.edit', compact('visit', 'clients', 'caregivers'));
    }

    public function update(Request $request, Visit $visit)
    {
        $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'activity'     => 'required|string|max:255',
            'visit_date'   => 'required|date',
            'status'       => 'required|string',
        ]);

        $visit->update($request->all());

        return redirect()->route('visits.index')->with('success', 'Visit updated successfully!');
    }
}
