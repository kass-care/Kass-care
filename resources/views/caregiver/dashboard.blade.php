@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $todayVisits = $todayVisits ?? collect();
    $assignedClients = $assignedClients ?? collect();
    $visits = $visits ?? collect();
    $completedVisitsCount = $completedVisitsCount ?? 0;
    $activeVisit = $activeVisit ?? null;
    $recentVisits = $visits instanceof \Illuminate\Support\Collection ? $visits->take(8) : collect($visits)->take(8);

    $resolveClientName = function ($client) {
        if (!$client) return 'Unknown Client';
        if (!empty($client->full_name)) return $client->full_name;
        if (!empty($client->name)) return $client->name;

        $name = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
        return $name ?: 'Unknown Client';
    };

    $formatVisitTime = function ($visit) {
        try {
            $start = !empty($visit->start_time) ? Carbon::parse($visit->start_time)->format('g:i A') : null;
            $end = !empty($visit->end_time) ? Carbon::parse($visit->end_time)->format('g:i A') : null;

            if ($start && $end) return $start . ' - ' . $end;
            return $start ?: ($end ?: 'N/A');
        } catch (\Throwable $e) {
            return 'N/A';
        }
    };

    $formatDateTime = function ($value, $fallback = 'N/A') {
        if (empty($value)) return $fallback;

        try {
            return Carbon::parse($value)->format('M d, Y h:i A');
        } catch (\Throwable $e) {
            return $fallback;
        }
    };

    $displayStatus = function ($status) {
        return ucfirst(str_replace('_', ' ', $status ?? 'scheduled'));
    };
@endphp

<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 rounded-3xl border border-emerald-800 bg-gradient-to-r from-emerald-900 via-emerald-800 to-green-700 p-8 shadow-xl">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.28em] text-emerald-200">
                        KASS CARE
                    </p>

                    <h1 class="text-4xl font-extrabold tracking-tight text-white">
                        Caregiver Command Center
                    </h1>

                    <p class="mt-2 text-base text-emerald-100">
                        Welcome back, {{ auth()->user()->name ?? 'Caregiver' }}
                    </p>

                    <p class="mt-1 text-sm text-emerald-200">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('caregiver.visits') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-emerald-800 shadow">
                        Open Visits
                    </a>
                        <a href="{{ route('caregiver.emar.index') }}"
   class="inline-flex items-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-black text-white shadow-lg hover:bg-emerald-600">
    💊 eMAR
</a>
                    <a href="{{ route('caregiver.care-logs.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-slate-900 shadow">
                        Open Care Logs
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Today's Visits</p>
                <h2 class="mt-3 text-4xl font-extrabold text-emerald-800">{{ $todayVisits->count() }}</h2>
                <p class="mt-2 text-sm text-slate-600">Visits scheduled for today</p>
            </div>

            <div class="rounded-3xl border border-blue-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Assigned Clients</p>
                <h2 class="mt-3 text-4xl font-extrabold text-blue-800">{{ $assignedClients->count() }}</h2>
                <p class="mt-2 text-sm text-slate-600">Clients linked to your care history</p>
            </div>

            <div class="rounded-3xl border border-indigo-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[0.20em] text-slate-500">Completed Visits</p>
                <h2 class="mt-3 text-4xl font-extrabold text-indigo-800">{{ $completedVisitsCount }}</h2>
                <p class="mt-2 text-sm text-slate-600">Successfully completed visits</p>
            </div>
        </div>

        @if ($activeVisit)
            <div class="mb-8 rounded-3xl border border-emerald-700 bg-gradient-to-r from-emerald-800 to-green-700 p-6 text-white shadow-xl">
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
                            {{ !empty($activeVisit->check_in_time) ? $formatDateTime($activeVisit->check_in_time) : 'N/A' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $activeVisit->id]) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-emerald-800 shadow">
                            Open Care Log
                        </a>

                        <a href="{{ route('caregiver.checkout', $activeVisit->id) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow">
                            Check Out
                        </a>
                    </div>
                </div>
            </div>
        @endif
                        <div class="mb-8 overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm">
    <div class="border-b border-emerald-100 bg-emerald-50 px-6 py-5">
        <h3 class="text-2xl font-black text-slate-900">Today’s Assigned Patients</h3>
        <p class="mt-1 text-sm text-slate-600">Tap a patient to open the assigned visit and chart care.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($assignedClients->take(12) as $client)
            <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $client->visit_id]) }}"
               class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl">

                <div class="flex items-center gap-4">
                    <div class="h-20 w-20 overflow-hidden rounded-2xl border-2 border-emerald-200 bg-emerald-50">
                        @if(!empty($client->photo))
                            <img src="{{ asset('storage/' . $client->photo) }}"
                                 class="h-full w-full object-cover"
                                 alt="Patient photo">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-3xl font-black text-emerald-700">
                                {{ strtoupper(substr($client->name ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-[0.25em] text-emerald-600">
                            Patient
                        </p>

                        <h4 class="truncate text-xl font-black text-slate-900">
                            {{ $client->name }}
                        </h4>

                        <p class="mt-1 text-xs font-semibold text-slate-500">
                            Visit #{{ $client->visit_id }} • {{ $displayStatus($client->latest_status ?? 'scheduled') }}
                        </p>
                    </div>
                </div>

                @if(!empty($client->duties))
                    <div class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                        <p class="mb-1 text-xs font-black uppercase tracking-[0.2em] text-slate-400">Duties</p>
                        {{ \Illuminate\Support\Str::limit($client->duties, 130) }}
                    </div>
                @endif

                <div class="mt-4 inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white group-hover:bg-emerald-700">
                    Open Shift →
                </div>
            </a>
        @empty
            <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                No assigned patients found yet.
            </div>
        @endforelse
    </div>
</div>

                                <h4 class="mt-2 text-2xl font-black text-slate-900">
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

                            <a href="{{ route('caregiver.visits') }}"
                               class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-bold text-indigo-700">
                                Open Visit
                            </a>
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
