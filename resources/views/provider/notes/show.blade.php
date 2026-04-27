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

<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">View Clinical Note</h1>
            <p class="text-gray-600">Provider note details for this visit.</p>
        </div>

        <a href="{{ route('provider.notes.index') }}"
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
            Back to Notes
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <p><strong>Visit ID:</strong> {{ $visit?->id ?? 'N/A' }}</p>
            <p><strong>Client:</strong> {{ $clientName }}</p>
            <p><strong>MRN:</strong> {{ $client?->mrn ?? 'N/A' }}</p>
            <p><strong>Date of Birth:</strong> {{ $clientDob }}</p>
            <p><strong>Caregiver:</strong> {{ $caregiver?->name ?? 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $visitDate }}</p>
        </div>

        <hr>

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

        <div>
            <h2 class="text-lg font-bold mb-2">Clinical Note</h2>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 whitespace-pre-line text-sm text-slate-800">
{{ $providerNote->note ?? '' }}
            </div>
        </div>
 <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
    <p class="text-xs uppercase text-slate-500 font-bold">Chief Complaint</p>
    <p class="mt-2 text-sm whitespace-pre-line">
        {{ $providerNote->chief_complaint ?? 'N/A' }}
    </p>
</div>          

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <p class="text-xs uppercase text-slate-500 font-bold">Subjective</p>
                <p class="mt-2 text-sm whitespace-pre-line">{{ $providerNote->subjective ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <p class="text-xs uppercase text-slate-500 font-bold">Assessment</p>
                <p class="mt-2 text-sm whitespace-pre-line">{{ $providerNote->assessment ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <p class="text-xs uppercase text-slate-500 font-bold">Plan</p>
                <p class="mt-2 text-sm whitespace-pre-line">{{ $providerNote->plan ?? 'N/A' }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
