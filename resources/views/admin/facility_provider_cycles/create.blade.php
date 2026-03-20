@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Create Facility Provider Cycle</h1>

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
            <form method="POST" action="{{ route('admin.facility-provider-cycles.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Facility</label>
                        <select name="facility_id" class="w-full border rounded-lg p-3">
                            <option value="">Choose facility</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Provider</label>
                        <select name="provider_id" class="w-full border rounded-lg p-3">
                            <option value="">Choose provider</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name ?? $provider->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Review Interval (Days)</label>
                        <input type="number" name="review_interval_days" value="{{ old('review_interval_days', 60) }}"
                               class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Next Due Date</label>
                        <input type="date" name="next_due_at" value="{{ old('next_due_at') }}"
                               class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Scheduled For</label>
                        <input type="date" name="scheduled_for" value="{{ old('scheduled_for') }}"
                               class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Status</label>
                        <select name="status" class="w-full border rounded-lg p-3">
                            @foreach(['current', 'due_soon', 'due', 'scheduled', 'completed', 'overdue'] as $status)
                                <option value="{{ $status }}" {{ old('status', 'current') === $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Priority</label>
                        <select name="priority" class="w-full border rounded-lg p-3">
                            @foreach(['normal', 'high', 'critical'] as $priority)
                                <option value="{{ $priority }}" {{ old('priority', 'normal') === $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="4" class="w-full border rounded-lg p-3">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-3 rounded-lg hover:bg-indigo-700">
                        Save Cycle
                    </button>

                    <a href="{{ route('admin.facility-provider-cycles.index') }}"
                       class="bg-gray-200 text-gray-800 px-5 py-3 rounded-lg hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
