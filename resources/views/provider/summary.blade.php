@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-3xl bg-gradient-to-r from-indigo-700 to-blue-600 text-white p-8 shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-indigo-100 font-semibold">KASS CARE</p>
                <h1 class="mt-3 text-4xl font-bold">Provider Patient Summary</h1>
                <p class="mt-3 text-indigo-100 max-w-3xl">
                    Real-time provider overview of patients, visits, charting, provider notes, and clinical workflow activity.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 min-w-[260px]">
                <a href="{{ route('provider.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                    Back to Dashboard
                </a>

                <a href="{{ route('provider.alerts') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 transition">
                    Open Alerts
                </a>

                <a href="{{ route('provider.calendar') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                    Open Schedule
                </a>

                <a href="{{ route('provider.compliance') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                    Compliance
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-8">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Total Visits</p>
            <p class="mt-4 text-4xl font-bold text-gray-900">{{ $totalVisits }}</p>
            <p class="mt-2 text-sm text-gray-500">All provider-visible visits</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Scheduled</p>
            <p class="mt-4 text-4xl font-bold text-blue-700">{{ $scheduledVisits }}</p>
            <p class="mt-2 text-sm text-gray-500">Upcoming planned care visits</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Completed</p>
            <p class="mt-4 text-4xl font-bold text-green-700">{{ $completedVisits }}</p>
            <p class="mt-2 text-sm text-gray-500">Finished documented visits</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Missed</p>
            <p class="mt-4 text-4xl font-bold text-red-700">{{ $missedVisits }}</p>
            <p class="mt-2 text-sm text-gray-500">Visits requiring follow-up</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 mt-5">
        <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-indigo-500 font-semibold">In Progress</p>
            <p class="mt-3 text-3xl font-bold text-indigo-700">{{ $inProgressVisits }}</p>
        </div>

        <div class="rounded-2xl bg-sky-50 border border-sky-100 p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-sky-500 font-semibold">Clients</p>
            <p class="mt-3 text-3xl font-bold text-sky-700">{{ $clientsCount }}</p>
        </div>

        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-500 font-semibold">Caregivers</p>
            <p class="mt-3 text-3xl font-bold text-emerald-700">{{ $caregiversCount }}</p>
        </div>

        <div class="rounded-2xl bg-amber-50 border border-amber-100 p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-amber-600 font-semibold">Care Logs</p>
            <p class="mt-3 text-3xl font-bold text-amber-700">{{ $careLogsCount }}</p>
        </div>

        <div class="rounded-2xl bg-violet-50 border border-violet-100 p-5">
            <p class="text-xs uppercase tracking-[0.25em] text-violet-600 font-semibold">Provider Notes</p>
            <p class="mt-3 text-3xl font-bold text-violet-700">{{ $notesCount }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-3xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Recent Patient Activity</h2>
                <p class="text-sm text-gray-500 mt-1">Latest provider-visible visit records and documentation flow.</p>
            </div>
        </div>

        @if($recentVisits->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
                            <th class="px-6 py-4 font-semibold">Client</th>
                            <th class="px-6 py-4 font-semibold">Caregiver</th>
                            <th class="px-6 py-4 font-semibold">Visit Date</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Care Logs</th>
                            <th class="px-6 py-4 font-semibold">Provider Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentVisits as $visit)
                            @php
                                $status = strtolower((string) ($visit->status ?? 'pending'));

                                $statusClasses = match($status) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'missed' => 'bg-red-100 text-red-700',
                                    'in_progress', 'in progress' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $visit->client->full_name ?? $visit->client->name ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $visit->caregiver->name ?? 'Unassigned' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $visit->careLogs->count() }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $visit->providerNote ? 'Yes' : 'No' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                No provider summary data available yet.
            </div>
        @endif
    </div>
</div>
@endsection
