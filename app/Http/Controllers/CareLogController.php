<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use Illuminate\Http\Request;

class CareLogController extends Controller
{
    public function index()
    {
        $careLogs = CareLog::with(['client', 'caregiver', 'visit'])
            ->latest()
            ->get();

        return view('carelogs.index', compact('careLogs'));
    }

    public function create()
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();
        $visits = Visit::with(['client', 'caregiver'])->latest()->get();

        return view('carelogs.create', compact('clients', 'caregivers', 'visits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'visit_id' => 'nullable|exists:visits,id',
            'notes' => 'nullable|string',
            'adl_status' => 'nullable|string|max:255',
            'meal_notes' => 'nullable|string',
            'medication_notes' => 'nullable|string',
            'organization_id' => 'nullable|integer',
            'bathroom_assistance' => 'nullable|string|max:255',
            'mobility_support' => 'nullable|string|max:255',
            'charting_notes' => 'nullable|string',
            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        CareLog::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'visit_id' => $request->visit_id,
            'notes' => $request->notes,
            'adl_status' => $request->adl_status,
            'meal_notes' => $request->meal_notes,
            'medication_notes' => $request->medication_notes,
            'organization_id' => $request->organization_id ?? 1,
            'bathroom_assistance' => $request->bathroom_assistance,
            'mobility_support' => $request->mobility_support,
            'charting_notes' => $request->charting_notes,
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('provider.calendar')->with('success', 'Care log saved successfully.');
    }

    public function show($id)
    {
        $careLog = CareLog::with(['client', 'caregiver', 'visit'])->findOrFail($id);

        return view('carelogs.show', compact('careLog'));
    }

    public function edit($id)
    {
        $careLog = CareLog::findOrFail($id);
        $clients = Client::all();
        $caregivers = Caregiver::all();
        $visits = Visit::with(['client', 'caregiver'])->latest()->get();

        return view('carelogs.edit', compact('careLog', 'clients', 'caregivers', 'visits'));
    }

    public function update(Request $request, $id)
    {
        $careLog = CareLog::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'visit_id' => 'nullable|exists:visits,id',
            'notes' => 'nullable|string',
            'adl_status' => 'nullable|string|max:255',
            'meal_notes' => 'nullable|string',
            'medication_notes' => 'nullable|string',
            'organization_id' => 'nullable|integer',
            'bathroom_assistance' => 'nullable|string|max:255',
            'mobility_support' => 'nullable|string|max:255',
            'charting_notes' => 'nullable|string',
            'check_in_time' => 'nullable',
            'check_out_time' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $careLog->update([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'visit_id' => $request->visit_id,
            'notes' => $request->notes,
            'adl_status' => $request->adl_status,
            'meal_notes' => $request->meal_notes,
            'medication_notes' => $request->medication_notes,
            'organization_id' => $request->organization_id ?? 1,
            'bathroom_assistance' => $request->bathroom_assistance,
            'mobility_support' => $request->mobility_support,
            'charting_notes' => $request->charting_notes,
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('provider.calendar')->with('success', 'Care log updated successfully.');
    }

    public function destroy($id)
    {
        $careLog = CareLog::findOrFail($id);
        $careLog->delete();

        return redirect()->route('care-logs.index')->with('success', 'Care log deleted successfully.');
    }
}
