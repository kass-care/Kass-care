@extends('layouts.app')

@section('content')
@php
    $client = $note->visit?->client ?? null;
    $noteText = $note->note ?? '';

    $icdSuggestions = $icdSuggestions ?? [];
    $cpt = $cpt ?? '99214';
    $pos = $pos ?? '12';

    $cptLabel = match ($cpt) {
        '99213' => 'Established patient visit — low complexity suggestion',
        '99214' => 'Established patient visit — moderate complexity suggestion',
        '99215' => 'Established patient visit — high complexity suggestion',
        default => 'E/M level based on documentation complexity',
    };

    $posLabel = match ($pos) {
        '12' => 'Home / residence setting suggestion',
        '13' => 'Assisted living facility suggestion',
        '31' => 'Skilled nursing facility suggestion',
        '32' => 'Nursing facility suggestion',
        default => 'Place of service requires review',
    };
@endphp

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <div class="bg-indigo-800 text-white rounded-3xl p-8 shadow">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>

                    <h1 class="text-3xl font-black">
                        Clinical Coding Assistant
                    </h1>
                      <form method="POST" action="{{ route('provider.claims.generate', $note->id) }}">
    @csrf

        <form method="POST" action="{{ route('provider.claims.generate', $note->id) }}"
      class="mt-4 rounded-2xl bg-white/10 border border-white/20 p-4">
    @csrf

    <p class="text-sm font-bold text-white mb-3">
        Provider Final Coding Selection
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-bold text-indigo-100 mb-1">ICD-10 Codes</label>
            <input type="text"
                   name="icd_codes"
                   value="{{ collect($icdSuggestions)->pluck('code')->implode(', ') }}"
                   class="w-full rounded-xl border-0 px-3 py-2 text-sm text-slate-900"
                   placeholder="Example: R06.02, J44.9">
        </div>

        <div>
            <label class="block text-xs font-bold text-indigo-100 mb-1">CPT Code</label>
            <input type="text"
                   name="cpt_code"
                   value="{{ $cpt }}"
                   class="w-full rounded-xl border-0 px-3 py-2 text-sm text-slate-900"
                   placeholder="99214">
        </div>

        <div>
            <label class="block text-xs font-bold text-indigo-100 mb-1">POS</label>
            <input type="text"
                   name="pos_code"
                   value="{{ $pos }}"
                   class="w-full rounded-xl border-0 px-3 py-2 text-sm text-slate-900"
                   placeholder="12">
        </div>
    </div>

    <button class="mt-4 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-600">
        💰 Generate Claim
    </button>
</form>
                    <p class="mt-2 text-indigo-100">
                        Suggested ICD-10, CPT/HCPCS, POS, and care-plan support for provider review.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('provider.notes.show', $note->id) }}"
                       class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800 hover:bg-indigo-50">
                        Back to Note
                    </a>

                    <a href="{{ route('provider.notes.index') }}"
                       class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-900 hover:bg-yellow-300">
                        Notes
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Patient Context</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase font-bold text-slate-500">Client</p>
                    <p class="mt-1 font-bold text-slate-900">
                        {{ $client?->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase font-bold text-slate-500">DOB</p>
                    <p class="mt-1 font-bold text-slate-900">
                        {{ $client?->date_of_birth ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs uppercase font-bold text-slate-500">Visit</p>
                    <p class="mt-1 font-bold text-slate-900">
                        #{{ $note->visit_id ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Clinical Note Review</h2>

            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5 text-sm whitespace-pre-line text-slate-800">
                {{ $noteText ?: 'No note text available.' }}
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Suggested Coding</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                    <p class="text-xs uppercase font-bold text-indigo-700">ICD-10 Suggestions</p>

                    <div class="mt-4 space-y-3">
                        @forelse($icdSuggestions as $icd)
                            <div class="rounded-xl bg-white border border-indigo-100 p-3">
                                <p class="text-lg font-black text-slate-900">
                                    {{ $icd['code'] ?? 'Review' }}
                                </p>
                                <p class="text-sm text-slate-600">
                                    {{ $icd['label'] ?? 'Provider/biller review needed' }}
                                </p>
                            </div>
                        @empty
                            <div class="rounded-xl bg-white border border-indigo-100 p-3">
                                <p class="text-lg font-black text-slate-900">Needs Review</p>
                                <p class="text-sm text-slate-600">
                                    No clear ICD-10 suggestion detected.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                    <p class="text-xs uppercase font-bold text-emerald-700">CPT / E-M Suggestion</p>

                    <div class="mt-4 rounded-xl bg-white border border-emerald-100 p-3">
                        <p class="text-lg font-black text-slate-900">
                            {{ $cpt }}
                        </p>
                        <p class="text-sm text-slate-600">
                            {{ $cptLabel }}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5">
                    <p class="text-xs uppercase font-bold text-purple-700">Place of Service</p>

                    <div class="mt-4 rounded-xl bg-white border border-purple-100 p-3">
                        <p class="text-lg font-black text-slate-900">
                            POS {{ $pos }}
                        </p>
                        <p class="text-sm text-slate-600">
                            {{ $posLabel }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-3xl shadow border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Plan Support</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-xs uppercase font-bold text-slate-500">Documentation Check</p>

                    <ul class="mt-3 text-sm text-slate-700 space-y-2 list-disc list-inside">
                        <li>
                            Chief complaint:
                            <strong>{{ !empty($note->chief_complaint) ? 'Documented' : 'Needs review' }}</strong>
                        </li>
                        <li>
                            Subjective findings:
                            <strong>{{ !empty($note->subjective) ? 'Documented' : 'Needs review' }}</strong>
                        </li>
                        <li>
                            Objective findings:
                            <strong>{{ !empty($note->objective) ? 'Documented' : 'Needs review' }}</strong>
                        </li>
                        <li>
                            Assessment:
                            <strong>{{ !empty($note->assessment) ? 'Documented' : 'Needs review' }}</strong>
                        </li>
                        <li>
                            Plan:
                            <strong>{{ !empty($note->plan) ? 'Documented' : 'Needs review' }}</strong>
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-xs uppercase font-bold text-slate-500">Billing Review Notes</p>
                    <p class="mt-3 text-sm text-slate-700">
                        Provider or biller should verify final ICD-10, CPT/HCPCS, modifier, payer rule,
                        medical necessity, documentation support, and place-of-service selection before insurance submission.
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-yellow-50 border border-yellow-200 p-5">
            <p class="font-bold text-yellow-900">Important</p>
            <p class="mt-1 text-sm text-yellow-900">
                Coding suggestions are decision-support only. They are not a final billing determination.
                Provider or certified biller must verify all codes before submission.
            </p>
        </div>

    </div>
</div>
@endsection
