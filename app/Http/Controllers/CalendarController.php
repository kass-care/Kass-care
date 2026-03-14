<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;

class CalendarController extends Controller
{
    public function index()
    {
        // Fetch all visits with their clients
        $visits = Visit::with('client')->get();

        // Format visits for the calendar
        $events = $visits->map(function ($visit) {
            return [
                'title' => ($visit->client->name ?? 'Unknown Client') . ' - ' . $visit->activity,
                'start' => $visit->scheduled_at,
            ];
        });

        // Convert events to JSON for the calendar
        $events = $events->toJson();

        return view('calendar.index', compact('events'));
    }
}
