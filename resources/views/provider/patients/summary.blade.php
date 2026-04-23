@extends('layouts.app')

@section('content')
@php
    $providerNotes = $providerNotes ?? collect();
    $snapshot = $snapshot ?? [];
    $intelligence = $intelligence ?? ['risk_level' => 'LOW', 'alerts' => []];

    $riskLevel = $intelligence['risk_level'] ?? 'LOW';

    if ($riskLevel === 'HIGH') {
        $riskClasses = 'bg-red-100 text-red-700';
    } elseif ($riskLevel === 'MODERATE') {
        $riskClasses = 'bg-yellow-100 text-yellow-700';
    } else {
        $riskClasses = 'bg-green-100 text-green-700';
    }

    $latestVitalsData = [];

    if ($latestCareLog ?? null) {
        $latestVitalsData = is_array($latestCareLog->vitals ?? null) ? $latestCareLog->vitals : [];
    }

    $bp = $latestVitalsData['bp'] ?? $latestVitalsData['blood_pressure'] ?? 'N/A';
    $pulse = $latestVitalsData['pulse'] ?? 'N/A';
    $temp = $latestVitalsData['temperature'] ?? $latestVitalsData['temp'] ?? 'N/A';
    $oxygen = $latestVitalsData['oxygen'] ?? $latestVitalsData['oxygen_saturation'] ?? 'N/A';
@endphp

