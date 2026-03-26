@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">KASS CARE SUPER ADMIN DASHBOARD</h1>
            <p class="text-gray-500 mt-1">Manage facilities, providers, caregivers, clients, and visits.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm uppercase tracking-wide text-gray-500">Clients</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">{{ $clientCount ?? 0 }}</h2>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm uppercase tracking-wide text-gray-500">Caregivers</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">{{ $caregiverCount ?? 0 }}</h2>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm uppercase tracking-wide text-gray-500">Visits</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">{{ $visitCount ?? 0 }}</h2>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-sm uppercase tracking-wide text-gray-500">Alerts</p>
            <h2 class="text-3xl font-bold text-red-500 mt-2">{{ $alertCount ?? 0 }}</h2>
        </div>
    </div>

    {{-- FACILITIES --}}
    <div class="bg-white shadow rounded-xl p-6 mb-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Facilities</h2>

        @if(isset($facilities) && $facilities->count())
            <div class="space-y-3">
                @foreach($facilities as $facility)
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between border p-4 rounded-lg">
                        <div class="mb-3 md:mb-0">
                            <h3 class="font-bold text-gray-800">{{ $facility->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $facility->address ?? 'No address' }}</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('facility.select', $facility->id) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                    Enter Facility
                                </button>
                            </form>

                            <a href="{{ route('admin.facilities.index') }}"
                               class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                                Open Facilities
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No facilities found.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('admin.facilities.create') }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Add Facility
            </a>
        </div>
    </div>

    {{-- MAIN CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- PROVIDERS --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">Providers</h3>
            <p class="text-gray-500 mb-4">Manage providers and permissions for each facility.</p>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.providers.index') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Open Providers
                </a>

                <a href="{{ route('admin.providers.create') }}"
                   class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Add Provider
                </a>
            </div>
        </div>

        {{-- CAREGIVERS --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">Caregivers</h3>
            <p class="text-gray-500 mb-4">Manage caregivers, assignments, and workforce structure.</p>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.caregivers.index') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Open Caregivers
                </a>

                <a href="{{ route('admin.caregivers.create') }}"
                   class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Add Caregiver
                </a>
            </div>
        </div>

        {{-- CLIENTS --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">Clients</h3>
            <p class="text-gray-500 mb-4">Open all clients and create new client records as super admin.</p>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.clients.index') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Open Clients
                </a>

                <a href="{{ route('admin.clients.create') }}"
                   class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Add Client
                </a>
            </div>
        </div>

        {{-- VISITS --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-2">Visits</h3>
            <p class="text-gray-500 mb-4">Access visit records and create visits directly from the owner dashboard.</p>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.visits.index') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Open Visits
                </a>

                <a href="{{ route('admin.visits.create') }}"
                   class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Add Visit
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
