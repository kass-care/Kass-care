@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Super Admin Dashboard</h1>
                <p class="text-slate-600 mt-1">Kass Care owner control center</p>
            </div>

            @if(session('facility_id'))
                <a href="{{ route('superadmin.exit') }}"
                   class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                    Exit Facility View
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow p-6 border border-slate-100">
                <p class="text-sm text-slate-500">Total Facilities</p>
                <h2 class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalFacilities }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 border border-slate-100">
                <p class="text-sm text-slate-500">Total Users</p>
                <h2 class="text-3xl font-bold text-slate-900 mt-2">{{ $totalUsers }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 border border-slate-100">
                <p class="text-sm text-slate-500">Total Visits</p>
                <h2 class="text-3xl font-bold text-green-600 mt-2">{{ $totalVisits }}</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-xl font-semibold text-slate-900">Facilities</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="text-left px-6 py-3">ID</th>
                            <th class="text-left px-6 py-3">Facility Name</th>
                            <th class="text-left px-6 py-3">Address</th>
                            <th class="text-left px-6 py-3">Phone</th>
                            <th class="text-left px-6 py-3">Pro Feature</th>
                            <th class="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                            <tr class="border-t border-slate-100">
                                <td class="px-6 py-4">{{ $facility->id }}</td>
                                <td class="px-6 py-4 font-medium">{{ $facility->name }}</td>
                                <td class="px-6 py-4">{{ $facility->address ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $facility->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if($facility->feature_pro)
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Enabled
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                            Disabled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('superadmin.impersonate', $facility->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Enter Facility
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-slate-500">
                                    No facilities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
