@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-8">
        <p class="text-xs font-bold tracking-[0.35em] text-indigo-500 uppercase">Provider Intelligence</p>
        <h1 class="mt-2 text-3xl font-black text-slate-900">Alert Intelligence Screen</h1>
        <p class="mt-2 text-sm text-slate-500">
            Flagged vitals summarized by numbers first, then patient-level alerts for rapid provider review.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Active Alerts</p>
            <p class="mt-3 text-3xl font-black text-slate-900">{{ $totalActiveAlerts }}</p>
            <p class="mt-1 text-sm text-slate-500">All current flagged signal events</p>
        </div>

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-rose-600 font-bold">Critical</p>
            <p class="mt-3 text-3xl font-black text-rose-700">{{ $criticalAlerts }}</p>
            <p class="mt-1 text-sm text-rose-600">Immediate attention recommended</p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-amber-700 font-bold">High</p>
            <p class="mt-3 text-3xl font-black text-amber-700">{{ $highAlerts }}</p>
            <p class="mt-1 text-sm text-amber-700">Needs provider review soon</p>
        </div>

        <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-cyan-700 font-bold">Medium</p>
            <p class="mt-3 text-3xl font-black text-cyan-700">{{ $mediumAlerts }}</p>
            <p class="mt-1 text-sm text-cyan-700">Monitor and document</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm mb-8">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-900">Alert Counts by Type</h2>
            <p class="mt-1 text-sm text-slate-500">Numbers first, exactly how providers want to triage.</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($alertTypeSummary as $summary)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $summary['type'] }}</p>
                                <p class="mt-2 text-3xl font-black text-indigo-700">{{ $summary['count'] }}</p>
                                <p class="text-sm text-slate-500">flagged occurrences</p>
                            </div>

                            <div class="text-right text-xs space-y-2">
                                <div class="rounded-full bg-rose-100 px-3 py-1 font-semibold text-rose-700">
                                    Critical: {{ $summary['critical_count'] }}
                                </div>
                                <div class="rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-700">
                                    High: {{ $summary['high_count'] }}
                                </div>
                                <div class="rounded-full bg-cyan-100 px-3 py-1 font-semibold text-cyan-700">
                                    Medium: {{ $summary['medium_count'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-500">
                        No active alert counts right now.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-900">Patient-Level Alert Queue</h2>
            <p class="mt-1 text-sm text-slate-500">
                Latest active flagged signal per patient to keep the review screen clean.
            </p>
        </div>

        <div class="p-6 space-y-4">
            @forelse($latestPatientAlerts as $alert)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-lg font-bold text-slate-900">{{ $alert['client_name'] }}</h3>

                                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                                    Room: {{ $alert['room'] ?? '-' }}
                                </span>

                                @if($alert['severity'] === 'critical')
                                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">Critical</span>
                                @elseif($alert['severity'] === 'high')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">High</span>
                                @else
                                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-bold text-cyan-700">Medium</span>
                                @endif
                            </div>

                            <p class="mt-2 text-sm font-semibold text-slate-700">{{ $alert['type'] }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $alert['message'] }}</p>

                            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500">
                                <span>Facility: {{ $alert['facility_name'] }}</span>
                                <span>Flagged: {{ optional($alert['flagged_at'])->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.clients.show', $alert['client_id']) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Open Patient
                            </a>
                            <form method="POST" action="{{ route('provider.alerts.review', $alert['care_log_id']) }}">
    @csrf
    <button type="submit"
            class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">
        Mark Reviewed
    </button>
</form>
                            </button>
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
