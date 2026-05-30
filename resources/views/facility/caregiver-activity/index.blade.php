@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl">
        <p class="text-xs uppercase tracking-[0.35em] font-black text-cyan-300">
            KASS CARE FACILITY OPERATIONS
        </p>

        <h1 class="mt-3 text-4xl font-black">
            Caregiver Accountability Command Center
        </h1>

        <p class="mt-3 text-slate-300">
            Monitor caregiver visits, care logs, and medication administration activity for today.
        </p>
    </div>
	<div class="mt-8 rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm">
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.3em] text-red-700">
                Facility Alerts
            </p>
            <h2 class="mt-2 text-2xl font-black text-red-900">
                Real-Time Accountability Warnings
            </h2>
        </div>

        <a href="{{ route('facility.mar.index') }}"
           class="inline-flex rounded-2xl bg-red-700 px-5 py-3 text-sm font-black text-white hover:bg-red-800">
            Review Medication Issues
        </a>
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-red-100">
            <p class="text-xs font-black uppercase text-red-600">Late Caregivers</p>
            <p class="mt-2 text-3xl font-black text-red-700">⚠ {{ $summary['late_caregivers'] ?? 0 }}</p>
        </div>

        <div class="rounded-2xl bg-white p-4 border border-orange-100">
            <p class="text-xs font-black uppercase text-orange-600">Missing Clock-Ins</p>
            <p class="mt-2 text-3xl font-black text-orange-700">⚠ {{ $summary['missing_clock_ins'] ?? 0 }}</p>
        </div>

        <div class="rounded-2xl bg-white p-4 border border-rose-100">
            <p class="text-xs font-black uppercase text-rose-600">Medication Issues</p>
            <p class="mt-2 text-3xl font-black text-rose-700">⚠ {{ $summary['medication_issues'] ?? 0 }}</p>
        </div>

        <div class="rounded-2xl bg-white p-4 border border-purple-100">
            <p class="text-xs font-black uppercase text-purple-600">Corrections Today</p>
            <p class="mt-2 text-3xl font-black text-purple-700">✍️ {{ $summary['corrections_today'] ?? 0 }}</p>
        </div>

        <div class="rounded-2xl bg-white p-4 border border-amber-100">
            <p class="text-xs font-black uppercase text-amber-600">GPS Pending</p>
            <p class="mt-2 text-3xl font-black text-amber-700">📍 {{ $summary['gps_pending'] ?? 0 }}</p>
        </div>
    </div>
</div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="rounded-3xl bg-white border p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-slate-500">Visits Today</p>
            <p class="mt-3 text-4xl font-black text-slate-900">{{ $summary['visits_today'] }}</p>
        </div>

        <div class="rounded-3xl bg-emerald-50 border border-emerald-200 p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-emerald-700">Completed</p>
            <p class="mt-3 text-4xl font-black text-emerald-700">{{ $summary['completed_visits'] }}</p>
        </div>

        <div class="rounded-3xl bg-blue-50 border border-blue-200 p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-blue-700">In Progress</p>
            <p class="mt-3 text-4xl font-black text-blue-700">{{ $summary['in_progress_visits'] }}</p>
        </div>

        <div class="rounded-3xl bg-indigo-50 border border-indigo-200 p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-indigo-700">Care Logs</p>
            <p class="mt-3 text-4xl font-black text-indigo-700">{{ $summary['care_logs_today'] }}</p>
        </div>

        <div class="rounded-3xl bg-green-50 border border-green-200 p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-green-700">Meds Signed</p>
            <p class="mt-3 text-4xl font-black text-green-700">{{ $summary['meds_administered'] }}</p>
        </div>

        <div class="rounded-3xl bg-red-50 border border-red-200 p-5 shadow-sm">
            <p class="text-xs font-black uppercase text-red-700">Missed/Held</p>
            <p class="mt-3 text-4xl font-black text-red-700">{{ $summary['meds_not_administered'] }}</p>
        </div>
    </div>

   <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="rounded-3xl bg-emerald-600 p-5 text-white shadow">
        <p class="text-xs font-black uppercase tracking-[0.25em]">On Duty</p>
        <p class="mt-3 text-4xl font-black">{{ $summary['on_duty'] ?? 0 }}</p>
    </div>

    <div class="rounded-3xl bg-amber-400 p-5 text-amber-950 shadow">
        <p class="text-xs font-black uppercase tracking-[0.25em]">Scheduled</p>
        <p class="mt-3 text-4xl font-black">{{ $summary['scheduled'] ?? 0 }}</p>
    </div>

    <div class="rounded-3xl bg-cyan-600 p-5 text-white shadow">
        <p class="text-xs font-black uppercase tracking-[0.25em]">Completed Shifts</p>
        <p class="mt-3 text-4xl font-black">{{ $summary['completed_shifts'] ?? 0 }}</p>
    </div>

    <div class="rounded-3xl bg-slate-800 p-5 text-white shadow">
        <p class="text-xs font-black uppercase tracking-[0.25em]">Off Duty</p>
        <p class="mt-3 text-4xl font-black">
            {{ $caregivers->where('shift_status', 'off_duty')->count() }}
        </p>
    </div>
