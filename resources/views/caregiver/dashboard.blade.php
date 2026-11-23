@extends('layouts.app')

@section('title', 'Today')

@section('content')
@php
    use Carbon\Carbon;

    $todayVisits = collect($todayVisits ?? []);
    $assignedClients = collect($assignedClients ?? []);
    $visits = collect($visits ?? []);
    $completedVisitsCount = $completedVisitsCount ?? 0;
    $activeVisit = $activeVisit ?? null;

    $clientName = function ($client) {
        if (!$client) return 'Unknown client';
        if (!empty($client->full_name)) return $client->full_name;
        if (!empty($client->name)) return $client->name;
        $name = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
        return $name ?: 'Unknown client';
    };

    $time = function ($value) {
        if (empty($value)) return null;
        try { return Carbon::parse($value)->format('g:i A'); } catch (\Throwable $e) { return null; }
    };

    $statusKey = fn ($s) => str_replace(' ', '_', strtolower($s ?? 'scheduled'));
    $statusText = fn ($s) => ucfirst(str_replace('_', ' ', $s ?? 'scheduled'));

    $sortedToday = $todayVisits->sortBy(fn ($v) => $v->start_time ?? '99:99')->values();
    $nextVisit = $activeVisit ? null : $sortedToday->first(fn ($v) => $statusKey($v->status ?? '') === 'scheduled');
    $doneToday = $sortedToday->filter(fn ($v) => $statusKey($v->status ?? '') === 'completed')->count();
    $leftToday = $sortedToday->count() - $doneToday;
    $firstName = explode(' ', trim(auth()->user()->name ?? 'there'))[0];
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

