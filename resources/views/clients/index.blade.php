@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8 px-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Global Patient Command Center
            </h1>

            <p class="text-gray-500 text-sm mt-1">
                Search, manage, and monitor patient compliance across facilities
            </p>
        </div>

        <a href="{{ route('admin.clients.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
            + Add Client
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6">
        <div class="flex gap-3">
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search patients by name, email, phone..."
                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
            >

            <button
                class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition"
            >
                Search
            </button>
        </div>
    </form>

    {{-- Compliance Summary --}}
    @php
        $completedCount = $clients->where('compliance_status', 'completed')->count();
        $dueSoonCount = $clients->where('compliance_status', 'due_soon')->count();
        $overdueCount = $clients->where('compliance_status', 'overdue')->count();
        $noVisitsCount = $clients->where('compliance_status', 'no_visits')->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
            <p class="text-xs uppercase tracking-wide text-emerald-700 font-semibold">Completed</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $completedCount }}</p>
            <p class="text-sm text-emerald-700/80 mt-1">Seen recently</p>
        </div>

        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
            <p class="text-xs uppercase tracking-wide text-amber-700 font-semibold">Due Soon</p>
            <p class="mt-2 text-3xl font-bold text-amber-700">{{ $dueSoonCount }}</p>
            <p class="text-sm text-amber-700/80 mt-1">Approaching due date</p>
        </div>

        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4">
            <p class="text-xs uppercase tracking-wide text-rose-700 font-semibold">Overdue</p>
            <p class="mt-2 text-3xl font-bold text-rose-700">{{ $overdueCount }}</p>
            <p class="text-sm text-rose-700/80 mt-1">Needs urgent follow-up</p>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
            <p class="text-xs uppercase tracking-wide text-slate-700 font-semibold">No Visits</p>
            <p class="mt-2 text-3xl font-bold text-slate-700">{{ $noVisitsCount }}</p>
            <p class="text-sm text-slate-700/80 mt-1">No history recorded</p>
        </div>
    </div>

    {{-- Patient Table --}}
    <div class="bg-white shadow rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 text-gray-600 text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Facility</th>
                        <th class="px-4 py-3 text-left">Last Visit</th>
                        <th class="px-4 py-3 text-left">Compliance</th>
                        <th class="px-4 py-3 text-left">Days</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">
                                {{ $client->id }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $client->name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->email ?? '-' }}</div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $client->facility->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ optional($client->latestVisit?->visit_date)->format('M d, Y') ?? 'No visit yet' }}
                            </td>

                            <td class="px-4 py-3">
                                @if($client->compliance_status === 'completed')
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        Completed
                                    </span>
                                @elseif($client->compliance_status === 'due_soon')
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700">
                                        Due Soon
                                    </span>
                                @elseif($client->compliance_status === 'overdue')
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-rose-100 text-rose-700">
                                        Overdue
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
                                        No Visits
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $client->compliance_days !== null ? $client->compliance_days . ' days' : '-' }}
                            </td>

                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('admin.clients.show', $client->id) }}"
                                   class="text-indigo-600 hover:underline">
                                    View
                                </a>

                                <a href="{{ route('admin.clients.edit', $client->id) }}"
                                   class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.clients.destroy', $client->id) }}"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete this client?')"
                                        class="text-red-600 hover:underline"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400">
                                No clients found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $clients->links() }}
    </div>

</div>

@endsection
