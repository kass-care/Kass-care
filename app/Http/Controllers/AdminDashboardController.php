<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Caregiver;
use App\Models\Facility;
use App\Models\Visit;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $selectedFacilityId = session('facility_id');
        $selectedFacility = $selectedFacilityId ? Facility::find($selectedFacilityId) : null;

        $facilityCount = Facility::count();

        $clientCount = 0;
        $caregiverCount = 0;
        $visitCount = 0;
        $scheduledVisitCount = 0;
        $completedVisitCount = 0;
        $missedVisitCount = 0;
        $inProgressVisitCount = 0;
        $alertCount = 0;
        $openTaskCount = 0;
        $reviewTaskCount = 0;

        /*
        |--------------------------------------------------------------------------
        | Clients
        |--------------------------------------------------------------------------
        */
        try {
            $clientQuery = Client::query();

            if ($selectedFacilityId && Schema::hasColumn('clients', 'facility_id')) {
                $clientQuery->where('facility_id', $selectedFacilityId);
            }

            $clientCount = $clientQuery->count();
        } catch (\Throwable $e) {
            $clientCount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Caregivers
        |--------------------------------------------------------------------------
        */

            try {

    $caregiverQuery = \App\Models\User::where('role','caregiver');

    if ($selectedFacilityId && Schema::hasColumn('users','facility_id')) {
        $caregiverQuery->where('facility_id',$selectedFacilityId);
    }

    $caregiverCount = $caregiverQuery->count();

} catch (\Throwable $e) {
    $caregiverCount = 0;
}
        /*
        |--------------------------------------------------------------------------
        | Visits
        |--------------------------------------------------------------------------
        */
        try {
            $visitBaseQuery = Visit::query();

            if ($selectedFacilityId && Schema::hasColumn('visits', 'facility_id')) {
                $visitBaseQuery->where('facility_id', $selectedFacilityId);
            }

            $visitCount = (clone $visitBaseQuery)->count();

            if (Schema::hasColumn('visits', 'status')) {
                $scheduledVisitCount = (clone $visitBaseQuery)
                    ->where('status', 'scheduled')
                    ->count();

                $completedVisitCount = (clone $visitBaseQuery)
                    ->where('status', 'completed')
                    ->count();

                $missedVisitCount = (clone $visitBaseQuery)
                    ->where('status', 'missed')
                    ->count();

                $inProgressVisitCount = (clone $visitBaseQuery)
                    ->where('status', 'in_progress')
                    ->count();
            }
        } catch (\Throwable $e) {
            $visitCount = 0;
            $scheduledVisitCount = 0;
            $completedVisitCount = 0;
            $missedVisitCount = 0;
            $inProgressVisitCount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Alerts
        |--------------------------------------------------------------------------
        */
        try {
            if (class_exists(\App\Models\Alert::class)) {
                $alertQuery = \App\Models\Alert::query();

                if ($selectedFacilityId && Schema::hasColumn('alerts', 'facility_id')) {
                    $alertQuery->where('facility_id', $selectedFacilityId);
                }

                $alertCount = $alertQuery->count();
            }
        } catch (\Throwable $e) {
            $alertCount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Tasks
        |--------------------------------------------------------------------------
        */
        try {
            if (class_exists(\App\Models\Task::class)) {
                $openTaskQuery = \App\Models\Task::query();
                $reviewTaskQuery = \App\Models\Task::query();

                if ($selectedFacilityId && Schema::hasColumn('tasks', 'facility_id')) {
                    $openTaskQuery->where('facility_id', $selectedFacilityId);
                    $reviewTaskQuery->where('facility_id', $selectedFacilityId);
                }

                if (Schema::hasColumn('tasks', 'status')) {
                    $openTaskCount = $openTaskQuery
                        ->whereNotIn('status', ['completed', 'cancelled'])
                        ->count();

                    $reviewTaskCount = $reviewTaskQuery
                        ->where('status', 'review')
                        ->count();
                } else {
                    $openTaskCount = $openTaskQuery->count();
                    $reviewTaskCount = 0;
                }
            }
        } catch (\Throwable $e) {
            $openTaskCount = 0;
            $reviewTaskCount = 0;
        }

        $facilities = Facility::latest()->get();

        return view('admin.dashboard', compact(
            'selectedFacilityId',
            'selectedFacility',
            'facilityCount',
            'clientCount',
            'caregiverCount',
            'visitCount',
            'scheduledVisitCount',
            'completedVisitCount',
            'missedVisitCount',
            'inProgressVisitCount',
            'alertCount',
            'openTaskCount',
            'reviewTaskCount',
            'facilities'
        ));
    }

    public function facilityHome()
    {
        $user = auth()->user();

        $facilityId = $user->facility_id;
        $facility = \App\Models\Facility::find($facilityId);

        $patients = 0;
        $caregivers = 0;
        $visits = 0;

        try {
            if (Schema::hasColumn('clients', 'facility_id')) {
                $patients = \App\Models\Client::where('facility_id', $facilityId)->count();
            }
        } catch (\Throwable $e) {
            $patients = 0;
        }

        try {
            if (Schema::hasColumn('caregivers', 'facility_id')) {
                $caregivers = \App\Models\Caregiver::where('facility_id', $facilityId)->count();
            }
        } catch (\Throwable $e) {
            $caregivers = 0;
        }

        try {
            if (Schema::hasColumn('visits', 'facility_id')) {
                $visits = \App\Models\Visit::where('facility_id', $facilityId)->count();
            }
        } catch (\Throwable $e) {
            $visits = 0;
        }

        return view('admin.facility-home', [
            'facility' => $facility,
            'patients' => $patients,
            'caregivers' => $caregivers,
            'visits' => $visits,
        ]);
    }
}
