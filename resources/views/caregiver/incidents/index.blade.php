@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-950 via-red-900 to-rose-800 p-8 text-white shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-red-100">
                        KassCare Safety & Incident Center
                    </p>

                    <h1 class="mt-2 text-3xl font-black">
                        🚨 Incident Reports
                    </h1>

                    <p class="mt-2 max-w-3xl text-red-100">
                        Document, track, and review resident safety events, falls, medication errors, injuries, and emergency transfers.
                    </p>
                </div>

                <a href="{{ route('caregiver.incidents.create') }}"
                   class="rounded-2xl bg-white px-5 py-3 font-black text-red-800 shadow hover:bg-red-50">
                    + New Incident
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @php
            $total = $reports->total();
            $openCount = $reports->where('status', 'open')->count();
            $criticalCount = $reports->where('severity', 'Critical')->count();
            $highCount = $reports->where('severity', 'High')->count();
        @endphp

        <div class="mb-6 grid gap-4 md:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Reports</p>
                <h2 class="mt-3 text-4xl font-black text-slate-900">{{ $total }}</h2>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Open</p>
                <h2 class="mt-3 text-4xl font-black text-amber-800">{{ $openCount }}</h2>
            </div>

            <div class="rounded-3xl border border-red-200 bg-red-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-red-700">Critical</p>
                <h2 class="mt-3 text-4xl font-black text-red-800">{{ $criticalCount }}</h2>
            </div>

            <div class="rounded-3xl border border-orange-200 bg-orange-50 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-orange-700">High Severity</p>
                <h2 class="mt-3 text-4xl font-black text-orange-800">{{ $highCount }}</h2>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-xl font-black text-slate-900">Recent Incident Reports</h2>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($reports as $report)
                    @php
                        $clientName = $report->client->name
                            ?? trim(($report->client->first_name ?? '') . ' ' . ($report->client->last_name ?? ''))
                            ?: 'Resident not selected';

                        $severityClass = match($report->severity) {
                            'Critical' => 'bg-red-100 text-red-800 border-red-200',
                            'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'Moderate' => 'bg-amber-100 text-amber-800 border-amber-200',
                            default => 'bg-green-100 text-green-800 border-green-200',
                        };
                    @endphp

                    <div class="p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-black text-slate-900">
                                        {{ $report->incident_type ?? 'Incident' }}
                                    </h3>

                                    <span class="rounded-full border px-3 py-1 text-xs font-black uppercase {{ $severityClass }}">
                                        {{ $report->severity }}
                                    </span>

                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase text-slate-600">
                                        {{ $report->status }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-600">
                                    <strong>Resident:</strong> {{ $clientName }}
                                    · <strong>Location:</strong> {{ $report->location ?? 'N/A' }}
                                </p>

                                <p class="mt-1 text-xs font-semibold text-slate-400">
                                    Reported {{ optional($report->created_at)->format('M j, Y g:i A') }}
                                </p>

                                <p class="mt-3 text-sm text-slate-700">
                                    {{ \Illuminate\Support\Str::limit($report->description, 180) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-lg font-bold text-slate-700">No incident reports yet.</p>
                        <a href="{{ route('caregiver.incidents.create') }}"
                           class="mt-4 inline-flex rounded-xl bg-red-700 px-5 py-3 font-bold text-white">
                            Create First Incident
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
