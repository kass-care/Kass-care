@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 border border-indigo-900">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-widest text-indigo-200 font-bold mb-2">
                        KASS CARE
                    </p>
                    <h1 class="text-3xl font-extrabold text-white">
                        Write Clinical Note
                    </h1>
                    <p class="text-indigo-100 mt-2">
                        Add provider documentation for this visit.
                    </p>
                </div>

                <a href="{{ route('provider.notes.index') }}"
                   class="inline-flex items-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-indigo-800 shadow hover:bg-indigo-50 transition">
                    ← Back to Notes
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="mb-6 space-y-2">
                <p><strong>Visit ID:</strong> {{ $visit->id }}</p>
                <p><strong>Client:</strong> {{ $visit->client->name ?? 'N/A' }}</p>
                <p><strong>Caregiver:</strong> {{ $visit->caregiver->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $visit->visit_date ?? 'N/A' }}</p>
            </div>

            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="visit_id" value="{{ $visit->id }}">

                <div>
                    <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                        Clinical Note
                    </label>
                    <textarea
                        name="note"
                        id="note"
                        rows="8"
                        required
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('note') }}</textarea>
                </div>

                <div>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
