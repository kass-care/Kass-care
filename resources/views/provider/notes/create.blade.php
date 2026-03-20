@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">New Provider Note</h1>
            <p class="text-slate-600 mt-2">Select a visit, then write the clinical note.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

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
            <form action="{{ route('provider.notes.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="visit_id" class="block text-sm font-semibold text-slate-700 mb-2">
                        Select Visit
                    </label>

                    <select
                        name="visit_id"
                        id="visit_id"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="">Choose visit</option>
                        @foreach($visits as $visit)
                            <option value="{{ $visit->id }}"
                                {{ old('visit_id', $selectedVisit) == $visit->id ? 'selected' : '' }}>
                                Visit #{{ $visit->id }}
                                - Client: {{ $visit->client->name ?? 'N/A' }}
                                - Caregiver: {{ $visit->caregiver->name ?? 'N/A' }}
                                - Date: {{ $visit->visit_date ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-2 text-xs text-slate-500">
                        A provider note is attached to a visit. The client and caregiver come from that visit automatically.
                    </p>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">
                        Note Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Reviewed" {{ old('status') == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="Signed" {{ old('status') == 'Signed' ? 'selected' : '' }}>Signed</option>
                    </select>
                </div>

                <div>
                    <label for="note" class="block text-sm font-semibold text-slate-700 mb-2">
                        Clinical Note
                    </label>

                    <textarea
                        name="note"
                        id="note"
                        rows="10"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Write provider note here..."
                        required
                    >{{ old('note') }}</textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                    >
                        Save Note
                    </button>

                    <a
                        href="{{ route('provider.calendar') }}"
                        class="inline-flex items-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
