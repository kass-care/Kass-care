<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Caregiver;
use Carbon\Carbon;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user, 403, 'Unauthorized.');

        $caregiver = Caregiver::where('user_id', $user->id)->first();

        $visits = collect();
        $todayVisits = collect();
        $activeVisit = null;

        if ($caregiver) {
            $visits = Visit::with(['client', 'caregiver'])
                ->where('caregiver_id', $caregiver->id)
                ->latest()
                ->get();

            $todayVisits = $visits->filter(function ($visit) {
                if (!empty($visit->scheduled_at)) {
                    return Carbon::parse($visit->scheduled_at)->isToday();
                }

                if (!empty($visit->visit_date)) {
                    return Carbon::parse($visit->visit_date)->isToday();
                }

                return false;
            });

            $activeVisit = $todayVisits->first();
        }

        return view('caregiver.dashboard', [
            'caregiver' => $caregiver,
            'visits' => $visits,
            'todayVisits' => $todayVisits,
            'activeVisit' => $activeVisit,
        ]);
    }
}
