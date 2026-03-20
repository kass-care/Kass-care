@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">

        <div class="bg-white rounded-2xl shadow p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Visit Details</h1>
                    <p class="text-slate-500 mt-1">Clinical visit overview and linked provider notes.</p>
                </div>

                <a href="{{ route('provider.calendar') }}"
                   class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">
                    Back to Calendar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-slate-500">Client</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $visit->client->name ?? 'No Client' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Caregiver</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $visit->caregiver->name ?? 'No Caregiver' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Visit Date</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $visit->visit_date ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Visit Time</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $visit->visit_time ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Activity</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $visit->activity ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="text-lg font-semibold text-slate-900">{{ ucfirst($visit->status ?? 'scheduled') }}</p>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('provider-notes.create', ['visit_id' => $visit->id]) }}"
                   class="bg-emerald-600 text-white px-5 py-3 rounded-lg hover:bg-emerald-700">
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

            @forelse($providerNotes as $note)
                <div class="border border-slate-200 rounded-xl p-6 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="font-semibold text-slate-900">
                                Note #{{ $note->id }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ $note->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>

                        <div>
                            @if($note->signed_at)
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
                            <p class="text-slate-600">{{ $note->subjective ?: '—' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Objective</h3>
                            <p class="text-slate-600">{{ $note->objective ?: '—' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Assessment</h3>
                            <p class="text-slate-600">{{ $note->assessment ?: '—' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Plan</h3>
                            <p class="text-slate-600">{{ $note->plan ?: '—' }}</p>
                        </div>

                        <div>
                            <h3 class="font-semibold text-slate-800">Follow Up</h3>
                            <p class="text-slate-600">{{ $note->follow_up ?: '—' }}</p>
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
