@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 text-center">

    <h1 class="text-2xl font-bold mb-4">Subscription Required</h1>

    <p class="mb-6 text-gray-600">
        Your facility does not have an active subscription.
    </p>

    <a href="{{ route('billing.index') }}"
       class="bg-indigo-600 text-white px-6 py-3 rounded-lg">
        Go to Billing
    </a>

</div>
@endsection
