@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Care Log Details</h1>
            <p class="text-sm text-slate-500 mt-2">Saved caregiver ADL charting, vitals, and care notes.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Visit ID</p>
                    <p class="mt-1 text-slate-900 font-semibold">#{{ $careLog->visit->id ?? 'N/A' }}</p>
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
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">ADL Charting</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(($careLog->adls ?? []) as $adl => $value)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm text-slate-500 capitalize">{{ $adl }}</p>
                            <p class="text-base font-semibold text-slate-900 mt-1">{{ $value ?: 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No ADL charting recorded.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Vitals</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse(($careLog->vitals ?? []) as $key => $value)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                            <p class="text-base font-semibold text-slate-900 mt-1">{{ $value ?: 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No vitals recorded.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">Care Notes</p>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 whitespace-pre-line text-slate-800">
                    {{ $careLog->notes ?? 'No notes added.' }}
                </div>
            </div>

            <div>
                <a href="{{ route('caregiver.care-logs.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Back to Care Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
