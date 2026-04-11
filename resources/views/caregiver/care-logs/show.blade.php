@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @php
            $visit = $careLog->visit;
            $client = $visit?->client;
            $caregiver = $visit?->caregiver;

            $clientName =
                $client?->full_name
                ?? trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))
                ?: ($client->name ?? 'N/A');

            $caregiverName =
                $caregiver?->name
                ?? 'N/A';

            $createdAt = $careLog->created_at
                ? $careLog->created_at->format('M d, Y h:i A')
                : 'N/A';

            $adls = is_array($careLog->adls) ? $careLog->adls : [];
            $vitals = is_array($careLog->vitals) ? $careLog->vitals : [];

            $alerts = [];

            if (!empty($vitals['blood_pressure'])) {
                $bp = trim((string) $vitals['blood_pressure']);

                if (str_contains($bp, '/')) {
                    [$systolic, $diastolic] = array_pad(explode('/', $bp, 2), 2, null);

                    $systolic = is_numeric(trim((string) $systolic)) ? (int) trim((string) $systolic) : null;
                    $diastolic = is_numeric(trim((string) $diastolic)) ? (int) trim((string) $diastolic) : null;

                    if (!is_null($systolic) && $systolic >= 140) {
                        $alerts[] = [
                            'title' => 'High Blood Pressure',
                            'message' => 'Blood pressure is above normal range.',
                        ];
                    }

                    if (!is_null($diastolic) && $diastolic >= 90) {
                        $alerts[] = [
                            'title' => 'Elevated Diastolic Pressure',
                            'message' => 'Diastolic pressure is above normal range.',
                        ];
                    }
                }
            }

            if (!empty($vitals['oxygen_saturation']) && is_numeric($vitals['oxygen_saturation'])) {
                if ((int) $vitals['oxygen_saturation'] < 92) {
                    $alerts[] = [
                        'title' => 'Low Oxygen Saturation',
                        'message' => 'Oxygen saturation is below normal range.',
                    ];
                }
            }

            if (!empty($vitals['temperature']) && is_numeric($vitals['temperature'])) {
                $temp = (float) $vitals['temperature'];

                if ($temp >= 100.4) {
                    $alerts[] = [
                        'title' => 'Fever Alert',
                        'message' => 'Temperature is above normal range.',
                    ];
                }
            }
        @endphp

        <div class="mb-8">
            <p class="text-xs font-semibold tracking-[0.25em] text-indigo-600 uppercase">
                Care Log Details
            </p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Visit Care Documentation
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Review ADL charting, vitals, alerts, and care notes for this saved visit.
            </p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2 space-y-6">

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-500 px-6 py-5 text-white">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-indigo-100 font-semibold">
                                    Visit Snapshot
                                </p>
                                <h2 class="mt-2 text-2xl font-bold">
                                    {{ $clientName }}
                                </h2>
                                <p class="mt-1 text-sm text-indigo-100">
                                    Caregiver: {{ $caregiverName }}
                                </p>
                            </div>
                            <div class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
                                Saved Care Log
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Visit ID</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">#{{ $careLog->visit_id ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Client</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $clientName }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Caregiver</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $caregiverName }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Created</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $createdAt }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900">Clinical Alerts</h2>
                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                            {{ count($alerts) }} ALERT{{ count($alerts) === 1 ? '' : 'S' }}
                        </span>
                    </div>

                    @if(count($alerts))
                        <div class="space-y-3">
                            @foreach($alerts as $alert)
                                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-4">
                                    <p class="font-semibold text-red-800">{{ $alert['title'] }}</p>
                                    <p class="mt-1 text-sm text-red-700">{{ $alert['message'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                            No abnormal alerts detected from the saved vitals.
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-5">Vitals</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Blood Pressure</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['blood_pressure'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Pulse</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['pulse'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Temperature</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['temperature'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Respiratory Rate</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['respiratory_rate'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Oxygen Saturation</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['oxygen_saturation'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Blood Sugar</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $vitals['blood_sugar'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Weight</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">
                                @if(!empty($vitals['weight']))
                                    {{ $vitals['weight'] }} lb
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-5">ADL Charting</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">ADL Status</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $adls['adl_status'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Bathroom Assistance</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $adls['bathroom_assistance'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Mobility Support</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $adls['mobility_support'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Meal Notes</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ $adls['meal_notes'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Medication Notes</p>
                            <p class="mt-2 text-base font-semibold text-slate-900 whitespace-pre-line">{{ $adls['medication_notes'] ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Charting Notes</p>
                            <p class="mt-2 text-base font-semibold text-slate-900 whitespace-pre-line">{{ $adls['charting_notes'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Care Notes</h2>

                    @if(!empty($careLog->notes))
                        <div class="rounded-xl bg-slate-50 p-4 text-slate-700 whitespace-pre-line">
                            {{ $careLog->notes }}
                        </div>
                    @else
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-500">
                            No notes added.
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('caregiver.care-logs.index') }}"
                           class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                            Back to Care Logs
                        </a>

                        <a href="{{ route('caregiver.dashboard') }}"
                           class="inline-flex w-full items-center justify-center rounded-xl bg-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-300">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 p-6 text-white shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-200 font-semibold">
                        KASS CARE
                    </p>
                    <h3 class="mt-3 text-xl font-bold">
                        Professional Care Documentation
                    </h3>
                    <p class="mt-3 text-sm text-slate-200 leading-6">
                        Clean charting, quick review, and clear alerts for safer care delivery.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
