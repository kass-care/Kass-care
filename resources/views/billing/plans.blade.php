@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-8 text-center">Choose Your Plan</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white shadow-lg rounded-xl p-6 text-center border">
            <h2 class="text-xl font-semibold mb-2">Starter</h2>
            <p class="text-gray-500 mb-4">1 Facility</p>
            <p class="text-3xl font-bold mb-6">$29<span class="text-sm">/month</span></p>

            <a href="{{ route('billing.checkout', 'starter') }}"
               class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Choose Starter
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 text-center border-2 border-blue-500">
            <h2 class="text-xl font-semibold mb-2">Growth</h2>
            <p class="text-gray-500 mb-4">3 Facilities</p>
            <p class="text-3xl font-bold mb-6">$79<span class="text-sm">/month</span></p>

            <a href="{{ route('billing.checkout', 'growth') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Choose Growth
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-6 text-center border">
            <h2 class="text-xl font-semibold mb-2">Enterprise</h2>
            <p class="text-gray-500 mb-4">Unlimited Facilities</p>
            <p class="text-3xl font-bold mb-6">$199<span class="text-sm">/month</span></p>

            <a href="{{ route('billing.checkout', 'enterprise') }}"
               class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                Choose Enterprise
            </a>
        </div>

    </div>
</div>
@endsection
