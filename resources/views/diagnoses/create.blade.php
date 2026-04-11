@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-widest text-indigo-600 font-semibold">
                KASS CARE
            </p>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">
                Add Diagnosis
            </h1>
            <p class="text-sm text-gray-600 mt-2">
                Record a diagnosis for {{ $client->name }}.
            </p>
        </div>

        <a href="{{ route('provider.patients.workspace', $client->id) }}"
           class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
            Back to Client
        </a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <form action="{{ route('diagnoses.store', $client) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Diagnosis Name
                </label>
                <input type="text"
                       name="diagnosis_name"
                       value="{{ old('diagnosis_name') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       required>
                @error('diagnosis_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    ICD Code
                </label>
                <input type="text"
                       name="icd_code"
                       value="{{ old('icd_code') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('icd_code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="status"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    <option value="">Select status</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="chronic" {{ old('status') == 'chronic' ? 'selected' : '' }}>Chronic</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes"
                          rows="5"
                          class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-5 py-3 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                    Save Diagnosis
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
