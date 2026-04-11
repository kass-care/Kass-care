@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">

    <div class="mb-8 rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500 text-white shadow-xl overflow-hidden">
        <div class="px-8 py-8 md:px-10 md:py-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-100 font-semibold">
                        KASS Care
                    </p>
                    <h1 class="mt-2 text-3xl md:text-4xl font-extrabold">
                        Facility Command Center
                    </h1>
                    <p class="mt-3 text-indigo-100 text-lg font-semibold">
                        {{ $facility->name }}
                    </p>
                    <p class="mt-2 text-indigo-100 max-w-3xl">
                        Real-time facility intelligence for clients, caregivers, visits, providers, and operational control.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.facilities.index') }}"
                       class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-700 shadow hover:bg-indigo-50 transition">
                        Back to Facilities
                    </a>

                    <form method="POST" action="{{ route('facility.select', $facility->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-xl border border-white/40 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">
                            Enter Facility Context
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">
        <div class="rounded-2xl bg-white p-6 shadow border border-indigo-100">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Clients</p>
            <h2 class="mt-3 text-4xl font-extrabold text-indigo-700">{{ $clientCount }}</h2>
            <p class="mt-2 text-sm text-gray-500">Registered in this facility</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow border border-indigo-100">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Caregivers</p>
            <h2 class="mt-3 text-4xl font-extrabold text-indigo-700">{{ $caregiverCount }}</h2>
            <p class="mt-2 text-sm text-gray-500">Assigned workforce</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow border border-indigo-100">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Visits</p>
            <h2 class="mt-3 text-4xl font-extrabold text-indigo-700">{{ $visitCount }}</h2>
            <p class="mt-2 text-sm text-gray-500">Scheduled visits</p>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow border border-indigo-100">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Providers</p>
            <h2 class="mt-3 text-4xl font-extrabold text-indigo-700">{{ $providerCount }}</h2>
            <p class="mt-2 text-sm text-gray-500">Linked providers</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-10">
        <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800">Facility Details</h2>
            <p class="text-sm text-gray-500 mt-1">Core information for this facility.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Name</p>
                    <p class="mt-2 text-lg font-bold text-gray-800">{{ $facility->name }}</p>
                </div>

                <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Address</p>
                    <p class="mt-2 text-lg font-bold text-gray-800">{{ $facility->address ?? 'Not provided' }}</p>
                </div>

                <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Phone</p>
                    <p class="mt-2 text-lg font-bold text-gray-800">{{ $facility->phone ?? 'Not provided' }}</p>
                </div>

                <div class="rounded-2xl bg-gray-50 p-5 border border-gray-100">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-500 font-semibold">Email</p>
                    <p class="mt-2 text-lg font-bold text-gray-800">{{ $facility->email ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800">Quick Actions</h2>
            <p class="text-sm text-gray-500 mt-1">Jump into related facility workflows.</p>

            <div class="mt-6 flex flex-col gap-3">
                <a href="{{ route('admin.clients.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-700 transition">
                    Open Clients
                </a>

                <a href="{{ route('admin.caregivers.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white border border-indigo-200 px-5 py-3 text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition">
                    Open Caregivers
                </a>

                <a href="{{ route('admin.visits.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white border border-indigo-200 px-5 py-3 text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition">
                    Open Visits
                </a>

                <a href="{{ route('admin.providers.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white border border-indigo-200 px-5 py-3 text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition">
                    Open Providers
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800">Operations Snapshot</h2>
        <p class="text-sm text-gray-500 mt-1">Enterprise placeholder block for the next upgrade.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">
            <div class="rounded-2xl bg-indigo-50 border border-indigo-100 p-5">
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-700 font-semibold">Client Load</p>
                <p class="mt-2 text-3xl font-extrabold text-indigo-700">{{ $clientCount }}</p>
                <p class="mt-2 text-sm text-indigo-700/80">Clients currently linked to this facility.</p>
            </div>

            <div class="rounded-2xl bg-amber-50 border border-amber-100 p-5">
                <p class="text-xs uppercase tracking-[0.25em] text-amber-700 font-semibold">Care Delivery</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $visitCount }}</p>
                <p class="mt-2 text-sm text-amber-700/80">Visits currently associated with this facility.</p>
            </div>

            <div class="rounded-2xl bg-green-50 border border-green-100 p-5">
                <p class="text-xs uppercase tracking-[0.25em] text-green-700 font-semibold">Staffing</p>
                <p class="mt-2 text-3xl font-extrabold text-green-700">{{ $caregiverCount }}</p>
                <p class="mt-2 text-sm text-green-700/80">Caregivers supporting this facility.</p>
            </div>
        </div>
    </div>
</div>
@endsection
