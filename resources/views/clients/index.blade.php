@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Clients</h1>

        <a href="{{ route('clients.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            + Add Client
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Phone</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($clients as $client)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $client->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $client->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $client->email ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $client->phone ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('clients.show', $client) }}"
                               class="text-indigo-600 hover:underline">
                                View
                            </a>

                            <a href="{{ route('clients.edit', $client) }}"
                               class="text-yellow-600 hover:underline">
                                Edit
                            </a>

                            <form action="{{ route('clients.destroy', $client) }}"
                                  method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    onclick="return confirm('Delete this client?')"
                                    class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">
                            No clients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
