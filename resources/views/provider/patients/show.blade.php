@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Patient Summary</h1>
        <p class="text-sm text-gray-500 mt-1">Clinical overview for provider review.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $patient->name }}</h2>
                <p class="text-gray-600 mt-2"><span class="font-semibold">Phone:</span> {{ $patient->phone ?: 'N/A' }}</p>
                <p class="text-gray-600"><span class="font-semibold">Status:</span> {{ $patient->status ?: 'N/A' }}</p>
            </div>

            <div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Diagnoses</h3>

            @forelse($patient->diagnoses as $diagnosis)
                <div class="py-2 border-b border-gray-100 text-gray-700">
                    {{ $diagnosis->name ?? $diagnosis->diagnosis ?? 'Diagnosis' }}
                </div>
            @empty
                <p class="text-sm text-gray-400">No diagnoses recorded yet.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Medications</h3>

            @forelse($patient->medications as $medication)
                <div class="py-2 border-b border-gray-100">
                    <div class="font-medium text-gray-800">
                        {{ $medication->name ?? $medication->medication_name ?? 'Medication' }}
                    </div>
                    @if(!empty($medication->dose))
                        <div class="text-sm text-gray-500">Dose: {{ $medication->dose }}</div>
                    @endif
                    @if(!empty($medication->frequency))
                        <div class="text-sm text-gray-500">Frequency: {{ $medication->frequency }}</div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400">No medications recorded yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Visits</h3>

        @forelse($patient->visits->sortByDesc('visit_date')->take(5) as $visit)
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <div class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ ucfirst($visit->status ?? 'scheduled') }}
                    </div>
                </div>

                <div class="text-sm text-gray-400">
                    Visit ID: {{ $visit->id }}
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">No visits found for this patient.</p>
        @endforelse
    </div>
</div>
@endsection
