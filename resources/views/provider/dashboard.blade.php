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
        $screeningCount   = $screeningCount ?? ($preventiveScreeningCount ?? 0);

        $recentPatients = collect();

        if (isset($patients) && $patients instanceof \Illuminate\Support\Collection) {
            $recentPatients = $patients->take(5);
        } elseif (isset($patients) && is_iterable($patients)) {
            $recentPatients = collect($patients)->take(5);
        }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div class="rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 text-white p-8 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] font-semibold text-indigo-100">KASS CARE</p>

                <h1 class="mt-3 text-4xl font-bold">
                    Provider Clinical Command Center
                </h1>

                <p class="mt-3 text-indigo-100 text-lg max-w-3xl">
                    Monitor clinical alerts, review patient activity, communicate with facilities,
                    and move quickly across notes, pharmacy, compliance, and patient workspaces.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if(Route::has('provider.messages.create'))
                        <a href="{{ route('provider.messages.create') }}"
                           class="inline-flex items-center rounded-2xl bg-white px-5 py-3 font-semibold text-indigo-700 hover:bg-indigo-50">
                            ✉️ Message Facility
                        </a>
                    @endif

                    @if(Route::has('provider.alerts.index'))
                        <a href="{{ route('provider.alerts.index', ['type' => 'all']) }}"
                           class="inline-flex items-center rounded-2xl bg-white/15 px-5 py-3 font-semibold text-white hover:bg-white/20">
                            🔔 Open Alerts
                        </a>
                    @endif

                    @if(Route::has('provider.notes.index'))
                        <a href="{{ route('provider.notes.index') }}"
                           class="inline-flex items-center rounded-2xl bg-white/15 px-5 py-3 font-semibold text-white hover:bg-white/20">
                            📝 Provider Notes
                        </a>
                    @endif

                    @if(Route::has('provider.compliance'))
                        <a href="{{ route('provider.compliance') }}"
                           class="inline-flex items-center rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-white hover:bg-emerald-600">
                            ✅ Compliance
                        </a>
                    @endif
                </div>
            </div>

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

                <a href="{{ Route::has('provider.alerts.index') ? route('provider.alerts', ['type' => 'all']) : '#' }}"
                   class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">Flagged</p>
                    <p class="mt-3 text-4xl font-bold text-red-700">{{ $flaggedCount }}</p>
                </a>

                <a href="{{ Route::has('provider.alerts.index') ? route('provider.alerts.index', ['type' => 'high_bp']) : '#' }}"
                   class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold">High BP</p>
                    <p class="mt-3 text-4xl font-bold text-red-700">{{ $highBpCount }}</p>
                </a>

                <a href="{{ Route::has('provider.alerts') ? route('provider.alerts.index', ['type' => 'low_oxygen']) : '#' }}"
                   class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-orange-500 font-semibold">Low Oxygen</p>
                    <p class="mt-3 text-4xl font-bold text-orange-700">{{ $lowOxygenCount }}</p>
                </a>

                <a href="{{ Route::has('provider.alerts') ? route('provider.alerts.index', ['type' => 'fever']) : '#' }}"
                   class="rounded-2xl border border-yellow-100 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-yellow-600 font-semibold">Fever / Temp</p>
                    <p class="mt-3 text-4xl font-bold text-yellow-700">{{ $feverCount }}</p>
                </a>

                <a href="{{ Route::has('provider.alerts') ? route('provider.alerts.index', ['type' => 'preventive_screening']) : '#' }}"
                   class="rounded-2xl border border-purple-100 bg-purple-50 p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase tracking-[0.25em] text-purple-600 font-semibold">Screenings</p>
                    <p class="mt-3 text-4xl font-bold text-purple-700">{{ $screeningCount }}</p>
                </a>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900">Current Patients</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Quick access to patient workspaces and summaries.
                </p>

                <div class="mt-5 space-y-3">
                    @forelse($recentPatients as $patient)
                        @php
                            $patientName = $patient->name ?? $patient->full_name ?? 'Unnamed Patient';
                            $patientRoom = $patient->room ?? 'N/A';

                            $patientAlerts = $patient->dashboard_alert_count
                                ?? $patient->alert_count
                                ?? $patient->alerts_count
                                ?? 0;

                            $patientMeds = $patient->dashboard_medication_count
                                ?? $patient->medication_count
                                ?? 0;

                            $patientDiagnoses = $patient->dashboard_diagnosis_count
                                ?? $patient->diagnosis_count
                                ?? 0;

                            $patientLastVisit = $patient->dashboard_last_visit
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
                                    <div class="text-lg font-semibold text-gray-900">{{ $patientName }}</div>
                                    <div class="text-sm text-gray-500 mt-1">Room {{ $patientRoom }}</div>

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
                                            {{ $patientLastVisit ? \Carbon\Carbon::parse($patientLastVisit)->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if(Route::has('provider.patients.workspace'))
                                        <a href="{{ route('provider.patients.workspace', $patient->id) }}"
                                           class="inline-flex items-center rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-sm font-semibold text-white">
                                            Review Patient
                                        </a>
                                    @endif

                                    @if(Route::has('provider.patients.summary'))
                                        <a href="{{ route('provider.patients.summary', $patient->id) }}"
                                           class="inline-flex items-center rounded-xl bg-gray-200 hover:bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-800">
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

            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-semibold">
                    Provider Task Summary
                </p>

                <h3 class="mt-4 text-2xl font-bold text-gray-900">
                    Alert-Driven Workflow
                </h3>

                <p class="mt-3 text-gray-500 leading-7">
                    Keep the dashboard clean by reviewing patient alert details from the Alerts page.
                    Click any alert count above to open matching clinical items.
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Quick Access</h2>

                <div class="space-y-4">
                    @if(Route::has('provider.messages.create'))
                        <a href="{{ route('provider.messages.create') }}"
                           class="flex items-center justify-between rounded-2xl bg-indigo-50 px-5 py-4 transition hover:bg-indigo-100">
                            <span class="font-semibold text-indigo-700">✉️ Message Facility</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.messages.index'))
                        <a href="{{ route('provider.messages.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-slate-50 px-5 py-4 transition hover:bg-slate-100">
                            <span class="font-semibold text-slate-700">📥 View Messages</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.alerts.index'))
                        <a href="{{ route('provider.alerts.index', ['type' => 'all']) }}"
                           class="flex items-center justify-between rounded-2xl bg-red-50 px-5 py-4 transition hover:bg-red-100">
                            <span class="font-semibold text-red-700">🔔 Clinical Alerts</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.calendar'))
                        <a href="{{ route('provider.calendar') }}"
                           class="flex items-center justify-between rounded-2xl bg-blue-50 px-5 py-4 transition hover:bg-blue-100">
                            <span class="font-semibold text-blue-700">📅 Open Schedule</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.compliance'))
                        <a href="{{ route('provider.compliance') }}"
                           class="flex items-center justify-between rounded-2xl bg-yellow-50 px-5 py-4 transition hover:bg-yellow-100">
                            <span class="font-semibold text-yellow-700">✅ Compliance Dashboard</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.notes.index'))
                        <a href="{{ route('provider.notes.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-green-50 px-5 py-4 transition hover:bg-green-100">
                            <span class="font-semibold text-green-700">📝 Provider Notes</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.pharmacy.index'))
                        <a href="{{ route('provider.pharmacy.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-cyan-50 px-5 py-4 transition hover:bg-cyan-100">
                            <span class="font-semibold text-cyan-700">💊 Pharmacy / Rx</span>
                            <span>↗️</span>
                        </a>
                    @endif

                    @if(Route::has('provider.tasks.index'))
                        <a href="{{ route('provider.tasks.index') }}"
                           class="flex items-center justify-between rounded-2xl bg-purple-50 px-5 py-4 transition hover:bg-purple-100">
                            <span class="font-semibold text-purple-700">📌 Tasks</span>
                            <span>↗️</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-semibold">
                    Provider Summary
                </p>

                <h3 class="mt-4 text-2xl font-bold text-gray-900">
                    Real-time Clinical Oversight
                </h3>

                <p class="mt-3 text-gray-500 leading-7">
                    Monitor alerts, open filtered patient results, communicate with facilities,
                    and keep provider attention focused without flooding the dashboard.
                </p>
            </div>

            <div class="rounded-3xl border border-indigo-100 bg-indigo-50 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 font-semibold">
                    Communication
                </p>

                <h3 class="mt-4 text-xl font-bold text-indigo-900">
                    Facility Messaging
                </h3>

                <p class="mt-3 text-sm text-indigo-700 leading-6">
                    Send messages to facilities, review incoming facility concerns,
                    and keep patient communication connected to provider workflow.
                </p>

                @if(Route::has('provider.messages.create'))
                    <a href="{{ route('provider.messages.create') }}"
                       class="mt-5 inline-flex rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-700">
                        Start Message
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
