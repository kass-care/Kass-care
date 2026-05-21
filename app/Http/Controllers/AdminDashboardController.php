<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Client;
use App\Models\ComplianceDocument;
use App\Models\Facility;
use App\Models\User;
use App\Models\Visit;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $selectedFacilityId = session('facility_id');
        $selectedFacility = $selectedFacilityId ? Facility::find($selectedFacilityId) : null;

        $facilityCount = Facility::count();

        $clientQuery = Client::query();
        $caregiverQuery = User::where('role', 'caregiver');
        $visitQuery = Visit::query();

        if ($selectedFacilityId) {
            $clientQuery->where('facility_id', $selectedFacilityId);
            $caregiverQuery->where('facility_id', $selectedFacilityId);
            $visitQuery->where('facility_id', $selectedFacilityId);
        }

        $clientCount = (clone $clientQuery)->count();
        $caregiverCount = (clone $caregiverQuery)->count();
        $providerCount = User::where('role', 'provider')->count();
        $visitCount = (clone $visitQuery)->count();

        $scheduledVisitCount = (clone $visitQuery)->where('status', 'scheduled')->count();
        $completedVisitCount = (clone $visitQuery)->where('status', 'completed')->count();
        $missedVisitCount = (clone $visitQuery)->where('status', 'missed')->count();
        $inProgressVisitCount = (clone $visitQuery)->where('status', 'in_progress')->count();

        $expiredDocuments = 0;
        $expiringSoonDocuments = 0;

        if (class_exists(ComplianceDocument::class)) {
            $documentQuery = ComplianceDocument::query();

            if ($selectedFacilityId) {
                $documentQuery->where('facility_id', $selectedFacilityId);
            }

            $expiredDocuments = (clone $documentQuery)
                ->whereDate('expires_at', '<', now())
                ->count();

            $expiringSoonDocuments = (clone $documentQuery)
                ->whereDate('expires_at', '>=', now())
                ->whereDate('expires_at', '<=', now()->addDays(30))
                ->count();
        }

        $alertCount = $missedVisitCount + $expiredDocuments;
        $openTaskCount = $scheduledVisitCount + $expiringSoonDocuments;
        $reviewTaskCount = $inProgressVisitCount;

        $facilities = Facility::latest()->get();
        $recentVisits = Visit::latest()->take(5)->get();

                   return view('admin.dashboard', compact(
    'selectedFacilityId',
    'selectedFacility',
    'facilityCount',
    'clientCount',
    'caregiverCount',
    'providerCount',
    'visitCount',
    'scheduledVisitCount',
    'completedVisitCount',
    'missedVisitCount',
    'inProgressVisitCount',
    'expiredDocuments',
    'expiringSoonDocuments',
    'alertCount',
    'openTaskCount',
    'reviewTaskCount',
    'facilities',
    'recentVisits'
));
    }

    public function facilityHome()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $facilityId = session('facility_id') ?? $user->facility_id;

        abort_if(!$facilityId, 403, 'No facility selected.');

        $facility = Facility::findOrFail($facilityId);

        $patients = Client::where('facility_id', $facilityId)->count();

        $caregivers = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->count();

        $providers = User::where('role', 'provider')->count();

        $visits = Visit::where('facility_id', $facilityId)->count();

        $expiredDocuments = ComplianceDocument::where('facility_id', $facilityId)
            ->whereDate('expires_at', '<', now())
            ->count();

        $expiringSoonDocuments = ComplianceDocument::where('facility_id', $facilityId)
            ->whereDate('expires_at', '>=', now())
            ->whereDate('expires_at', '<=', now()->addDays(30))
            ->count();

        return view('admin.facility-home', compact(
            'facility',
            'patients',
            'caregivers',
            'providers',
            'visits',
            'expiredDocuments',
            'expiringSoonDocuments'
        ));
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
