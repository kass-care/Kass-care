@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 space-y-6">

    {{-- HEADER --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Facility Billing
        </h1>
        <p class="text-gray-600 mt-2">
            Manage your facility subscription and access KASS Care features.
        </p>
    </div>

    {{-- FACILITY DETAILS --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">
            Facility Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div>
                <span class="text-gray-500">Name:</span>
                <div class="font-medium">
                    {{ $facility->name ?? 'N/A' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500">Plan:</span>
                <div class="font-medium">
                    {{ $facility->plan ?? 'No Plan' }}
                </div>
            </div>

            <div>
                <span class="text-gray-500">Status:</span>
                <div class="font-medium">
                    @if(isset($facility) && $facility->subscription_status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="text-gray-500">Ends At:</span>
                <div class="font-medium">
                    {{ $facility->subscription_ends_at ?? 'N/A' }}
                </div>
            </div>

        </div>
    </div>

    {{-- ACTION BOX --}}
    <div class="bg-white shadow rounded-xl p-6 text-center">

        @if(!isset($facility) || $facility->subscription_status !== 'active')

            <h3 class="text-xl font-bold text-red-600 mb-3">
                Subscription Required
            </h3>

            <p class="text-gray-600 mb-6">
                Activate your facility to unlock all features including providers, caregivers, visits, and care logs.
            </p>

            {{-- STRIPE FORM --}}
            <form action="{{ route('billing.subscribe') }}" method="POST">
                @csrf

                {{-- REAL STRIPE PRICE ID --}}
                <input type="hidden" name="price_id" value="price_1TDwRrIdKc8nV1TpxXO5SDqV">

                <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                    Subscribe Now
                </button>
            </form>

        @else

            <h3 class="text-xl font-bold text-green-600 mb-3">
                🎉 Your Facility is Active
            </h3>

            <p class="text-gray-600">
                You have full access to all KASS Care features.
            </p>

        @endif

    </div>

</div>
@endsection
