@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add Facility Caregiver</h1>
        <p class="text-gray-600 mt-1">Create a caregiver account for this facility</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-6">
        <form method="POST" action="{{ route('facility.caregivers.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password"
                       name="password"
                       required
                       class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition">
                    Save Caregiver
                </button>

                <a href="{{ route('facility.caregivers.index') }}"
                   class="inline-flex items-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
