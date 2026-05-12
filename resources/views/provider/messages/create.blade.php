@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6 border border-indigo-100">

        <h1 class="text-2xl font-bold mb-6">New Message</h1>

        <form method="POST" action="{{ route('provider.messages.store') }}">
            @csrf
                    @csrf

<div class="mb-4">
    <label class="block text-sm font-black text-slate-700 mb-2">
        Send To Provider
    </label>

    <select
        name="recipient_provider_id"
        required
        class="w-full rounded-2xl border border-indigo-200 px-4 py-3 font-semibold shadow-sm focus:border-indigo-500 fo>
    >
        <option value="">Select provider</option>

        @foreach($providers as $provider)
            <option value="{{ $provider->id }}">
                {{ $provider->name }}
            </option>
        @endforeach
    </select>
</div>
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
              <div class="mt-8 flex flex-col gap-4 md:flex-row">

    <button
        type="submit"
        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-700 via-blue-600 to-cyan-500 px-8 py-4 text-lg font-black text-white shadow-2xl transition hover:scale-[1.01]"
    >
        ✉️ Send Provider Message
    </button>

    <a
        href="{{ route('provider.messages.index') }}"
        class="inline-flex items-center justify-center rounded-2xl bg-slate-200 px-8 py-4 text-lg font-black text-slate-700 hover:bg-slate-300"
    >
        Cancel
    </a>

</div>
        </form>

    </div>
</div>
@endsection
