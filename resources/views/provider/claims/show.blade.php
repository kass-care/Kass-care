@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4">

        <div class="mb-8 rounded-3xl bg-gradient-to-r from-indigo-900 via-blue-800 to-slate-900 p-8 text-white shadow">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-200 font-bold">KASS CARE CLAIM DETAIL</p>
                    <h1 class="mt-2 text-3xl font-black">{{ $claim->claim_number }}</h1>
                    <p class="mt-2 text-indigo-100">Clearinghouse-ready claim record.</p>
                </div>

                <a href="{{ route('provider.claims.index') }}"
                   class="rounded-2xl bg-white px-5 py-3 text-sm font-bold text-indigo-900">
                    Back to Claims
                </a>
            </div>
        </div>

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

        @php
            $badge = match($claim->status) {
                'draft' => 'bg-slate-100 text-slate-700',
                'submitted' => 'bg-amber-100 text-amber-800',
                'paid' => 'bg-emerald-100 text-emerald-800',
                'denied' => 'bg-rose-100 text-rose-800',
                default => 'bg-slate-100 text-slate-700',
            };
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-7 shadow">
                <div class="flex items-center justify-between border-b border-slate-200 pb-5">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Claim Summary</h2>
                        <p class="mt-1 text-sm text-slate-500">Client, visit, coding, and claim amount.</p>
                    </div>

                    <span class="rounded-full px-4 py-2 text-xs font-black uppercase {{ $badge }}">
                        {{ $claim->status }}
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Client</p>
                        <p class="mt-2 text-xl font-black text-slate-900">{{ $claim->client?->name ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Visit</p>
                        <p class="mt-2 text-xl font-black text-slate-900">#{{ $claim->visit_id ?? 'N/A' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Estimated Amount</p>
                        <p class="mt-2 text-xl font-black text-emerald-600">${{ number_format((float) $claim->estimated_amount, 2) }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Clearinghouse ID</p>
                        <p class="mt-2 text-sm font-black text-slate-900 break-all">{{ $claim->external_id ?? 'Not submitted yet' }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 p-5">
                    <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Coding</p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-slate-500">ICD Codes</p>
                            <p class="font-bold text-slate-900">{{ implode(', ', $claim->icd_codes ?? []) ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">CPT Code</p>
                            <p class="font-bold text-slate-900">{{ $claim->cpt_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">POS</p>
                            <p class="font-bold text-slate-900">{{ $claim->pos_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 p-5">
                    <p class="text-xs uppercase tracking-widest text-slate-400 font-bold">Billing Notes</p>
                    <p class="mt-3 text-sm leading-6 text-slate-700">{{ $claim->billing_notes ?? 'No billing notes available.' }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow">
                    <h3 class="text-xl font-black text-slate-900">Actions</h3>

                    <div class="mt-5 space-y-3">
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->plan === 'provider_pro')
                            @if($claim->status === 'draft')
                                <form method="POST" action="{{ route('provider.claims.submit', $claim->id) }}">
                                    @csrf
                                    <button class="w-full rounded-2xl bg-indigo-600 px-4 py-3 font-bold text-white hover:bg-indigo-700">
                                        🚀 Submit Claim
                                    </button>
                                </form>
                            @else
                                <div class="rounded-2xl bg-slate-100 px-4 py-3 text-center text-sm font-bold text-slate-600">
                                    Claim already processed
                                </div>
                            @endif
                        @else
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                Upgrade to <strong>Provider Pro</strong> to submit and manage revenue.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow">
                    <h3 class="text-xl font-black text-slate-900">Claim Timeline</h3>

                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full bg-slate-500"></div>
                            <div>
                                <p class="font-bold text-slate-900">Draft Created</p>
                                <p class="text-slate-500">{{ $claim->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full {{ $claim->submitted_at ? 'bg-indigo-600' : 'bg-slate-300' }}"></div>
                            <div>
                                <p class="font-bold text-slate-900">Submitted</p>
                                <p class="text-slate-500">{{ $claim->submitted_at?->format('M d, Y H:i') ?? 'Pending' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 h-3 w-3 rounded-full {{ $claim->paid_at ? 'bg-emerald-600' : ($claim->denied_at ? 'bg-rose-600' : 'bg-slate-300') }}"></div>
                            <div>
                                <p class="font-bold text-slate-900">Final Decision</p>
                                <p class="text-slate-500">
                                    @if($claim->paid_at)
                                        Paid {{ $claim->paid_at->format('M d, Y H:i') }}
                                    @elseif($claim->denied_at)
                                        Denied {{ $claim->denied_at->format('M d, Y H:i') }}
                                    @else
                                        Pending
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
