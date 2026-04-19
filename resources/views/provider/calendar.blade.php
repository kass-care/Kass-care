@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>
                    <h1 class="text-4xl font-extrabold text-white">
                        Provider Schedule
                    </h1>
                    <p class="text-indigo-100 mt-2 text-base">
                        Review scheduled visits and provider-facing visit planning.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('provider.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800 shadow hover:bg-gray-100">
                        ← Back to Dashboard
                    </a>

                    <a href="{{ route('provider.notes.index') }}"
                       class="inline-flex items-center rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-900 shadow hover:bg-yellow-300">
                        Open Notes
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Scheduled Visits</h2>
                    <p class="text-sm text-gray-600 mt-1">Provider-facing visit list.</p>
                </div>

                <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                    {{ $visits->count() }} Total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Visit ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Caregiver</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Activity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Visit Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($visits as $visit)
                            @php
                                $status = strtolower($visit->status ?? 'scheduled');

                                $badgeClass = match ($status) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'in progress', 'in_progress' => 'bg-blue-100 text-blue-700',
                                    'missed' => 'bg-red-100 text-red-700',
                                    'assigned' => 'bg-purple-100 text-purple-700',
                                    'scheduled' => 'bg-indigo-100 text-indigo-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">#{{ $visit->id }}</td>

                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $visit->client->full_name ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $visit->caregiver->name ?? 'Unassigned' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $visit->activity ?? 'No activity set' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $visit->visit_date ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600">
                                            View only
                                        </span>

                                        <a href="{{ route('provider.notes.create', ['visit_id' => $visit->id]) }}"
                                           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                            Provider Notes
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No scheduled visits found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
