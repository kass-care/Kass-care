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
        $schedules = Schedule::with(['client', 'caregiver'])->orderBy('schedule_date')->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $clients = Client::all();
        $caregivers = Caregiver::all();
        return view('schedules.create', compact('clients', 'caregivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'date'         => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'notes'        => 'nullable|string',
        ]);

        Schedule::create([
            'client_id'     => $request->client_id,
            'caregiver_id'  => $request->caregiver_id,
            'schedule_date' => $request->date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'client_id'    => 'required|exists:clients,id',
            'caregiver_id' => 'required|exists:caregivers,id',
            'date'         => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'notes'        => 'nullable|string',
        ]);

        $schedule->update([
            'client_id'     => $request->client_id,
            'caregiver_id'  => $request->caregiver_id,
            'schedule_date' => $request->date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'notes'         => $request->notes,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully');
    }

    public function calendar()
    {
        $schedules = Schedule::with(['client', 'caregiver'])->get();
        return view('schedules.calendar', compact('schedules'));
    }
}
