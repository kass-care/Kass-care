@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 p-8">

    <div class="max-w-7xl mx-auto">

        <h1 class="text-3xl font-bold text-slate-900 mb-4">
            Admin Dashboard
        </h1>

        <p class="text-slate-600 mb-8">
            Welcome to Kass Care Admin Panel
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-slate-500">Clients</h2>
                <p class="text-3xl font-bold text-indigo-600">--</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-slate-500">Caregivers</h2>
                <p class="text-3xl font-bold text-indigo-600">--</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-slate-500">Facilities</h2>
                <p class="text-3xl font-bold text-indigo-600">--</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-sm text-slate-500">Visits</h2>
                <p class="text-3xl font-bold text-indigo-600">--</p>
            </div>

        </div>

    </div>

</div>

@endsection
