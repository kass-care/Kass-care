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
                    <select name="visit_id" id="visit_id" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Choose visit</option>
                        @foreach($visits as $visit)
                            <option value="{{ $visit->id }}" {{ old('visit_id', $selectedVisit) == $visit->id ? 'selected' : '' }}>
                                Visit #{{ $visit->id }} - Client: {{ $visit->client->name ?? 'N/A' }} - Caregiver: {{ $visit->caregiver->name ?? 'N/A' }} - Date: {{ $visit->visit_date ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">ADL Charting</h2>

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
