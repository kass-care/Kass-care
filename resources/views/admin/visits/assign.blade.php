@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Assign Caregiver</h1>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="mb-6 space-y-2 text-sm text-slate-700">
                <p><strong>Visit ID:</strong> {{ $visit->id }}</p>
                <p><strong>Client:</strong> {{ $visit->client->name ?? 'N/A' }}</p>
                <p><strong>Current Caregiver:</strong> {{ $visit->caregiver->name ?? 'Unassigned' }}</p>
                <p><strong>Status:</strong> {{ $visit->status ?? 'N/A' }}</p>
            </div>

            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.visits.assign.update', $visit->id) }}">
                @csrf
                @method('PATCH')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Select Caregiver
                    </label>

                    <select name="caregiver_id" class="w-full border border-slate-300 rounded-lg p-3">
                        <option value="">Choose caregiver</option>
                        @foreach($caregivers as $caregiver)
                            <option value="{{ $caregiver->id }}"
                                {{ old('caregiver_id', $visit->caregiver_id) == $caregiver->id ? 'selected' : '' }}>
                                {{ $caregiver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">
                        Save Assignment
                    </button>

                    <a href="{{ route('admin.visits.index') }}"
                       class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
