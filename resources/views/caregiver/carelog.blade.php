@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-indigo-600 font-semibold">KASS CARE</p>
                <h1 class="text-3xl font-bold text-slate-900 mt-2">Care Log Entry</h1>
                <p class="text-slate-600 mt-2">Complete ADLs, vitals, and notes for this visit.</p>
            </div>

            <a href="{{ route('caregiver.dashboard') }}"
               class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                ← Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-4 shadow-sm">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-500 p-6 text-white">
                <p class="text-indigo-100 text-xs uppercase tracking-[0.2em]">Visit Client</p>
                <h2 class="text-2xl font-bold mt-2">{{ $visit->client->name ?? 'N/A' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                    <div class="bg-white/10 rounded-xl px-4 py-3">
                        <span class="block text-indigo-100 text-xs uppercase">Visit ID</span>
                        <span class="font-semibold">#{{ $visit->id }}</span>
                    </div>
                    <div class="bg-white/10 rounded-xl px-4 py-3">
                        <span class="block text-indigo-100 text-xs uppercase">Current Status</span>
                        <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $visit->status ?? 'N/A')) }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('caregiver.carelog.store', $visit->id) }}" method="POST" class="p-8 space-y-8">
                @csrf

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">ADL Charting</h3>

                    @php
                        $adlOptions = ['Independent', 'Assisted', 'Dependent', 'Not Done'];
                        $adls = ['bathing', 'dressing', 'toileting', 'feeding', 'mobility', 'grooming'];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($adls as $adl)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2 capitalize">
                                    {{ $adl }}
                                </label>
                                <select name="adls[{{ $adl }}]"
                                        class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                    <option value="">Select status</option>
                                    @foreach($adlOptions as $option)
                                        <option value="{{ $option }}" {{ old("adls.$adl") == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Vitals</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Temperature</label>
                            <input type="text"
                                   name="vitals[temperature]"
                                   value="{{ old('vitals.temperature') }}"
                                   placeholder="e.g. 98.6°F"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Blood Pressure</label>
                            <input type="text"
                                   name="vitals[blood_pressure]"
                                   value="{{ old('vitals.blood_pressure') }}"
                                   placeholder="e.g. 120/80"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pulse</label>
                            <input type="text"
                                   name="vitals[pulse]"
                                   value="{{ old('vitals.pulse') }}"
                                   placeholder="e.g. 72 bpm"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Respiration</label>
                            <input type="text"
                                   name="vitals[respiration]"
                                   value="{{ old('vitals.respiration') }}"
                                   placeholder="e.g. 16/min"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Oxygen Saturation</label>
                            <input type="text"
                                   name="vitals[oxygen_saturation]"
                                   value="{{ old('vitals.oxygen_saturation') }}"
                                   placeholder="e.g. 98%"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Blood Sugar</label>
                            <input type="text"
                                   name="vitals[blood_sugar]"
                                   value="{{ old('vitals.blood_sugar') }}"
                                   placeholder="e.g. 110 mg/dL"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-700 mb-2">Care Notes</label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="6"
                        placeholder="Describe care provided, client response, concerns, and anything important..."
                        class="w-full rounded-2xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white hover:bg-indigo-700 transition shadow">
                        Save Care Log
                    </button>

                    <a href="{{ route('caregiver.dashboard') }}"
                       class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