<div class="kc-page">

    <div class="kc-greeting">
        <h1>{{ $greeting }}, {{ $firstName }}</h1>
        <p>{{ now()->format('l, F j') }}</p>
    </div>

    {{-- The one thing to do right now --}}
    @if ($activeVisit)
        <section class="kc-focus kc-focus--live" aria-label="Visit in progress">
            <p class="kc-focus-label">You are with</p>
            <h2>{{ $clientName($activeVisit->client ?? null) }}</h2>
            <p class="kc-focus-meta">
                Checked in {{ $time($activeVisit->check_in_time ?? null) ?? 'earlier' }}
            </p>
            <div class="kc-focus-actions">
                <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $activeVisit->id]) }}" class="kc-btn kc-btn--light">
                    <x-kc-icon name="clipboard" /> Write care log
                </a>
                <a href="{{ route('caregiver.checkout', $activeVisit->id) }}" class="kc-btn kc-btn--danger">
                    <x-kc-icon name="logout" /> Check out
                </a>
            </div>
        </section>
    @elseif ($nextVisit)
        <section class="kc-focus" aria-label="Next visit">
            <p class="kc-focus-label">Next visit{{ $time($nextVisit->start_time ?? null) ? ' at ' . $time($nextVisit->start_time) : '' }}</p>
            <h2>{{ $clientName($nextVisit->client ?? null) }}</h2>
            <p class="kc-focus-meta">Check in when you arrive at the client's home.</p>
            <div class="kc-focus-actions">
                <a href="{{ route('caregiver.checkin', $nextVisit->id) }}" class="kc-btn kc-btn--primary" style="background:#fff;color:var(--kc-ink)">
                    <x-kc-icon name="login" /> Check in
                </a>
                <a href="{{ route('caregiver.visits') }}" class="kc-btn kc-btn--ghost" style="color:#fff;border-color:rgba(255,255,255,.25)">
                    All visits
                </a>
            </div>
        </section>
    @else
        <section class="kc-focus" aria-label="Your day">
            <p class="kc-focus-label">{{ $sortedToday->count() ? 'All done for today' : 'Nothing scheduled' }}</p>
            <h2>{{ $sortedToday->count() ? 'Great work today.' : 'No visits today.' }}</h2>
            <p class="kc-focus-meta">New visits from your facility will show up here.</p>
            <div class="kc-focus-actions">
                <a href="{{ route('caregiver.visits') }}" class="kc-btn kc-btn--light">
                    <x-kc-icon name="calendar" /> See all visits
                </a>
            </div>
        </section>
    @endif

    {{-- Shortcuts to the caregiver tools --}}
    <div class="kc-tiles" style="margin-bottom:16px">
        @if(Route::has('caregiver.shifts.index'))
            <a class="kc-tile" href="{{ route('caregiver.shifts.index') }}"><x-kc-icon name="clock" style="width:26px;height:26px;color:var(--kc-teal)" /><span style="display:block;margin-top:6px;font-weight:700;color:var(--kc-ink)">My shifts</span></a>
        @endif
        @if(Route::has('caregiver.emar.index'))
            <a class="kc-tile" href="{{ route('caregiver.emar.index') }}"><x-kc-icon name="pill" style="width:26px;height:26px;color:var(--kc-teal)" /><span style="display:block;margin-top:6px;font-weight:700;color:var(--kc-ink)">Medications</span></a>
        @endif
        @if(Route::has('caregiver.care-logs.index'))
            <a class="kc-tile" href="{{ route('caregiver.care-logs.index') }}"><x-kc-icon name="clipboard" style="width:26px;height:26px;color:var(--kc-teal)" /><span style="display:block;margin-top:6px;font-weight:700;color:var(--kc-ink)">Care logs</span></a>
        @endif
        @if(Route::has('caregiver.incidents.index'))
            <a class="kc-tile kc-tile--bad" href="{{ route('caregiver.incidents.index') }}"><x-kc-icon name="alert" style="width:26px;height:26px;color:#9a2c1b" /><span style="display:block;margin-top:6px;font-weight:700;color:#9a2c1b">Report an incident</span></a>
        @endif
    </div>

    <ul class="kc-stats">
        <li><strong>{{ $sortedToday->count() }}</strong> visits today</li>
        <li><strong>{{ max($leftToday, 0) }}</strong> still to go</li>
        <li><strong>{{ $completedVisitsCount }}</strong> completed in total</li>
    </ul>

    <div class="kc-grid kc-grid--2-1">

        {{-- Today's schedule, in time order --}}
        <section class="kc-card">
            <div class="kc-card-head">
                <h2>Today's schedule</h2>
                <a href="{{ route('caregiver.visits') }}">All visits</a>
            </div>

            @if ($sortedToday->isEmpty())
                <div class="kc-empty">
                    <strong>No visits scheduled today</strong>
                    Enjoy the rest. Check back later for new assignments.
                </div>
            @else
                <ol class="kc-timeline">
                    @foreach ($sortedToday as $visit)
                        @php $key = $statusKey($visit->status ?? 'scheduled'); @endphp
                        <li>
                            <div class="kc-time">
                                {{ $time($visit->start_time ?? null) ?? 'Any time' }}
                                @if($time($visit->end_time ?? null))<small>to {{ $time($visit->end_time) }}</small>@endif
                            </div>
                            <div>
                                <p class="kc-visit-name">{{ $clientName($visit->client ?? null) }}</p>
                                <span class="kc-pill kc-pill--{{ $key }}">{{ $statusText($visit->status ?? 'scheduled') }}</span>

                                <div class="kc-visit-actions">
                                    @if ($key === 'scheduled')
                                        <a href="{{ route('caregiver.checkin', $visit->id) }}" class="kc-btn kc-btn--primary kc-btn--sm">Check in</a>
                                    @elseif ($key === 'in_progress')
                                        <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $visit->id]) }}" class="kc-btn kc-btn--ghost kc-btn--sm">Care log</a>
                                        <a href="{{ route('caregiver.checkout', $visit->id) }}" class="kc-btn kc-btn--danger kc-btn--sm">Check out</a>
                                    @endif
                                    @if (in_array($key, ['scheduled', 'in_progress']) && Route::has('caregiver.visits.report-issue'))
                                        <a href="{{ route('caregiver.visits.report-issue', $visit->id) }}" class="kc-btn kc-btn--ghost kc-btn--sm">Report a problem</a>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>

        <div class="kc-grid">
            {{-- Clients --}}
            <section class="kc-card">
                <div class="kc-card-head"><h2>My clients</h2></div>
                @if ($assignedClients->isEmpty())
                    <div class="kc-empty">Clients appear here after your first visit.</div>
                @else
                    <ul class="kc-list">
                        @foreach ($assignedClients->take(8) as $client)
                            <li>
                                @if(!empty($client->photo))
                                    <img src="{{ asset('storage/' . $client->photo) }}" alt="" width="44" height="44" style="width:44px;height:44px;border-radius:12px;object-fit:cover;flex:none">
                                @else
                                    <span class="kc-avatar" style="border-radius:12px;width:44px;height:44px">{{ strtoupper(mb_substr($client->name ?? 'P', 0, 1)) }}</span>
                                @endif
                                <div class="kc-list-main" style="flex:1">
                                    <p class="kc-list-title">
                                        @if(!empty($client->visit_id))
                                            <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $client->visit_id]) }}" style="color:inherit;text-decoration:none">{{ $client->name ?? $clientName($client) }}</a>
                                        @else
                                            {{ $client->name ?? $clientName($client) }}
                                        @endif
                                    </p>
                                    <p class="kc-list-sub">
                                        Last visit
                                        {{ !empty($client->latest_visit_date) ? Carbon::parse($client->latest_visit_date)->format('M j') : 'not yet' }}
                                    </p>
                                </div>
                                <span class="kc-pill kc-pill--{{ $statusKey($client->latest_status ?? '') }}">{{ $statusText($client->latest_status ?? 'n/a') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            {{-- Recent activity --}}
            <section class="kc-card">
                <div class="kc-card-head">
                    <h2>Recent visits</h2>
                    <a href="{{ route('caregiver.care-logs.index') }}">Care logs</a>
                </div>
                @if ($visits->isEmpty())
                    <div class="kc-empty">Your visit history will show here.</div>
                @else
                    <ul class="kc-list">
                        @foreach ($visits->take(5) as $visit)
                            <li>
                                <div class="kc-list-main">
                                    <p class="kc-list-title">{{ $clientName($visit->client ?? null) }}</p>
                                    <p class="kc-list-sub">{{ !empty($visit->updated_at) ? Carbon::parse($visit->updated_at)->diffForHumans() : '' }}</p>
                                </div>
                                <span class="kc-pill kc-pill--{{ $statusKey($visit->status ?? '') }}">{{ $statusText($visit->status ?? 'n/a') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