</div>

    <div class="mt-8 rounded-3xl bg-white border shadow-sm overflow-hidden">
        <div class="border-b px-6 py-5">
            <h2 class="text-2xl font-black text-slate-900">Caregiver Status Today</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            @forelse($caregivers as $caregiver)
                <div class="rounded-3xl border border-slate-200 p-5 shadow-sm">
                    <h3 class="text-xl font-black text-slate-900">{{ $caregiver->name }}</h3>
                     <div class="mt-4 rounded-2xl bg-slate-900 p-4 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-black uppercase tracking-[0.25em] text-cyan-300">
                Accountability Score
            </p>
            <p class="mt-2 text-4xl font-black">
                {{ $caregiver->accountability_score }}%
            </p>
        </div>

        <span class="rounded-2xl bg-cyan-400 px-4 py-2 text-sm font-black text-slate-950">
            {{ $caregiver->accountability_grade }}
        </span>
    </div>
</div>

			<div class="mt-4 grid grid-cols-2 gap-3 text-sm">
    <div class="rounded-2xl bg-slate-50 p-3">
        <p class="font-bold text-slate-500">Shift</p>
        <p class="text-lg font-black text-slate-900">{{ strtoupper($caregiver->shift_status) }}</p>
    </div>

    <div class="rounded-2xl bg-emerald-50 p-3">
        <p class="font-bold text-emerald-600">GPS</p>
        <p class="text-lg font-black text-emerald-700">
            {{ $caregiver->gps_verified ? 'Verified ✅' : 'Not Yet' }}
        </p>
    </div>

    <div class="rounded-2xl bg-blue-50 p-3">
        <p class="font-bold text-blue-600">Clock In</p>
        <p class="text-sm font-black text-blue-700">
            {{ $caregiver->clock_in_at ? $caregiver->clock_in_at->format('g:i A') : '--' }}
        </p>
    </div>

    <div class="rounded-2xl bg-amber-50 p-3">
        <p class="font-bold text-amber-600">Clock Out</p>
        <p class="text-sm font-black text-amber-700">
            {{ $caregiver->clock_out_at ? $caregiver->clock_out_at->format('g:i A') : '--' }}
        </p>
    </div>

    <div class="rounded-2xl bg-cyan-50 p-3">
        <p class="font-bold text-cyan-600">Residents</p>
        <p class="text-2xl font-black text-cyan-700">{{ $caregiver->assigned_residents_count }}</p>
    </div>

    <div class="rounded-2xl bg-indigo-50 p-3">
        <p class="font-bold text-indigo-600">Care Logs</p>
        <p class="text-2xl font-black text-indigo-700">{{ $caregiver->care_logs_count }}</p>
    </div>

    <div class="rounded-2xl bg-green-50 p-3">
        <p class="font-bold text-green-600">Meds</p>
        <p class="text-2xl font-black text-green-700">{{ $caregiver->meds_signed_count }}</p>
    </div>

    <div class="rounded-2xl bg-slate-50 p-3">
        <p class="font-bold text-slate-500">Visits</p>
        <p class="text-2xl font-black text-slate-900">{{ $caregiver->today_visits_count }}</p>
    </div>
</div>
                    <p class="mt-4 text-sm font-semibold text-slate-500">
                        Last Activity:
                        {{ $caregiver->last_activity_at ? $caregiver->last_activity_at->diffForHumans() : 'No activity today' }}
                    </p>
                </div>
            @empty
                <div class="col-span-full p-8 text-center text-slate-500">
                    No caregivers found for this facility.
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8 rounded-3xl bg-white border shadow-sm overflow-hidden">
        <div class="border-b px-6 py-5">
            <h2 class="text-2xl font-black text-slate-900">Today’s Visit Activity</h2>
        </div>

        <div class="divide-y">
            @forelse($visitsToday as $visit)
                <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="font-black text-slate-900">
                            {{ $visit->client->name ?? 'Unknown Patient' }}
                        </p>
                        <p class="text-sm text-slate-500">
                            Caregiver: {{ $visit->caregiver->name ?? 'Unassigned' }}
                        </p>
                    </div>

                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-black text-slate-700">
                        {{ strtoupper($visit->status ?? 'scheduled') }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    No visits scheduled today.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
