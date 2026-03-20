@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Create Care Log</h1>
            <p class="text-slate-600 mt-2">Document visit care details.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-6">
            <form method="POST" action="{{ auth()->check() && auth()->user()->role === 'provider' ? route('provider.care-logs.store') : route('care-logs.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Client</label>
                        <select name="client_id" class="w-full rounded-lg border-slate-300">
                            <option value="">Select client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', optional($selectedVisit->client ?? null)->id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Caregiver</label>
                        <select name="caregiver_id" class="w-full rounded-lg border-slate-300">
                            <option value="">Select caregiver</option>
                            @foreach($caregivers as $caregiver)
                                <option value="{{ $caregiver->id }}"
                                    {{ old('caregiver_id', optional($selectedVisit->caregiver ?? null)->id) == $caregiver->id ? 'selected' : '' }}>
                                    {{ $caregiver->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Visit</label>
                    <select name="visit_id" class="w-full rounded-lg border-slate-300">
                        <option value="">Select visit</option>
                        @foreach($visits as $visit)
                            <option value="{{ $visit->id }}"
                                {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}>
                                Visit #{{ $visit->id }} - {{ $visit->client->name ?? 'No Client' }} - {{ $visit->visit_date ?? $visit->scheduled_at ?? 'No Date' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ADL Status</label>
                    <input type="text" name="adl_status" value="{{ old('adl_status') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                    <textarea name="notes" rows="4" class="w-full rounded-lg border-slate-300">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meal Notes</label>
                    <textarea name="meal_notes" rows="3" class="w-full rounded-lg border-slate-300">{{ old('meal_notes') }}</textarea>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Medication Notes</label>
                    <textarea name="medication_notes" rows="3" class="w-full rounded-lg border-slate-300">{{ old('medication_notes') }}</textarea>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bathroom Assistance</label>
                    <input type="text" name="bathroom_assistance" value="{{ old('bathroom_assistance') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mobility Support</label>
                    <input type="text" name="mobility_support" value="{{ old('mobility_support') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Charting Notes</label>
                    <textarea name="charting_notes" rows="4" class="w-full rounded-lg border-slate-300">{{ old('charting_notes') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check In Time</label>
                        <input type="datetime-local" name="check_in_time" value="{{ old('check_in_time') }}" class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Check Out Time</label>
                        <input type="datetime-local" name="check_out_time" value="{{ old('check_out_time') }}" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude') }}" class="w-full rounded-lg border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude') }}" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                        Save Care Log
                    </button>

                    <a href="{{ auth()->check() && auth()->user()->role === 'provider' ? route('provider.calendar') : route('care-logs.index') }}"
                       class="bg-slate-200 text-slate-800 px-6 py-3 rounded-lg hover:bg-slate-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
