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

    <div class="mt-8 rounded-3xl bg-white border shadow-sm overflow-hidden">
        <div class="border-b px-6 py-5">
            <h2 class="text-2xl font-black text-slate-900">Caregiver Status Today</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            @forelse($caregivers as $caregiver)
                <div class="rounded-3xl border border-slate-200 p-5 shadow-sm">
                    <h3 class="text-xl font-black text-slate-900">{{ $caregiver->name }}</h3>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl bg-slate-50 p-3">
                            <p class="font-bold text-slate-500">Visits</p>
                            <p class="text-2xl font-black text-slate-900">{{ $caregiver->today_visits_count }}</p>
                        </div>

                        <div class="rounded-2xl bg-emerald-50 p-3">
                            <p class="font-bold text-emerald-600">Completed</p>
                            <p class="text-2xl font-black text-emerald-700">{{ $caregiver->completed_visits_count }}</p>
                        </div>

                        <div class="rounded-2xl bg-indigo-50 p-3">
                            <p class="font-bold text-indigo-600">Care Logs</p>
                            <p class="text-2xl font-black text-indigo-700">{{ $caregiver->care_logs_count }}</p>
                        </div>

                        <div class="rounded-2xl bg-green-50 p-3">
                            <p class="font-bold text-green-600">Meds</p>
                            <p class="text-2xl font-black text-green-700">{{ $caregiver->meds_signed_count }}</p>
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
