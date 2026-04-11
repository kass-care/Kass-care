@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    @php
        $facilityId = session('facility_id') ?? auth()->user()->facility_id ?? null;

        $recentPatients = collect();

        if (isset($patients) && $patients instanceof \Illuminate\Support\Collection) {
            $recentPatients = $patients->take(5);
        } elseif (class_exists(\App\Models\Client::class) && $facilityId) {
            $recentPatients = \App\Models\Client::where('facility_id', $facilityId)
                ->latest()
                ->take(5)
                ->get();
        }

        $scheduledCount = $scheduledCount ?? 0;
        $completedCount = $completedCount ?? 0;
        $inProgressCount = $inProgressCount ?? 0;
        $flaggedCount = $flaggedCount ?? 0;
        $highBpCount = $highBpCount ?? 0;
        $lowOxygenCount = $lowOxygenCount ?? 0;
        $feverCount = $feverCount ?? 0;
        $pulseIssueCount = $pulseIssueCount ?? 0;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT SIDE --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- HERO --}}
            <div class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 text-white p-8 shadow-lg">
                <p class="text-xs uppercase tracking-[0.35em] font-semibold text-indigo-100">
                    KASS CARE
                </p>

                <h1 class="mt-3 text-4xl font-bold">
                    Provider Clinical Command Center
                </h1>

                <p class="mt-3 text-indigo-100 text-lg max-w-3xl">
                    Review caregiver charting, monitor flagged clinical alerts, follow up on patient risks,
                    and move quickly across notes, compliance, pharmacy, and care logs.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('provider.rounds.index') }}"
                       class="inline-flex items-center rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-5 py-3 font-semibold transition">
                        Start Rounds
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'all']) }}"
                       class="inline-flex items-center rounded-2xl bg-white/15 hover:bg-white/20 px-5 py-3 font-semibold transition">
                        Open Alerts
                    </a>

                    <a href="{{ route('provider.notes.index') }}"
                       class="inline-flex items-center rounded-2xl bg-white/15 hover:bg-white/20 px-5 py-3 font-semibold transition">
                        Provider Notes
                    </a>
                </div>
            </div>

            {{-- CLICKABLE ALERT + STATUS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">
                        Scheduled
                    </p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">
                        {{ $scheduledCount }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">
                        Completed
                    </p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">
                        {{ $completedCount }}
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">
                        In Progress
                    </p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">
                        {{ $inProgressCount }}
                    </p>
                </div>

                <a href="{{ route('provider.alerts', ['type' => 'all']) }}"
                   class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm hover:shadow-md transition block">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">
                        Flagged
                    </p>
                    <p class="mt-3 text-4xl font-bold text-red-700">
                        {{ $flaggedCount }}
                    </p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'high_bp']) }}"
                   class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm hover:shadow-md transition block">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">
                        High BP
                    </p>
                    <p class="mt-3 text-4xl font-bold text-red-700">
                        {{ $highBpCount }}
                    </p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'low_oxygen']) }}"
                   class="rounded-2xl border border-orange-100 bg-orange-50 p-5 shadow-sm hover:shadow-md transition block">
                    <p class="text-xs uppercase tracking-[0.25em] text-orange-500 font-semibold">
                        Low Oxygen
                    </p>
                    <p class="mt-3 text-4xl font-bold text-orange-700">
                        {{ $lowOxygenCount }}
                    </p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'fever']) }}"
                   class="rounded-2xl border border-yellow-100 bg-yellow-50 p-5 shadow-sm hover:shadow-md transition block">
                    <p class="text-xs uppercase tracking-[0.25em] text-yellow-600 font-semibold">
                        Fever / Temp
                    </p>
                    <p class="mt-3 text-4xl font-bold text-yellow-700">
                        {{ $feverCount }}
                    </p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'pulse']) }}"
                   class="rounded-2xl border border-purple-100 bg-purple-50 p-5 shadow-sm hover:shadow-md transition block">
                    <p class="text-xs uppercase tracking-[0.25em] text-purple-600 font-semibold">
                        Pulse Issues
                    </p>
                    <p class="mt-3 text-4xl font-bold text-purple-700">
                        {{ $pulseIssueCount }}
                    </p>
                </a>
            </div>

            {{-- RECENT PATIENTS --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Recent Patients
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Quick access to patient workspaces and summaries.
                        </p>
                    </div>

                    <a href="{{ route('provider.diagnosis.index') }}"
                       class="inline-flex items-center rounded-2xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                        Patients Workspace
                    </a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($recentPatients as $patient)
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl border border-gray-200 p-4">
                            <div>
                                <div class="font-semibold text-gray-900">
                                    {{ $patient->name ?? $patient->full_name ?? 'Unnamed Patient' }}
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Room: {{ $patient->room ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('provider.patients.workspace', $patient->id) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                    Open Workspace
                                </a>

                                <a href="{{ route('provider.patients.summary', $patient->id) }}"
                                   class="inline-flex items-center rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                                    Patient Summary
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-gray-50 py-12 text-center text-gray-500">
                            No patients registered yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TASK / ALERT ACTION SUMMARY --}}
            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-semibold">
                    Provider Task Summary
                </p>

                <h3 class="mt-4 text-2xl font-bold text-gray-900">
                    Alert-Driven Workflow
                </h3>

                <p class="mt-3 text-gray-500 leading-7">
                    Keep the dashboard clean by reviewing patient alert details from the Alerts page.
                    Click any alert count above to open the matching patients and act quickly.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('provider.alerts', ['type' => 'all']) }}"
                       class="inline-flex items-center rounded-2xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition">
                        View All Alerts
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'high_bp']) }}"
                       class="inline-flex items-center rounded-2xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 transition">
                        Review High BP
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'low_oxygen']) }}"
                       class="inline-flex items-center rounded-2xl bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700 hover:bg-orange-100 transition">
                        Review Low Oxygen
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'fever']) }}"
                       class="inline-flex items-center rounded-2xl bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700 hover:bg-yellow-100 transition">
                        Review Fever / Temp
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="space-y-6">

            {{-- QUICK ACCESS --}}
            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">
                    Quick Access
                </h2>

                <div class="space-y-4">
                    <a href="{{ route('provider.alerts', ['type' => 'all']) }}"
                       class="flex items-center justify-between rounded-2xl bg-indigo-50 px-5 py-4 transition hover:bg-indigo-100">
                        <span class="font-semibold text-indigo-700">Clinical Alerts</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.calendar') }}"
                       class="flex items-center justify-between rounded-2xl bg-blue-50 px-5 py-4 transition hover:bg-blue-100">
                        <span class="font-semibold text-blue-700">Open Schedule</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.compliance') }}"
                       class="flex items-center justify-between rounded-2xl bg-yellow-50 px-5 py-4 transition hover:bg-yellow-100">
                        <span class="font-semibold text-yellow-700">Compliance Dashboard</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.notes.index') }}"
                       class="flex items-center justify-between rounded-2xl bg-green-50 px-5 py-4 transition hover:bg-green-100">
                        <span class="font-semibold text-green-700">Provider Notes</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.pharmacy.index') }}"
                       class="flex items-center justify-between rounded-2xl bg-cyan-50 px-5 py-4 transition hover:bg-cyan-100">
                        <span class="font-semibold text-cyan-700">Pharmacy / Rx</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('billing.index') }}"
                       class="flex items-center justify-between rounded-2xl bg-red-50 px-5 py-4 transition hover:bg-red-100">
                        <span class="font-semibold text-red-700">Billing</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.care-logs.index') }}"
                       class="flex items-center justify-between rounded-2xl bg-gray-100 px-5 py-4 transition hover:bg-gray-200">
                        <span class="font-semibold text-gray-700">Care Logs</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ route('provider.tasks.index') }}"
                       class="flex items-center justify-between rounded-2xl bg-purple-50 px-5 py-4 transition hover:bg-purple-100">
                        <span class="font-semibold text-purple-700">Tasks</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>

            {{-- PROVIDER SUMMARY --}}
            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-semibold">
                    Provider Summary
                </p>

                <h3 class="mt-4 text-2xl font-bold text-gray-900">
                    Real-time Clinical Oversight
                </h3>

                <p class="mt-4 text-gray-500 leading-7">
                    Monitor counts, open filtered alert results, move into patient workspaces,
                    and keep provider attention focused without flooding the dashboard with every alert card.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
