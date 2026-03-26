@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Schedule Facility Cycle</h1>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="mb-6 space-y-2 text-sm text-slate-700">
                <p><strong>Facility:</strong> {{ $cycle->facility->name ?? 'N/A' }}</p>
                <p><strong>Next Due Date:</strong> {{ $cycle->next_due_at ? $cycle->next_due_at->format('Y-m-d') : 'N/A' }}</p>
                <p><strong>Current Scheduled Date:</strong> {{ $cycle->scheduled_for ? $cycle->scheduled_for->format('Y-m-d') : 'None' }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($cycle->priority) }}</p>
            </div>

            <form method="POST" action="{{ route('provider.cycles.schedule.store', $cycle->id) }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Schedule Date</label>
                    <input type="date"
                           name="scheduled_for"
                           value="{{ old('scheduled_for', optional($cycle->scheduled_for)->format('Y-m-d')) }}"
                           class="w-full border rounded-lg p-3">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="4" class="w-full border rounded-lg p-3">{{ old('notes', $cycle->notes) }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">
                        Save Schedule
                    </button>

                    <a href="{{ route('provider.compliance') }}"
                       class="bg-gray-200 text-gray-800 px-5 py-3 rounded-lg hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
