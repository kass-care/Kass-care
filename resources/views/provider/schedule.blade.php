@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Provider Schedule</h1>

        <div class="flex items-center gap-4">
            <a href="{{ route('provider.dashboard') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-2xl p-8 mb-8">
        <h2 class="text-2xl font-semibold mb-2">Schedule Workspace</h2>
        <p class="text-gray-600">
            This is your provider scheduling area. The dashboard button is connected.
        </p>
    </div>

</div>
@endsection
