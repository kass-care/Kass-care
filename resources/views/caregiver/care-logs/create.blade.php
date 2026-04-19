@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">New Care Log</h1>
            <p class="text-slate-600 mt-2">Select a visit and chart ADLs plus caregiver notes.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form action="{{ route('caregiver.care-logs.store') }}" method="POST" class="space-y-8">
                @csrf

                <div>
                    <label for="visit_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Visit</label>
                           <select name="visit_id" id="visit_id"
        class="w-full rounded-xl border-slate-300 focus:border-indigo-500">

    <option value="">Choose visit</option>

    @foreach($visits as $visit)
        <option value="{{ $visit->id }}"
            {{ old('visit_id', optional($selectedVisit)->id) == $visit->id ? 'selected' : '' }}>
            Visit #{{ $visit->id }}
            — Client: {{ $visit->client->name ?? 'N/A' }}
        </option>
    @endforeach

</select>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">ADL Charting</h2>
                          <div>
    <h2 class="text-lg font-semibold text-slate-900 mb-4">Vitals</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="blood_pressure" class="block text-sm font-semibold text-slate-700 mb-2">Blood Pressure</label>
            <input
                type="text"
                name="blood_pressure"
                id="blood_pressure"
                value="{{ old('blood_pressure') }}"
                placeholder="120/80"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="pulse" class="block text-sm font-semibold text-slate-700 mb-2">Pulse</label>
            <input
                type="number"
                name="pulse"
                id="pulse"
                value="{{ old('pulse') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="temperature" class="block text-sm font-semibold text-slate-700 mb-2">Temperature</label>
            <input
                type="number"
                step="0.1"
                name="temperature"
                id="temperature"
                value="{{ old('temperature') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="respiratory_rate" class="block text-sm font-semibold text-slate-700 mb-2">Respiratory Rate</label>
            <input
                type="number"
                name="respiratory_rate"
                id="respiratory_rate"
                value="{{ old('respiratory_rate') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="oxygen_saturation" class="block text-sm font-semibold text-slate-700 mb-2">Oxygen Saturation</label>
            <input
                type="number"
                name="oxygen_saturation"
                id="oxygen_saturation"
                value="{{ old('oxygen_saturation') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="blood_sugar" class="block text-sm font-semibold text-slate-700 mb-2">Blood Sugar</label>
            <input
                type="number"
                step="0.1"
                name="blood_sugar"
                id="blood_sugar"
                value="{{ old('blood_sugar') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="weight" class="block text-sm font-semibold text-slate-700 mb-2">Weight</label>
            <input
                type="number"
                step="0.1"
                name="weight"
                id="weight"
                value="{{ old('weight') }}"
                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>
    </div>
</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $adlOptions = ['Independent', 'Assisted', 'Dependent', 'Not Done'];
                        @endphp

                        @foreach (['bathing', 'dressing', 'toileting', 'feeding', 'mobility', 'grooming'] as $adl)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2 capitalize">{{ $adl }}</label>
                                <select name="adls[{{ $adl }}]" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select status</option>
                                    @foreach($adlOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-700 mb-2">Care Notes</label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="5"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Write care log notes here..."
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Save Care Log
                    </button>

                    <a href="{{ route('caregiver.care-logs.index') }}"
                       class="inline-flex items-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
