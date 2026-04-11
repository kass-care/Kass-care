@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">

        <div class="bg-white rounded-2xl shadow p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Visit Details</h1>
                    <p class="text-slate-500 mt-1">
                        Clinical visit overview and linked provider notes.
                    </p>
                </div>

                <a href="{{ route('caregiver.visits') }}"
                   class="inline-flex items-center justify-center bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                    Back to Caregiver Visits
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-slate-500">Client</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ optional($visit->client)->name ?? 'No Client' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Caregiver</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ optional($visit->caregiver)->name ?? 'No Caregiver' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Facility</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ optional($visit->facility)->name ?? 'No Facility' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Provider</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ optional($visit->provider)->name ?? 'No Provider' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Visit Date</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Visit Time</p>
                    <p class="text-lg font-semibold text-slate-900">
                        @if($visit->start_time || $visit->end_time)
                            {{ $visit->start_time ? \Carbon\Carbon::parse($visit->start_time)->format('h:i A') : '--:--' }}
                            -
                            {{ $visit->end_time ? \Carbon\Carbon::parse($visit->end_time)->format('h:i A') : '--:--' }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Activity</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $visit->activity ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ ucfirst($visit->status ?? 'scheduled') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Check In</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $visit->check_in_time ? \Carbon\Carbon::parse($visit->check_in_time)->format('M d, Y h:i A') : 'Not checked in' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Check Out</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $visit->check_out_time ? \Carbon\Carbon::parse($visit->check_out_time)->format('M d, Y h:i A') : 'Not checked out' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Duration</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $visit->duration_minutes ? $visit->duration_minutes . ' mins' : 'Not available' }}
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('provider.notes.create', ['visit_id' => $visit->id]) }}"
                   class="inline-flex items-center justify-center bg-emerald-600 text-white px-5 py-3 rounded-lg hover:bg-emerald-700 transition">
                    + Add Clinical Note
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Clinical Notes</h2>
                    <p class="text-slate-500">Provider notes linked to this visit.</p>
                </div>
            </div>

            @forelse(($providerNotes ?? collect()) as $note)
                <div class="border border-slate-200 rounded-xl p-6 mb-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <p class="font-semibold text-slate-900">
                                Note #{{ $note->id }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ $note->created_at ? $note->created_at->format('M d, Y h:i A') : 'No date' }}
                            </p>
                        </div>

                        <div>
                            @if(!empty($note->signed_at))
                                <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                                    Signed
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-700">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-slate-800">Subjective</h3>
                            <p class="text-slate-600">{{ $note->subjective ?? '--' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Objective</h3>
                            <p class="text-slate-600">{{ $note->objective ?? '--' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Assessment</h3>
                            <p class="text-slate-600">{{ $note->assessment ?? '--' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Plan</h3>
                            <p class="text-slate-600">{{ $note->plan ?? '--' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Follow Up</h3>
                            <p class="text-slate-600">{{ $note->follow_up ?? '--' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-slate-500">
                    No clinical notes linked to this visit yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
