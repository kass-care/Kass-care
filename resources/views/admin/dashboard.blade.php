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
    <div class="mb-6 rounded-3xl border border-cyan-200 bg-cyan-50 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-cyan-700">
                    Active Facility Context
                </p>

                <h3 class="mt-1 text-2xl font-black text-slate-900">
                    {{ $selectedFacility->name }}
                </h3>

                <p class="mt-1 text-sm text-slate-600">
                    Super admin is currently operating inside this facility.
                </p>
            </div>

            <form action="{{ route('clear.facility') }}" method="POST">
                @csrf

                <button type="submit"
                        class="rounded-2xl bg-red-600 px-5 py-3 text-sm font-bold text-white">
                    Exit Facility
                </button>
            </form>
        </div>
    </div>
@endif
    <section class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-900 text-white shadow-2xl p-8 lg:p-10">
        <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-bold">
            KASSCARE GLOBAL OPERATIONS
        </p>
           

        <h1 class="mt-4 text-4xl md:text-5xl font-black">
            Super Admin Command Center
        </h1>

        <p class="mt-4 max-w-3xl text-slate-300 leading-7">
            Global visibility into facilities, clients, providers, caregivers, visits,
            compliance, alerts, and platform operations.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.facilities.index') }}"
               class="rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-bold text-slate-950">
                Open Facilities
            </a>

            @if(Route::has('admin.tasks.index'))
                <a href="{{ route('admin.tasks.index') }}"
                   class="rounded-2xl bg-white/10 px-5 py-3 text-sm font-bold text-white">
                    Open Tasks
                </a>
            @endif
        </div>
    </section>

{{-- LIVE SYSTEM ALERT STRIP --}}
<section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-rose-600 via-amber-500 to-cyan-600 text-white">
        <div class="flex items-center gap-4 px-6 py-3 overflow-hidden">
            <div class="shrink-0">
                <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-black tracking-[0.2em] uppercase">
                    LIVE SYSTEM STATUS
                </span>
            </div>

            <div class="overflow-hidden whitespace-nowrap flex-1">
                <div class="animate-pulse text-sm font-semibold">
                    🚨 {{ $alertCount ?? 0 }} Active Alerts •
                    📋 {{ $openTaskCount ?? 0 }} Open Tasks •
                    🩺 {{ $inProgressVisitCount ?? 0 }} Visits In Progress •
                    ✅ {{ $completedVisitCount ?? 0 }} Visits Completed •
                    🏥 {{ $facilityCount ?? 0 }} Facilities Active •
                    👨‍⚕️ {{ $caregiverCount ?? 0 }} Caregivers Online
                </div>
            </div>
        </div>
    </div>
