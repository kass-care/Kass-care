@extends('layouts.app')

@section('title', 'Census')

@section('content')
@php
    $isProvider = auth()->user()->role === 'provider';
    $chartUrl = function ($client) use ($isProvider) {
        if ($isProvider && Route::has('provider.patients.workspace')) return route('provider.patients.workspace', $client->id);
        if (Route::has('facility.patients.show')) return route('facility.patients.show', $client->id);
        return null;
    };
    $tabs = [
        'all' => 'All patients',
        'attention' => 'Needs attention',
        'in_care' => 'In care now',
        'none' => 'No visit today',
        'allergies' => 'Allergies',
    ];
    $qs = fn ($extra) => array_filter(array_merge(['q' => $search ?: null, 'inactive' => $showInactive ? 1 : null], $extra));
@endphp

<div class="kc-page">
    <div class="kc-page-head">
        <div>
            <h1>Census</h1>
            <p>Every patient in the facility, sorted by room. Tap a patient to open their chart.</p>
        </div>
        @if(!$isProvider && Route::has('facility.patients.create'))
            <a class="kc-btn kc-btn--primary" href="{{ route('facility.patients.create') }}"><x-kc-icon name="users" /> Add patient</a>
        @endif
    </div>

    @if(!$facilityId)
        <section class="kc-focus">
            <p class="kc-focus-label">Choose a facility</p>
            <h2>Pick a facility first to see its census.</h2>
        </section>
    @else
        <form method="GET" action="{{ route('kc.census') }}" class="kc-census-search" role="search">
            <input type="hidden" name="show" value="{{ $filter }}">
            <label for="kc-q" class="kc-sr">Search patients</label>
            <x-kc-icon name="search" />
            <input id="kc-q" name="q" class="kc-input" value="{{ $search }}" placeholder="Search by name, room or MRN" autocomplete="off">
            <button class="kc-btn kc-btn--primary" type="submit">Search</button>
            @if($search !== '')<a class="kc-btn kc-btn--ghost" href="{{ route('kc.census', $qs(['q' => null, 'show' => $filter])) }}">Clear</a>@endif
        </form>

        <nav class="kc-tabs" aria-label="Filter patients">
            @foreach($tabs as $key => $label)
                <a href="{{ route('kc.census', $qs(['show' => $key])) }}" @if($filter === $key) aria-current="page" @endif>
                    {{ $label }} ({{ $counts[$key] ?? 0 }})
                </a>
            @endforeach
        </nav>

        @if($rows->isEmpty())
            <section class="kc-card">
                <div class="kc-empty">
                    <strong>{{ $search !== '' ? 'No patients match "' . $search . '"' : 'No patients in this view' }}</strong>
                    {{ $search !== '' ? 'Check the spelling, or search by room number instead.' : 'Try another filter above.' }}
                </div>
            </section>
        @else
            <ul class="kc-census">
                @foreach($rows as $row)
                    @php $c = $row->client; $url = $chartUrl($c); @endphp
                    <li class="kc-census-card {{ $row->alerts ? 'has-alert' : '' }}">
                        <a href="{{ $url ?? '#' }}" class="kc-census-link">
                            <img class="kc-census-photo" src="{{ $c->photo_url }}" alt="Photo of {{ $c->name }}">
                            <div class="kc-census-main">
                                <div class="kc-census-top">
                                    <span class="kc-census-name">{{ $c->name ?? 'Unnamed patient' }}</span>
                                    @if(!empty($c->room))<span class="kc-census-room">Room {{ $c->room }}</span>@endif
                                </div>
                                <div class="kc-census-facts">
                                    @php
                                        $age = null;
                                        try { $age = $c->date_of_birth ? \Carbon\Carbon::parse($c->date_of_birth)->age : ($c->age ?? null); } catch (\Throwable $e) {}
                                    @endphp
                                    @if($age)<span>{{ $age }} yrs</span>@endif
                                    @if(!empty($c->gender))<span>{{ ucfirst($c->gender) }}</span>@endif
                                    @if(!empty($c->mrn))<span>MRN {{ $c->mrn }}</span>@endif
                                    @if(!empty($c->status) && strtolower($c->status) !== 'active')<span>{{ ucfirst($c->status) }}</span>@endif
                                </div>
                                <div class="kc-census-chips">
                                    <span class="kc-pill kc-pill--{{ ['in_care' => 'in_progress', 'scheduled' => 'scheduled', 'done' => 'completed', 'none' => 'none'][$row->state] }}">
                                        {{ $row->stateLabel }}{{ $row->caregiver ? ', ' . $row->caregiver : '' }}
                                    </span>
                                    @if($row->alerts)
                                        <span class="kc-pill kc-pill--missed">{{ $row->alerts }} open {{ $row->alerts === 1 ? 'alert' : 'alerts' }}</span>
                                    @endif
                                    @if($row->hasAllergies)
                                        <span class="kc-pill kc-pill--expired" title="{{ $row->allergies }}">Allergies</span>
                                    @endif
                                </div>
                                <p class="kc-census-last">
                                    Last completed visit:
                                    {{ $row->lastVisit ? \Carbon\Carbon::parse($row->lastVisit)->format('M j') : 'none yet' }}
                                </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <p class="kc-hint" style="margin-top:16px">
            @if($showInactive)
                Showing discharged and inactive patients too. <a href="{{ route('kc.census', $qs(['inactive' => null, 'show' => $filter])) }}">Hide them</a>
            @else
                Discharged and inactive patients are hidden. <a href="{{ route('kc.census', $qs(['inactive' => 1, 'show' => $filter])) }}">Show them</a>
            @endif
        </p>
    @endif
</div>
@endsection
