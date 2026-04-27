@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-8">
        <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-bold">
            Provider Intelligence
        </p>

        <h1 class="mt-2 text-3xl font-black text-slate-900">
            Alert Intelligence Screen
        </h1>

        <p class="mt-2 text-sm text-slate-600">
            Flagged clinical and preventive alerts for rapid provider review.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Active Alerts</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ $totalActiveAlerts ?? 0 }}</p>
            <p class="mt-2 text-sm text-slate-500">All unresolved alert events</p>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-bold">Critical</p>
            <p class="mt-3 text-4xl font-black text-red-700">{{ $criticalAlerts ?? 0 }}</p>
            <p class="mt-2 text-sm text-red-600">Immediate attention recommended</p>
        </div>

        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-yellow-600 font-bold">High</p>
            <p class="mt-3 text-4xl font-black text-yellow-700">{{ $highAlerts ?? 0 }}</p>
            <p class="mt-2 text-sm text-yellow-700">Needs provider review soon</p>
        </div>

        <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-cyan-600 font-bold">Medium</p>
            <p class="mt-3 text-4xl font-black text-cyan-700">{{ $mediumAlerts ?? 0 }}</p>
            <p class="mt-2 text-sm text-cyan-700">Monitor and document</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm mb-8 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-black text-slate-900">Alert Counts by Type</h2>
            <p class="text-sm text-slate-500 mt-1">
                Numbers first, exactly how providers want to triage.
            </p>
        </div>

        <div class="p-6">
            @forelse($alertTypeSummary ?? [] as $summary)
                <a href="{{ Route::has('provider.alerts.index') ? route('provider.alerts.index', ['type' => $summary['raw_type'] ?? 'all']) : '#' }}"
                   class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 mb-3 hover:bg-white hover:shadow-sm transition">
                    <div>
                        <p class="font-bold text-slate-900">
                            {{ $summary['type'] ?? 'Clinical Alert' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Critical: {{ $summary['critical_count'] ?? 0 }}
                            · High: {{ $summary['high_count'] ?? 0 }}
                            · Medium: {{ $summary['medium_count'] ?? 0 }}
                        </p>
                    </div>

                    <div class="text-3xl font-black text-indigo-700">
                        {{ $summary['count'] ?? 0 }}
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-500">
                    No active alert counts right now.
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-black text-slate-900">Patient-Level Alert Queue</h2>
            <p class="text-sm text-slate-500 mt-1">
                Latest unresolved alert per patient to keep the review screen clean.
            </p>
        </div>

        <div class="p-6 space-y-4">
            @forelse($latestPatientAlerts ?? [] as $alert)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-lg font-black text-slate-900">
                                    {{ $alert['client_name'] ?? 'Unknown Patient' }}
                                </h3>

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                    Room {{ $alert['room'] ?? 'N/A' }}
                                </span>

                                <span class="rounded-full px-3 py-1 text-xs font-bold
                                    @if(($alert['severity'] ?? 'info') === 'critical') bg-red-100 text-red-700
                                    @elseif(($alert['severity'] ?? 'info') === 'high') bg-yellow-100 text-yellow-700
                                    @elseif(($alert['severity'] ?? 'info') === 'medium') bg-cyan-100 text-cyan-700
                                    @else bg-indigo-100 text-indigo-700
                                    @endif">
                                    {{ ucfirst($alert['severity'] ?? 'info') }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm font-semibold text-slate-700">
                                {{ $alert['type'] ?? 'Clinical Alert' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-600">
                                {{ $alert['message'] ?? 'Clinical alert requires provider review.' }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500">
                                <span>Facility: {{ $alert['facility_name'] ?? '-' }}</span>
                                <span>Visit ID: {{ $alert['visit_id'] ?? 'N/A' }}</span>
                                <span>
                                    Flagged:
                                    {{ !empty($alert['flagged_at']) ? \Carbon\Carbon::parse($alert['flagged_at'])->format('M d, Y h:i A') : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            @if(!empty($alert['client_id']) && Route::has('provider.patients.workspace'))
                                <a href="{{ route('provider.patients.workspace', $alert['client_id']) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                    Review Patient
                                </a>
                            @endif

                            @if(!empty($alert['alert_id']) && Route::has('provider.alerts.resolve'))
                                <form method="POST" action="{{ route('provider.alerts.resolve', $alert['alert_id']) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Mark Reviewed
                                    </button>
                                </form>
                            @elseif(!empty($alert['care_log_id']) && Route::has('provider.alerts.review'))
                                <form method="POST" action="{{ route('provider.alerts.review', $alert['care_log_id']) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Mark Reviewed
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-sm text-emerald-700">
                    No active patient alerts right now. The board is clear.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
