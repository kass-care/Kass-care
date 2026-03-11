<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evv;
use App\Models\Visit;

class EvvController extends Controller
{

    // CLOCK IN
    public function start(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'caregiver_id' => 'required|exists:caregivers,id'
        ]);

        $evv = Evv::create([
            'visit_id' => $request->visit_id,
            'caregiver_id' => $request->caregiver_id,
            'clock_in_time' => now(),
            'gps_lat' => $request->gps_lat,
            'gps_lng' => $request->gps_lng
        ]);

        return response()->json($evv);
    }


    // CLOCK OUT
    public function end(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'caregiver_id' => 'required|exists:caregivers,id'
        ]);

        $evv = Evv::where('visit_id', $request->visit_id)
                  ->where('caregiver_id', $request->caregiver_id)
                  ->first();

        if (!$evv) {
            return response()->json([
                'message' => 'Clock-in record not found'
            ], 404);
        }

        $evv->clock_out_time = now();

        // Calculate visit duration
        $start = strtotime($evv->clock_in_time);
        $end = strtotime($evv->clock_out_time);

        $minutes = round(($end - $start) / 60);

        $evv->total_minutes = $minutes;

        $evv->save();

        return response()->json($evv);
    }

}