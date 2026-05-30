@extends('layouts.app')

@section('content')
@php
    $client = $note->visit?->client ?? $note->client ?? null;
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
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase font-bold text-purple-600">Documentation Quality</p>
            <h2 class="mt-1 text-xl font-black text-slate-900">Coding Readiness Score</h2>
        </div>
            
        <div class="rounded-2xl bg-purple-100 px-5 py-3 text-3xl font-black text-purple-800">
            {{ $documentationScore ?? 0 }}%
        </div>
    </div>
@php
    $estimatedAmount = match ($cpt) {
        '99215' => 225,
        '99214' => 150,
        '99213' => 95,
        default => 75,
    };

    $nextCpt = null;
    $nextAmount = null;

    if ($cpt === '99213') {
        $nextCpt = '99214';
        $nextAmount = 150;
    } elseif ($cpt === '99214') {
        $nextCpt = '99215';
        $nextAmount = 225;
    }

    $revenueOpportunity = $nextAmount
        ? ($nextAmount - $estimatedAmount)
        : 0;

    $confidenceLabel = ($documentationScore ?? 0) >= 85
        ? 'Strong'
        : (($documentationScore ?? 0) >= 70 ? 'Moderate' : 'Needs Review');
$claimRiskScore = 100;
$claimRiskFactors = [];

if (empty($note->chief_complaint)) {
    $claimRiskScore -= 15;
    $claimRiskFactors[] = 'Chief complaint missing';
}

if (empty($note->subjective)) {
    $claimRiskScore -= 15;
    $claimRiskFactors[] = 'Subjective findings missing';
}

if (empty($note->objective)) {
    $claimRiskScore -= 15;
    $claimRiskFactors[] = 'Objective findings missing';
}

if (empty($note->assessment)) {
    $claimRiskScore -= 20;
    $claimRiskFactors[] = 'Assessment missing';
}

if (empty($note->plan)) {
    $claimRiskScore -= 20;
    $claimRiskFactors[] = 'Plan missing';
}

if (empty($icdSuggestions)) {
    $claimRiskScore -= 15;
    $claimRiskFactors[] = 'No clear ICD-10 support detected';
}

$claimRiskScore = max(0, $claimRiskScore);

$claimRiskLabel = match (true) {
    $claimRiskScore >= 85 => 'LOW RISK',
    $claimRiskScore >= 70 => 'MODERATE RISK',
    default => 'HIGH RISK',
};
@endphp
<div class="bg-white rounded-3xl shadow border border-emerald-200 p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase font-bold text-emerald-600">Suggested Revenue</p>
            <h2 class="mt-1 text-xl font-black text-slate-900">Estimated Claim Value</h2>
            <p class="mt-1 text-sm text-slate-600">
                Based on suggested E/M CPT level and documentation readiness.
            </p>
        </div>

        <div class="text-right">
            <p class="text-4xl font-black text-emerald-700">
                ${{ number_format($estimatedAmount, 2) }}
            </p>
            <p class="mt-1 text-sm font-bold text-slate-500">
                CPT {{ $cpt }}
            </p>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
            <p class="text-xs uppercase font-bold text-emerald-700">CPT</p>
            <p class="mt-1 text-2xl font-black text-emerald-800">{{ $cpt }}</p>
        </div>
            
        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
            <p class="text-xs uppercase font-bold text-indigo-700">POS</p>
            <p class="mt-1 text-2xl font-black text-indigo-800">{{ $pos }}</p>
        </div>

        <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4">
            <p class="text-xs uppercase font-bold text-amber-700">Confidence</p>
            <p class="mt-1 text-2xl font-black text-amber-800">{{ $confidenceLabel }}</p>
        </div>
    </div>
</div>
@if($nextCpt)
<div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
    <p class="text-xs uppercase font-bold text-emerald-700">
        Revenue Opportunity
    </p>

    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <p class="text-xs text-slate-500">Current</p>
            <p class="font-black text-slate-900">
                {{ $cpt }} = ${{ number_format($estimatedAmount,2) }}
            </p>
        </div>

        <div>
            <p class="text-xs text-slate-500">Potential</p>
            <p class="font-black text-indigo-800">
                {{ $nextCpt }} = ${{ number_format($nextAmount,2) }}
            </p>
        </div>

        <div>
            <p class="text-xs text-slate-500">Opportunity</p>
            <p class="font-black text-emerald-700">
                +${{ number_format($revenueOpportunity,2) }}
            </p>
        </div>

    </div>

    <p class="mt-3 text-sm text-slate-700">
        Improving documentation completeness may support a higher complexity level when clinically justified.
    </p>
