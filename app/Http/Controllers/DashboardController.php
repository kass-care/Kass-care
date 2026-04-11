<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Visit;
use App\Models\Facility;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the current Facility from the session
        $selectedFacilityId = session('facility_id');

        // 2. Main Stats (KPIs)
        $facilityCount = Facility::count();

        if ($selectedFacilityId) {
            $clientCount = Client::where('facility_id', $selectedFacilityId)->count();
            $caregiverCount = Caregiver::where('facility_id', $selectedFacilityId)->count();
            $visitCount = Visit::where('facility_id', $selectedFacilityId)->count();
            
            // Intelligence Stats
            $scheduledVisitCount = Visit::where('facility_id', $selectedFacilityId)->where('status', 'scheduled')->count();
            $completedVisitCount = Visit::where('facility_id', $selectedFacilityId)->where('status', 'completed')->count();
        } else {
            // Super Admin view (Total platform counts)
            $clientCount = Client::count();
            $caregiverCount = Caregiver::count();
            $visitCount = Visit::count();
            $scheduledVisitCount = Visit::where('status', 'scheduled')->count();
            $completedVisitCount = Visit::where('status', 'completed')->count();
        }

        // 3. Alerts & Tasks (Using your safe try-catch logic)
        $alertCount = 0;
        $openTaskCount = 0;
        $reviewTaskCount = 0;

        try {
            if (class_exists(\App\Models\Alert::class)) {
                $alertQuery = \App\Models\Alert::query();
                if ($selectedFacilityId) {
                    $alertQuery->where('facility_id', $selectedFacilityId);
                }
                $alertCount = $alertQuery->count();
            }
        } catch (\Throwable $e) { $alertCount = 0; }

        try {
            if (class_exists(\App\Models\Task::class)) {
                $taskQuery = \App\Models\Task::query();
                if ($selectedFacilityId) {
                    $taskQuery->where('facility_id', $selectedFacilityId);
                }
                $openTaskCount = (clone $taskQuery)->whereNotIn('status', ['completed', 'cancelled'])->count();
                $reviewTaskCount = (clone $taskQuery)->where('status', 'review')->count();
            }
        } catch (\Throwable $e) { 
            $openTaskCount = 0; 
            $reviewTaskCount = 0; 
        }

        // 4. Facility List for the Command Center
        $facilities = Facility::latest()->get();

        // 5. Return all data to the view
        return view('dashboard.index', compact(
            'selectedFacilityId',
            'facilityCount',
            'clientCount',
            'caregiverCount',
            'visitCount',
            'alertCount',
            'openTaskCount',
            'reviewTaskCount',
            'scheduledVisitCount',
            'completedVisitCount',
            'facilities'
        ));
    }
}
