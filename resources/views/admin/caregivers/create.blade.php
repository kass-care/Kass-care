@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Add Caregiver</h1>

    <form method="POST" action="{{ route('admin.caregivers.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" required
                   class="w-full border rounded-lg px-4 py-2 mt-1">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" required
                   class="w-full border rounded-lg px-4 py-2 mt-1">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" required
                   class="w-full border rounded-lg px-4 py-2 mt-1">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('admin.caregivers.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                Back
            </a>

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Save Caregiver
            </button>
        </div>

    </form>

</div>
@endsection
