@extends('layouts.app')

@section('title', 'Care updates')

@section('content')
@php
    use Carbon\Carbon;

    $time = function ($v) {
        if (empty($v)) return null;
        try { return Carbon::parse($v)->format('g:i A'); } catch (\Throwable $e) { return null; }
    };
    $date = function ($v) {
        if (empty($v)) return '';
        try { return Carbon::parse($v)->format('l, M j'); } catch (\Throwable $e) { return ''; }
    };
    $first = explode(' ', trim(auth()->user()->name ?? ''))[0] ?: 'there';
@endphp

<div class="kc-page">
    <div class="kc-greeting">
        <h1>Hello, {{ $first }}</h1>
        <p>
            @if($client)
                Care updates for {{ $client->name }}
            @else
                Welcome to KASSCare
            @endif
        </p>
    </div>

    @if(!$client)
        <section class="kc-focus">
            <p class="kc-focus-label">Not linked yet</p>
            <h2>Your account isn't linked to a loved one yet.</h2>
            <p class="kc-focus-meta">Please call the care agency so they can finish setting up your access.</p>
        </section>
    @else
        @php
            $todayVisits = collect($todayVisits ?? []);
            $upcoming = collect($upcoming ?? []);
            $recent = collect($recent ?? []);
            $notes = collect($notes ?? []);
            $nextToday = $todayVisits->first(fn ($v) => strtolower($v->status ?? '') === 'scheduled');
            $doneToday = $todayVisits->first(fn ($v) => strtolower($v->status ?? '') === 'completed');
        @endphp

        {{-- Right now --}}
        @if($current)
            <section class="kc-focus kc-focus--live" aria-label="Care happening now">
                <p class="kc-focus-label">Care is happening now</p>
                <h2>{{ $current->caregiver->name ?? 'A caregiver' }} is with {{ explode(' ', $client->name)[0] }}</h2>
                <p class="kc-focus-meta">Arrived at {{ $time($current->check_in_time) ?? $time($current->start_time) ?? 'the scheduled time' }}</p>
            </section>
        @elseif($nextToday)
            <section class="kc-focus" aria-label="Next visit today">
                <p class="kc-focus-label">Next visit today</p>
                <h2>{{ $time($nextToday->start_time) ? 'At ' . $time($nextToday->start_time) : 'Later today' }}</h2>
                <p class="kc-focus-meta">{{ $nextToday->caregiver->name ?? 'Your caregiver' }} is scheduled to visit.</p>
            </section>
        @elseif($doneToday)
            <section class="kc-focus kc-focus--live" aria-label="Today's visit">
                <p class="kc-focus-label">Today's visit is done</p>
                <h2>{{ $doneToday->caregiver->name ?? 'The caregiver' }} finished at {{ $time($doneToday->check_out_time) ?? $time($doneToday->end_time) ?? 'the end of the visit' }}</h2>
                <p class="kc-focus-meta">You can read the care notes below.</p>
            </section>
        @else
            <section class="kc-focus" aria-label="Next visit">
                <p class="kc-focus-label">No visit today</p>
                <h2>
                    @if($upcoming->first())
                        Next visit {{ $date($upcoming->first()->visit_date) }}
                    @else
                        No upcoming visits scheduled
                    @endif
                </h2>
                <p class="kc-focus-meta">Questions about the schedule? Call the agency office.</p>
            </section>
        @endif

        <div class="kc-grid kc-grid--2-1">
            {{-- Care notes from recent visits --}}
            <section class="kc-card">
                <div class="kc-card-head"><h2>Recent visits</h2></div>
                @if($recent->isEmpty())
                    <div class="kc-empty"><strong>No completed visits yet</strong>Notes from each visit will appear here.</div>
                @else
                    @foreach($recent as $visit)
                        @php
                            $logs = $notes->get($visit->id, collect());
                            $adls = $logs->flatMap(function ($log) {
                                $a = is_string($log->adls) ? json_decode($log->adls, true) : $log->adls;
                                if (!is_array($a)) return [];
                                return collect($a)->map(fn ($v, $k) => is_string($k) ? ($v ? $k : null) : $v)->filter()->values();
                            })->unique()->take(8);
                            $text = $logs->pluck('notes')->filter()->first();
                        @endphp
                        <article class="kc-note">
                            <div class="kc-note-head">
                                <strong>{{ $date($visit->visit_date) }}</strong>
                                <span class="kc-list-sub" style="margin:0">
                                    {{ $visit->caregiver->name ?? 'Caregiver' }}
                                    @if($time($visit->check_in_time) && $time($visit->check_out_time))
                                        , {{ $time($visit->check_in_time) }} to {{ $time($visit->check_out_time) }}
                                    @endif
                                </span>
                            </div>
                            @if($text)
                                <p>{{ \Illuminate\Support\Str::limit($text, 280) }}</p>
                            @else
                                <p class="kc-list-sub">Visit completed. No written note was added.</p>
                            @endif
                            @if($adls->count())
                                <div class="kc-chips">
                                    @foreach($adls as $adl)<span>{{ ucfirst(str_replace('_', ' ', (string) $adl)) }}</span>@endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                @endif
            </section>

            <section class="kc-card">
                <div class="kc-card-head"><h2>Coming up</h2></div>
                @if($upcoming->isEmpty())
                    <div class="kc-empty">No upcoming visits on the schedule.</div>
                @else
                    <ul class="kc-list">
                        @foreach($upcoming as $visit)
                            <li>
                                <div class="kc-list-main">
                                    <p class="kc-list-title">{{ $date($visit->visit_date) }}</p>
                                    <p class="kc-list-sub">{{ $time($visit->start_time) ?? 'Time to be confirmed' }}{{ $visit->caregiver?->name ? ', with ' . $visit->caregiver->name : '' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    @endif
</div>
@endsection
