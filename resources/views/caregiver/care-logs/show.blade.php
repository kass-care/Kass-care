@extends('layouts.layout')

@section('content')
@php
    $visit = $careLog->visit;
    $client = $visit?->client;
    $caregiver = $visit?->caregiver;

    $clientName = $client?->name ?? 'N/A';
    $caregiverName = $caregiver?->name ?? 'N/A';

    $adls = is_array($careLog->adls) ? $careLog->adls : [];
    $vitals = is_array($careLog->vitals) ? $careLog->vitals : [];

    $adlValue = function ($keys, $fallback = 'N/A') use ($adls) {
        foreach ((array) $keys as $key) {
            if (isset($adls[$key]) && $adls[$key] !== '') {
                return $adls[$key];
            }
        }
        return $fallback;
    };

    $vitalValue = function ($keys, $fallback = 'N/A') use ($vitals) {
        foreach ((array) $keys as $key) {
            if (isset($vitals[$key]) && $vitals[$key] !== '') {
                return $vitals[$key];
            }
        }
        return $fallback;
    };

    $alerts = [];

    $bp = $vitalValue(['blood_pressure', 'bp'], null);
    if ($bp && str_contains($bp, '/')) {
        [$systolic] = explode('/', $bp, 2);
        if (is_numeric(trim($systolic)) && (int) trim($systolic) >= 140) {
            $alerts[] = ['title' => 'High Blood Pressure', 'message' => 'Blood pressure is above normal range.'];
        }
    }

    $oxygen = $vitalValue(['oxygen_saturation', 'oxygen'], null);
    if ($oxygen !== null && is_numeric($oxygen) && (float) $oxygen < 92) {
        $alerts[] = ['title' => 'Low Oxygen Saturation', 'message' => 'Oxygen saturation is below normal range.'];
    }

    $temp = $vitalValue(['temperature', 'temp'], null);
    if ($temp !== null && is_numeric($temp) && (float) $temp >= 100.4) {
        $alerts[] = ['title' => 'Fever Alert', 'message' => 'Temperature is above normal range.'];
    }
@endphp

<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <p class="text-xs font-bold tracking-[0.25em] text-indigo-600 uppercase">Care Log Details</p>
            <h1 class="mt-2 text-3xl font-black text-slate-900">Visit Care Documentation</h1>
            <p class="mt-2 text-sm text-slate-600">Review ADL charting, vitals, alerts, and care notes.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-700 to-blue-500 px-6 py-5 text-white">
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100 font-bold">Visit Snapshot</p>
                        <h2 class="mt-2 text-2xl font-black">{{ $clientName }}</h2>
                        <p class="mt-1 text-sm text-indigo-100">Caregiver: {{ $caregiverName }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase text-slate-500 font-bold">Visit ID</p>
                            <p class="mt-2 text-lg font-black">#{{ $careLog->visit_id ?? 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase text-slate-500 font-bold">Client</p>
                            <p class="mt-2 text-lg font-black">{{ $clientName }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase text-slate-500 font-bold">Caregiver</p>
                            <p class="mt-2 text-lg font-black">{{ $caregiverName }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs uppercase text-slate-500 font-bold">Created</p>
                            <p class="mt-2 text-lg font-black">{{ $careLog->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-black text-slate-900">Clinical Alerts</h2>
                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                            {{ count($alerts) }} ALERT{{ count($alerts) === 1 ? '' : 'S' }}
                        </span>
                    </div>

                    @forelse($alerts as $alert)
                        <div class="mb-3 rounded-xl border border-red-200 bg-red-50 px-4 py-4">
                            <p class="font-bold text-red-800">{{ $alert['title'] }}</p>
                            <p class="mt-1 text-sm text-red-700">{{ $alert['message'] }}</p>
                        </div>
                    @empty
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                            No abnormal alerts detected from the saved vitals.
                        </div>
                    @endforelse
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-black text-slate-900 mb-5">Vitals</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach([
                            'Blood Pressure' => $vitalValue(['blood_pressure', 'bp']),
                            'Pulse' => $vitalValue('pulse'),
                            'Temperature' => $vitalValue(['temperature', 'temp']),
                            'Respiratory Rate' => $vitalValue('respiratory_rate'),
                            'Oxygen Saturation' => $vitalValue(['oxygen_saturation', 'oxygen']),
                            'Blood Sugar' => $vitalValue('blood_sugar'),
                            'Weight' => $vitalValue('weight') !== 'N/A' ? $vitalValue('weight') . ' lb' : 'N/A',
                        ] as $label => $value)
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500 font-bold">{{ $label }}</p>
                                <p class="mt-2 text-lg font-black text-slate-900">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-black text-slate-900 mb-5">ADL Charting</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            'ADL Status' => $adlValue(['adl_status', 'status']),
                            'Bathroom Assistance' => $adlValue(['bathroom_assistance', 'bathroom', 'toileting']),
                            'Mobility Support' => $adlValue(['mobility_support', 'mobility']),
                            'Meal Notes' => $adlValue(['meal_notes', 'meals', 'feeding']),
                            'Medication Notes' => $adlValue(['medication_notes', 'medications']),
                            'Charting Notes' => $adlValue(['charting_notes', 'notes']),
                        ] as $label => $value)
                            <div class="rounded-xl bg-slate-50 p-4 {{ in_array($label, ['Medication Notes', 'Charting Notes']) ? 'sm:col-span-2' : '' }}">
                                <p class="text-xs uppercase tracking-wide text-slate-500 font-bold">{{ $label }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-900 whitespace-pre-line">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-6">
                    <h2 class="text-xl font-black text-slate-900 mb-4">Care Notes</h2>

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
                    <h3 class="text-lg font-black text-slate-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('caregiver.care-logs.index') }}"
                           class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-white font-bold">
                            Back to Care Logs
                        </a>

                        <a href="{{ route('caregiver.dashboard') }}"
                           class="inline-flex w-full items-center justify-center rounded-xl bg-slate-200 px-4 py-3 text-slate-800 font-bold">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 p-6 text-white shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-200 font-bold">KASS CARE</p>
                    <h3 class="mt-3 text-xl font-black">Professional Care Documentation</h3>
                    <p class="mt-3 text-sm text-slate-200 leading-6">
                        Clean charting, quick review, and clear alerts for safer care delivery.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
