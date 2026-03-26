@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Add Facility</h1>

    <form method="POST" action="{{ route('admin.facilities.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Location</label>
            <input type="text" name="location" class="w-full border rounded p-2">
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Save
        </button>
    </form>

</div>
@endsection
