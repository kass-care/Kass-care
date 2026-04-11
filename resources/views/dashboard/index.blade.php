@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- PAGE HEADER --}}
    <div class="mb-6 rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 text-white shadow-2xl overflow-hidden">
        <div class="px-6 py-6 md:px-8 md:py-7">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 items-stretch">
                <div class="xl:col-span-2">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-cyan-300 font-semibold">
                        KASS CARE SAAS
                    </p>
                    
                    <div class="flex items-center gap-3 mt-2">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full border-2 border-yellow-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="absolute -top-1 -right-1 text-yellow-300 animate-pulse">+</span>
                        </div>
                        <span class="text-sm font-semibold text-yellow-200">we are not helpless</span>
                    </div>

                    <h1 class="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight">
                        Super Admin Engineering Console
                    </h1>
                    <p class="mt-3 max-w-3xl text-sm text-indigo-100 leading-6">
                        Full command over facilities, workforce, clients, visits, and backups. This dashboard is built to make you feel in control.
                    </p>
                </div>
            </div>
            
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('facilities.index') }}" class="inline-flex items-center rounded-xl bg-cyan-400 px-4 py-2.5 text-sm font-bold text-slate-900">
                    Open Facilities
                </a>
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white">
                    Open Tasks
                </a>
            </div>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- EXECUTIVE KPI CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Facilities', 'count' => $facilityCount ?? 0, 'color' => 'text-indigo-700'],
                ['label' => 'Clients', 'count' => $clientCount ?? 0, 'color' => 'text-indigo-700'],
                ['label' => 'Caregivers', 'count' => $caregiverCount ?? 0, 'color' => 'text-indigo-700'],
                ['label' => 'Visits', 'count' => $visitCount ?? 0, 'color' => 'text-indigo-700'],
                ['label' => 'Open Tasks', 'count' => $openTaskCount ?? 0, 'color' => 'text-amber-700'],
                ['label' => 'Alerts', 'count' => $alertCount ?? 0, 'color' => 'text-rose-700'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-indigo-100">
                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">{{ $stat['label'] }}</p>
                <h2 class="mt-2 text-3xl font-extrabold {{ $stat['color'] }}">{{ $stat['count'] }}</h2>
            </div>
        @endforeach
    </div>

    {{-- OPERATIONS + FACILITIES --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        {{-- Facility Command --}}
        <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Facility Command</p>
                    <h2 class="mt-1 text-2xl font-bold text-gray-900">Active Facilities</h2>
                </div>
                <a href="{{ route('facilities.create') }}" class="mt-4 md:mt-0 inline-flex items-center rounded-xl bg-cyan-400 px-5 py-3 text-sm font-bold text-slate-950">
                    + Add Facility
                </a>
            </div>

            @if(isset($facilities) && $facilities->count() > 0)
                <div class="space-y-4">
                    @foreach($facilities as $facility)
                        <div class="rounded-2xl border border-gray-200 p-4 hover:shadow-md transition bg-white">
                            <div class="flex flex-col md:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $facility->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $facility->address ?? 'No address set' }}</p>
                                </div>
                                <div class="mt-4 flex gap-3">
                                    <form action="{{ route('facilities.select', $facility->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                                            Enter Facility
                                        </button>
                                    </form>
                                    <a href="{{ route('facilities.show', $facility->id) }}" class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                    No facilities found. Create one to get started.
                </div>
            @endif
        </div>

        {{-- System Insights --}}
        <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">System Insight</p>
            <h2 class="mt-1 text-2xl font-bold text-gray-900">Admin Intelligence</h2>
            
            <div class="mt-5 space-y-4">
                <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                    <p class="text-[10px] uppercase tracking-widest text-indigo-700 font-semibold">Scheduled Visits</p>
                    <p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $scheduledVisitCount ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                    <p class="text-[10px] uppercase tracking-widest text-emerald-700 font-semibold">Completed Today</p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-700">{{ $completedVisitCount ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-rose-50 border border-rose-100 p-4">
                    <p class="text-[10px] uppercase tracking-widest text-rose-700 font-semibold">System Alerts</p>
                    <p class="mt-1 text-2xl font-extrabold text-rose-700">{{ $alertCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
