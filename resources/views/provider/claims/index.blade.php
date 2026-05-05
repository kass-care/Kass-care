@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- HEADER -->
        <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-blue-800 to-slate-900 p-8 text-white shadow mb-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-200 font-bold">
                        KASS CARE CLAIMS
                    </p>
                    <h1 class="mt-2 text-3xl font-black">Claims Command Center</h1>
                    <p class="mt-2 text-indigo-100">
                        Track claim drafts, clearinghouse IDs, payments, and denials.
                    </p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="rounded-2xl bg-white px-5 py-3 text-sm font-bold text-indigo-900">
                    Back to Notes
                </a>
            </div>
        </div>

        <!-- SUCCESS / ERROR -->
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- 🔥 REVENUE CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white rounded-2xl p-6 shadow border">
                <p class="text-sm text-slate-500">Total Revenue</p>
                <p class="text-2xl font-black text-emerald-600 mt-2">
                    ${{ number_format($totalPaid, 2) }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow border">
                <p class="text-sm text-slate-500">Pending Claims</p>
                <p class="text-2xl font-black text-yellow-600 mt-2">
                    ${{ number_format($totalPending, 2) }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow border">
                <p class="text-sm text-slate-500">Denied Claims</p>
                <p class="text-2xl font-black text-red-600 mt-2">
                    ${{ number_format($totalDenied, 2) }}
                </p>
            </div>

        </div>

        <!-- TABLE -->
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-600">
                    <tr>
                        <th class="px-5 py-4 text-left">Claim</th>
                        <th class="px-5 py-4 text-left">Client</th>
                        <th class="px-5 py-4 text-left">Codes</th>
                        <th class="px-5 py-4 text-left">External ID</th>
                        <th class="px-5 py-4 text-left">Status</th>
                        <th class="px-5 py-4 text-left">Amount</th>
                        <th class="px-5 py-4 text-left">Dates</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-slate-50">

                            <!-- CLAIM -->
                            <td class="px-5 py-4">
                                <a href="{{ route('provider.claims.show', $claim->id) }}"
                                   class="font-black text-indigo-700 hover:underline">
                                    {{ $claim->claim_number }}
                                </a>
                                <p class="mt-1 text-xs text-slate-500">
                                    Visit #{{ $claim->visit_id ?? 'N/A' }}
                                </p>
                            </td>

                            <!-- CLIENT -->
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $claim->client?->name ?? 'N/A' }}
                            </td>

                            <!-- CODES -->
                            <td class="px-5 py-4">
                                <p><span class="font-bold">ICD:</span> {{ implode(', ', $claim->icd_codes ?? []) }}</p>
                                <p><span class="font-bold">CPT:</span> {{ $claim->cpt_code ?? 'N/A' }}</p>
                                <p><span class="font-bold">POS:</span> {{ $claim->pos_code ?? 'N/A' }}</p>
                            </td>

                            <!-- EXTERNAL -->
                            <td class="px-5 py-4">
                                <span class="rounded-xl bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                    {{ $claim->external_id ?? 'Pending' }}
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td class="px-5 py-4">
                                @php
                                    $badge = match($claim->status) {
                                        'draft' => 'bg-slate-100 text-slate-700',
                                        'submitted' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-emerald-100 text-emerald-800',
                                        'denied' => 'bg-red-100 text-red-800',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="rounded-full px-3 py-1 text-xs font-black uppercase {{ $badge }}">
                                    {{ $claim->status }}
                                </span>
                            </td>

                            <!-- AMOUNT -->
                            <td class="px-5 py-4 font-black text-emerald-600 text-lg">
                                ${{ number_format((float) $claim->estimated_amount, 2) }}
                            </td>

                            <!-- DATES -->
                            <td class="px-5 py-4 text-xs text-slate-600">
                                <p>Submitted: {{ $claim->submitted_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                                <p>Paid: {{ $claim->paid_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                                <p>Denied: {{ $claim->denied_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                No claims generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
