@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-slate-900">Visit Care Log</h1>

            <a href="{{ route('provider.notes.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                ← Back to Provider Notes
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-6 border-b bg-slate-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700">
                    <div>
                        <p><span class="font-semibold text-slate-900">Visit ID:</span> {{ $visit->id }}</p>
                        <p><span class="font-semibold text-slate-900">Client:</span> {{ $visit->client->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p><span class="font-semibold text-slate-900">Caregiver:</span> {{ $visit->caregiver->name ?? 'Unassigned' }}</p>
                        <p>
                            <span class="font-semibold text-slate-900">Status:</span>
                            @php
                                $status = $visit->status ?? 'scheduled';
                                $statusLabel = ucwords(str_replace('_', ' ', $status));
                            @endphp

                            @if($status === 'completed')
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ $statusLabel }}
                                </span>
                            @elseif($status === 'in_progress')
                                <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $statusLabel }}
                                </span>
                            @elseif($status === 'missed')
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    {{ $statusLabel }}
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    {{ $statusLabel }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-8">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 mb-3">General Notes</h2>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-700">
                        {!! nl2br(e($careLog->notes ?? 'No notes yet.')) !!}
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-slate-900 mb-3">ADL Notes</h2>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-700">
                        @if(!empty($careLog?->adl_notes))
                            <ul class="space-y-2">
                                @foreach(explode(', ', $careLog->adl_notes) as $adl)
                                    <li class="border-b border-slate-200 pb-2 last:border-b-0 last:pb-0">
                                        {{ $adl }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No ADL notes yet.</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-slate-900 mb-3">Vitals</h2>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-700">
                        @if(!empty($careLog?->vitals))
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(explode(', ', $careLog->vitals) as $vital)
                                    <li class="rounded-lg bg-white border border-slate-200 px-4 py-3">
                                        {{ $vital }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No vitals yet.</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-slate-900 mb-3">Mood / Condition</h2>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-700">
                        {{ $careLog->mood ?? 'No mood/condition yet.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
