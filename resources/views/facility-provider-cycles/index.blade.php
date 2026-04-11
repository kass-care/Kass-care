@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Facility Provider Cycles</h1>
            <p class="text-sm text-gray-500 mt-1">
                Track 60-day provider compliance cycles across facilities.
            </p>
        </div>

        <a href="{{ route('facility-provider-cycles.create') }}"
           class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
            New Cycle
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500">Total Cycles</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $cycles->count() }}</div>
        </div>

        <div class="rounded-2xl border border-green-100 bg-green-50 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Current</div>
            <div class="mt-2 text-3xl font-bold text-green-700">{{ $currentCount ?? 0 }}</div>
        </div>

        <div class="rounded-2xl border border-yellow-100 bg-yellow-50 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Due Soon</div>
            <div class="mt-2 text-3xl font-bold text-yellow-700">{{ $dueSoonCount ?? 0 }}</div>
        </div>

        <div class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Due / Overdue</div>
            <div class="mt-2 text-3xl font-bold text-red-700">{{ ($dueCount ?? 0) + ($overdueCount ?? 0) }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="px-4 py-3 font-semibold">Facility</th>
                        <th class="px-4 py-3 font-semibold">Provider</th>
                        <th class="px-4 py-3 font-semibold">Cycle Start</th>
                        <th class="px-4 py-3 font-semibold">Cycle End</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cycles as $cycle)
                        @php
                            $status = $cycle->computed_status ?? 'current';

                            $badgeClasses = match($status) {
                                'current' => 'bg-green-100 text-green-700 border border-green-200',
                                'due_soon' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                'due' => 'bg-orange-100 text-orange-700 border border-orange-200',
                                'overdue' => 'bg-red-100 text-red-700 border border-red-200',
                                default => 'bg-gray-100 text-gray-700 border border-gray-200',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">{{ $cycle->facility->name ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $cycle->provider->name ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $cycle->cycle_start ?? 'N/A' }}</td>
                            <td class="px-4 py-4">{{ $cycle->cycle_end ?? 'N/A' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                                No facility cycles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
