@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Provider Notes</h1>
                <p class="text-slate-600 mt-1">Clinical documentation view for visits with care logs.</p>
            </div>

            <a href="{{ route('provider.dashboard') }}"
               class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-semibold text-slate-900">Documented Visits</h2>
                <p class="text-slate-500 mt-1">Only visits that already have caregiver charting.</p>
            </div>

            @if($visits->count())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Visit ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Caregiver</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr class="border-t border-slate-200">
                                    <td class="px-6 py-4 text-sm text-slate-700">#{{ $visit->id }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $visit->client->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $visit->caregiver->name ?? 'Unassigned' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
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
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('provider.notes.show', $visit->id) }}"
                                           class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                            Review Note
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-slate-600">
                    No documented visits yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
