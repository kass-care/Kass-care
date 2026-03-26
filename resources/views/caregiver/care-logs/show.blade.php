@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <p class="text-xs uppercase tracking-[0.2em] text-indigo-600 font-semibold">KASS CARE</p>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">Care Log Details</h1>
            <p class="text-slate-600 mt-2">Saved caregiver ADL charting, vitals, and care notes.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Visit ID</p>
                    <p class="mt-1 text-slate-900 font-semibold">#{{ $careLog->visit_id ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Client</p>
                    <p class="mt-1 text-slate-900 font-semibold">{{ $careLog->visit->client->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Caregiver</p>
                    <p class="mt-1 text-slate-900 font-semibold">{{ $careLog->visit->caregiver->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Created</p>
                    <p class="mt-1 text-slate-900 font-semibold">{{ $careLog->created_at?->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-4">ADL Charting</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(($careLog->adls ?? []) as $adl => $value)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $adl) }}</p>
                            <p class="text-base font-semibold text-slate-900 mt-1">{{ $value ?: 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No ADL charting recorded.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-4">Vitals</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(($careLog->vitals ?? []) as $key => $value)
                        <div class="rounded-2xl border border-slate-200 bg-indigo-50 p-4">
                            <p class="text-sm text-indigo-700 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                            <p class="text-base font-semibold text-slate-900 mt-1">{{ $value ?: 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No vitals recorded.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-4">Care Notes</h2>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 whitespace-pre-line text-slate-800">
                    {{ $careLog->notes ?? 'No notes added.' }}
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('caregiver.care-logs.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Back to Care Logs
                </a>

                <a href="{{ route('caregiver.dashboard') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
