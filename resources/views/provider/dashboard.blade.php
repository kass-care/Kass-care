@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">Provider Dashboard</h1>
                <p class="mt-2 text-gray-600">
                    Review scheduled visits, caregiver documentation, vitals, provider notes, and pharmacy orders.
                </p>
            </div>

            <a href="{{ route('billing.index') }}"
               class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                Billing
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Welcome to your clinical workspace</h2>
            <p class="mt-2 text-gray-600">
                Manage your schedule, provider notes, compliance, care logs, and pharmacy workflows from one place.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Schedule -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Schedule</h3>
                <p class="text-gray-600 mb-6">
                    Manage provider appointments and visit planning.
                </p>

                <a href="{{ route('provider.calendar') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                    Open Schedule
                </a>
            </div>

            <!-- Provider Notes -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Provider Notes</h3>
                <p class="text-gray-600 mb-6">
                    Review and manage provider clinical notes linked to visits.
                </p>

                <a href="{{ route('provider.notes.index') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                    Open Notes
                </a>
            </div>

            <!-- Compliance -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Compliance</h3>
                <p class="text-gray-600 mb-6">
                    Track documentation gaps and compliance risks.
                </p>

                <a href="{{ route('provider.compliance') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                    View Compliance
                </a>
            </div>

            <!-- Pharmacy Orders -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Pharmacy Orders</h3>
                <p class="text-gray-600 mb-6">
                    Create, review, email, fax, and download prescription orders.
                </p>

                <a href="{{ route('provider.pharmacy.index') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700 transition">
                    Open Pharmacy
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
