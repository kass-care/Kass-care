@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-black text-slate-900">Provider Messages</h1>
        <p class="text-sm text-gray-500">Messages from facilities and nurses.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        @forelse($messages as $message)
            <a href="{{ route('provider.messages.show', $message) }}"
               class="block border-b border-gray-100 p-5 hover:bg-gray-50">
                <div class="flex justify-between gap-4">
                    <div>
                        <div class="font-bold text-slate-900">
                            {{ $message->subject ?? 'No subject' }}
                        </div>

                        <div class="text-sm text-gray-600 mt-1">
                            From: {{ $message->facility->name ?? 'Facility' }}
                            @if($message->client)
                                • Patient: {{ $message->client->name }}
                            @endif
                        </div>

                        <div class="text-sm text-gray-700 mt-3">
                            {{ \Illuminate\Support\Str::limit($message->message, 160) }}
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-block text-xs px-3 py-1 rounded-full
                            {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($message->priority ?? 'normal') }}
                        </span>

                        <div class="mt-2 text-xs">
                            @if($message->replied_at)
                                <span class="text-green-600 font-semibold">Replied</span>
                            @elseif($message->read_at)
                                <span class="text-blue-600 font-semibold">Read</span>
                            @else
                                <span class="inline-flex items-center bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                    NEW
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-8 text-center text-gray-500">
                No messages yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
