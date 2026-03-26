@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold">Provider Visit Details</h1>
            <p class="text-gray-600">Clinical review of caregiver documentation, vitals, and provider notes.</p>
        </div>

        <a href="{{ route('provider.notes.index') }}"
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
            Back to Notes
        </a>
    </div>

    <div class="bg-white shadow rounded-2xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <p><strong>Visit ID:</strong> {{ $visit->id ?? 'N/A' }}</p>
                <p><strong>Client:</strong> {{ $visit->client->name ?? 'N/A' }}</p>
                <p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $visit->visit_date ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $visit->status ?? 'N/A' }}</p>
                <p><strong>Activity:</strong> {{ $visit->activity ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-2xl p-8 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Care Logs & Vitals</h2>

        @if(($visit->careLogs ?? collect())->count())
            @foreach($visit->careLogs as $log)
                <div class="border rounded-xl p-4 mb-4">
                    <div class="mb-3 space-y-1">
                        <p><strong>Log ID:</strong> {{ $log->id }}</p>
                        <p><strong>Notes:</strong> {{ $log->notes ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Vitals</h3>

                        @if(!empty($log->vitals) && is_array($log->vitals))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($log->vitals as $key => $value)
                                    <div class="bg-slate-50 border rounded-lg px-3 py-2">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value ?: 'N/A' }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No vitals recorded.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-gray-500">No care logs found for this visit.</div>
        @endif
    </div>

    <div class="bg-white shadow rounded-2xl p-8">
        <h2 class="text-2xl font-semibold mb-4">Provider Note</h2>

        @if($visit->providerNote)
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 whitespace-pre-line">
                {{ $visit->providerNote->note }}
            </div>
        @else
            <div class="text-gray-500">No provider note added yet.</div>
        @endif
    </div>

</div>
@endsection
