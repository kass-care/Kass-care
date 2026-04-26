@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('facility.messages.index') }}"
           class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
            ← Back to Messages
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-700 to-blue-600 px-8 py-6 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-100 font-semibold">
                KassCare Messaging
            </p>
            <h1 class="mt-2 text-3xl font-black">Message Provider</h1>
            <p class="mt-2 text-sm text-indigo-100">
                Send a patient-related message from the facility to the provider.
            </p>
        </div>

        <form method="POST" action="{{ route('facility.messages.store') }}" class="p-8 space-y-6">
            @csrf

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-4">
                    <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Provider</label>
                <select name="provider_id" required
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    <option value="">Select provider</option>
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                            {{ $provider->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Patient / Client</label>
                <select name="client_id"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    <option value="">General facility message</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Priority</label>
                <select name="priority"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3">
                    <option value="normal">Normal</option>
                    <option value="urgent">Urgent 🚨</option>
                    <option value="follow_up">Follow Up</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Subject</label>
                <input type="text"
                       name="subject"
                       value="{{ old('subject') }}"
                       class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                       placeholder="Example: Medication concern, behavior change, refill request">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Message</label>
                <textarea name="message"
                          rows="7"
                          required
                          class="w-full rounded-2xl border border-slate-300 px-4 py-3"
                          placeholder="Write message to provider...">{{ old('message') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                        class="rounded-2xl bg-indigo-600 px-6 py-3 font-bold text-white shadow hover:bg-indigo-700">
                    Send Message
                </button>

                <a href="{{ route('facility.messages.index') }}"
                   class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-center font-bold text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