<div class="max-w-7xl mx-auto py-8 px-4">

    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl shadow-xl overflow-hidden mb-6">
        <div class="px-6 py-6 md:px-8 md:py-7">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-14 w-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ strtoupper(substr($patient->name ?? 'P', 0, 1)) }}
                        </div>

                        <div>
                            <div class="text-xs uppercase tracking-[0.25em] text-indigo-200 font-semibold">
                                Patient Banner
                            </div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                                {{ $patient->name ?? 'Unknown Patient' }}
                            </h1>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 mt-5">

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Date of Birth</div>
                            <div class="mt-1 text-base font-semibold">
                                {{ !empty($patient->date_of_birth) ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Age</div>
                            <div class="mt-1 text-base font-semibold">
                                {{ $snapshot['age'] ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Phone</div>
                            <div class="mt-1 text-base font-semibold">
                                {{ $patient->phone ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Facility</div>
                            <div class="mt-1 text-base font-semibold">
                                {{ $patient->facility->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Provider</div>
                            <div class="mt-1 text-base font-semibold">
                                {{ $patient->provider->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 text-white">
                            <div class="text-xs uppercase tracking-wide text-indigo-200">Status</div>
                            <div class="mt-1">
                                <span class="inline-flex items-center rounded-full bg-emerald-400/20 px-3 py-1 text-sm font-semibold text-emerald-200 border border-emerald-300/20">
                                    {{ $patient->status ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-wrap gap-2 mt-5">
                        <span class="inline-flex items-center rounded-full bg-rose-400/20 px-3 py-1 text-xs font-semibold text-rose-100 border border-rose-300/20">
                            Diagnosis Count: {{ $snapshot['diagnosisCount'] ?? 0 }}
                        </span>

                        <span class="inline-flex items-center rounded-full bg-amber-400/20 px-3 py-1 text-xs font-semibold text-amber-100 border border-amber-300/20">
                            Medications: {{ $snapshot['medicationCount'] ?? 0 }}
                        </span>

                        <span class="inline-flex items-center rounded-full bg-cyan-400/20 px-3 py-1 text-xs font-semibold text-cyan-100 border border-cyan-300/20">
                            Visits: {{ $snapshot['visitCount'] ?? 0 }}
                        </span>

                        @if(!empty($snapshot['last_visit']))
                            <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-100 border border-white/10">
                                Last Visit: {{ $snapshot['last_visit'] }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:min-w-[220px]">
                    <a href="{{ route('provider.patients.workspace', $patient->id) }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-white text-indigo-800 px-5 py-3 text-sm font-bold shadow hover:bg-indigo-50 transition">
                        Open My Workspace
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/15 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                        Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Clinical Snapshot</h2>

            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-bold {{ $riskClasses }}">
                Risk Level: {{ $riskLevel }}
            </span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                <div class="text-xs uppercase text-gray-500">Age</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $snapshot['age'] ?? '—' }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                <div class="text-xs uppercase text-gray-500">Diagnoses</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $snapshot['diagnosisCount'] ?? 0 }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                <div class="text-xs uppercase text-gray-500">Medications</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $snapshot['medicationCount'] ?? 0 }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                <div class="text-xs uppercase text-gray-500">Visits</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $snapshot['visitCount'] ?? 0 }}</div>
            </div>

            <div class="rounded-xl bg-gray-50 border border-gray-100 p-4">
                <div class="text-xs uppercase text-gray-500">Last Visit</div>
                <div class="mt-2 text-lg font-bold text-gray-900">
                    {{ !empty($snapshot['last_visit']) ? $snapshot['last_visit'] : 'N/A' }}
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="font-semibold text-gray-900 mb-3">Active Alerts</h3>

            @if(!empty($intelligence['alerts']))
                <div class="flex flex-wrap gap-2">
                    @foreach($intelligence['alerts'] as $alert)
                        <span class="inline-flex items-center rounded-full bg-red-100 text-red-700 px-3 py-2 text-sm font-medium">
                            {{ $alert }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl bg-green-50 text-green-700 px-4 py-3 text-sm font-medium">
                    No active critical alerts detected.
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Latest Vitals</h2>

            @if(($latestCareLog ?? null) && $latestCareLog->created_at)
                <span class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($latestCareLog->created_at)->format('M d, Y h:i A') }}
                </span>
            @endif
        </div>

        @if($latestCareLog ?? null)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-xl border border-gray-100 bg-indigo-50 p-4">
                    <div class="text-sm text-gray-600">Blood Pressure</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $bp }}</div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-emerald-50 p-4">
                    <div class="text-sm text-gray-600">Pulse</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pulse }}</div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-amber-50 p-4">
                    <div class="text-sm text-gray-600">Temperature</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $temp }}</div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-cyan-50 p-4">
                    <div class="text-sm text-gray-600">Oxygen</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $oxygen }}</div>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                No vitals recorded yet.
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Diagnoses</h2>

            @forelse(($patient->diagnoses ?? collect()) as $diagnosis)
                <div class="py-3 border-b border-gray-100 last:border-b-0">
                    <div class="font-semibold text-gray-900">
                        {{ $diagnosis->name ?? $diagnosis->diagnosis ?? 'Diagnosis' }}
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                    No diagnoses recorded.
                </div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Medications</h2>

            @forelse(($patient->medications ?? collect()) as $med)
                <div class="py-3 border-b border-gray-100 last:border-b-0">
                    <div class="font-semibold text-gray-900">
                        {{ $med->name ?? $med->medication_name ?? 'Medication' }}
                    </div>

                    <div class="mt-1 text-sm text-gray-600 space-y-1">
                        @if(!empty($med->dose))
                            <div>Dose: {{ $med->dose }}</div>
                        @endif

                        @if(!empty($med->frequency))
                            <div>Frequency: {{ $med->frequency }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                    No medications recorded.
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Visits</h2>

        @forelse((($patient->visits ?? collect())->sortByDesc('visit_date')->take(5)) as $visit)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 py-4 border-b border-gray-100 last:border-b-0">
                <div>
                    <div class="font-semibold text-gray-900">
                        {{ !empty($visit->visit_date) ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ ucfirst($visit->status ?? 'scheduled') }}
                    </div>
                </div>

                <div class="text-sm text-gray-500">
                    Visit ID: {{ $visit->id }}
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                No visits found.
            </div>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Provider Notes</h2>

        @forelse($providerNotes->take(5) as $note)
            <div class="py-4 border-b border-gray-100 last:border-b-0">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-2">
                    <div class="font-semibold text-gray-900">
                        Note #{{ $note->id }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $note->created_at ? \Carbon\Carbon::parse($note->created_at)->format('M d, Y h:i A') : 'N/A' }}
                    </div>
                </div>

                <div class="text-sm text-gray-700">
                    {{ \Illuminate\Support\Str::limit($note->note ?? 'No note text available.', 220) }}
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500">
                No provider notes yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
