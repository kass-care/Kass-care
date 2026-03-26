@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-300 bg-green-100 px-4 py-3 text-green-900 font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- HERO -->
        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>

                    <h1 class="text-4xl font-extrabold text-white">
                        Caregiver Dashboard
                    </h1>

                    <p class="text-indigo-100 mt-2 text-base">
                        Welcome back, {{ auth()->user()->name ?? 'Caregiver User' }}
                    </p>

                    <p class="text-indigo-200 mt-1 text-sm">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800 shadow hover:bg-gray-100">
                        ← Back
                    </a>

                    <a href="{{ route('caregiver.care-logs.index') }}"
                       class="inline-flex items-center rounded-xl bg-yellow-400 px-5 py-3 text-sm font-bold text-slate-900 shadow hover:bg-yellow-300">
                        Open Care Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border-2 border-indigo-200 rounded-2xl shadow p-6">
                <p class="text-xs uppercase tracking-wide text-gray-500 font-bold">Today's Visits</p>
                <h2 class="text-4xl font-extrabold text-indigo-800 mt-3">
                    {{ $todayVisitsCount ?? 0 }}
                </h2>
                <p class="text-sm text-gray-600 mt-2">Visits scheduled for today</p>
            </div>

            <div class="bg-white border-2 border-indigo-200 rounded-2xl shadow p-6">
                <p class="text-xs uppercase tracking-wide text-gray-500 font-bold">Assigned Clients</p>
                <h2 class="text-4xl font-extrabold text-indigo-800 mt-3">
                    {{ $assignedClients->count() ?? 0 }}
                </h2>
                <p class="text-sm text-gray-600 mt-2">Clients linked to your visits</p>
            </div>

            <div class="bg-white border-2 border-indigo-200 rounded-2xl shadow p-6">
                <p class="text-xs uppercase tracking-wide text-gray-500 font-bold">Completed Visits</p>
                <h2 class="text-4xl font-extrabold text-indigo-800 mt-3">
                    {{ $completedVisitsCount ?? 0 }}
                </h2>
                <p class="text-sm text-gray-600 mt-2">Successfully completed visit records</p>
            </div>
        </div>

        <!-- ACTIVE VISIT -->
        @if($activeVisit)
            <div class="bg-green-700 rounded-2xl shadow-xl p-6 mb-8 text-white border border-green-800">
                <p class="text-xs uppercase tracking-widest text-green-200 font-bold mb-2">
                    Active Visit
                </p>

                <h2 class="text-2xl font-extrabold">
                    {{ $activeVisit->client->name ?? 'N/A' }}
                </h2>

                <p class="mt-2 text-green-100">
                    Status: {{ ucfirst(str_replace('_', ' ', $activeVisit->status ?? 'in progress')) }}
                </p>

                <p class="text-sm text-green-200 mt-1">
                    Checked in: {{ $activeVisit->check_in_time ?? 'N/A' }}
                </p>

                <div class="flex flex-wrap gap-3 mt-5">
                    <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $activeVisit->id]) }}"
                       class="rounded-xl bg-white px-5 py-3 text-sm font-bold text-green-800 shadow hover:bg-green-50">
                        Open Care Log
                    </a>

                    <a href="{{ route('caregiver.checkout', $activeVisit->id) }}"
                       class="rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-red-700">
                        Check Out
                    </a>
                </div>
            </div>
        @endif

        <!-- TODAY'S SCHEDULE -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Today's Schedule</h3>
                    <p class="text-sm text-gray-600">Your visits for today</p>
                </div>

                <span class="text-sm font-bold text-indigo-700">
                    {{ $todayVisits->count() }} visit(s)
                </span>
            </div>

            <div class="p-6 space-y-4">
                @forelse($todayVisits as $visit)
                    <div class="rounded-2xl border-2 border-gray-200 p-5 bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">
                                    {{ $visit->client->name ?? 'N/A' }}
                                </h4>

                                <p class="text-sm text-gray-600 mt-1">
                                    Status: {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'N/A')) }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if(($visit->status ?? '') === 'scheduled')
                                    <a href="{{ route('caregiver.checkin', $visit->id) }}"
                                       class="rounded-lg bg-green-600 text-white px-4 py-2 text-sm font-bold hover:bg-green-700">
                                        Check In
                                    </a>
                                @elseif(($visit->status ?? '') === 'in_progress')
                                    <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $visit->id]) }}"
                                       class="rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-bold hover:bg-indigo-700">
                                        Care Log
                                    </a>

                                    <a href="{{ route('caregiver.checkout', $visit->id) }}"
                                       class="rounded-lg bg-red-600 text-white px-4 py-2 text-sm font-bold hover:bg-red-700">
                                        Check Out
                                    </a>
                                @elseif(($visit->status ?? '') === 'completed')
                                    <span class="rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm font-bold border border-green-200">
                                        Done
                                    </span>
                                @else
                                    <span class="rounded-lg bg-gray-100 text-gray-800 px-4 py-2 text-sm font-bold border border-gray-200">
                                        {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'N/A')) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 p-10 text-center text-gray-600 bg-gray-50">
                        No visits today.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ASSIGNED CLIENTS -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-5">
                <h3 class="text-xl font-bold text-gray-900">Assigned Clients</h3>
                <p class="text-sm text-gray-600">Clients connected to your visit history</p>
            </div>

            <div class="p-6 space-y-4">
                @forelse($assignedClients as $client)
                    <div class="rounded-2xl border-2 border-gray-200 p-5 bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $client->name }}</h4>

                                <p class="text-sm text-gray-700 mt-1">
                                    Latest status:
                                    <span class="font-semibold">
                                        {{ ucfirst(str_replace('_', ' ', $client->latest_status ?? 'N/A')) }}
                                    </span>
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Last activity:
                                    {{ !empty($client->latest_visit_date) ? \Carbon\Carbon::parse($client->latest_visit_date)->format('M d, Y h:i A') : 'N/A' }}
                                </p>
                            </div>

                            @if(!empty($client->visit_id))
                                <a href="{{ route('caregiver.care-logs.create', ['visit_id' => $client->visit_id]) }}"
                                   class="rounded-lg bg-indigo-100 text-indigo-800 px-4 py-2 text-sm font-bold border border-indigo-200 hover:bg-indigo-200">
                                    Open Visit
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 p-10 text-center text-gray-600 bg-gray-50">
                        No assigned clients found yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RECENT HISTORY -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Recent Visit History</h3>
                    <p class="text-sm text-gray-600">Latest activity from your visit records</p>
                </div>

                <a href="{{ route('caregiver.care-logs.index') }}"
                   class="text-sm font-bold text-indigo-700 hover:text-indigo-900">
                    View Care Logs →
                </a>
            </div>

            <div class="p-6 space-y-4">
                @forelse($visits->take(8) as $visit)
                    <div class="rounded-2xl border-2 border-gray-200 p-5 bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">
                                    {{ $visit->client->name ?? 'N/A' }}
                                </h4>

                                <p class="text-sm text-gray-700 mt-1">
                                    Status: {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'N/A')) }}
                                </p>
                            </div>

                            <div class="text-sm font-medium text-gray-500">
                                {{ \Carbon\Carbon::parse($visit->updated_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 p-10 text-center text-gray-600 bg-gray-50">
                        No visit history yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
