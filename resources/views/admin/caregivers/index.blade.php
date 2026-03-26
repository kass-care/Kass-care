@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Caregivers</h1>
                <p class="text-gray-600 mt-1">Manage caregiver accounts and facility assignments.</p>
            </div>

            <a href="{{ route('admin.caregivers.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-semibold shadow-sm">
                + Add Caregiver
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-100 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-indigo-100">
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700">Name</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700">Email</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700">Facility</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700">Role</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($caregivers as $caregiver)
                        <tr class="border-b border-gray-100 hover:bg-indigo-50 transition">
                            <td class="py-4 px-4 text-gray-900 font-medium">
                                {{ $caregiver->name }}
                            </td>
                            <td class="py-4 px-4 text-gray-700">
                                {{ $caregiver->email }}
                            </td>
                            <td class="py-4 px-4 text-gray-700">
                                {{ $caregiver->facility->name ?? 'Not assigned' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    {{ ucfirst($caregiver->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.caregivers.edit', $caregiver->id) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.caregivers.destroy', $caregiver->id) }}"
                                          onsubmit="return confirm('Delete this caregiver?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                No caregivers found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
