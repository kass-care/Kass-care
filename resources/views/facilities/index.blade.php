@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-3xl font-bold mb-6">Facilities Dashboard</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Facility Button -->
    <button onclick="document.getElementById('newFacilityModal').classList.remove('hidden')"
        class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
        + NEW FACILITY
    </button>

    <!-- Facilities Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $facility)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $facility->id }}</td>
                <td class="px-4 py-2">{{ $facility->name }}</td>
                <td class="px-4 py-2">{{ $facility->address ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-2 text-center">No facilities found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- New Facility Modal -->
    <div id="newFacilityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded p-6 w-1/3">
            <h2 class="text-xl font-bold mb-4">Add New Facility</h2>
            <form action="{{ route('facilities.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block mb-1">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full border px-3 py-2 rounded @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button"
                        onclick="document.getElementById('newFacilityModal').classList.add('hidden')"
                        class="mr-2 px-4 py-2 rounded border">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Facility</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
