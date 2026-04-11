@extends('layouts.app')

@section('content')

@php
    $latestVisit = $client->visits->sortByDesc(function ($visit) {
        return $visit->visit_date ?? $visit->created_at;
    })->first();

    $latestCareLog = $latestVisit?->careLogs?->sortByDesc('created_at')->first();

    $riskLevel = 'Stable';
    $riskColor = 'emerald';
    $riskReasons = [];

    if ($latestCareLog && is_array($latestCareLog->vitals)) {
        $vitals = $latestCareLog->vitals;

        $bp = $vitals['blood_pressure'] ?? null;
        $temp = $vitals['temperature'] ?? null;
        $oxygen = $vitals['oxygen_saturation'] ?? null;
        $resp = $vitals['respiratory_rate'] ?? null;

        if ($bp) {
            $parts = explode('/', (string) $bp);
            $systolic = isset($parts[0]) ? (int) trim($parts[0]) : null;
            if ($systolic && $systolic >= 160) {
                $riskReasons[] = 'BP elevated';
            }
        }

        if ((float) $temp >= 100.4) {
            $riskReasons[] = 'Fever detected';
        }

        if ((float) $oxygen > 0 && (float) $oxygen < 90) {
            $riskReasons[] = 'Low oxygen';
        }

        if ((float) $resp >= 22) {
            $riskReasons[] = 'Respiratory rate elevated';
        }

        if (count($riskReasons) >= 2) {
            $riskLevel = 'High Risk';
            $riskColor = 'rose';
        } elseif (count($riskReasons) === 1) {
            $riskLevel = 'Moderate Risk';
            $riskColor = 'amber';
        }
    }
@endphp

