@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Create Care Log</h1>
            <p class="text-slate-600 mt-1">Document care provided for a visit.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-6">
            <form action="{{ route('care-logs.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client</label>
                        <select name="client_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Caregiver</label>
                        <select name="caregiver_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Select Caregiver</option>
                            @foreach($caregivers as $caregiver)
                                <option value="{{ $caregiver->id }}" {{ old('caregiver_id') == $caregiver->id ? 'selected' : '' }}>
                                    {{ $caregiver->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Linked Visit</label>
                        <select name="visit_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Visit (Optional)</option>
                            @foreach($visits as $visit)
                                <option value="{{ $visit->id }}" {{ old('visit_id') == $visit->id ? 'selected' : '' }}>
                                    Visit #{{ $visit->id }} -
                                    {{ $visit->client->name ?? 'No Client' }} -
                                    {{ $visit->visit_date ?? 'No Date' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                        <textarea name="notes" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">ADL Status</label>
                        <input type="text" name="adl_status" value="{{ old('adl_status') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Bathroom Assistance</label>
                        <input type="text" name="bathroom_assistance" value="{{ old('bathroom_assistance') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mobility Support</label>
                        <input type="text" name="mobility_support" value="{{ old('mobility_support') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Meal Notes</label>
                        <input type="text" name="meal_notes" value="{{ old('meal_notes') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Medication Notes</label>
                        <input type="text" name="medication_notes" value="{{ old('medication_notes') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check In Time</label>
                        <input type="time" name="check_in_time" value="{{ old('check_in_time') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check Out Time</label>
                        <input type="time" name="check_out_time" value="{{ old('check_out_time') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Charting Notes</label>
                        <textarea name="charting_notes" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('charting_notes') }}</textarea>
                    </div>

                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700">
                        Save Care Log
                    </button>

                    <a href="{{ route('provider.calendar') }}"
                       class="bg-slate-200 text-slate-700 px-6 py-3 rounded-lg hover:bg-slate-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
