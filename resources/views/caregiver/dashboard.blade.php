@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="mb-8">
        <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500 rounded-2xl shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">Caregiver Dashboard</h1>
                    <p class="text-indigo-100 text-sm md:text-base">
                        Welcome back, {{ Auth::user()->name ?? 'Caregiver' }}. Manage your visits, check-ins, care logs, and vitals from one place.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 w-full md:w-auto">
                    <div class="bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-white/10">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Role</p>
                        <p class="text-lg font-semibold">Caregiver</p>
                    </div>
                    <div class="bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-white/10">
                        <p class="text-xs uppercase tracking-wide text-indigo-100">Visits Today</p>
                        <p class="text-lg font-semibold">{{ $todayVisits->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($activeVisit)
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-indigo-600 font-semibold mb-2">Current Visit</p>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $activeVisit->client->name ?? 'Client' }}
                        </h2>
                        <p class="text-gray-600 mt-2">
                            Activity: {{ $activeVisit->activity ?? 'No activity set' }}
                        </p>
                        <p class="text-gray-500 text-sm mt-1">
                            Status: {{ $activeVisit->status ?? 'Pending' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <a href="{{ route('caregiver.checkin', $activeVisit->id) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 font-semibold transition">
                            Check In
                        </a>

                        <a href="{{ route('caregiver.checkout', $activeVisit->id) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-5 py-3 font-semibold transition">
                            Check Out
                        </a>

                        <a href="{{ route('caregiver.carelog', $activeVisit->id) }}"
                           class="inline-flex items-center justify-center rounded-xl border border-indigo-200 hover:bg-indigo-50 text-indigo-700 px-5 py-3 font-semibold transition">
                            Care Log
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

        <a href="#assigned-visits"
           class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-indigo-100 transition duration-300 overflow-hidden">
            <div class="h-2 bg-indigo-600"></div>
            <div class="p-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-2">Assigned Visits</h2>
                <p class="text-gray-600 text-sm leading-6 mb-4">
                    View your daily assignments and stay on top of scheduled client visits.
                </p>

                <div class="inline-flex items-center text-indigo-700 font-semibold text-sm">
                    View visits
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ $activeVisit ? route('caregiver.checkin', $activeVisit->id) : route('calendar') }}"
           class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-indigo-100 transition duration-300 overflow-hidden">
            <div class="h-2 bg-indigo-500"></div>
            <div class="p-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-2">Check-In / Out</h2>
                <p class="text-gray-600 text-sm leading-6 mb-4">
                    Record arrival and departure times for visits and keep attendance accurate.
                </p>

                <div class="inline-flex items-center text-indigo-700 font-semibold text-sm">
                    {{ $activeVisit ? 'Open visit actions' : 'Open calendar' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ $activeVisit ? route('caregiver.carelog', $activeVisit->id) : route('calendar') }}"
           class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-indigo-100 transition duration-300 overflow-hidden">
            <div class="h-2 bg-indigo-400"></div>
            <div class="p-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M8 4h8a2 2 0 012 2v12a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-2">Care Logs</h2>
                <p class="text-gray-600 text-sm leading-6 mb-4">
                    Document ADLs, care activities, notes, and observations for every visit.
                </p>

                <div class="inline-flex items-center text-indigo-700 font-semibold text-sm">
                    {{ $activeVisit ? 'Open care log' : 'Open calendar' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ $activeVisit ? route('caregiver.carelog', $activeVisit->id) : route('calendar') }}"
           class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-indigo-100 transition duration-300 overflow-hidden">
            <div class="h-2 bg-indigo-300"></div>
            <div class="p-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 13h4l2 5 4-10 2 5h4" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-gray-900 mb-2">Vitals</h2>
                <p class="text-gray-600 text-sm leading-6 mb-4">
                    Record vitals and health observations as part of your visit documentation.
                </p>

                <div class="inline-flex items-center text-indigo-700 font-semibold text-sm">
                    {{ $activeVisit ? 'Open vitals form' : 'Open calendar' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-indigo-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                <span class="text-xs font-semibold text-indigo-700 bg-indigo-100 px-3 py-1 rounded-full">
                    Caregiver Tools
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ $activeVisit ? route('caregiver.checkin', $activeVisit->id) : route('calendar') }}"
                   class="rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 transition p-5">
                    <h4 class="font-bold text-indigo-900 mb-1">Start Visit Check-In</h4>
                    <p class="text-sm text-indigo-800">
                        {{ $activeVisit ? 'Open the current visit check-in page.' : 'No active visit found. Open calendar instead.' }}
                    </p>
                </a>

                <a href="{{ $activeVisit ? route('caregiver.checkout', $activeVisit->id) : route('calendar') }}"
                   class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 transition p-5">
                    <h4 class="font-bold text-indigo-900 mb-1">Complete Check-Out</h4>
                    <p class="text-sm text-gray-700">
                        {{ $activeVisit ? 'Finish the visit and record departure time.' : 'No active visit found. Open calendar instead.' }}
                    </p>
                </a>

                <a href="{{ $activeVisit ? route('caregiver.carelog', $activeVisit->id) : route('calendar') }}"
                   class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 transition p-5">
                    <h4 class="font-bold text-indigo-900 mb-1">Write Care Log</h4>
                    <p class="text-sm text-gray-700">
                        {{ $activeVisit ? 'Document care delivered during the visit.' : 'No active visit found. Open calendar instead.' }}
                    </p>
                </a>

                <a href="{{ route('calendar') }}"
                   class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 transition p-5">
                    <h4 class="font-bold text-indigo-900 mb-1">Open Calendar</h4>
                    <p class="text-sm text-gray-700">View all scheduled items on the main calendar.</p>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-5">Today Overview</h3>

            <div class="space-y-4">
                <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-4">
                    <p class="text-xs uppercase tracking-wide text-indigo-700 font-semibold mb-1">Visits</p>
                    <p class="text-2xl font-bold text-indigo-900">{{ $todayVisits->count() }}</p>
                    <p class="text-sm text-gray-600 mt-1">Assigned visits scheduled for today.</p>
                </div>

                <div class="rounded-xl bg-white border border-gray-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Documentation</p>
                    <p class="text-lg font-bold text-gray-900">Care Logs + Vitals</p>
                    <p class="text-sm text-gray-600 mt-1">Keep every visit fully documented.</p>
                </div>

                <div class="rounded-xl bg-white border border-gray-200 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Workflow</p>
                    <p class="text-lg font-bold text-gray-900">Check-In to Check-Out</p>
                    <p class="text-sm text-gray-600 mt-1">Simple flow for daily caregiving activities.</p>
                </div>
            </div>
        </div>

    </div>

    <div id="assigned-visits" class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Assigned Visits</h3>
                <p class="text-gray-600 text-sm mt-1">Real visit records connected to this caregiver dashboard.</p>
            </div>
            <span class="text-xs font-semibold text-indigo-700 bg-indigo-100 px-3 py-1 rounded-full">
                {{ $visits->count() }} Total
            </span>
        </div>

        @if($visits->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-indigo-100">
                            <th class="py-3 px-4 text-sm font-semibold text-gray-700">Client</th>
                            <th class="py-3 px-4 text-sm font-semibold text-gray-700">Activity</th>
                            <th class="py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visits as $visit)
                            <tr class="border-b border-gray-100 hover:bg-indigo-50 transition">
                                <td class="py-4 px-4 font-medium text-gray-900">
                                    {{ $visit->client->name ?? 'Client' }}
                                </td>
                                <td class="py-4 px-4 text-gray-700">
                                    {{ $visit->activity ?? 'No activity set' }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                        {{ $visit->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('caregiver.checkin', $visit->id) }}"
                                           class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                            Check In
                                        </a>

                                        <a href="{{ route('caregiver.checkout', $visit->id) }}"
                                           class="px-3 py-2 rounded-lg bg-indigo-100 text-indigo-800 text-sm font-semibold hover:bg-indigo-200 transition">
                                            Check Out
                                        </a>

                                        <a href="{{ route('caregiver.carelog', $visit->id) }}"
                                           class="px-3 py-2 rounded-lg border border-indigo-200 text-indigo-700 text-sm font-semibold hover:bg-indigo-50 transition">
                                            Care Log
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-indigo-200 bg-indigo-50 p-8 text-center">
                <h4 class="text-lg font-bold text-indigo-900 mb-2">No visits found</h4>
                <p class="text-sm text-indigo-700">
                    No caregiver visits are available yet for your assigned facility.
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
