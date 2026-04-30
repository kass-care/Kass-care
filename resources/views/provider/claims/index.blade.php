@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow p-8 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold">KASS CARE</p>
                    <h1 class="text-3xl font-black mt-2">Claims Drafts</h1>
                    <p class="text-indigo-100 mt-2">Generated insurance claim drafts from provider notes.</p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800">
                    Back to Notes
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow border border-slate-200 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Claim #</th>
                        <th class="px-6 py-4 text-left">Client</th>
                        <th class="px-6 py-4 text-left">Visit</th>
                        <th class="px-6 py-4 text-left">ICD</th>
                        <th class="px-6 py-4 text-left">CPT</th>
                        <th class="px-6 py-4 text-left">POS</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Amount</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-bold">
    <a href="{{ route('provider.claims.show', $claim->id) }}"
       class="text-indigo-600 hover:underline">
        {{ $claim->claim_number }}
    </a>
</td>
                            <td class="px-6 py-4">{{ $claim->client?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">#{{ $claim->visit_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ implode(', ', $claim->icd_codes ?? []) }}</td>
                            <td class="px-6 py-4">{{ $claim->cpt_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $claim->pos_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-800">
                                    {{ strtoupper($claim->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format((float) $claim->estimated_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-500">
                                No claim drafts yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
