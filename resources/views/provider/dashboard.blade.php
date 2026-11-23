@extends('layouts.app')

@section('title', 'Home')

@section('content')
@php
    use Carbon\Carbon;

    $patients = collect($patients ?? []);
    $flaggedCareLogs = collect($flaggedCareLogs ?? []);
    $providerFacilities = collect($providerFacilities ?? []);
    $r = fn ($name, $params = []) => Route::has($name) ? route($name, $params) : null;

    $firstName = explode(' ', trim(auth()->user()->name ?? ''))[0] ?: 'there';
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

    $alertTypes = [
        ['high_bp', 'High blood pressure', $highBpCount ?? 0],
        ['low_oxygen', 'Low oxygen', $lowOxygenCount ?? 0],
        ['fever', 'Fever', $feverCount ?? 0],
        ['pulse', 'Pulse', $pulseIssueCount ?? 0],
    ];
    $flagged = (int) ($flaggedCount ?? $flaggedCareLogs->count());

    $unreadMessages = 0;
    try {
        $unreadMessages = \App\Models\ProviderMessage::where('provider_id', auth()->id())->whereNull('read_at')->count();
    } catch (\Throwable $e) {}

    $pendingMeds = 0;
    try {
        if (!empty($facilityId)) {
            $pendingMeds = \App\Models\Medication::where('approval_status', 'pending')
                ->whereHas('client', fn ($q) => $q->where('facility_id', $facilityId))
                ->count();
        }
    } catch (\Throwable $e) {}

    $todo = [
        ['Abnormal vitals to review', $flagged, $r('provider.alerts.index', ['type' => 'all']), 'activity'],
        ['Medications waiting for approval', $pendingMeds, $r('provider.medication-approvals.index'), 'pill'],
        ['Unread facility messages', $unreadMessages, $r('provider.messages.index'), 'message'],
    ];
    $todoTotal = $flagged + $pendingMeds + $unreadMessages;
@endphp

