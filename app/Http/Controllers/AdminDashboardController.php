<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\Visit;
use App\Models\Claim;
use App\Models\User;
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

        try {
            $clientQuery = Client::query();

            if ($selectedFacilityId && Schema::hasColumn('clients', 'facility_id')) {
                $clientQuery->where('facility_id', $selectedFacilityId);
            }

            $clientCount = $clientQuery->count();
        } catch (\Throwable $e) {
            $clientCount = 0;
        }

        try {
            $caregiverQuery = User::where('role', 'caregiver');

            if ($selectedFacilityId && Schema::hasColumn('users', 'facility_id')) {
                $caregiverQuery->where('facility_id', $selectedFacilityId);
            }

            $caregiverCount = $caregiverQuery->count();
        } catch (\Throwable $e) {
            $caregiverCount = 0;
        }

        try {
            $visitQuery = Visit::query();

            if ($selectedFacilityId && Schema::hasColumn('visits', 'facility_id')) {
                $visitQuery->where('facility_id', $selectedFacilityId);
            }

            $visitCount = (clone $visitQuery)->count();

            if (Schema::hasColumn('visits', 'status')) {
                $scheduledVisitCount = (clone $visitQuery)->where('status', 'scheduled')->count();
                $completedVisitCount = (clone $visitQuery)->where('status', 'completed')->count();
                $missedVisitCount = (clone $visitQuery)->where('status', 'missed')->count();
                $inProgressVisitCount = (clone $visitQuery)->where('status', 'in_progress')->count();
            }
        } catch (\Throwable $e) {
            $visitCount = 0;
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
        $facility = Facility::find($facilityId);

        $patients = 0;
        $caregivers = 0;
        $visits = 0;

        try {
            if ($facilityId && Schema::hasColumn('clients', 'facility_id')) {
                $patients = Client::where('facility_id', $facilityId)->count();
            }
        } catch (\Throwable $e) {
            $patients = 0;
        }

        try {
            if ($facilityId && Schema::hasColumn('users', 'facility_id')) {
                $caregivers = User::where('role', 'caregiver')
                    ->where('facility_id', $facilityId)
                    ->count();
            }
        } catch (\Throwable $e) {
            $caregivers = 0;
        }

        try {
            if ($facilityId && Schema::hasColumn('visits', 'facility_id')) {
                $visits = Visit::where('facility_id', $facilityId)->count();
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

    public function revenue()
    {
        $facilityId = session('facility_id') ?? auth()->user()->facility_id ?? null;

        abort_if(!$facilityId, 403, 'No facility selected.');

        $totalPaid = Claim::where('facility_id', $facilityId)
            ->where('status', 'paid')
            ->sum('estimated_amount');

        $totalPending = Claim::where('facility_id', $facilityId)
            ->whereIn('status', ['draft', 'submitted'])
            ->sum('estimated_amount');

        $totalDenied = Claim::where('facility_id', $facilityId)
            ->where('status', 'denied')
            ->sum('estimated_amount');

        $claims = Claim::with(['client', 'provider'])
            ->where('facility_id', $facilityId)
            ->latest()
            ->get();

        return view('facility.revenue.index', compact(
            'totalPaid',
            'totalPending',
            'totalDenied',
            'claims'
        ));
    }
}
