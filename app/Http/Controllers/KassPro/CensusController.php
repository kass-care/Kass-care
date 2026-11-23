<?php

namespace App\Http\Controllers\KassPro;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Census board: every patient in the facility on one screen, with photo,
 * room, allergies, today's care status and open alerts.
 */
class CensusController extends Controller
{
    private const INACTIVE = ['discharged', 'inactive', 'deceased', 'archived'];

    public function index(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && in_array($user->role, ['admin', 'super_admin', 'provider'], true), 403);

        $facilityId = session('facility_id') ?? $user->facility_id;
        if (!$facilityId) {
            return view('kasspro.census', ['facilityId' => null, 'rows' => collect(), 'counts' => [], 'filter' => 'all', 'search' => '', 'showInactive' => false]);
        }

        $search = trim((string) $request->get('q', ''));
        $filter = $request->get('show', 'all');
        $showInactive = $request->boolean('inactive');

        $clients = Client::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->when(!$showInactive && Schema::hasColumn('clients', 'status'), function ($q) {
                $q->where(function ($q) {
                    $q->whereNull('status')->orWhereNotIn('status', self::INACTIVE);
                });
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->whereLike('name', "%{$search}%")
                        ->orWhereLike('room', "%{$search}%")
                        ->orWhereLike('mrn', "%{$search}%");
                });
            })
            ->get();

        $clientIds = $clients->pluck('id');
        $today = Carbon::today();

        // Today's visits per patient
        $todayVisits = Visit::withoutGlobalScopes()
            ->with('caregiver:id,name')
            ->where('facility_id', $facilityId)
            ->whereIn('client_id', $clientIds)
            ->whereDate('visit_date', $today)
            ->get()
            ->groupBy('client_id');

        // Last completed visit per patient
        $lastVisit = Visit::withoutGlobalScopes()
            ->where('facility_id', $facilityId)
            ->whereIn('client_id', $clientIds)
            ->where('status', 'completed')
            ->selectRaw('client_id, max(visit_date) as last_date')
            ->groupBy('client_id')
            ->pluck('last_date', 'client_id');

        // Open vital-sign alerts from the last 7 days, matched to patients through their visits
        $alertCounts = collect();
        if (class_exists(\App\Models\Alert::class) && Schema::hasTable('alerts')) {
            $recentVisits = Visit::withoutGlobalScopes()
                ->where('facility_id', $facilityId)
                ->whereIn('client_id', $clientIds)
                ->whereDate('visit_date', '>=', $today->copy()->subDays(7))
                ->pluck('client_id', 'id');

            if ($recentVisits->isNotEmpty()) {
                $alertCounts = \App\Models\Alert::withoutGlobalScopes()
                    ->whereIn('visit_id', $recentVisits->keys())
                    ->where('resolved', false)
                    ->get(['visit_id'])
                    ->groupBy(fn ($a) => $recentVisits[$a->visit_id] ?? 0)
                    ->map->count();
            }
        }

        $rows = $clients->map(function ($client) use ($todayVisits, $lastVisit, $alertCounts) {
            $visits = $todayVisits->get($client->id, collect())->sortBy(fn ($v) => $v->start_time ?? '99:99');
            $current = $visits->first(fn ($v) => in_array(strtolower($v->status ?? ''), ['in_progress', 'in progress'], true));
            $next = $visits->first(fn ($v) => strtolower($v->status ?? '') === 'scheduled');
            $done = $visits->first(fn ($v) => strtolower($v->status ?? '') === 'completed');

            if ($current) {
                $state = ['in_care', 'In care now', $current->caregiver->name ?? null];
            } elseif ($next) {
                $time = null;
                try { $time = $next->start_time ? Carbon::parse($next->start_time)->format('g:i A') : null; } catch (\Throwable $e) {}
                $state = ['scheduled', $time ? "Visit at {$time}" : 'Visit today', $next->caregiver->name ?? null];
            } elseif ($done) {
                $state = ['done', 'Visit done today', $done->caregiver->name ?? null];
            } else {
                $state = ['none', 'No visit today', null];
            }

            $allergies = trim((string) ($client->allergies ?? ''));
            $hasAllergies = $allergies !== '' && !in_array(strtolower($allergies), ['none', 'nka', 'nkda', 'no known allergies', 'n/a', 'na'], true);

            return (object) [
                'client' => $client,
                'state' => $state[0],
                'stateLabel' => $state[1],
                'caregiver' => $state[2],
                'alerts' => (int) ($alertCounts[$client->id] ?? 0),
                'lastVisit' => $lastVisit[$client->id] ?? null,
                'hasAllergies' => $hasAllergies,
                'allergies' => $allergies,
            ];
        });

        $counts = [
            'all' => $rows->count(),
            'attention' => $rows->where('alerts', '>', 0)->count(),
            'in_care' => $rows->where('state', 'in_care')->count(),
            'none' => $rows->where('state', 'none')->count(),
            'allergies' => $rows->where('hasAllergies', true)->count(),
        ];

        $rows = match ($filter) {
            'attention' => $rows->where('alerts', '>', 0),
            'in_care' => $rows->where('state', 'in_care'),
            'none' => $rows->where('state', 'none'),
            'allergies' => $rows->where('hasAllergies', true),
            default => $rows,
        };

        // Sort by room (natural order: 2 before 10), then by name
        $rows = $rows->sort(function ($a, $b) {
            $ra = (string) ($a->client->room ?? '');
            $rb = (string) ($b->client->room ?? '');
            if ($ra === '' && $rb !== '') return 1;
            if ($rb === '' && $ra !== '') return -1;
            return strnatcasecmp($ra, $rb) ?: strcasecmp((string) $a->client->name, (string) $b->client->name);
        })->values();

        return view('kasspro.census', compact('facilityId', 'rows', 'counts', 'filter', 'search', 'showInactive'));
    }
}
