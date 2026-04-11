@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Facilities</h1>
            <p class="text-gray-500">Manage all facilities from one place.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Dashboard
            </a>

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.facilities.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Add Facility
                </a>
            @endif
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @auth
        @if(auth()->user()->role === 'admin')
            <div class="mb-4 text-sm text-gray-600">
                You are viewing your assigned facility only.
            </div>
        @endif
    @endauth

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Address</th>
                    <th class="text-left p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $facility)
                    <tr class="border-t">
                        <td class="p-3">{{ $facility->name }}</td>
                        <td class="p-3">{{ $facility->address ?? '—' }}</td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.facilities.show', $facility) }}"
                                   class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm">
                                    View
                                </a>

                                <a href="{{ route('admin.facilities.edit', $facility) }}"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                    Edit
                                </a>

                                @if(auth()->user()->role === 'super_admin')
                                    <form action="{{ route('admin.facilities.destroy', $facility) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this facility?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">
                            No facilities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
