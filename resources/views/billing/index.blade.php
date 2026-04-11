@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Facility Billing</h1>
                    <p class="mt-2 text-sm text-slate-600">
                        Manage your facility subscription and unlock KASS Care features safely.
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Subscription Overview --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-8">
            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Account</p>
                <h2 class="mt-3 text-xl font-black text-slate-900">
                    {{ $user->name ?? 'N/A' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    {{ $user->email ?? 'N/A' }}
                </p>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Current Plan</p>
                <h2 class="mt-3 text-xl font-black text-slate-900">
                    {{ $currentPlan !== 'none' ? ucfirst($currentPlan) : 'No Plan' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Facility limit:
                    <span class="font-semibold text-slate-700">
                        {{ $user->facility_limit ?? 0 }}
                    </span>
                </p>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Subscription Status</p>
                <h2 class="mt-3 text-xl font-black
                    @if(($subscriptionStatus ?? 'inactive') === 'active') text-emerald-600
                    @elseif(($subscriptionStatus ?? 'inactive') === 'pending') text-amber-600
                    @else text-rose-600
                    @endif">
                    {{ ucfirst($subscriptionStatus ?? 'inactive') }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    @if(($subscriptionStatus ?? 'inactive') === 'active')
                        Your subscription is active.
                    @elseif(($subscriptionStatus ?? 'inactive') === 'pending')
                        Payment is in progress.
                    @else
                        Subscription required to unlock protected features.
                    @endif
                </p>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Ends At</p>
                <h2 class="mt-3 text-xl font-black text-slate-900">
                    {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('M d, Y') : 'N/A' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Renewal tracking for billing continuity.
                </p>
            </div>
        </div>

        {{-- Notice --}}
        @if(($subscriptionStatus ?? 'inactive') !== 'active')
            <div class="mb-8 rounded-3xl border border-amber-200 bg-amber-50 px-6 py-5">
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-black text-amber-900">Subscription Required</h3>
                    <p class="text-sm text-amber-800">
                        Activate your facility subscription to unlock providers, caregivers, visits, care logs, and protected operational workflows.
                    </p>
                </div>
            </div>
        @endif

        {{-- Plan Cards --}}
        <div class="mb-4">
            <h3 class="text-2xl font-black text-slate-900">Choose Your Plan</h3>
            <p class="mt-1 text-sm text-slate-600">
                Select the plan that matches your facility growth stage.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach($plans as $key => $plan)
                @php
                    $isCurrent = $currentPlan === $key;
                @endphp

                <div class="relative rounded-3xl border shadow-sm p-6 bg-white
                    {{ $isCurrent ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-slate-200' }}">

                    @if($isCurrent)
                        <div class="absolute top-4 right-4 rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">
                            Current Plan
                        </div>
                    @endif

                    <div class="pr-16">
                        <h4 class="text-xl font-black text-slate-900">{{ $plan['name'] }}</h4>
                        <p class="mt-2 text-3xl font-black text-indigo-700">{{ $plan['price_label'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">
                            Facility limit:
                            <span class="font-semibold text-slate-700">{{ $plan['facility_limit'] }}</span>
                        </p>
                    </div>

                    <div class="mt-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Included Features</p>
                        <ul class="mt-3 space-y-2">
                            @foreach($plan['features'] as $feature)
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="mt-0.5 text-emerald-600">•</span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-6">
                        @if($isCurrent && ($subscriptionStatus ?? 'inactive') === 'active')
                            <div class="w-full rounded-2xl bg-emerald-100 px-4 py-3 text-center text-sm font-bold text-emerald-800">
                                Active Plan
                            </div>
                        @else
                            <form method="POST" action="{{ route('billing.subscribe') }}">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $key }}">

                                <button type="submit"
                                        class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white hover:bg-indigo-700 transition">
                                    Subscribe {{ $plan['name'] }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
