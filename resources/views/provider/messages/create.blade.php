@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6 border border-indigo-100">

        <h1 class="text-2xl font-bold mb-6">New Message</h1>

        <form method="POST" action="{{ route('provider.messages.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Select Facility</label>
                <select name="facility_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Choose facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Message</label>
                <textarea name="message"
                    rows="5"
                    class="w-full border rounded-lg px-3 py-2"
                    placeholder="Write your message..."></textarea>
            </div>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg">
                    Send Message
                </button>

                <a href="{{ route('provider.messages.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
