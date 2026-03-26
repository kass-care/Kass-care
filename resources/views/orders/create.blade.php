@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Create Order</h1>

    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6">
        <form method="POST" action="{{ route('orders.store') }}" class="space-y-5">
            @csrf

            @if(isset($selectedVisit) && $selectedVisit)
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <p class="text-sm text-blue-800">
                        Creating order from visit:
                        <strong>#{{ $selectedVisit->id }}</strong>
                        for
                        <strong>{{ $selectedVisit->client->name ?? 'Unknown Client' }}</strong>
                    </p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1">Client</label>
                <select name="client_id" class="w-full border rounded-lg p-3">
                    <option value="">-- Select Client --</option>

                    @forelse($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId ?? '') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @empty
                        <option value="">No clients available</option>
                    @endforelse
                </select>
            </div>

            <input type="hidden" name="visit_id" value="{{ old('visit_id', $selectedVisit->id ?? '') }}">

            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select name="type" class="w-full border rounded-lg p-3">
                    <option value="Lab" {{ old('type') == 'Lab' ? 'selected' : '' }}>Lab</option>
                    <option value="Imaging" {{ old('type') == 'Imaging' ? 'selected' : '' }}>Imaging</option>
                    <option value="Medication" {{ old('type') == 'Medication' ? 'selected' : '' }}>Medication</option>
                    <option value="Referral" {{ old('type') == 'Referral' ? 'selected' : '' }}>Referral</option>
                    <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-lg p-3" placeholder="Enter order title">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded-lg p-3" placeholder="Enter order description">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Destination</label>
                <input type="text" name="destination" value="{{ old('destination') }}" class="w-full border rounded-lg p-3" placeholder="Lab, hospital, pharmacy, specialist...">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Priority</label>
                    <select name="priority" class="w-full border rounded-lg p-3">
                        <option value="Routine" {{ old('priority') == 'Routine' ? 'selected' : '' }}>Routine</option>
                        <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="STAT" {{ old('priority') == 'STAT' ? 'selected' : '' }}>STAT</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg p-3">
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Ordered" {{ old('status') == 'Ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Ordered Date</label>
                    <input type="date" name="ordered_date" value="{{ old('ordered_date') }}" class="w-full border rounded-lg p-3">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Follow Up Date</label>
                    <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}" class="w-full border rounded-lg p-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Result Notes</label>
                <textarea name="result_notes" rows="4" class="w-full border rounded-lg p-3" placeholder="Enter result notes if available">{{ old('result_notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    Save Order
                </button>

                <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
