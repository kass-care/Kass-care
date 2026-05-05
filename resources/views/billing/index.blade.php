@extends('layouts.app')

@section('content')
@php
    $role = $user->role ?? null;

    $visiblePlans = collect($plans)->filter(function ($plan, $key) use ($role) {
        if ($role === 'admin') {
            return $key === 'facility';
        }

        if ($role === 'provider') {
            return in_array($key, ['provider_solo', 'provider_pro']);
        }

        if ($role === 'super_admin') {
            return true;
        }

        return false;
    });
@endphp

<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">
                        {{ $role === 'provider' ? 'Provider Billing' : 'Facility Billing' }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-600">
                        Manage your subscription and unlock the correct KASSCare features for your role.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">
                    Back to Dashboard
                </a>
            </div>
        </div>

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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-8">
            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Account</p>
                <h2 class="mt-3 text-xl font-black text-slate-900">{{ $user->name ?? 'N/A' }}</h2>
                <p class="mt-2 text-sm text-slate-500">{{ $user->email ?? 'N/A' }}</p>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Current Plan</p>
                <h2 class="mt-3 text-xl font-black text-slate-900">
                    {{ $currentPlan !== 'none' ? ucwords(str_replace('_', ' ', $currentPlan)) : 'No Plan' }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Facility limit:
                    <span class="font-semibold text-slate-700">{{ $user->facility_limit ?? 0 }}</span>
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
                <p class="mt-2 text-sm text-slate-500">Renewal tracking for billing continuity.</p>
            </div>
        </div>

        @if(($subscriptionStatus ?? 'inactive') !== 'active')
            <div class="mb-8 rounded-3xl border border-amber-200 bg-amber-50 px-6 py-5">
                <h3 class="text-lg font-black text-amber-900">Subscription Required</h3>
                <p class="mt-2 text-sm text-amber-800">
                    Activate your subscription to unlock your allowed KASSCare features.
                </p>
            </div>
        @endif

        <div class="mb-4">
            <h3 class="text-2xl font-black text-slate-900">Choose Your Plan</h3>
            <p class="mt-1 text-sm text-slate-600">
                @if($role === 'admin')
                    Facility admins only see the Facility plan.
                @elseif($role === 'provider')
                    Providers only see Provider Solo and Provider Pro.
                @else
                    Select the correct plan.
                @endif
            </p>
        </div>

        @if($visiblePlans->isEmpty())
            <div class="rounded-3xl bg-white border border-slate-200 p-8 text-slate-700">
                No billing plans are available for your role.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($visiblePlans as $key => $plan)
                    @php $isCurrent = $currentPlan === $key; @endphp

                    <div class="relative rounded-3xl border shadow-sm p-6 bg-white
                        {{ $isCurrent ? 'border-indigo-500 ring-2 ring-indigo-100' : 'border-slate-200' }}">

                        @if($isCurrent)
                            <div class="absolute top-4 right-4 rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">
                                Current Plan
                            </div>
                        @endif

                        <h4 class="text-xl font-black text-slate-900">{{ $plan['name'] }}</h4>
                        <p class="mt-2 text-3xl font-black text-indigo-700">{{ $plan['price_label'] }}</p>

                        <p class="mt-2 text-sm text-slate-500">
                            Facility limit:
                            <span class="font-semibold text-slate-700">{{ $plan['facility_limit'] }}</span>
                        </p>

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
                                            class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white hover:bg-indigo-700">
                                        Subscribe {{ $plan['name'] }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
