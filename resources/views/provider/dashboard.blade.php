@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6">
    <div class="bg-white shadow rounded-xl p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Provider Dashboard</h1>
        <p class="text-gray-600 mb-8">
            Welcome to the provider workspace.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-blue-800 mb-2">Clinical Notes</h2>
                <p class="text-sm text-gray-600">Review and manage provider notes.</p>
            </div>

            <div class="bg-purple-50 border border-purple-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-purple-800 mb-2">Schedule</h2>
                <p class="text-sm text-gray-600">Access visits and calendar workflow.</p>
            </div>

            <div class="bg-rose-50 border border-rose-100 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-rose-800 mb-2">Compliance</h2>
                <p class="text-sm text-gray-600">Track documentation and compliance tasks.</p>
            </div>
        </div>
    </div>
</div>
@endsection
