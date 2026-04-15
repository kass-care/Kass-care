@extends('layouts.admin')

@section('page_title', 'Super Admin Command Center')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($selectedFacility)
        <section class="rounded-[28px] bg-gradient-to-r from-indigo-900 via-indigo-800 to-cyan-700 text-white shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-0">
                <div class="xl:col-span-2 p-8 lg:p-10">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-cyan-200 font-bold">Facility Context Active</p>
                    <h1 class="mt-3 text-3xl md:text-4xl font-black leading-tight">
                        {{ $selectedFacility->name }}
                    </h1>
                    <p class="mt-4 max-w-3xl text-sm md:text-base text-indigo-100 leading-7">
                        You are currently viewing platform data filtered to this facility. All dashboard totals below reflect the selected facility context.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.facilities.show', $selectedFacility) }}"
                           class="inline-flex items-center rounded-2xl bg-cyan-300 px-5 py-3 text-sm font-bold text-slate-900 hover:bg-cyan-200 transition">
                            View Facility Command Center
                        </a>

                        <a href="{{ route('admin.facilities.index') }}"
                           class="inline-flex items-center rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                            Change Facility
                        </a>

                        <form action="{{ route('facility.clear') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center rounded-2xl border border-red-300/30 bg-red-500/20 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500/30 transition">
                                Clear Facility Context
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t xl:border-t-0 xl:border-l border-white/10 bg-black/10 p-8">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-cyan-200 font-bold">Live Facility Snapshot</p>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                            <span class="text-sm text-indigo-100">Clients</span>
                            <span class="text-sm font-bold text-white">{{ $clientCount }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                            <span class="text-sm text-indigo-100">Caregivers</span>
                            <span class="text-sm font-bold text-white">{{ $caregiverCount }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                            <span class="text-sm text-indigo-100">Visits</span>
                            <span class="text-sm font-bold text-white">{{ $visitCount }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                            <span class="text-sm text-indigo-100">Alerts</span>
                            <span class="text-sm font-bold text-white">{{ $alertCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="rounded-[28px] bg-gradient-to-r from-slate-950 via-slate-900 to-cyan-950 text-white shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-0">
                <div class="xl:col-span-2 p-8 lg:p-10">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-cyan-300 font-bold">KASS CARE CONTROL LAYER</p>
                    <h1 class="mt-3 text-3xl md:text-4xl font-black leading-tight">
                        Super Admin Engineering Console
                    </h1>
                    <p class="mt-4 max-w-3xl text-sm md:text-base text-slate-300 leading-7">
                        Full command over facilities, workforce, clients, visits, alerts, tasks, backups, and platform operations. This dashboard is built to make you feel in control.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.facilities.index') }}"
                           class="inline-flex items-center rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 hover:bg-cyan-300 transition">
                            Open Facilities
                        </a>

                        @if(Route::has('admin.tasks.index'))
                            <a href="{{ route('admin.tasks.index') }}"
                               class="inline-flex items-center rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                                Open Tasks
                            </a>
                        @endif

                        @if(Route::has('admin.audit.index'))
                            <a href="{{ route('admin.audit.index') }}"
                               class="inline-flex items-center rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                                View Audit Logs
                            </a>
                        @endif
                    </div>
                </div>

                <div class="border-t xl:border-t-0 xl:border-l border-white/10 bg-white/5 backdrop-blur-sm p-8">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-cyan-300 font-bold">Live Status</p>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <span class="text-sm text-slate-300">Platform</span>
                            <span class="text-sm font-bold text-emerald-400">Online</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <span class="text-sm text-slate-300">Alerts</span>
                            <span class="text-sm font-bold text-rose-300">{{ $alertCount ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <span class="text-sm text-slate-300">Open Tasks</span>
                            <span class="text-sm font-bold text-amber-300">{{ $openTaskCount ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <span class="text-sm text-slate-300">In Progress Visits</span>
                            <span class="text-sm font-bold text-cyan-300">{{ $inProgressVisitCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-5">
        <div class="rounded-[24px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">
                {{ $selectedFacility ? 'Selected Facility' : 'Facilities' }}
            </p>
            <h2 class="mt-4 text-4xl font-black text-slate-900">
                {{ $selectedFacility ? 1 : ($facilityCount ?? 0) }}
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                {{ $selectedFacility ? 'Facility context currently active' : 'Locations under platform control' }}
            </p>
        </div>

        <div class="rounded-[24px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">Clients</p>
            <h2 class="mt-4 text-4xl font-black text-slate-900">{{ $clientCount ?? 0 }}</h2>
            <p class="mt-2 text-sm text-slate-500">
                {{ $selectedFacility ? 'Active patient records in selected facility' : 'Active patient records in system' }}
            </p>
        </div>

        <div class="rounded-[24px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">Caregivers</p>
            <h2 class="mt-4 text-4xl font-black text-slate-900">{{ $caregiverCount ?? 0 }}</h2>
            <p class="mt-2 text-sm text-slate-500">
                {{ $selectedFacility ? 'Workforce in selected facility' : 'Workforce available in operations' }}
            </p>
        </div>

        <div class="rounded-[24px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">Visits</p>
            <h2 class="mt-4 text-4xl font-black text-slate-900">{{ $visitCount ?? 0 }}</h2>
            <p class="mt-2 text-sm text-slate-500">
                {{ $selectedFacility ? 'Total visits in selected facility' : 'Total recorded visits' }}
            </p>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 rounded-[28px] bg-white border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">Operations Pulse</p>
                    <h3 class="mt-2 text-2xl font-black text-slate-900">
                        {{ $selectedFacility ? 'Facility Visit Operations' : 'Visit Operations' }}
                    </h3>
                </div>

                @if(Route::has('admin.visits.index'))
                    <a href="{{ route('admin.visits.index') }}"
                       class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Open Visits
                    </a>
                @endif
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-600 font-bold">Scheduled</p>
                    <p class="mt-3 text-3xl font-black text-indigo-900">{{ $scheduledVisitCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-600 font-bold">Completed</p>
                    <p class="mt-3 text-3xl font-black text-emerald-900">{{ $completedVisitCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl bg-amber-50 border border-amber-100 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-600 font-bold">In Progress</p>
                    <p class="mt-3 text-3xl font-black text-amber-900">{{ $inProgressVisitCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl bg-rose-50 border border-rose-100 p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-rose-600 font-bold">Missed</p>
                    <p class="mt-3 text-3xl font-black text-rose-900">{{ $missedVisitCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">Tasks & Review</p>
            <h3 class="mt-2 text-2xl font-black text-slate-900">Action Queue</h3>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-600 font-bold">Open Tasks</p>
                    <p class="mt-2 text-3xl font-black text-amber-900">{{ $openTaskCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl bg-cyan-50 border border-cyan-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-cyan-600 font-bold">Review Queue</p>
                    <p class="mt-2 text-3xl font-black text-cyan-900">{{ $reviewTaskCount ?? 0 }}</p>
                </div>

                <div class="rounded-2xl bg-rose-50 border border-rose-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-rose-600 font-bold">Alerts</p>
                    <p class="mt-2 text-3xl font-black text-rose-900">{{ $alertCount ?? 0 }}</p>
                </div>
            </div>

            @if(Route::has('admin.tasks.index'))
                <div class="mt-6">
                    <a href="{{ route('admin.tasks.index') }}"
                       class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Open Task Center
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 2xl:grid-cols-3 gap-6">
        <div class="2xl:col-span-2 rounded-[28px] bg-white border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">
                        {{ $selectedFacility ? 'Selected Facility Context' : 'Facility Command' }}
                    </p>
                    <h3 class="mt-2 text-2xl font-black text-slate-900">
                        {{ $selectedFacility ? $selectedFacility->name : 'Facility Access' }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-500">
                        {{ $selectedFacility ? 'You are currently scoped into this facility.' : 'Enter, inspect, and manage all registered facilities from one place.' }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.facilities.index') }}"
                       class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Open Facilities
                    </a>

                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('admin.facilities.create') }}"
                           class="inline-flex items-center rounded-2xl bg-cyan-500 px-4 py-3 text-sm font-bold text-slate-950 hover:bg-cyan-400 transition">
                            Add Facility
                        </a>
                    @endif
                </div>
            </div>

            @if($selectedFacility)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.facilities.show', $selectedFacility) }}"
                       class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5 hover:bg-indigo-100 transition">
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-600 font-bold">Facility Command Center</p>
                        <h4 class="mt-2 text-lg font-black text-slate-900">View {{ $selectedFacility->name }}</h4>
                        <p class="mt-2 text-sm text-slate-600">Open this facility's detailed intelligence dashboard.</p>
                    </a>

                    @if(Route::has('facility.patients.index'))
                        <a href="{{ route('facility.patients.index') }}"
                           class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 hover:bg-emerald-100 transition">
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-600 font-bold">Patients</p>
                            <h4 class="mt-2 text-lg font-black text-slate-900">Open Facility Patients</h4>
                            <p class="mt-2 text-sm text-slate-600">Jump into the selected facility patient workspace.</p>
                        </a>
                    @endif

                    @if(Route::has('facility.caregivers.index'))
                        <a href="{{ route('facility.caregivers.index') }}"
                           class="rounded-2xl border border-amber-200 bg-amber-50 p-5 hover:bg-amber-100 transition">
                            <p class="text-xs uppercase tracking-[0.2em] text-amber-600 font-bold">Caregivers</p>
                            <h4 class="mt-2 text-lg font-black text-slate-900">Open Facility Caregivers</h4>
                            <p class="mt-2 text-sm text-slate-600">See caregivers assigned to this facility context.</p>
                        </a>
                    @endif

                    @if(Route::has('admin.visits.index'))
                        <a href="{{ route('admin.visits.index') }}"
                           class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5 hover:bg-cyan-100 transition">
                            <p class="text-xs uppercase tracking-[0.2em] text-cyan-600 font-bold">Visits</p>
                            <h4 class="mt-2 text-lg font-black text-slate-900">Open Facility Visits</h4>
                            <p class="mt-2 text-sm text-slate-600">Continue into visit operations filtered to this facility context.</p>
                        </a>
                    @endif
                </div>
            @else
                <div class="mt-6 space-y-4">
                    @forelse($facilities as $facility)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <h4 class="text-lg font-bold text-slate-900">{{ $facility->name }}</h4>
                                <p class="mt-1 text-sm text-slate-500">{{ $facility->address ?: 'No address provided' }}</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <form action="{{ route('select.facility', $facility->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                                        Enter Facility
                                    </button>
                                </form>

                                <a href="{{ route('admin.facilities.show', $facility->id) }}"
                                   class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    View Facility
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center text-slate-500">
                            No facilities found yet.
                        </div>
                    @endforelse
                </div>
            @endif
        </div>

        <div class="rounded-[28px] bg-white border border-slate-200 shadow-sm p-6">
            <p class="text-[11px] uppercase tracking-[0.28em] text-slate-400 font-bold">System Insight</p>
            <h3 class="mt-2 text-2xl font-black text-slate-900">
                {{ $selectedFacility ? 'Facility Intelligence' : 'Admin Intelligence' }}
            </h3>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">
                        {{ $selectedFacility ? 'Facility Name' : 'Facility Coverage' }}
                    </p>
                    <p class="mt-2 text-3xl font-black text-slate-900">
                        {{ $selectedFacility ? $selectedFacility->name : ($facilityCount ?? 0) }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $selectedFacility ? 'Currently selected context' : 'Facilities currently in system' }}
                    </p>
                </div>

                <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-600 font-bold">Review Queue</p>
                    <p class="mt-2 text-3xl font-black text-amber-900">{{ $reviewTaskCount ?? 0 }}</p>
                    <p class="mt-1 text-sm text-amber-700/80">Tasks waiting for review</p>
                </div>

                <div class="rounded-2xl bg-rose-50 border border-rose-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-rose-600 font-bold">Alert Monitor</p>
                    <p class="mt-2 text-3xl font-black text-rose-900">{{ $alertCount ?? 0 }}</p>
                    <p class="mt-1 text-sm text-rose-700/80">System alerts visible in current context</p>
                </div>

                <div class="rounded-2xl bg-cyan-50 border border-cyan-100 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-cyan-600 font-bold">Workforce</p>
                    <p class="mt-2 text-3xl font-black text-cyan-900">{{ $caregiverCount ?? 0 }}</p>
                    <p class="mt-1 text-sm text-cyan-700/80">
                        {{ $selectedFacility ? 'Caregivers in selected facility' : 'Caregivers active in platform' }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
