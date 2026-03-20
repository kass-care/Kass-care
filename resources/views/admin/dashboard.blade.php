@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6">
    <div class="bg-white shadow rounded-xl p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600 mb-8">
            Welcome to the admin area of KASSCare.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-indigo-800 mb-2">Facilities</h2>
                <p class="text-sm text-gray-600">Manage facilities and system structure.</p>
            </div>

            <div class="bg-green-50 border border-green-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-green-800 mb-2">Providers</h2>
                <p class="text-sm text-gray-600">Manage providers and access control.</p>
            </div>

            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-yellow-800 mb-2">Caregivers</h2>
                <p class="text-sm text-gray-600">Manage caregivers and operational workflows.</p>
            </div>
        </div>
    </div>
</div>
@endsection
