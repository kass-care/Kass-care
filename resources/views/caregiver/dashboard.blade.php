@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $todayVisits = $todayVisits ?? collect();
    $assignedClients = $assignedClients ?? collect();
    $visits = $visits ?? collect();
    $completedVisitsCount = $completedVisitsCount ?? 0;
    $activeVisit = $activeVisit ?? null;

    $resolveClientName = function ($client) {
        if (!$client) {
            return 'Unknown Client';
        }

        if (!empty($client->full_name)) {
            return $client->full_name;
        }

        $first = trim((string) ($client->first_name ?? ''));
        $last = trim((string) ($client->last_name ?? ''));
        $combined = trim($first . ' ' . $last);

        if ($combined !== '') {
            return $combined;
        }

        if (!empty($client->name)) {
            return $client->name;
        }

        return 'Unknown Client';
    };

    $formatVisitTime = function ($visit) {
        $start = null;
        $end = null;

        try {
            if (!empty($visit->start_time)) {
                $start = Carbon::parse($visit->start_time)->format('g:i A');
            }
        } catch (\Throwable $e) {
            $start = null;
        }

        try {
            if (!empty($visit->end_time)) {
                $end = Carbon::parse($visit->end_time)->format('g:i A');
            }
        } catch (\Throwable $e) {
            $end = null;
        }

        if ($start && $end) {
            return $start . ' - ' . $end;
        }

        if ($start) {
            return $start;
        }

        if ($end) {
            return $end;
        }

        return 'N/A';
    };

    $formatDateTime = function ($value, $fallback = 'N/A') {
        if (empty($value)) {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->format('M d, Y h:i A');
        } catch (\Throwable $e) {
            return $fallback;
        }
    };

    $displayStatus = function ($status) {
        return ucfirst(str_replace('_', ' ', $status ?? 'scheduled'));
    };

    $recentVisits = $visits instanceof \Illuminate\Support\Collection ? $visits->take(8) : collect($visits)->take(8);
@endphp

