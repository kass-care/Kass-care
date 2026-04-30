@extends('layouts.app')

@section('content')
@php
    $visit = $providerNote->visit ?? null;
    $client = $visit?->client;
    $caregiver = $visit?->caregiver;

    $clientName = $client?->name ?? 'N/A';

    $clientDob = $client?->date_of_birth
        ? \Carbon\Carbon::parse($client->date_of_birth)->format('m/d/Y')
        : 'N/A';

    $visitDate = $visit?->visit_date
        ? \Carbon\Carbon::parse($visit->visit_date)->format('m/d/Y')
        : 'N/A';

    $weight = $client?->weight;
    $height = $client?->height;
    $bmi = $client?->bmi;

    $bmiStatus = null;

    if ($bmi) {
        if ($bmi < 18.5) {
            $bmiStatus = 'Underweight';
        } elseif ($bmi < 25) {
            $bmiStatus = 'Normal';
        } elseif ($bmi < 30) {
            $bmiStatus = 'Overweight';
        } else {
            $bmiStatus = 'Obese';
        }
    }
@endphp

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">View Clinical Note</h1>
                <p class="text-slate-600 mt-1">Provider note details for this visit.</p>
            </div>

            <div class="flex gap-3">
                @if(Route::has('provider.notes.edit'))
                    <a href="{{ route('provider.notes.edit', $providerNote) }}"
                       class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-900 hover:bg-yellow-500">
                        ✏️ Edit Note
                    </a>
                @endif
                 <a href="{{ route('provider.coding.assistant', $providerNote->id) }}"
   class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-bold text-white hover:bg-purple-700 transition">
    🧠 Coding Assistant
</a>

                <a href="{{ route('provider.notes.index') }}"
                   class="rounded-xl bg-white border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100">
                    Back to Notes
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <p><strong>Visit ID:</strong> {{ $visit?->id ?? 'N/A' }}</p>
                <p><strong>Client:</strong> {{ $clientName }}</p>
                <p><strong>MRN:</strong> {{ $client?->mrn ?? 'N/A' }}</p>
                <p><strong>Date of Birth:</strong> {{ $clientDob }}</p>
                <p><strong>Caregiver:</strong> {{ $caregiver?->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $visitDate }}</p>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Clinical Measurements</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="rounded-xl bg-white p-4">
                        <p class="text-xs uppercase text-slate-500 font-bold">Weight</p>
                        <p class="mt-1 font-semibold">{{ $weight ? $weight . ' lb' : 'N/A' }}</p>
                    </div>

                    <div class="rounded-xl bg-white p-4">
                        <p class="text-xs uppercase text-slate-500 font-bold">Height</p>
                        <p class="mt-1 font-semibold">{{ $height ? $height . ' inches' : 'N/A' }}</p>
                    </div>

                    <div class="rounded-xl bg-white p-4">
                        <p class="text-xs uppercase text-slate-500 font-bold">BMI</p>
                        <p class="mt-1 font-semibold">
                            {{ $bmi ?? 'N/A' }}
                            @if($bmiStatus)
                                <span class="text-xs text-slate-500">({{ $bmiStatus }})</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                    <h3 class="font-bold text-yellow-800">Chief Complaint</h3>
                    <p class="mt-2 text-sm whitespace-pre-line text-slate-800">
                        {{ $providerNote->chief_complaint ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                    <h3 class="font-bold text-blue-800">Subjective</h3>
                    <p class="mt-2 text-sm whitespace-pre-line text-slate-800">
                        {{ $providerNote->subjective ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-green-200 bg-green-50 p-5">
                    <h3 class="font-bold text-green-800">Objective</h3>
                    <p class="mt-2 text-sm whitespace-pre-line text-slate-800">
                        {{ $providerNote->objective ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                    <h3 class="font-bold text-purple-800">Assessment</h3>
                    <p class="mt-2 text-sm whitespace-pre-line text-slate-800">
                        {{ $providerNote->assessment ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                    <h3 class="font-bold text-indigo-800">Plan</h3>
                    <p class="mt-2 text-sm whitespace-pre-line text-slate-800">
                        {{ $providerNote->plan ?? 'N/A' }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
