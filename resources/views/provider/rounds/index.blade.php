@extends('layouts.app')

<style>
@keyframes pulseGlow {
    0% { box-shadow: 0 0 0 rgba(239,68,68,0.6); }
    50% { box-shadow: 0 0 20px rgba(239,68,68,0.9); }
    100% { box-shadow: 0 0 0 rgba(239,68,68,0.6); }
}

.rounds-due {
    animation: pulseGlow 2s infinite;
    border: 2px solid #ef4444;
}
</style>

@section('content')

<div class="max-w-7xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Today's Facility Rounds</h1>
            <p class="text-gray-600 mt-1">
                See which patients need attention today, grouped by facility and sorted by priority.
            </p>
        </div>

        <a href="{{ route('provider.dashboard') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold">
            Back to Dashboard
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs uppercase text-gray-500">Facilities</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['facilities'] }}
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs uppercase text-gray-500">Patients</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['patients'] }}
            </div>
        </div>

        <div class="bg-red-50 rounded-2xl border border-red-100 shadow-sm p-5">
            <div class="text-xs uppercase text-red-600">High Priority</div>
            <div class="text-3xl font-bold text-red-700 mt-2">
                {{ $stats['high'] }}
            </div>
        </div>

        <div class="bg-yellow-50 rounded-2xl border border-yellow-100 shadow-sm p-5">
            <div class="text-xs uppercase text-yellow-700">Moderate Priority</div>
            <div class="text-3xl font-bold text-yellow-800 mt-2">
                {{ $stats['moderate'] }}
            </div>
        </div>

    </div>

    {{-- Facilities --}}
    @forelse($groupedRounds as $facilityName => $patients)

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-6">

            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $facilityName }}
                </h2>

                <span class="text-sm text-gray-500">
                    {{ $patients->count() }} patient(s)
                </span>
            </div>

            <div class="space-y-4">

                @foreach($patients as $item)

                    @php
                        $client = $item['client'];

                        $priorityClasses = match($item['priority']) {
                            'HIGH' => 'bg-red-100 text-red-700',
                            'MODERATE' => 'bg-yellow-100 text-yellow-700',
                            default => 'bg-green-100 text-green-700'
                        };
                    @endphp

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                        {{-- Top row --}}
                        <div class="flex justify-between items-start">

                            <div>

                                <div class="flex items-center gap-3 mb-2">

                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $client->name }}
                                    </h3>

                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $priorityClasses }}">
                                        {{ $item['priority'] }} PRIORITY
                                    </span>

                                </div>

                                {{-- Patient info --}}
                                <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">

                                    <span class="bg-white px-3 py-1 rounded-lg border">
                                        DOB:
                                        {{ $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->format('M d, Y') : 'N/A' }}
                                    </span>

                                    <span class="bg-white px-3 py-1 rounded-lg border">
                                        Age:
                                        {{ $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->age : '—' }}
                                    </span>

                                    <span class="bg-white px-3 py-1 rounded-lg border">
                                        Phone:
                                        {{ $client->phone ?? 'N/A' }}
                                    </span>

                                    <span class="bg-white px-3 py-1 rounded-lg border">
                                        Last Visit:
                                        {{ $item['last_visit'] ? \Carbon\Carbon::parse($item['last_visit']->visit_date)->format('M d, Y') : 'None' }}
                                    </span>

                                </div>

                                {{-- Alerts --}}
                                <div class="flex flex-wrap gap-2">

                                    @forelse($item['alerts'] as $alert)

                                        @php
                                            $alertColor = match($alert) {
                                                'High blood pressure' => 'bg-red-100 text-red-700',
                                                'Low oxygen' => 'bg-red-100 text-red-700',
                                                'Fever' => 'bg-orange-100 text-orange-700',
                                                'Abnormal pulse' => 'bg-purple-100 text-purple-700',
                                                'Visit overdue' => 'bg-red-100 text-red-700',
                                                'Visit due soon' => 'bg-yellow-100 text-yellow-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp

                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $alertColor }}">
                                            {{ $alert }}
                                        </span>

                                    @empty

                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Stable
                                        </span>

                                    @endforelse

                                </div>

                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2">

                                <a href="{{ route('provider.patients.workspace', $client->id) }}"
                                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700">
                                    Open Workspace
                                </a>

                                <a href="{{ route('provider.patients.workspace', $client->id) }}"
                                   class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
                                    Open Summary
                                </a>

                            </div>

                        </div>

                        {{-- Mark rounded --}}
                        @if($item['last_visit'])

                            <form action="{{ route('provider.rounds.markRounded', $item['last_visit']->id) }}"
                                  method="POST"
                                  class="mt-4">

                                @csrf

                                <button type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    Mark Rounded
                                </button>

                            </form>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>

    @empty

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10 text-center text-gray-500">
            No patients available for rounds yet.
        </div>

    @endforelse

</div>

@endsection