<div class="max-w-7xl mx-auto py-8 px-6">

    {{-- Header --}}
    <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 text-white shadow-2xl border border-indigo-900/60 overflow-hidden">
        <div class="px-6 py-6 md:px-8 md:py-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-300 font-semibold">
                        Patient Clinical Workspace
                    </p>

                    <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">
                        {{ $client->name }}
                    </h1>

                    <p class="mt-2 text-indigo-100 text-sm md:text-base">
                        Clinical overview, timeline, vitals, care logs, and provider activity
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3 text-sm">
                        <span class="rounded-full bg-white/10 border border-white/10 px-4 py-2">
                            Facility: {{ $client->facility->name ?? '-' }}
                        </span>

                        <span class="rounded-full bg-white/10 border border-white/10 px-4 py-2">
                            Provider: {{ $client->provider->name ?? '-' }}
                        </span>

                        <span class="rounded-full bg-white/10 border border-white/10 px-4 py-2">
                            Visits: {{ $client->visits->count() }}
                        </span>

                        @if(!empty($client->room))
                            <span class="rounded-full bg-white/10 border border-white/10 px-4 py-2">
                                Room: {{ $client->room }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="w-full max-w-sm">
                    <div class="rounded-2xl bg-white/10 border border-white/10 p-5 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-200">
                            Patient Risk Engine
                        </p>

                        <div class="mt-3">
                            <span class="inline-flex rounded-full px-4 py-2 text-sm font-bold
                                @if($riskColor === 'rose') bg-rose-400/20 text-rose-200 border border-rose-300/30
                                @elseif($riskColor === 'amber') bg-amber-400/20 text-amber-200 border border-amber-300/30
                                @else bg-emerald-400/20 text-emerald-200 border border-emerald-300/30
                                @endif">
                                {{ $riskLevel }}
                            </span>
                        </div>

                        <div class="mt-4 text-sm text-indigo-100">
                            @if(count($riskReasons))
                                <p class="font-semibold mb-2">Reason:</p>
                                <ul class="space-y-1">
                                    @foreach($riskReasons as $reason)
                                        <li>• {{ $reason }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No active high-risk signals detected from the latest care log.</p>
                            @endif
                        </div>
                    </div>

                        <a href="{{ route('admin.clients.index') }}"
                       class="mt-4 inline-flex items-center justify-center w-full rounded-xl bg-cyan-400 px-4 py-3 text-sm font-bold text-slate-950 hover:bg-cyan-300 transition">
                        Back to Patients
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
            <h2 class="font-semibold text-lg mb-4 text-gray-800">Patient Info</h2>

           <div class="space-y-2 text-sm">
    <p><strong>Name:</strong> {{ $client->name }}</p>

    @if($client->date_of_birth)
        <p><strong>Age:</strong> {{ \Carbon\Carbon::parse($client->date_of_birth)->age }} years</p>
    @endif
<p><strong>Date of Birth:</strong>
    {{ $client->date_of_birth ? $client->date_of_birth->format('M d, Y') : '-' }}
</p>

<p><strong>Age:</strong>
    {{ $client->age ?? ($client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->age : '-') }}
</p>

    <p><strong>Gender:</strong> {{ $client->gender ?? '-' }}</p>
    <p><strong>Room:</strong> {{ $client->room ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $client->email ?? '-' }}</p>
    <p><strong>Phone:</strong> {{ $client->phone ?? '-' }}</p>
     <p><strong>Height:</strong> {{ $client->height ?? '-' }} cm</p>

<p><strong>Weight:</strong> {{ $client->weight ?? '-' }} kg</p>

<p><strong>BMI:</strong> {{ $client->bmi ?? '-' }}</p>
    <p><strong>Facility:</strong> {{ $client->facility->name ?? '-' }}</p>
    <p><strong>Provider:</strong> {{ $client->provider->name ?? '-' }}</p>
</div>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
            <h2 class="font-semibold text-lg mb-4 text-gray-800">Diagnoses</h2>

            @forelse($client->diagnoses as $diagnosis)
                <div class="mb-2 rounded-lg bg-rose-50 border border-rose-100 px-3 py-2 text-sm text-rose-700">
                    {{ $diagnosis->name ?? 'Diagnosis' }}
                </div>
            @empty
                <p class="text-gray-400 text-sm">No diagnoses recorded</p>
            @endforelse
        </div>

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
            <h2 class="font-semibold text-lg mb-4 text-gray-800">Medications</h2>

            @forelse($client->medications as $medication)
                <div class="mb-2 rounded-lg bg-emerald-50 border border-emerald-100 px-3 py-2 text-sm text-emerald-700">
                    {{ $medication->name ?? 'Medication' }}
                </div>
            @empty
                <p class="text-gray-400 text-sm">No medications recorded</p>
            @endforelse
        </div>

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
            <h2 class="font-semibold text-lg mb-4 text-gray-800">Profile Snapshot</h2>

            <div class="space-y-3">
                <div class="rounded-lg bg-indigo-50 border border-indigo-100 px-3 py-2">
                    <p class="text-xs uppercase tracking-wide text-indigo-700">Visits</p>
                    <p class="text-2xl font-bold text-indigo-700">{{ $client->visits->count() }}</p>
                </div>

                <div class="rounded-lg bg-amber-50 border border-amber-100 px-3 py-2">
                    <p class="text-xs uppercase tracking-wide text-amber-700">Diagnoses</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $client->diagnoses->count() }}</p>
                </div>

                <div class="rounded-lg bg-cyan-50 border border-cyan-100 px-3 py-2">
                    <p class="text-xs uppercase tracking-wide text-cyan-700">Medications</p>
                    <p class="text-2xl font-bold text-cyan-700">{{ $client->medications->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Visit History --}}
    <div class="bg-white shadow rounded-2xl p-6 border border-gray-100 mb-6">
        <h2 class="font-semibold text-xl mb-4 text-gray-800">Visit History</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 text-gray-700 text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Provider</th>
                        <th class="px-4 py-3 text-left">Caregiver</th>
                        <th class="px-4 py-3 text-left">Facility</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($client->visits as $visit)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ optional($visit->visit_date)->format('M d, Y') ?? optional($visit->created_at)->format('M d, Y') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $visit->provider->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $visit->caregiver->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $visit->facility->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    @if(($visit->status ?? '') === 'completed') bg-emerald-100 text-emerald-700
                                    @elseif(($visit->status ?? '') === 'missed') bg-rose-100 text-rose-700
                                    @elseif(($visit->status ?? '') === 'in_progress') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $visit->status ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">
                                No visits recorded
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Clinical Timeline --}}
    <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Patient Timeline Engine</h2>
                <p class="text-sm text-gray-500">Clinical events, care logs, provider notes, and vitals in one place</p>
            </div>
        </div>

        @forelse($client->visits as $visit)
            <div class="relative pl-6 pb-8 border-l-2 border-indigo-100 last:pb-0">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-indigo-600 border-4 border-white"></div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Visit Event
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ optional($visit->visit_date)->format('M d, Y') ?? optional($visit->created_at)->format('M d, Y') ?? '-' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1">
                                Provider: {{ $visit->provider->name ?? '-' }}
                            </span>

                            <span class="rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1">
                                Caregiver: {{ $visit->caregiver->name ?? '-' }}
                            </span>

                            <span class="rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1">
                                Status: {{ $visit->status ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Provider Note --}}
                    <div class="mt-5">
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-2">
                            Provider Note
                        </h4>

                        @if($visit->providerNote)
                            <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-4 text-sm text-gray-700">
                                {{ $visit->providerNote->note ?? $visit->providerNote->content ?? 'Provider note available.' }}
                            </div>
                        @else
                            <div class="rounded-xl bg-gray-100 p-4 text-sm text-gray-400">
                                No provider note recorded
                            </div>
                        @endif
                    </div>

                    {{-- Care Logs --}}
                    <div class="mt-5">
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">
                            Care Logs
                        </h4>

                        @forelse($visit->careLogs as $log)
                            <div class="mb-4 rounded-xl bg-white border border-gray-200 p-4">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Notes</p>
                                        <p class="text-sm text-gray-700">
                                            {{ $log->notes ?: 'No care log notes recorded' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Vitals</p>

                                        @if(is_array($log->vitals) && count($log->vitals))
                                            <div class="space-y-1 text-sm text-gray-700">
                                                @foreach($log->vitals as $key => $value)
                                                    <p>
                                                        <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400">No vitals recorded</p>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">ADLs</p>

                                        @if(is_array($log->adls) && count($log->adls))
                                            <div class="space-y-1 text-sm text-gray-700">
                                                @foreach($log->adls as $key => $value)
                                                    <p>
                                                        <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                    </p>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400">No ADLs recorded</p>
                                        @endif
                                    </div>

                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-slate-100 text-slate-700 text-xs font-semibold px-3 py-1">
                                        Reviewed: {{ $log->reviewed ? 'Yes' : 'No' }}
                                    </span>

                                    @if($log->reviewed_at)
                                        <span class="rounded-full bg-cyan-100 text-cyan-700 text-xs font-semibold px-3 py-1">
                                            Reviewed At: {{ $log->reviewed_at->format('M d, Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl bg-gray-100 p-4 text-sm text-gray-400">
                                No care logs recorded for this visit
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-gray-100 p-6 text-center text-gray-400">
                No timeline events available for this patient
            </div>
        @endforelse
    </div>

</div>

@endsection