</div>
@endif
   <div class="mt-5 rounded-2xl border border-blue-200 bg-blue-50 p-5">
    <p class="text-xs uppercase font-bold text-blue-700">
        Claim Approval Probability
    </p>

    <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-5xl font-black text-blue-800">
                {{ $claimRiskScore }}%
            </p>
            <p class="mt-1 text-sm font-black text-slate-700">
                {{ $claimRiskLabel }}
            </p>
        </div>

        <div class="md:text-right">
            @if(empty($claimRiskFactors))
                <p class="rounded-xl bg-emerald-100 px-4 py-3 text-sm font-bold text-emerald-800">
                    ✅ Claim documentation appears strong for review.
                </p>
            @else
                <div class="space-y-2">
                    @foreach($claimRiskFactors as $factor)
                        <p class="rounded-xl bg-white border border-blue-100 px-4 py-2 text-sm font-bold text-slate-700">
                            ⚠ {{ $factor }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <p class="mt-4 text-sm text-slate-700">
        This is a decision-support estimate only. Provider or biller must verify payer rules, medical necessity, ICD/CPT support, and final claim submission.
    </p>
</div>
@php
    $hasIcdSupport = !empty($icdSuggestions);

    $icdCptValidationStatus = $hasIcdSupport
        ? 'SUPPORTED'
        : 'NEEDS REVIEW';

    $icdCptValidationMessage = $hasIcdSupport
        ? 'At least one ICD-10 suggestion is present for provider/biller review.'
        : 'No clear ICD-10 support was detected for the suggested CPT level. Review diagnosis documentation before claim submission.';
@endphp

<div class="mt-5 rounded-2xl border border-violet-200 bg-violet-50 p-5">
    <p class="text-xs uppercase font-bold text-violet-700">
        ICD/CPT Validation
    </p>

    <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-3xl font-black text-violet-800">
                {{ $icdCptValidationStatus }}
            </p>
            <p class="mt-1 text-sm font-bold text-slate-600">
                CPT {{ $cpt }} · ICD Support Check
            </p>
        </div>

        <div class="rounded-xl bg-white border border-violet-100 px-4 py-3 text-sm font-bold text-slate-700 md:max-w-md">
            {{ $icdCptValidationMessage }}
        </div>
    </div>
</div>
@php
    $medicalNecessityScore = 100;
    $medicalNecessityFactors = [];

    if (empty($note->chief_complaint)) {
        $medicalNecessityScore -= 20;
        $medicalNecessityFactors[] = 'Chief complaint is needed to support why the visit occurred.';
    }

    if (empty($note->assessment)) {
        $medicalNecessityScore -= 25;
        $medicalNecessityFactors[] = 'Assessment is needed to support clinical decision-making.';
    }

    if (empty($note->plan)) {
        $medicalNecessityScore -= 25;
        $medicalNecessityFactors[] = 'Plan is needed to support ongoing treatment or monitoring.';
    }

    if (empty($note->objective)) {
        $medicalNecessityScore -= 15;
        $medicalNecessityFactors[] = 'Objective findings/vitals help support medical necessity.';
    }

    if ($cpt === '99215' && ($documentationScore ?? 0) < 90) {
        $medicalNecessityScore -= 20;
        $medicalNecessityFactors[] = 'High-complexity CPT 99215 may require stronger documentation support.';
    }

    if ($cpt === '99214' && ($documentationScore ?? 0) < 70) {
        $medicalNecessityScore -= 15;
        $medicalNecessityFactors[] = 'Moderate-complexity CPT 99214 may require stronger documentation support.';
    }

    $medicalNecessityScore = max(0, $medicalNecessityScore);

    $medicalNecessityStatus = match (true) {
        $medicalNecessityScore >= 85 => 'SUPPORTED',
        $medicalNecessityScore >= 70 => 'NEEDS REVIEW',
        default => 'HIGH RISK',
    };
@endphp

<div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-5">
    <p class="text-xs uppercase font-bold text-rose-700">
        Medical Necessity Review
    </p>

    <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-3xl font-black text-rose-800">
                {{ $medicalNecessityScore }}%
            </p>
            <p class="mt-1 text-sm font-bold text-slate-600">
                {{ $medicalNecessityStatus }}
            </p>
        </div>

        <div class="md:max-w-md">
            @if(empty($medicalNecessityFactors))
                <p class="rounded-xl bg-white border border-rose-100 px-4 py-3 text-sm font-bold text-slate-700">
                    ✅ Documentation appears to support medical necessity for review.
                </p>
            @else
                <div class="space-y-2">
                    @foreach($medicalNecessityFactors as $factor)
                        <p class="rounded-xl bg-white border border-rose-100 px-4 py-2 text-sm font-bold text-slate-700">
                            ⚠ {{ $factor }}
                        </p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
    <div class="mt-5">
        @if(!empty($documentationGaps))
            <ul class="space-y-2">
                @foreach($documentationGaps as $gap)
                    <li class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm font-bold text-amber-900">
                        ⚠ {{ $gap }}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-bold text-emerald-800">
                ✅ Documentation looks strong for coding review.
            </div>
        @endif
    </div>
</div>
     <div class="bg-white rounded-3xl shadow border border-cyan-200 p-6">
    <p class="text-xs uppercase font-bold text-cyan-700">AI Documentation Assistant</p>
    <h2 class="mt-1 text-xl font-black text-slate-900">Suggested Documentation Fixes</h2>
    <p class="mt-1 text-sm text-slate-600">
        Draft suggestions for provider review. Provider must verify before signing.
    </p>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-cyan-50 border border-cyan-100 p-4">
            <p class="text-xs uppercase font-bold text-cyan-700">Suggested Chief Complaint</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">
                {{ !empty($note->chief_complaint)
                    ? $note->chief_complaint
                    : 'Follow-up evaluation and care-plan review.' }}
            </p>
        </div>

        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
            <p class="text-xs uppercase font-bold text-indigo-700">Suggested Subjective</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">
                {{ !empty($note->subjective)
                    ? $note->subjective
                    : 'Patient/caregiver report should document current symptoms, medication tolerance, functional changes, and any new concerns since the last visit.' }}
            </p>
        </div>

        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
            <p class="text-xs uppercase font-bold text-emerald-700">Suggested Objective</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">
                {{ !empty($note->objective)
                    ? $note->objective
                    : 'Review recent vital signs, oxygen saturation, weight, care logs, eMAR activity, mobility status, and clinical measurements.' }}
            </p>
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
