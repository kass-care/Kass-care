@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">

            <div class="mb-8">
                <p class="text-xs uppercase tracking-widest text-indigo-600 font-bold mb-2">
                    Caregiver Visit Documentation
                </p>
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Care Log Entry
                </h1>
                <p class="text-sm text-gray-600 mt-2">
                    Record ADLs, vitals, and care notes for this visit.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Client</p>
                    <p class="mt-2 text-lg font-bold text-gray-900">
                        {{ $visit->client->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Visit ID</p>
                    <p class="mt-2 text-lg font-bold text-gray-900">
                        #{{ $visit->id }}
                    </p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-red-700">
                    <p class="font-bold mb-2">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('caregiver.carelog.store', $visit->id) }}">
                @csrf

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">ADL Charting</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Tasks Completed
                            </label>
                            <input
                                type="text"
                                name="adls[tasks_completed]"
                                value="{{ old('adls.tasks_completed') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Bathing, feeding, medication reminder, mobility support..."
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Client Condition
                            </label>
                            <input
                                type="text"
                                name="adls[client_condition]"
                                value="{{ old('adls.client_condition') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Stable, tired, declined, improved..."
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Follow Up Concerns
                            </label>
                            <input
                                type="text"
                                name="adls[follow_up_concerns]"
                                value="{{ old('adls.follow_up_concerns') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Any issue that needs provider/admin attention"
                            >
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Vitals</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Blood Pressure
                            </label>
                            <input
                                type="text"
                                name="vitals[blood_pressure]"
                                value="{{ old('vitals.blood_pressure') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="120/80"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Pulse
                            </label>
                            <input
                                type="text"
                                name="vitals[pulse]"
                                value="{{ old('vitals.pulse') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="72 bpm"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Temperature
                            </label>
                            <input
                                type="text"
                                name="vitals[temperature]"
                                value="{{ old('vitals.temperature') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="98.6 F"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Respiratory Rate
                            </label>
                            <input
                                type="text"
                                name="vitals[respiratory_rate]"
                                value="{{ old('vitals.respiratory_rate') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="16"
                            >
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Oxygen Saturation
                            </label>
                            <input
                                type="text"
                                name="vitals[oxygen_saturation]"
                                value="{{ old('vitals.oxygen_saturation') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="98%"
                            >
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Care Notes</h2>

                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea
                        name="notes"
                        rows="5"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Write care notes here..."
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-wrap gap-3 justify-between">
                    <a href="{{ route('caregiver.dashboard') }}"
                       class="inline-flex items-center rounded-xl bg-gray-200 px-5 py-3 text-sm font-bold text-gray-800 hover:bg-gray-300">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow hover:bg-indigo-700">
                        Save Care Log
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
