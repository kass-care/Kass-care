<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Admin Home | Kass Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">
@php
    $facility = auth()->user()->facility;

    $trialDaysRemaining = null;

    if (
        $facility &&
        ($facility->subscription_status ?? null) === 'trialing' &&
        $facility->trial_ends_at
    ) {
        $trialDaysRemaining = ceil(now()->diffInDays($facility->trial_ends_at, false));
    }
@endphp

<div class="max-w-7xl mx-auto px-6 py-10">

    @if(!is_null($trialDaysRemaining))
        <div class="mb-8 rounded-3xl border border-amber-300 bg-amber-50 p-6 shadow-sm text-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.35em] text-amber-700">
                        KASSCARE FREE TRIAL
                    </p>

                    <h2 class="mt-2 text-3xl font-black text-slate-900">
                        {{ max($trialDaysRemaining, 0) }} Days Remaining
                    </h2>

                    <p class="mt-2 text-sm font-semibold text-slate-600">
                        Your facility is currently using the KASSCare 30-day enterprise trial.
                        Activate billing before expiration to maintain uninterrupted access.
                    </p>
                </div>

                <a href="{{ route('billing.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-amber-600 px-8 py-4 text-lg font-black text-white shadow-xl hover:bg-amber-700">
                    💳 Activate Subscription
                </a>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-10 flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.35em] text-indigo-300">Kass Care</p>

            <h1 class="mt-2 text-4xl font-bold">
                Facility Command Center
            </h1>

            <p class="mt-3 text-slate-400">
                {{ $facility?->name ?? 'Your Facility' }}
            </p>

            <p class="mt-2 text-slate-500">
                Real-time facility intelligence for clients, caregivers, visits, providers, messaging, and operations.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900">
                Dashboard
            </a>

            @if(auth()->user()->facility_id)
                <form method="POST" action="/select-facility/{{ auth()->user()->facility_id }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-2xl border border-indigo-400 px-5 py-3 text-sm font-semibold text-indigo-100 hover:bg-indigo-500/20">
                        Enter Facility Context
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center rounded-2xl border border-red-400/50 bg-red-500/10 px-5 py-3 text-sm font-semibold text-red-200 hover:bg-red-500/20">
                    Logout
                </button>
            </form>
        </div>
    </div>
              @if(($expiredDocuments ?? 0) > 0 || ($expiringSoonDocuments ?? 0) > 0)
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        @if(($expiredDocuments ?? 0) > 0)
            <a href="{{ route('facility.compliance-documents.index') }}"
               class="rounded-3xl border border-red-300 bg-red-50 p-6 shadow-sm block hover:bg-red-100">
                <p class="text-xs font-black uppercase tracking-[0.3em] text-red-700">
                    Compliance Alert
                </p>
                <h2 class="mt-3 text-3xl font-black text-red-800">
                    {{ $expiredDocuments }} Expired Document(s)
                </h2>
                <p class="mt-2 text-sm font-semibold text-red-600">
                    Immediate renewal required.
                </p>
            </a>
        @endif

        @if(($expiringSoonDocuments ?? 0) > 0)
            <a href="{{ route('facility.compliance-documents.index') }}"
               class="rounded-3xl border border-yellow-300 bg-yellow-50 p-6 shadow-sm block hover:bg-yellow-100">
                <p class="text-xs font-black uppercase tracking-[0.3em] text-yellow-700">
                    Compliance Warning
                </p>
                <h2 class="mt-3 text-3xl font-black text-yellow-800">
                    {{ $expiringSoonDocuments }} Expiring Soon
                </h2>
                <p class="mt-2 text-sm font-semibold text-yellow-700">
                    Documents expiring within 30 days.
                </p>
            </a>
        @endif
    </div>
