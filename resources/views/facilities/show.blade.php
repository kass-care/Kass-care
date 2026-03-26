@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Facility Details</h1>

    <div class="bg-white shadow rounded-xl p-6">

        <div class="mb-4">
            <strong>Name:</strong>
            <p>{{ $facility->name }}</p>
        </div>

        <div class="mb-4">
            <strong>Address:</strong>
            <p>{{ $facility->address }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('facilities.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded">
                Back
            </a>
        </div>

    </div>

</div>
@endsection
