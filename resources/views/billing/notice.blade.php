@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <div class="bg-white border border-red-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-8 py-8 bg-gradient-to-r from-red-50 via-white to-indigo-50 border-b border-gray-100">
            <div class="text-xs uppercase tracking-[0.25em] text-red-500 font-semibold mb-3">
                KASS Care Billing Guard
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                Subscription Required
            </h1>

            <p class="text-gray-600 max-w-2xl">
                Your facility does not currently have an active subscription.
                Please activate billing to continue using protected Kass Care SaaS features.
            </p>
        </div>

        <div class="px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Status</div>
                    <div class="text-lg font-semibold text-red-600">Inactive</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Access</div>
                    <div class="text-lg font-semibold text-gray-900">Restricted</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Next Step</div>
                    <div class="text-lg font-semibold text-indigo-600">Activate Billing</div>
                </div>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">
                    Protected features currently locked
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
                    <div class="bg-white border border-indigo-100 rounded-xl px-4 py-3">Patients</div>
                    <div class="bg-white border border-indigo-100 rounded-xl px-4 py-3">Visits</div>
                    <div class="bg-white border border-indigo-100 rounded-xl px-4 py-3">Providers</div>
                    <div class="bg-white border border-indigo-100 rounded-xl px-4 py-3">Care Logs</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('billing.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Go to Billing
                </a>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
