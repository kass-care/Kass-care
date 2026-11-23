@extends('layouts.app')

@section('title', 'Home')

@section('content')
@php
    use Carbon\Carbon;

    $todayVisits = collect($todayVisits ?? []);
    $lateVisits = collect($lateVisits ?? []);
    $credentialAlerts = collect($credentialAlerts ?? []);
    $r = fn ($name, $params = []) => Route::has($name) ? route($name, $params) : null;

    $time = function ($v) {
        if (empty($v)) return null;
        try { return Carbon::parse($v)->format('g:i A'); } catch (\Throwable $e) { return null; }
    };
    $statusKey = fn ($s) => str_replace(' ', '_', strtolower($s ?? 'scheduled'));
    $statusText = fn ($s) => ucfirst(str_replace('_', ' ', $s ?? 'scheduled'));

    $inProgress = $todayVisits->filter(fn ($v) => $statusKey($v->status) === 'in_progress')->count();
    $done = $todayVisits->filter(fn ($v) => $statusKey($v->status) === 'completed')->count();
    $issues = $lateVisits->count() + (int) ($unassignedCount ?? 0) + $credentialAlerts->count();
    $facilityName = $facility->name ?? auth()->user()->facility?->name ?? 'your agency';
@endphp

<div class="kc-page">
    <div class="kc-page-head">
        <div>
            <h1>{{ $facilityName }}</h1>
            <p>{{ now()->format('l, F j') }}. Here is your agency today.</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            @if($r('facility.visits.create'))<a class="kc-btn kc-btn--primary" href="{{ $r('facility.visits.create') }}"><x-kc-icon name="calendar" /> Schedule a visit</a>@endif
            @if($r('facility.patients.create'))<a class="kc-btn kc-btn--ghost" href="{{ $r('facility.patients.create') }}"><x-kc-icon name="users" /> Add patient</a>@endif
        </div>
    </div>

    @if(!$facility)
        <section class="kc-focus">
            <p class="kc-focus-label">Almost ready</p>
            <h2>Your account is not linked to a facility yet.</h2>
            <p class="kc-focus-meta">Contact KASSCare support to finish setting up your agency.</p>
        </section>
    @else

    {{-- What needs fixing now --}}
    <section class="kc-focus {{ $issues ? '' : 'kc-focus--live' }}" aria-label="Needs attention">
        <p class="kc-focus-label">{{ $issues ? 'Needs your attention' : 'Everything is on track' }}</p>
        <h2>
            @if($lateVisits->count())
                {{ $lateVisits->count() }} {{ $lateVisits->count() === 1 ? 'caregiver has' : 'caregivers have' }} not checked in
            @elseif($unassignedCount ?? 0)
                {{ $unassignedCount }} upcoming {{ $unassignedCount === 1 ? 'visit has' : 'visits have' }} no caregiver
            @elseif($credentialAlerts->count())
                {{ $credentialAlerts->count() }} staff {{ $credentialAlerts->count() === 1 ? 'credential needs' : 'credentials need' }} renewing
            @else
                No late visits, gaps or expired credentials
            @endif
        </h2>
        <p class="kc-focus-meta">
            {{ $todayVisits->count() }} visits today: {{ $inProgress }} happening now, {{ $done }} completed.
        </p>
        <div class="kc-focus-actions">
            @if($r('facility.visits.index'))<a class="kc-btn kc-btn--light" href="{{ $r('facility.visits.index') }}"><x-kc-icon name="calendar" /> Open visits</a>@endif
            @if($credentialAlerts->count() && $r('kc.credentials.index'))<a class="kc-btn kc-btn--ghost" style="color:#fff;border-color:rgba(255,255,255,.25)" href="{{ $r('kc.credentials.index') }}"><x-kc-icon name="shield" /> Check credentials</a>@endif
        </div>
    </section>

    <div class="kc-tiles">
        <a class="kc-tile" href="{{ $r('facility.patients.index') ?? '#' }}"><b>{{ $clientsCount ?? 0 }}</b><span>Patients</span></a>
        <a class="kc-tile" href="{{ $r('facility.caregivers.index') ?? '#' }}"><b>{{ $caregiversCount ?? 0 }}</b><span>Caregivers</span></a>
        <a class="kc-tile" href="{{ $r('providers.index') ?? '#' }}"><b>{{ $providersCount ?? 0 }}</b><span>Providers</span></a>
        <a class="kc-tile" href="{{ $r('kc.family.index') ?? '#' }}"><b>{{ $familyCount ?? 0 }}</b><span>Family members</span></a>
    </div>

    <div class="kc-grid kc-grid--2-1">
        <section class="kc-card">
            <div class="kc-card-head">
                <h2>Today's visits</h2>
                @if($r('facility.visits.index'))<a href="{{ $r('facility.visits.index') }}">All visits</a>@endif
            </div>
            @if($todayVisits->isEmpty())
                <div class="kc-empty"><strong>No visits today</strong>Schedule visits so caregivers see them on their phones.</div>
            @else
                <div class="kc-table-wrap">
                    <table class="kc-table">
                        <thead><tr><th>Time</th><th>Patient</th><th>Caregiver</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($todayVisits as $visit)
                            @php $late = $lateVisits->contains('id', $visit->id); @endphp
                            <tr>
                                <td><strong>{{ $time($visit->start_time) ?? '—' }}</strong></td>
                                <td>{{ $visit->client->name ?? 'Unknown' }}</td>
                                <td>
                                    @if($visit->caregiver?->name)
                                        {{ $visit->caregiver->name }}
                                    @else
                                        <span class="kc-pill kc-pill--missed">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($late)
                                        <span class="kc-pill kc-pill--missed">Late</span>
                                    @else
                                        <span class="kc-pill kc-pill--{{ $statusKey($visit->status) }}">{{ $statusText($visit->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="kc-grid">
            <section class="kc-card">
                <div class="kc-card-head">
                    <h2>Credentials to renew</h2>
                    @if($r('kc.credentials.index'))<a href="{{ $r('kc.credentials.index') }}">Manage</a>@endif
                </div>
                @if($credentialAlerts->isEmpty())
                    <div class="kc-empty">Nothing expiring in the next 30 days.</div>
                @else
                    <ul class="kc-list">
                        @foreach($credentialAlerts as $c)
                            <li>
                                <div class="kc-list-main">
                                    <p class="kc-list-title">{{ $c->user->name ?? 'Staff member' }}</p>
                                    <p class="kc-list-sub">{{ $c->type }}, {{ $c->state === 'expired' ? 'expired' : 'expires' }} {{ $c->expires_on?->format('M j') }}</p>
                                </div>
                                <span class="kc-pill kc-pill--{{ $c->state }}">{{ $c->state === 'expired' ? 'Expired' : 'Soon' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="kc-card" style="padding:16px 20px 20px">
                <h2 style="margin:0 0 12px;font-size:19px">Quick actions</h2>
                <div style="display:grid;gap:8px">
                    @if($r('facility.caregivers.create'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('facility.caregivers.create') }}"><x-kc-icon name="heart" /> Add caregiver</a>@endif
                    @if($r('kc.family.index'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('kc.family.index') }}"><x-kc-icon name="users" /> Invite a family member</a>@endif
                    @if($r('facility.messages.create'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('facility.messages.create') }}"><x-kc-icon name="message" /> Message a provider</a>@endif
                    @if($r('facility.revenue'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('facility.revenue') }}"><x-kc-icon name="chart" /> Revenue</a>@endif
                </div>
            </section>
        </div>
    </div>
    @endif
</div>
@endsection
