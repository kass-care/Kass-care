@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="max-w-7xl mx-auto mb-4">
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Provider Calendar</h1>
                <p class="text-slate-600 mt-1">Scheduled visits overview</p>
            </div>

            <a href="{{ route('provider.dashboard') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Date</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Time</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Facility</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Client</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Caregiver</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Activity</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $visit)
                        @php
                            $status = strtolower($visit->status ?? 'scheduled');
                            $dateValue = $visit->visit_date ?? $visit->scheduled_at ?? null;

                            $formattedDate = $dateValue
                                ? \Carbon\Carbon::parse($dateValue)->format('M d, Y')
                                : 'N/A';

                            $formattedTime = $visit->visit_time
                                ? \Carbon\Carbon::parse($visit->visit_time)->format('h:i A')
                                : 'N/A';

                            $facilityName = 'No Facility';

                            if (isset($visit->facility) && $visit->facility) {
                                $facilityName = $visit->facility->name;
                            } elseif (isset($visit->client) && isset($visit->client->facility) && $visit->client->facility) {
                                $facilityName = $visit->client->facility->name;
                            }
                        @endphp

                        <tr class="border-t align-top">
                            <td class="px-6 py-4">{{ $formattedDate }}</td>
                            <td class="px-6 py-4">{{ $formattedTime }}</td>
                            <td class="px-6 py-4">{{ $facilityName }}</td>
                            <td class="px-6 py-4">{{ $visit->client->name ?? 'No Client' }}</td>
                            <td class="px-6 py-4">{{ $visit->caregiver->name ?? 'No Caregiver' }}</td>
                            <td class="px-6 py-4">{{ $visit->activity ?? 'N/A' }}</td>

                            <td class="px-6 py-4">
                                @if($status === 'completed')
                                    <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">Completed</span>
                                @elseif($status === 'missed')
                                    <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-700">Missed</span>
                                @elseif($status === 'in_progress')
                                    <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-700">In Progress</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">Scheduled</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('care-logs.index') }}"
                                       class="inline-block text-center bg-indigo-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-indigo-700">
                                        Care Log
                                    </a>

                                    <a href="{{ route('visits.index') }}"
                                       class="inline-block text-center bg-slate-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-slate-700">
                                        Visit Details
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                No visits found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
