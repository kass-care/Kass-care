@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <div class="rounded-3xl border border-indigo-100 bg-white shadow-2xl overflow-hidden">

        <div class="bg-indigo-700 px-8 py-6">
            <p class="text-xs uppercase tracking-[0.35em] font-black text-indigo-100">
                KASS CARE PROVIDER NETWORK
            </p>

            <h1 class="mt-2 text-3xl font-black text-white">
                Provider Communication Center
            </h1>

            <p class="mt-2 text-indigo-100 text-sm">
                Securely communicate with providers across the KassCare ecosystem.
            </p>
        </div>

        <div class="p-8">
            <form method="POST" action="{{ route('provider.messages.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Send To Provider
                        </label>

                        <select
                            name="recipient_provider_id"
                            required
                            class="w-full rounded-2xl border border-indigo-200 px-4 py-3 font-semibold shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        >
                            <option value="">Select provider</option>

                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">
                                    {{ $provider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Related Facility Optional
                        </label>

                        <select
                            name="facility_id"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold shadow-sm"
                        >
                            <option value="">Select facility</option>

                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}">
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-black text-slate-700 mb-2">
                        Related Patient Optional
                    </label>

                    <select
                        name="client_id"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold shadow-sm"
                    >
                        <option value="">Select patient</option>

                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->name ?? ('Patient #' . $client->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-black text-slate-700 mb-2">
                        Subject
                    </label>

                    <input
                        type="text"
                        name="subject"
                        placeholder="Example: Medication clarification"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold shadow-sm"
                    >
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-black text-slate-700 mb-2">
                        Priority
                    </label>

                    <select
                        name="priority"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 font-semibold shadow-sm"
                    >
                        <option value="normal">Normal</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High Priority</option>
                    </select>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-black text-slate-700 mb-2">
                        Provider Message
                    </label>

                    <textarea
                        name="message"
                        rows="8"
                        required
                        placeholder="Write your provider communication here..."
                        class="w-full rounded-2xl border border-slate-200 px-4 py-4 font-semibold shadow-sm"
                    ></textarea>
                </div>

                <div class="mt-8 flex flex-col gap-4 md:flex-row">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-700 px-8 py-4 text-lg font-black text-white shadow-xl hover:bg-indigo-800"
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

</div>
@endsection
