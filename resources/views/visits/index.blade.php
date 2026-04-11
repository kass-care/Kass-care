@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Visits</h1>

        <a href="{{ route('admin.visits.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Add New Visit
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Client</th>
                    <th class="px-4 py-3 text-left">Caregiver</th>
                    <th class="px-4 py-3 text-left">Activity</th>
                    <th class="px-4 py-3 text-left">Visit Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($visits as $visit)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $visit->id }}</td>
                        <td class="px-4 py-3">{{ $visit->client->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $visit->caregiver->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $visit->activity ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $visit->visit_date ?? 'N/A' }}</td>

                        <td class="px-4 py-3">
                            @if($visit->status === 'completed')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded">Completed</span>
                            @elseif($visit->status === 'in_progress')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">In Progress</span>
                            @elseif($visit->status === 'missed')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded">Missed</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
                                    {{ ucfirst($visit->status) }}
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.visits.edit', $visit->id) }}"
                                   class="bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600 text-sm">
                                    Edit
                                </a>

                                @if($visit->status === 'completed')
                                    <a href="{{ route('admin.claims.create', ['visit_id' => $visit->id]) }}"
                                       class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                                        Bill
                                    </a>
                                @endif

                                <form action="{{ route('admin.visits.destroy', $visit->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this visit?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            No visits found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
