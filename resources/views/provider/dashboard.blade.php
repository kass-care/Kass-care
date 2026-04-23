@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    @php
        $scheduledCount   = $scheduledCount ?? 0;
        $completedCount   = $completedCount ?? 0;
        $inProgressCount  = $inProgressCount ?? 0;
        $flaggedCount     = $flaggedCount ?? 0;
        $highBpCount      = $highBpCount ?? 0;
        $lowOxygenCount   = $lowOxygenCount ?? 0;
        $feverCount       = $feverCount ?? 0;
        $pulseIssueCount  = $pulseIssueCount ?? 0;

        $recentPatients = collect();

        if (isset($patients) && $patients instanceof \Illuminate\Support\Collection) {
            $recentPatients = $patients->take(5);
        } elseif (isset($patients) && is_iterable($patients)) {
            $recentPatients = collect($patients)->take(5);
        }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT SIDE --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- HERO --}}
            <div class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 text-white p-8 shadow-sm">
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
                    <a href="{{ route('provider.compliance') }}"
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

            {{-- DASHBOARD CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Scheduled</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">{{ $scheduledCount }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Completed</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">{{ $completedCount }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">In Progress</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900">{{ $inProgressCount }}</p>
                </div>

                <a href="{{ route('provider.alerts', ['type' => 'all']) }}"
                   class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">Flagged</p>
                    <p class="mt-3 text-4xl font-bold text-red-700">{{ $flaggedCount }}</p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'high_bp']) }}"
                   class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">High BP</p>
                    <p class="mt-3 text-4xl font-bold text-red-700">{{ $highBpCount }}</p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'low_oxygen']) }}"
                   class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-orange-500 font-semibold">Low Oxygen</p>
                    <p class="mt-3 text-4xl font-bold text-orange-700">{{ $lowOxygenCount }}</p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'fever']) }}"
                   class="rounded-2xl border border-yellow-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-yellow-600 font-semibold">Fever / Temp</p>
                    <p class="mt-3 text-4xl font-bold text-yellow-700">{{ $feverCount }}</p>
                </a>

                <a href="{{ route('provider.alerts', ['type' => 'pulse']) }}"
                   class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-purple-500 font-semibold">Pulse Issues</p>
                    <p class="mt-3 text-4xl font-bold text-purple-700">{{ $pulseIssueCount }}</p>
                </a>
            </div>

            {{-- RECENT PATIENTS --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Recent Patients</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Quick access to patient workspaces and summaries.
                        </p>
                             <a href="{{ route('provider.notes.index') }}"
   class="flex items-center justify-between rounded-2xl bg-indigo-50 px-5 py-4 transition hover:bg-indigo-100">
    <span class="font-semibold text-indigo-700">Patient Workspace</span>
    <span>↗</span>
</a>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse($recentPatients as $patient)
                        @php
                            $patientName = $patient->name ?? $patient->full_name ?? 'Unnamed Patient';
                            $patientRoom = $patient->room ?? 'N/A';

                            $patientAlerts =
                                $patient->dashboard_alert_count
                                ?? $patient->alert_count
                                ?? $patient->alerts_count
                                ?? 0;

                            $patientMeds =
                                $patient->dashboard_medication_count
                                ?? $patient->medication_count
                                ?? 0;

                            $patientDiagnoses =
                                $patient->dashboard_diagnosis_count
                                ?? $patient->diagnosis_count
                                ?? 0;

                            $patientLastVisit =
                                $patient->dashboard_last_visit
                                ?? $patient->last_visit
                                ?? null;

                            if ($patientAlerts >= 3) {
                                $alertBadgeClass = 'bg-red-100 text-red-700';
                                $alertIcon = '🔴';
                            } elseif ($patientAlerts >= 1) {
                                $alertBadgeClass = 'bg-yellow-100 text-yellow-700';
                                $alertIcon = '🟡';
                            } else {
                                $alertBadgeClass = 'bg-emerald-100 text-emerald-700';
                                $alertIcon = '🟢';
                            }
                        @endphp

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 hover:bg-white transition">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $patientName }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        Room {{ $patientRoom }}
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 {{ $alertBadgeClass }}">
                                            {{ $alertIcon }} {{ $patientAlerts }} Alert{{ $patientAlerts == 1 ? '' : 's' }}
                                        </span>

                                        <span class="inline-flex items-center rounded-full bg-cyan-100 text-cyan-700 px-3 py-1">
                                            💊 {{ $patientMeds }} Medication{{ $patientMeds == 1 ? '' : 's' }}
                                        </span>

                                        <span class="inline-flex items-center rounded-full bg-violet-100 text-violet-700 px-3 py-1">
                                            📋 {{ $patientDiagnoses }} Diagnosis{{ $patientDiagnoses == 1 ? '' : 'es' }}
                                        </span>

                                        <span class="inline-flex items-center rounded-full bg-gray-200 text-gray-700 px-3 py-1">
                                            📅 Last visit:
                                            {{ $patientLastVisit ? \Carbon\Carbon::parse($patientLastVisit)->format('M j, Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if (\Illuminate\Support\Facades\Route::has('provider.patients.workspace'))
                                        <a href="{{ route('provider.patients.workspace', $patient->id) }}"
                                           class="inline-flex items-center rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2">
                                            Open Workspace
                                        </a>
                                    @endif

                                    @if (\Illuminate\Support\Facades\Route::has('provider.patients.summary'))
                                        <a href="{{ route('provider.patients.summary', $patient->id) }}"
                                           class="inline-flex items-center rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2">
                                            Summary
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-gray-50 py-12 text-center text-gray-500">
                            No patients registered yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ALERT DRIVEN WORKFLOW --}}
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
                       class="inline-flex items-center rounded-2xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                        View All Alerts
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'high_bp']) }}"
                       class="inline-flex items-center rounded-2xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                        Review High BP
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'low_oxygen']) }}"
                       class="inline-flex items-center rounded-2xl bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700">
                        Review Low Oxygen
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'fever']) }}"
                       class="inline-flex items-center rounded-2xl bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                        Review Fever / Temp
                    </a>

                    <a href="{{ route('provider.alerts', ['type' => 'pulse']) }}"
                       class="inline-flex items-center rounded-2xl bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700">
                        Review Pulse Issues
                    </a>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE --}}
        <div class="space-y-6">

            {{-- QUICK ACCESS --}}
            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Quick Access</h2>

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


                    @if (\Illuminate\Support\Facades\Route::has('provider.tasks.index'))
                        <a href="{{ route('provider.tasks.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-purple-50 px-5 py-4 transition hover:bg-purple-100">
                            <span class="font-semibold text-purple-700">Tasks</span>
                            <span>↗</span>
                        </a>
                    @endif
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

                <p class="mt-3 text-gray-500 leading-7">
                    Monitor counts, open filtered alert results, move into patient workspaces,
                    and keep provider attention focused without flooding the dashboard with every single card.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
