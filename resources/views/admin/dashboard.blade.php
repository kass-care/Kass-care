@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="bg-white shadow rounded-2xl p-8 mb-8 border border-gray-100">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Super Admin Dashboard</h1>
        <p class="text-gray-600 text-lg">
            Full system overview of KASSCare. Manage facilities, providers, caregivers, clients, visits, and operations.
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6">
            <p class="text-sm font-semibold text-indigo-700 mb-2">Facilities</p>
            <h2 class="text-4xl font-bold text-gray-900">{{ $facilitiesCount ?? 0 }}</h2>
        </div>

        <div class="bg-green-50 border border-green-100 rounded-2xl p-6">
            <p class="text-sm font-semibold text-green-700 mb-2">Caregivers</p>
            <h2 class="text-4xl font-bold text-gray-900">{{ $caregiversCount ?? 0 }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-semibold text-gray-700 mb-2">Clients</p>
            <h2 class="text-4xl font-bold text-gray-900">{{ $clientsCount ?? 0 }}</h2>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <p class="text-sm font-semibold text-indigo-700 mb-2">Visits</p>
            <h2 class="text-4xl font-bold text-gray-900">{{ $visitsCount ?? 0 }}</h2>
        </div>
    </div>

    {{-- Management Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        {{-- Facilities --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Facilities</h3>
            <p class="text-gray-600 mb-6">
                Create, edit, and manage facilities across the system.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.facilities.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-semibold">
                    Open Facilities
                </a>

                <a href="{{ route('admin.facilities.create') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-3 rounded-lg font-semibold">
                    Add Facility
                </a>
            </div>
        </div>

        {{-- Providers --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Providers</h3>
            <p class="text-gray-600 mb-6">
                Manage providers and permissions for each facility.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.providers.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-semibold">
                    Open Providers
                </a>

                <a href="{{ route('admin.providers.create') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-3 rounded-lg font-semibold">
                    Add Provider
                </a>
            </div>
        </div>

        {{-- Caregivers --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Caregivers</h3>
            <p class="text-gray-600 mb-6">
                Manage caregivers, assignments, and workforce structure.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.caregivers.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-semibold">
                    Open Caregivers
                </a>

                <a href="{{ route('admin.caregivers.create') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-3 rounded-lg font-semibold">
                    Add Caregiver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
