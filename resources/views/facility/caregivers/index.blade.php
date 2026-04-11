@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Facility Caregivers</h1>
            <p class="text-gray-600 mt-1">Manage caregivers for this facility</p>
        </div>

        <a href="{{ route('facility.caregivers.create') }}"
           class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition">
            + Add Caregiver
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Facility</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($caregivers as $caregiver)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $caregiver->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $caregiver->email }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ auth()->user()->facility?->name ?? 'Facility' }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Caregiver
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('facility.caregivers.edit', $caregiver->id) }}"
                               class="text-indigo-600 hover:text-indigo-800 mr-4">
                                Edit
                            </a>

                            <form action="{{ route('facility.caregivers.destroy', $caregiver->id) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Delete this caregiver?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            No caregivers added for this facility yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
