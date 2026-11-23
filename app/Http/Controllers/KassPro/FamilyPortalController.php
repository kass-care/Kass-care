<?php

namespace App\Http\Controllers\KassPro;

use App\Http\Controllers\Controller;
use App\Models\CareLog;
use App\Models\Visit;
use Carbon\Carbon;

/**
 * Family portal: a read-only, plain-language view of a loved one's care.
 * Family members only ever see the one client they are linked to.
 */
class FamilyPortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'family', 403);

        $client = $user->client_id ? $user->familyClient()->withoutGlobalScopes()->first() : null;

        if (!$client || (int) $client->facility_id !== (int) $user->facility_id) {
            return view('kasspro.family-dashboard', ['client' => null]);
        }

        $visits = Visit::withoutGlobalScopes()
            ->with('caregiver:id,name')
            ->where('client_id', $client->id)
            ->where('facility_id', $user->facility_id)
            ->orderByDesc('visit_date')
            ->orderByDesc('id')
            ->take(40)
            ->get();

        $today = Carbon::today();
        $isToday = fn ($v) => !empty($v->visit_date) && Carbon::parse($v->visit_date)->isSameDay($today);

        $current = $visits->first(fn ($v) => in_array(strtolower($v->status ?? ''), ['in_progress', 'in progress']));
        $todayVisits = $visits->filter($isToday)->sortBy(fn ($v) => $v->start_time ?? '99:99')->values();
        $upcoming = $visits
            ->filter(fn ($v) => !empty($v->visit_date) && Carbon::parse($v->visit_date)->gt($today) && strtolower($v->status ?? '') === 'scheduled')
            ->sortBy(fn ($v) => $v->visit_date . ' ' . ($v->start_time ?? ''))
            ->take(5)
            ->values();
        $recent = $visits
            ->filter(fn ($v) => strtolower($v->status ?? '') === 'completed')
            ->take(8)
            ->values();

        $notes = CareLog::withoutGlobalScopes()
            ->whereIn('visit_id', $recent->pluck('id'))
            ->latest()
            ->get()
            ->groupBy('visit_id');

        return view('kasspro.family-dashboard', compact('client', 'current', 'todayVisits', 'upcoming', 'recent', 'notes'));
    }
}
