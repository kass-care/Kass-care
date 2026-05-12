<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\Visit;
use App\Models\Claim;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $selectedFacilityId = session('facility_id');
        $selectedFacility = $selectedFacilityId ? Facility::find($selectedFacilityId) : null;

        $facilityCount = Facility::count();

        $clientQuery = Client::query();
        if ($selectedFacilityId) {
            $clientQuery->where('facility_id', $selectedFacilityId);
        }

        $caregiverQuery = User::where('role', 'caregiver');
        if ($selectedFacilityId) {
            $caregiverQuery->where('facility_id', $selectedFacilityId);
        }

        $visitQuery = Visit::query();
        if ($selectedFacilityId) {
            $visitQuery->where('facility_id', $selectedFacilityId);
        }

        $clientCount = $clientQuery->count();
        $caregiverCount = $caregiverQuery->count();
        $visitCount = (clone $visitQuery)->count();

        $scheduledVisitCount = (clone $visitQuery)->where('status', 'scheduled')->count();
        $completedVisitCount = (clone $visitQuery)->where('status', 'completed')->count();
        $missedVisitCount = (clone $visitQuery)->where('status', 'missed')->count();
        $inProgressVisitCount = (clone $visitQuery)->where('status', 'in_progress')->count();

        $alertCount = 0;
        $openTaskCount = 0;
        $reviewTaskCount = 0;

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

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if(!$facilityId, 403, 'No facility selected.');

        $facility = Facility::find($facilityId);

        $patients = Client::where('facility_id', $facilityId)->count();

        $caregivers = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->count();

        $visits = Visit::where('facility_id', $facilityId)->count();

        $providers = User::where('role', 'provider')
            ->where('facility_id', $facilityId)
            ->count();

        return view('admin.facility-home', [
            'facility' => $facility,
            'patients' => $patients,
            'caregivers' => $caregivers,
            'visits' => $visits,
            'providers' => $providers,
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
