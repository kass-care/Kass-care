@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">

    <!-- BACK -->
    <div class="mb-6">
        <a href="{{ route('provider.messages.index') }}"
           class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
            ← Back to Messages
        </a>
    </div>

    <!-- HEADER -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-indigo-700 px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black">
                    {{ $message->subject ?? 'Provider Message' }}
                </h1>

                <p class="mt-1 text-sm text-indigo-100">
                    {{ $message->facility->name ?? 'Facility' }}
                    @if($message->client)
                        • {{ $message->client->name }}
                    @endif
                </p>
            </div>

            <span class="px-3 py-1 rounded-full text-xs font-bold
                {{ $message->priority === 'urgent'
                    ? 'bg-red-500 text-white animate-pulse'
                    : 'bg-white text-indigo-700' }}">
                {{ strtoupper($message->priority ?? 'normal') }}
            </span>
        </div>
    </div>

    <!-- CHAT AREA -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">

        <!-- FACILITY MESSAGE -->
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                F
            </div>

            <div class="max-w-md">
                <div class="text-xs text-gray-500 mb-1">
                    Facility
                </div>

                <div class="bg-indigo-50 border border-indigo-200 rounded-2xl px-5 py-3 text-gray-800 shadow-sm">
                    {{ $message->message }}
                </div>
            </div>
        </div>

        <!-- PROVIDER REPLY -->
        @if($message->provider_reply)
            <div class="flex items-start justify-end gap-3">
                <div class="max-w-md text-right">
                    <div class="text-xs text-gray-500 mb-1">
                        You
                    </div>

                    <div class="bg-green-100 border border-green-200 rounded-2xl px-5 py-3 text-gray-800 shadow-sm">
                        {{ $message->provider_reply }}
                    </div>
                </div>

                <div class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                    P
                </div>
            </div>
        @endif

    </div>

    <!-- REPLY BOX -->
    @if(!$message->provider_reply)
        <div class="mt-6 bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('provider.messages.reply', $message) }}">
                @csrf

                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Reply to Facility
                </label>

                <textarea name="provider_reply"
                          rows="4"
                          required
                          class="w-full rounded-2xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                          placeholder="Type your response..."></textarea>

                <div class="flex justify-end">
                    <button type="submit"
                            class="mt-4 rounded-2xl bg-indigo-600 px-6 py-3 text-white font-bold shadow hover:bg-indigo-700">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection
