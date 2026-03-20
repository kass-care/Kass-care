@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Compliance Dashboard</h1>

        <a href="{{ route('provider.dashboard') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <p class="text-gray-600 mb-4">
            This dashboard highlights missing documentation and compliance risks.
        </p>

        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg">
            Some visits are missing care logs. Please review and complete documentation.
        </div>
    </div>

</div>
@endsection
