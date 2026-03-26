<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Client;
use App\Models\Caregiver;

class CalendarController extends Controller
{
    public function index()
    {
        $todayVisits = Visit::whereDate('visit_date', today())->count();

        $completedToday = Visit::whereDate('visit_date', today())
            ->where('status', 'completed')
            ->count();

        $missingCareLogs = Visit::where('status', 'completed')
            ->whereDoesntHave('careLogs')
            ->count();

        $missedToday = Visit::where('status', 'missed')->count();

        $clients = Client::all();
        $caregivers = Caregiver::all();

        return view('calendar.index', compact(
            'todayVisits',
            'completedToday',
            'missingCareLogs',
            'missedToday',
            'clients',
            'caregivers'
        ));
    }

    public function events()
    {
        $visits = Visit::with(['client', 'caregiver'])->get();

        $events = [];

        foreach ($visits as $visit) {
            $color = '#3b82f6';

            if ($visit->status === 'completed') {
                $color = '#16a34a';
            } elseif ($visit->status === 'missed') {
                $color = '#dc2626';
            } elseif (in_array($visit->status, ['assigned', 'scheduled', 'pending', 'in_progress'])) {
                $color = '#7c3aed';
            }

            $events[] = [
                'id' => $visit->id,
                'title' => ($visit->client->name ?? 'Client') . ' - ' . ($visit->caregiver->name ?? 'Unassigned'),
                'start' => $visit->visit_date,
                'color' => $color,
                'status' => $visit->status ?? 'scheduled',
                'caregiver_id' => $visit->caregiver_id,
                'check_in_time' => $visit->check_in_time,
                'check_out_time' => $visit->check_out_time,
                'check_in_latitude' => $visit->check_in_latitude,
                'check_in_longitude' => $visit->check_in_longitude,
                'check_out_latitude' => $visit->check_out_latitude,
                'check_out_longitude' => $visit->check_out_longitude,
            ];
        }

        return response()->json($events);
    }

    public function assignCaregiver(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'caregiver_id' => 'required|exists:caregivers,id',
        ]);

        $visit = Visit::findOrFail($request->visit_id);
        $visit->caregiver_id = $request->caregiver_id;
        $visit->status = 'assigned';
        $visit->save();

        return response()->json(['success' => true]);
    }

    public function updateVisitStatus(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'status' => 'required|string',
        ]);

        $visit = Visit::findOrFail($request->visit_id);
        $visit->status = $request->status;

        if ($request->status === 'completed') {
            $visit->visit_completed = true;
        }

        $visit->save();

        return response()->json(['success' => true]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $visit = Visit::findOrFail($request->visit_id);

        $visit->check_in_time = now();
        $visit->visit_started = true;
        $visit->status = 'in_progress';
        $visit->check_in_latitude = $request->latitude;
        $visit->check_in_longitude = $request->longitude;
        $visit->save();

        return response()->json([
            'success' => true,
            'check_in_time' => $visit->check_in_time,
            'check_in_latitude' => $visit->check_in_latitude,
            'check_in_longitude' => $visit->check_in_longitude,
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $visit = Visit::findOrFail($request->visit_id);

        $visit->check_out_time = now();
        $visit->visit_completed = true;
        $visit->status = 'completed';
        $visit->check_out_latitude = $request->latitude;
        $visit->check_out_longitude = $request->longitude;
        $visit->save();

        return response()->json([
            'success' => true,
            'check_out_time' => $visit->check_out_time,
            'check_out_latitude' => $visit->check_out_latitude,
            'check_out_longitude' => $visit->check_out_longitude,
        ]);
    }
}
