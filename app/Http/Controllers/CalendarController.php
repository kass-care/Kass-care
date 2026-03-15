<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    // Show calendar page with all schedules
    public function index()
    {
        $calendars = Calendar::orderBy('start_time', 'asc')->get();
        return view('calendar.index', compact('calendars'));
    }

    // Store a new schedule
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
        ]);

        Calendar::create([
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'caregiver_id' => $request->caregiver_id,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('calendar.index')->with('success', 'Schedule added successfully!');
    }
}
