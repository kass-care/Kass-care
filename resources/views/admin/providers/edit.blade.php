@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Provider</h1>
                <p class="text-gray-600 mt-1">Update provider account details.</p>
            </div>

            <a href="{{ route('admin.providers.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium">
                Back
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-100 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.providers.update', $provider->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $provider->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $provider->email) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                       placeholder="Leave blank to keep current password">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Facility</label>
                <select name="facility_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}"
                            {{ old('facility_id', $provider->facility_id) == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold shadow-sm">
                    Update Provider
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