<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HERO --}}
        <div class="mb-8 rounded-3xl border border-emerald-800 bg-gradient-to-r from-emerald-900 via-emerald-800 to-green-700 p-8 shadow-xl">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.28em] text-emerald-200">
                        KASS CARE
                    </p>

                    <h1 class="text-4xl font-extrabold tracking-tight text-white">
                        Caregiver Dashboard
                    </h1>

                    <p class="mt-2 text-base text-emerald-100">
                        Welcome back, {{ auth()->user()->name ?? 'Caregiver User' }}
                    </p>

                    <p class="mt-1 text-sm text-emerald-200">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('caregiver.visits') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                        Open Visits
                    </a>

                    <a href="{{ route('caregiver.care-logs.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-900 shadow hover:bg-amber-300">
                        Open Care Logs
                    </a>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Today's Visits</p>
                <h2 class="mt-3 text-4xl font-extrabold text-emerald-800">{{ $todayVisits->count() }}</h2>
                <p class="mt-2 text-sm text-slate-600">Visits scheduled for today</p>
            </div>

            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Assigned Clients</p>
                <h2 class="mt-3 text-4xl font-extrabold text-emerald-800">{{ $assignedClients->count() }}</h2>
                <p class="mt-2 text-sm text-slate-600">Clients linked to your visit history</p>
            </div>

            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Completed Visits</p>
                <h2 class="mt-3 text-4xl font-extrabold text-emerald-800">{{ $completedVisitsCount }}</h2>
                <p class="mt-2 text-sm text-slate-600">Successfully completed visit records</p>
            </div>
        </div>

        {{-- ACTIVE VISIT --}}
        @if ($activeVisit)
            <div class="mb-8 rounded-3xl border border-emerald-800 bg-gradient-to-r from-emerald-800 to-green-700 p-6 text-white shadow-xl">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-200">
                            Active Visit
                        </p>

                        <h2 class="mt-2 text-3xl font-extrabold">
                            {{ $resolveClientName($activeVisit->client ?? null) }}
                        </h2>

                        <p class="mt-2 text-sm text-emerald-100">
                            Status: {{ $displayStatus($activeVisit->status ?? 'in_progress') }}
                        </p>

                        <p class="mt-1 text-sm text-emerald-200">
                            Checked in:
                            {{
                                !empty($activeVisit->check_in_time)
                                    ? $formatDateTime($activeVisit->check_in_time)
                                    : (!empty($activeVisit->check_in) ? $formatDateTime($activeVisit->check_in) : 'N/A')
                            }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $activeVisit->id]) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-emerald-800 shadow hover:bg-emerald-50">
                            Open Care Log
                        </a>

                        <a href="{{ route('caregiver.checkout', $activeVisit->id) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-red-700">
                            Check Out
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- TODAY'S SCHEDULE --}}
        <div class="mb-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Today's Schedule</h3>
                    <p class="mt-1 text-sm text-slate-500">Your visits for today</p>
                </div>

                <span class="text-sm font-semibold text-emerald-700">
                    {{ $todayVisits->count() }} visit(s)
                </span>
            </div>

            <div class="p-6 space-y-4">
                @forelse ($todayVisits as $visit)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-slate-900">
                                    {{ $resolveClientName($visit->client ?? null) }}
                                </h4>

                                <p class="mt-1 text-sm text-slate-600">
                                    Status:
                                    <span class="font-semibold">
                                        {{ $displayStatus($visit->status ?? 'scheduled') }}
                                    </span>
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Time: {{ $formatVisitTime($visit) }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if (($visit->status ?? '') === 'scheduled')
                                    <a href="{{ route('caregiver.checkin', $visit->id) }}"
                                       class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                        Check In
                                    </a>
                                @elseif (($visit->status ?? '') === 'in_progress')
                                    <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $visit->id]) }}"
                                       class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                        Care Log
                                    </a>

                                    <a href="{{ route('caregiver.checkout', $visit->id) }}"
                                       class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                        Check Out
                                    </a>
                                @elseif (($visit->status ?? '') === 'completed')
                                    <span class="inline-flex items-center justify-center rounded-lg bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">
                                        Done
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                                        {{ $displayStatus($visit->status ?? 'scheduled') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">
                        No visits today.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ASSIGNED CLIENTS --}}
        <div class="mb-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h3 class="text-2xl font-bold text-slate-900">Assigned Clients</h3>
                <p class="mt-1 text-sm text-slate-500">Clients connected to your visit history</p>
            </div>

            <div class="p-6 space-y-4">
                @forelse ($assignedClients->take(8) as $client)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-slate-900">
                                    {{ $client->name ?? $resolveClientName($client) }}
                                </h4>

                                <p class="mt-1 text-sm text-slate-700">
                                    Latest status:
                                    <span class="font-semibold">
                                        {{ $displayStatus($client->latest_status ?? 'n/a') }}
                                    </span>
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Last activity:
                                    {{
                                        !empty($client->latest_visit_date)
                                            ? $formatDateTime($client->latest_visit_date)
                                            : 'N/A'
                                    }}
                                </p>
                            </div>

                            <div>
                                <a href="{{ route('caregiver.visits') }}"
                                   class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                                    Open Visit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">
                        No assigned clients found yet.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RECENT VISIT HISTORY --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Recent Visit History</h3>
                    <p class="mt-1 text-sm text-slate-500">Latest activity from your visit records</p>
                </div>

                <a href="{{ route('caregiver.care-logs.index') }}"
                   class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                    View Care Logs →
                </a>
            </div>

            <div class="p-6 space-y-4">
                @forelse ($recentVisits as $visit)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h4 class="text-xl font-bold text-slate-900">
                                    {{ $resolveClientName($visit->client ?? null) }}
                                </h4>

                                <p class="mt-1 text-sm text-slate-700">
                                    Status:
                                    <span class="font-semibold">
                                        {{ $displayStatus($visit->status ?? 'n/a') }}
                                    </span>
                                </p>

                                <div class="mt-1 text-sm font-medium text-slate-500">
                                    {{ !empty($visit->updated_at) ? Carbon::parse($visit->updated_at)->diffForHumans() : 'N/A' }}
                                </div>
                            </div>

                            <div>
                                <a href="{{ route('caregiver.visits') }}"
                                   class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                                    Open Visit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">
                        No visit history yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
