@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Edit Facility</h1>

    <form method="POST" action="{{ route('admin.facilities.update', $facility) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ $facility->name }}"
                   class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Location</label>
            <input type="text" name="location" value="{{ $facility->location }}"
                   class="w-full border rounded p-2">
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>

</div>
@endsection
