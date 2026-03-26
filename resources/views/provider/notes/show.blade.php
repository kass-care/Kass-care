@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">View Clinical Note</h1>
            <p class="text-gray-600">Provider note details for this visit.</p>
        </div>

        <a href="{{ route('provider.notes.index') }}"
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
            Back to Notes
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-4">
        <p><strong>Visit ID:</strong> {{ $providerNote->visit->id ?? 'N/A' }}</p>
        <p><strong>Client:</strong> {{ $providerNote->visit->client->name ?? 'N/A' }}</p>
        <p><strong>Caregiver:</strong> {{ $providerNote->visit->caregiver->name ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ $providerNote->visit->visit_date ?? 'N/A' }}</p>

        <hr>

        <div>
            <h2 class="text-lg font-semibold mb-2">Clinical Note</h2>
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 whitespace-pre-line">
                {{ $providerNote->note }}
            </div>
        </div>
    </div>
</div>
@endsection
