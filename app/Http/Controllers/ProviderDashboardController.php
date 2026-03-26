<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    /**
     * Provider Dashboard
     */
    public function index()
    {
        $user = auth()->user();

        $visits = Visit::with(['client', 'caregiver'])
            ->when(!empty($user->facility_id), function ($query) use ($user) {
                $query->where('facility_id', $user->facility_id);
            })
            ->latest('visit_date')
            ->get();

        $scheduledVisits = $visits->filter(function ($visit) {
            return strtolower($visit->status ?? '') === 'scheduled';
        })->count();

        $completedVisits = $visits->filter(function ($visit) {
            return strtolower($visit->status ?? '') === 'completed';
        })->count();

        $inProgressVisits = $visits->filter(function ($visit) {
            return in_array(strtolower($visit->status ?? ''), ['in_progress', 'in progress']);
        })->count();

        return view('provider.dashboard', compact(
            'visits',
            'scheduledVisits',
            'completedVisits',
            'inProgressVisits'
        ));
    }

    /**
     * Provider Calendar
     */
    public function calendar()
    {
        $user = auth()->user();

        $visits = Visit::with(['client', 'caregiver'])
            ->when(!empty($user->facility_id), function ($query) use ($user) {
                $query->where('facility_id', $user->facility_id);
            })
            ->latest('visit_date')
            ->get();

        return view('provider.calendar', compact('visits'));
    }

    /**
     * Compliance Dashboard
     */
    public function compliance()
    {
        $user = auth()->user();

        $visits = Visit::with(['client', 'caregiver', 'careLogs', 'providerNote'])
            ->when(!empty($user->facility_id), function ($query) use ($user) {
                $query->where('facility_id', $user->facility_id);
            })
            ->get();

        $missedVisits = $visits->filter(function ($visit) {
            return strtolower($visit->status ?? '') === 'missed';
        });

        $missingCareLogs = $visits->filter(function ($visit) {
            return $visit->careLogs->isEmpty();
        });

        $missingNotes = $visits->filter(function ($visit) {
            return !$visit->providerNote;
        });

        return view('provider.compliance', [
            'missedVisits' => $missedVisits,
            'missingCareLogs' => $missingCareLogs,
            'missingNotes' => $missingNotes,
        ]);
    }
}
