@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Facility Messages</h1>
            <p class="text-sm text-gray-500">
                Communication between facility staff and providers
            </p>
        </div>

        <a href="{{ route('facility.messages.create') }}"
           class="rounded-2xl bg-indigo-600 px-6 py-3 text-white font-bold shadow hover:bg-indigo-700">
            + New Message
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">

        @forelse($messages as $message)
            <div class="border-b border-gray-100 p-5 hover:bg-gray-50">

                <div class="flex justify-between items-start gap-4">
                    <div>
                        <div class="font-bold text-slate-900">
                            {{ $message->subject ?? 'No subject' }}
                        </div>

                        <div class="text-sm text-gray-600 mt-1">
                            To: {{ $message->provider->name ?? 'Provider' }}
                            @if($message->client)
                                • Patient: {{ $message->client->name }}
                            @endif
                        </div>

                        <div class="text-xs text-gray-400 mt-2">
                            {{ $message->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-block text-xs px-3 py-1 rounded-full
                            {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($message->priority) }}
                        </span>

                        <div class="mt-2 text-xs">
                            @if($message->replied_at)
                                <span class="text-green-600 font-semibold">Replied</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-700">
                    {{ \Illuminate\Support\Str::limit($message->message, 150) }}
                </div>

                @if($message->provider_reply)
                    <div class="mt-3 bg-green-50 border border-green-100 rounded-xl p-3 text-sm">
                        <span class="font-semibold text-green-700">Provider Reply:</span>
                        <div class="mt-1 text-gray-700">
                            {{ \Illuminate\Support\Str::limit($message->provider_reply, 150) }}
                        </div>
                    </div>
                @endif

            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                No messages yet.
            </div>
        @endforelse

    </div>
</div>
@endsection