<div class="kc-page">

    <div class="kc-greeting" style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between">
        <div>
            <h1>{{ $greeting }}, {{ $firstName }}</h1>
            <p>{{ now()->format('l, F j') }}{{ isset($selectedFacility) && $selectedFacility ? ' at ' . $selectedFacility->name : '' }}</p>
        </div>

        @if($providerFacilities->count() > 1 && Route::has('select.facility'))
            <form method="POST" action="{{ route('select.facility', 0) }}" style="min-width:240px">
                @csrf
                <label for="kc-facility" class="kc-list-sub" style="display:block;margin-bottom:4px;font-weight:700">Facility</label>
                <select id="kc-facility" name="facility_id" class="kc-input"
                        onchange="this.form.action='/select-facility/' + (this.value || 0); this.form.submit();">
                    <option value="">Choose a facility</option>
                    @foreach($providerFacilities as $facility)
                        <option value="{{ $facility->id }}" @selected((int) ($facilityId ?? 0) === (int) $facility->id)>{{ $facility->name }}</option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

    @if(empty($facilityId))
        <section class="kc-focus" aria-label="Choose a facility">
            <p class="kc-focus-label">Start here</p>
            <h2>Choose a facility to see your patients.</h2>
            <p class="kc-focus-meta">
                {{ $providerFacilities->count() ? 'Pick one from the list above.' : 'No facility has added you yet. Ask the agency admin to link your account.' }}
            </p>
        </section>
    @else

        {{-- The one thing that matters most right now --}}
        <section class="kc-focus {{ $todoTotal ? '' : 'kc-focus--live' }}" aria-label="Your to-do">
            <p class="kc-focus-label">{{ $todoTotal ? 'Needs your attention' : 'All clear' }}</p>
            <h2>
                @if($flagged)
                    {{ $flagged }} care {{ $flagged === 1 ? 'log has' : 'logs have' }} abnormal vitals
                @elseif($pendingMeds)
                    {{ $pendingMeds }} {{ $pendingMeds === 1 ? 'medication is' : 'medications are' }} waiting for your approval
                @elseif($unreadMessages)
                    {{ $unreadMessages }} unread {{ $unreadMessages === 1 ? 'message' : 'messages' }} from facilities
                @else
                    Nothing waiting for you right now
                @endif
            </h2>
            <p class="kc-focus-meta">
                @if($flagged)
                    @foreach(array_filter($alertTypes, fn ($t) => $t[2] > 0) as $t){{ $loop->first ? '' : ', ' }}{{ $t[1] }} {{ $t[2] }}@endforeach
                @else
                    Caregiver vitals are checked automatically as they are logged.
                @endif
            </p>
            <div class="kc-focus-actions">
                @if($flagged && $r('provider.alerts.index'))
                    <a href="{{ $r('provider.alerts.index', ['type' => 'all']) }}" class="kc-btn kc-btn--light"><x-kc-icon name="bell" /> Review alerts</a>
                @elseif($pendingMeds && $r('provider.medication-approvals.index'))
                    <a href="{{ $r('provider.medication-approvals.index') }}" class="kc-btn kc-btn--light"><x-kc-icon name="pill" /> Review medications</a>
                @elseif($unreadMessages && $r('provider.messages.index'))
                    <a href="{{ $r('provider.messages.index') }}" class="kc-btn kc-btn--light"><x-kc-icon name="message" /> Open messages</a>
                @endif
                @if($r('provider.rounds.index'))
                    <a href="{{ $r('provider.rounds.index') }}" class="kc-btn kc-btn--ghost" style="color:#fff;border-color:rgba(255,255,255,.25)"><x-kc-icon name="stethoscope" /> Start rounds</a>
                @endif
            </div>
        </section>

        <ul class="kc-stats">
            <li><strong>{{ $scheduledCount ?? 0 }}</strong> visits scheduled</li>
            <li><strong>{{ $inProgressCount ?? 0 }}</strong> in progress</li>
            <li><strong>{{ $completedCount ?? 0 }}</strong> completed</li>
        </ul>

        <div class="kc-grid kc-grid--2-1">

            {{-- Patients --}}
            <section class="kc-card">
                <div class="kc-card-head">
                    <h2>Patients</h2>
                    @if($r('provider.patients.index'))<a href="{{ $r('provider.patients.index') }}">My patients</a>@elseif($r('provider.rounds.index'))<a href="{{ $r('provider.rounds.index') }}">Rounds list</a>@endif
                </div>
                @if($patients->isEmpty())
                    <div class="kc-empty"><strong>No patients yet</strong>Patients added by the facility will show here.</div>
                @else
                    <ul class="kc-list">
                        @foreach($patients as $patient)
                            @php
                                $alerts = (int) ($patient->dashboard_alert_count ?? 0);
                                $lastVisit = $patient->dashboard_last_visit ?? null;
                                $name = $patient->name ?? $patient->full_name ?? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) ?: 'Unnamed patient';
                            @endphp
                            <li>
                                <div class="kc-list-main">
                                    <p class="kc-list-title">
                                        @if($r('provider.patients.workspace', $patient->id))
                                            <a href="{{ route('provider.patients.workspace', $patient->id) }}" style="color:inherit;text-decoration:none">{{ $name }}</a>
                                        @else
                                            {{ $name }}
                                        @endif
                                    </p>
                                    <p class="kc-list-sub">
                                        {{ $patient->dashboard_medication_count ?? 0 }} meds,
                                        {{ $patient->dashboard_diagnosis_count ?? 0 }} diagnoses,
                                        last seen {{ $lastVisit ? Carbon::parse($lastVisit)->format('M j') : 'not yet' }}
                                    </p>
                                </div>
                                @if($alerts >= 3)
                                    <span class="kc-pill kc-pill--missed">{{ $alerts }} alerts</span>
                                @elseif($alerts > 0)
                                    <span class="kc-pill kc-pill--in_progress">{{ $alerts }} {{ $alerts === 1 ? 'alert' : 'alerts' }}</span>
                                @else
                                    <span class="kc-pill kc-pill--completed">Stable</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <div class="kc-grid">
                {{-- To-do --}}
                <section class="kc-card">
                    <div class="kc-card-head"><h2>To-do</h2></div>
                    <ul class="kc-list">
                        @foreach($todo as [$label, $count, $url, $icon])
                            <li>
                                <div class="kc-list-main">
                                    @if($url)
                                        <a class="kc-list-title" style="display:block;color:inherit;text-decoration:none" href="{{ $url }}">{{ $label }}</a>
                                    @else
                                        <p class="kc-list-title">{{ $label }}</p>
                                    @endif
                                </div>
                                <span class="kc-pill {{ $count ? 'kc-pill--in_progress' : 'kc-pill--completed' }}">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                {{-- Alerts by type --}}
                <section class="kc-card">
                    <div class="kc-card-head"><h2>Alerts by type</h2></div>
                    <ul class="kc-list">
                        @foreach($alertTypes as [$key, $label, $count])
                            <li>
                                <div class="kc-list-main">
                                    @if($r('provider.alerts.index'))
                                        <a class="kc-list-title" style="display:block;color:inherit;text-decoration:none" href="{{ $r('provider.alerts.index', ['type' => $key]) }}">{{ $label }}</a>
                                    @else
                                        <p class="kc-list-title">{{ $label }}</p>
                                    @endif
                                </div>
                                <span class="kc-pill {{ $count ? 'kc-pill--missed' : '' }}">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                {{-- Billing --}}
                <section class="kc-card">
                    <div class="kc-card-head">
                        <h2>Claims</h2>
                        @if($r('provider.claims.index'))<a href="{{ $r('provider.claims.index') }}">Open claims</a>@endif
                    </div>
                    <ul class="kc-list">
                        <li><span>Paid</span><strong>${{ number_format((float) ($totalRevenue ?? 0), 2) }}</strong></li>
                        <li><span>Submitted</span><span class="kc-pill kc-pill--scheduled">{{ $submittedClaims ?? 0 }}</span></li>
                        <li><span>Denied</span><span class="kc-pill {{ ($deniedClaims ?? 0) ? 'kc-pill--missed' : '' }}">{{ $deniedClaims ?? 0 }}</span></li>
                    </ul>
                </section>

                {{-- Shortcuts --}}
                <section class="kc-card" style="padding:16px 20px 20px">
                    <h2 style="margin:0 0 12px;font-size:19px">Quick actions</h2>
                    <div style="display:grid;gap:8px">
                        @if($r('provider.notes.create'))<a class="kc-btn kc-btn--primary kc-btn--block" href="{{ $r('provider.notes.create') }}"><x-kc-icon name="file-text" /> New note</a>@endif
                        @if($r('provider.pharmacy.create'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('provider.pharmacy.create') }}"><x-kc-icon name="pill" /> New prescription</a>@endif
                        @if($r('provider.messages.create'))<a class="kc-btn kc-btn--ghost kc-btn--block" href="{{ $r('provider.messages.create') }}"><x-kc-icon name="message" /> Message the facility</a>@endif
                    </div>
                </section>
            </div>
        </div>
    @endif
</div>
@endsection
