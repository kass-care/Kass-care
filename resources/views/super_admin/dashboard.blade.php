@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Super Admin Dashboard</h1>
        <p class="text-slate-600 mt-2">
            Platform-wide control center for Kass Care SaaS.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Facilities</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $facilitiesCount }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Total Users</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $usersCount }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Clients</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $clientsCount }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Visits</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $visitsCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Facility Admins</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $adminsCount }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Providers</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $providersCount }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500">Caregivers</h2>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $caregiversCount }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Actions</h2>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('facilities.index') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700">
                Manage Facilities
            </a>

            <a href="{{ route('clients.index') }}"
               class="bg-slate-800 text-white px-4 py-2 rounded-xl hover:bg-slate-900">
                View Clients
            </a>

            <a href="{{ route('visits.index') }}"
               class="bg-emerald-600 text-white px-4 py-2 rounded-xl hover:bg-emerald-700">
                View Visits
            </a>

            <a href="{{ route('caregivers.index') }}"
               class="bg-amber-500 text-white px-4 py-2 rounded-xl hover:bg-amber-600">
                View Caregivers
             <a href="{{ route('super_admin.users.index') }}"
   class="bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700">
    Manage Users
</a>
            </a>
        </div>
    </div>
</div>
@endsection
