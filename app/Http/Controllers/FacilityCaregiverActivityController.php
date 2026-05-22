<?php

namespace App\Http\Controllers;

use App\Models\CareLog;
use App\Models\EmarAdministration;
use App\Models\User;
use App\Models\Visit;

class FacilityCaregiverActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_if(!$user, 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        abort_if(!$facilityId, 403, 'No facility selected.');

        $today = now()->toDateString();

        $visitsToday = Visit::with(['client', 'caregiver'])
            ->where('facility_id', $facilityId)
            ->whereDate('visit_date', $today)
            ->latest()
            ->get();

        $visitIdsToday = $visitsToday->pluck('id');

        $careLogsToday = CareLog::with(['visit.client', 'visit.caregiver'])
            ->whereIn('visit_id', $visitIdsToday)
            ->latest()
            ->get();

        $emarToday = EmarAdministration::with(['client', 'caregiver', 'medication'])
            ->where('facility_id', $facilityId)
            ->whereDate('scheduled_date', $today)
            ->latest()
            ->get();

        $caregivers = User::where('role', 'caregiver')
            ->where('facility_id', $facilityId)
            ->orderBy('name')
            ->get()
            ->map(function ($caregiver) use ($visitsToday, $careLogsToday, $emarToday) {
                $caregiverVisits = $visitsToday->where('caregiver_id', $caregiver->id);
                $caregiverLogs = $careLogsToday->filter(function ($log) use ($caregiver) {
                    return optional($log->visit)->caregiver_id === $caregiver->id;
                });
                $caregiverMeds = $emarToday->where('caregiver_id', $caregiver->id);

                $lastVisit = $caregiverVisits->sortByDesc('updated_at')->first();
                $lastLog = $caregiverLogs->sortByDesc('updated_at')->first();
                $lastMed = $caregiverMeds->sortByDesc('updated_at')->first();

                $lastActivityAt = collect([
                    optional($lastVisit)->updated_at,
                    optional($lastLog)->updated_at,
                    optional($lastMed)->updated_at,
                ])->filter()->sortDesc()->first();

                $caregiver->today_visits_count = $caregiverVisits->count();
                $caregiver->completed_visits_count = $caregiverVisits->where('status', 'completed')->count();
                $caregiver->care_logs_count = $caregiverLogs->count();
                $caregiver->meds_signed_count = $caregiverMeds->where('status', 'administered')->count();
                $caregiver->last_activity_at = $lastActivityAt;

                return $caregiver;
            });

        $summary = [
            'visits_today' => $visitsToday->count(),
            'completed_visits' => $visitsToday->where('status', 'completed')->count(),
            'in_progress_visits' => $visitsToday->where('status', 'in_progress')->count(),
            'care_logs_today' => $careLogsToday->count(),
            'meds_administered' => $emarToday->where('status', 'administered')->count(),
            'meds_not_administered' => $emarToday->whereIn('status', ['missed', 'refused', 'held'])->count(),
        ];

        return view('facility.caregiver-activity.index', compact(
            'summary',
            'visitsToday',
            'careLogsToday',
            'emarToday',
            'caregivers'
        ));
    }
}
