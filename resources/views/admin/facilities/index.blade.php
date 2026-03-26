@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Facilities</h1>

        <a href="{{ route('admin.facilities.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
            + Add Facility
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Location</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $facility)
                    <tr class="border-t">
                        <td class="p-3">{{ $facility->name }}</td>
                        <td class="p-3">{{ $facility->location }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                               class="text-blue-600">Edit</a>

                            <form action="{{ route('admin.facilities.destroy', $facility) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">
                            No facilities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
