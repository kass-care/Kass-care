@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-4">

<form method="POST" action="{{ route('provider.claims.submit', $claim->id) }}">
    @csrf
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
        🚀 Submit Claim
    </button>
</form>

        <div class="bg-indigo-800 text-white rounded-3xl p-6 mb-6">
            <h1 class="text-2xl font-black">Claim Detail</h1>
            <p class="text-indigo-200">Claim #{{ $claim->claim_number }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 space-y-4">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Client</p>
                    <p class="font-bold">{{ $claim->client?->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Visit</p>
                    <p class="font-bold">#{{ $claim->visit_id }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="font-bold uppercase">{{ $claim->status }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Amount</p>
                    <p class="font-bold">${{ number_format($claim->estimated_amount, 2) }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-slate-500">ICD Codes</p>
                <p class="font-semibold">
                    {{ implode(', ', $claim->icd_codes ?? []) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">CPT Code</p>
                <p class="font-semibold">{{ $claim->cpt_code }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500">POS</p>
                <p class="font-semibold">{{ $claim->pos_code }}</p>
            </div>

            <div class="pt-4">
                <a href="{{ route('provider.claims.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Back to Claims
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