@endif


    {{-- Stats --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Clients</p>
            <p class="mt-4 text-5xl font-bold">{{ $patients ?? 0 }}</p>
            <p class="mt-2 text-slate-400">Registered in this facility</p>
        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Caregivers</p>
            <p class="mt-4 text-5xl font-bold">{{ $caregivers ?? 0 }}</p>
            <p class="mt-2 text-slate-400">Assigned workforce</p>
        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Visits</p>
            <p class="mt-4 text-5xl font-bold">{{ $visits ?? 0 }}</p>
            <p class="mt-2 text-slate-400">Scheduled visits</p>
        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Providers</p>
            <p class="mt-4 text-5xl font-bold">{{ $providers ?? 0 }}</p>
            <p class="mt-2 text-slate-400">Linked providers</p>
        </div>
    </div>

    {{-- Main content --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Facility Details --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8 lg:col-span-2">
            <h2 class="mb-2 text-2xl font-semibold">Facility Details</h2>
            <p class="mb-6 text-slate-400">Core information for this facility.</p>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Name</p>
                    <p class="mt-3 text-2xl font-semibold">
                        {{ $facility?->name ?? 'Not provided' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Address</p>
                    <p class="mt-3 text-2xl font-semibold">
                        {{ $facility?->address ?? 'Not provided' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Phone</p>
                    <p class="mt-3 text-2xl font-semibold">
                        {{ $facility?->phone ?? 'Not provided' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Email</p>
                    <p class="mt-3 text-2xl font-semibold">
                        {{ $facility?->email ?? auth()->user()->email ?? 'Not provided' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
            <h2 class="mb-2 text-2xl font-semibold">Quick Actions</h2>
            <p class="mb-6 text-slate-400">Jump into facility workflows.</p>

            <div class="space-y-3">
                <a href="{{ route('facility.messages.create') }}"
                   class="block w-full rounded-2xl bg-emerald-600 px-4 py-4 text-center font-semibold text-white hover:bg-emerald-500">
                    Message Provider
                </a>

                <a href="{{ route('facility.messages.index') }}"
                   class="block w-full rounded-2xl border border-emerald-500 px-4 py-4 text-center font-semibold text-emerald-200 hover:bg-emerald-500/10">
                    View Messages
                </a>

                <a href="{{ route('facility.mar.index') }}"
                   class="block w-full rounded-2xl bg-indigo-600 px-4 py-4 text-center font-semibold text-white hover:bg-indigo-500">
                    Open Clients
                </a>

                <a href="{{ route('facility.caregivers.index') }}"
                   class="block w-full rounded-2xl border border-slate-700 px-4 py-4 text-center font-semibold text-slate-200 hover:bg-slate-800">
                    Open Caregivers
                </a>

                <a href="{{ route('facility.visits.index') }}"
                   class="block w-full rounded-2xl border border-slate-700 px-4 py-4 text-center font-semibold text-slate-200 hover:bg-slate-800">
                    Open Visits
                </a>

                <a href="{{ route('providers.index') }}"
                   class="block w-full rounded-2xl border border-slate-700 px-4 py-4 text-center font-semibold text-slate-200 hover:bg-slate-800">
                    Open Providers
                </a>

                <a href="{{ route('facility.patients.index') }}"
                   class="block w-full rounded-2xl bg-amber-500 px-4 py-4 text-center font-bold text-slate-950 hover:bg-amber-400">
                    Add Medication / Supplement
                </a>

                <a href="{{ route('facility.readiness.index') }}"
                   class="block w-full rounded-2xl bg-green-700 px-4 py-4 text-center font-black text-white hover:bg-green-800">
                    ✅ State Inspection Readiness
                </a>
                          <a href="{{ route('facility.mar.index') }}"
   class="block w-full rounded-2xl bg-cyan-600 px-4 py-4 text-center font-black text-white hover:bg-cyan-700">
    💊 Medication Administration Record (MAR)
</a>

<a href="{{ route('facility.shifts.index') }}"
   class="block w-full rounded-2xl bg-purple-700 px-4 py-4 text-center font-black text-white hover:bg-purple-800">
    🕒 Shift Management
</a>

<a href="{{ route('caregiver.activity') }}"
   class="block w-full rounded-2xl bg-cyan-700 px-4 py-4 text-center font-black text-white hover:bg-cyan-800">
    👁 Caregiver Activity
</a>
<a href="{{ route('facility.compliance-documents.index') }}"
   class="block w-full rounded-2xl bg-cyan-700 px-4 py-4 text-center font-black text-white hover:bg-cyan-800">
    🗂 Compliance Vault
</a>
            </div>
        </div>

    </div>

</div>
</body>
</html>