</section>
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

    <a href="{{ route('admin.facilities.index') }}"
       class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
            Facilities
        </p>

        <h2 class="mt-4 text-5xl font-black text-slate-900">
            {{ $facilityCount ?? 0 }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Total facilities onboarded
        </p>
    </a>

    <a href="{{ route('admin.clients.index') }}"
       class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
            Clients
        </p>

        <h2 class="mt-4 text-5xl font-black text-slate-900">
            {{ $clientCount ?? 0 }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Active patient records
        </p>
    </a>

    <a href="{{ route('admin.providers.index') }}"
       class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
            Providers
        </p>

        <h2 class="mt-4 text-5xl font-black text-slate-900">
            {{ $providerCount ?? 0 }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Providers across facilities
        </p>
    </a>

    <a href="{{ route('admin.caregivers.index') }}"
       class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
            Caregivers
        </p>

        <h2 class="mt-4 text-5xl font-black text-slate-900">
            {{ $caregiverCount ?? 0 }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Caregiver workforce
        </p>
    </a>

</section>
<section class="grid grid-cols-1 xl:grid-cols-4 gap-5">
    <a href="{{ route('admin.visits.index', ['status' => 'scheduled']) }}"
       class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.2em] text-indigo-600 font-bold">Scheduled Visits</p>
        <h2 class="mt-4 text-5xl font-black text-indigo-900">{{ $scheduledVisitCount ?? 0 }}</h2>
        <p class="mt-2 text-sm text-indigo-700/80">Open scheduled visit queue</p>
    </a>

    <a href="{{ route('admin.visits.index', ['status' => 'completed']) }}"
       class="rounded-3xl bg-emerald-50 border border-emerald-100 p-6 hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.2em] text-emerald-600 font-bold">Completed Visits</p>
        <h2 class="mt-4 text-5xl font-black text-emerald-900">{{ $completedVisitCount ?? 0 }}</h2>
        <p class="mt-2 text-sm text-emerald-700/80">Review completed visits</p>
    </a>

    <a href="{{ route('admin.visits.index', ['status' => 'in_progress']) }}"
       class="rounded-3xl bg-amber-50 border border-amber-100 p-6 hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.2em] text-amber-600 font-bold">In Progress</p>
        <h2 class="mt-4 text-5xl font-black text-amber-900">{{ $inProgressVisitCount ?? 0 }}</h2>
        <p class="mt-2 text-sm text-amber-700/80">Monitor active visits</p>
    </a>

    <a href="{{ route('admin.visits.index', ['status' => 'missed']) }}"
       class="rounded-3xl bg-rose-50 border border-rose-100 p-6 hover:shadow-xl hover:-translate-y-1 transition">
        <p class="text-xs uppercase tracking-[0.2em] text-rose-600 font-bold">Missed Visits</p>
        <h2 class="mt-4 text-5xl font-black text-rose-900">{{ $missedVisitCount ?? 0 }}</h2>
        <p class="mt-2 text-sm text-rose-700/80">Investigate missed visits</p>
    </a>
</section>
<section class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
            Operations Intelligence
        </p>

        <h3 class="mt-2 text-3xl font-black text-slate-900">
            Platform Activity Feed
        </h3>

        <div class="mt-6 space-y-4">
            @forelse($recentVisits ?? [] as $visit)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-start justify-between gap-4">
                    <div>
                        <p class="font-bold text-slate-900">
                            Visit #{{ $visit->id }} updated
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Status: {{ ucfirst(str_replace('_', ' ', $visit->status ?? 'unknown')) }}
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            {{ optional($visit->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    @if(Route::has('admin.visits.show'))
                        <a href="{{ route('admin.visits.show', $visit->id) }}"
                           class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-bold text-white">
                            View
                        </a>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500">
                    No recent activity yet.
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl bg-slate-950 text-white shadow-2xl p-6">
        <p class="text-xs uppercase tracking-[0.25em] text-cyan-300 font-bold">
            Command Actions
        </p>

        <h3 class="mt-2 text-2xl font-black">
            Quick Control
        </h3>

        <div class="mt-6 space-y-3">
            <a href="{{ route('admin.facilities.index') }}"
               class="block rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold hover:bg-white/20">
                🏥 Manage Facilities
            </a>

            <a href="{{ route('admin.clients.index') }}"
               class="block rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold hover:bg-white/20">
                👥 Manage Clients
            </a>

            <a href="{{ route('admin.providers.index') }}"
               class="block rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold hover:bg-white/20">
                👨‍⚕️ Manage Providers
            </a>

            <a href="{{ route('admin.visits.index') }}"
               class="block rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold hover:bg-white/20">
                📅 Manage Visits
            </a>
        </div>
    </div>

</section>

    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">
                    Facility Command Layer
                </p>
                <h3 class="mt-2 text-3xl font-black text-slate-900">
                    Facility Operations
                </h3>
            </div>

            @if(Route::has('admin.facilities.create') && auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.facilities.create') }}"
                   class="rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-bold text-slate-950">
                    Add Facility
                </a>
            @endif
        </div>

        <div class="mt-6 space-y-4">
            @forelse($facilities as $facility)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h4 class="text-lg font-black text-slate-900">{{ $facility->name }}</h4>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $facility->address ?: 'No address provided' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if(Route::has('select.facility'))
                            <form action="{{ route('select.facility', $facility->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white">
                                    Enter Facility
                                </button>
                            </form>
                        @endif

                        @if(Route::has('admin.facilities.show'))
                            <a href="{{ route('admin.facilities.show', $facility->id) }}"
                               class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700">
                                View Facility
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center text-slate-500">
                    No facilities found.
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
