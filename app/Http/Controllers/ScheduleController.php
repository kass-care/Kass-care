<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Client;
use App\Models\Caregiver;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['client','caregiver'])
            ->orderBy('date')
            ->get();

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('schedules.create', compact('clients','caregivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'caregiver_id' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        Schedule::create([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'date' => $request->date,
            'notes' => $request->notes
        ]);

        return redirect()->route('schedules.index')
            ->with('success','Schedule created successfully');
    }

    public function show(Schedule $schedule)
    {
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('schedules.edit', compact('schedule','clients','caregivers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'client_id' => 'required',
            'caregiver_id' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $schedule->update([
            'client_id' => $request->client_id,
            'caregiver_id' => $request->caregiver_id,
            'date' => $request->date,
            'notes' => $request->notes
        ]);

        return redirect()->route('schedules.index')
            ->with('success','Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success','Schedule deleted successfully');
    }

    public function calendar()
    {
        $schedules = Schedule::with(['client','caregiver'])
            ->orderBy('date')
            ->get();

        return view('schedules.calendar', compact('schedules'));
    }
}
