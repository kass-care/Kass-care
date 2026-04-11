<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use Carbon\Carbon;

class EvvController extends Controller
{
    public function checkIn(Request $request, $visitId)
    {
        $visit = Visit::findOrFail($visitId);

        $visit->check_in_time = Carbon::now();
        $visit->check_in_latitude = $request->latitude;
        $visit->check_in_longitude = $request->longitude;
        $visit->visit_started = true;
        $visit->visit_started_at = Carbon::now();
        $visit->status = 'in_progress';

        $visit->save();

        return back()->with('success', 'Visit checked in successfully.');
    }

    public function checkOut(Request $request, $visitId)
    {
        $visit = Visit::findOrFail($visitId);

        $visit->check_out_time = Carbon::now();
        $visit->check_out_latitude = $request->latitude;
        $visit->check_out_longitude = $request->longitude;
        $visit->visit_completed = true;
        $visit->visit_completed_at = Carbon::now();
        $visit->status = 'completed';

        if ($visit->check_in_time) {
            $visit->duration_minutes = Carbon::parse($visit->check_in_time)
                ->diffInMinutes(Carbon::now());
        }

        $visit->save();

        return back()->with('success', 'Visit checked out successfully.');
    }
}
