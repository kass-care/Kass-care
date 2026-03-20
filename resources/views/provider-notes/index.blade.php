@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-6">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Clinical Notes</h1>
                <p class="text-slate-500">All your patient notes</p>
            </div>

            <a href="{{ route('provider-notes.create') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700">
               + New Note
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-100 text-sm text-slate-600">
                    <tr>
                        <th class="p-4">Client</th>
                        <th class="p-4">Visit</th>
                        <th class="p-4">Created</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notes as $note)
                    <tr class="border-t">
                        <td class="p-4">{{ $note->client->name ?? '-' }}</td>
                        <td class="p-4">#{{ $note->visit_id ?? '-' }}</td>
                        <td class="p-4">{{ $note->created_at->format('M d, Y') }}</td>
                        <td class="p-4">
                            @if($note->signed_at)
                                <span class="text-green-600 font-semibold">Signed</span>
                            @else
                                <span class="text-yellow-600 font-semibold">Draft</span>
                            @endif
                        </td>
                        <td class="p-4 space-x-2">
                            <a href="{{ route('provider-notes.show', $note) }}" class="text-blue-600">View</a>
                            <a href="{{ route('provider-notes.edit', $note) }}" class="text-indigo-600">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-500">
                            No clinical notes yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
