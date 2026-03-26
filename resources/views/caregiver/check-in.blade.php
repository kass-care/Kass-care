@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6 border border-indigo-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold mb-2 text-gray-900">Caregiver Check-In</h1>
                <p class="text-gray-600">
                    Client: <strong>{{ $visit->client->name ?? 'N/A' }}</strong>
                </p>
            </div>

            <a href="{{ route('caregiver.dashboard') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('caregiver.checkin.store', $visit->id) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium mb-2 text-gray-700">Visit ID</label>
                <input type="text"
                       value="{{ $visit->id }}"
                       readonly
                       class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
            </div>

            <div>
                <label class="block font-medium mb-2 text-gray-700">Check-In Time</label>
                <input type="datetime-local"
                       name="check_in_time"
                       value="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full border rounded-lg px-3 py-2 text-gray-700">
            </div>

            <div class="flex items-center gap-4 pt-2">
                 <button type="submit"
        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-200">
    ✅ Save Check-In
</button>
                <a href="{{ route('caregiver.dashboard') }}"
                   class="text-gray-700 font-medium hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
