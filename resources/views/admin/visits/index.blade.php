@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Manage Visit Assignments</h1>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow p-6">
            @if($visits->count())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left p-3">Visit ID</th>
                                <th class="text-left p-3">Client</th>
                                <th class="text-left p-3">Current Caregiver</th>
                                <th class="text-left p-3">Status</th>
                                <th class="text-left p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr class="border-b">
                                    <td class="p-3">{{ $visit->id }}</td>
                                    <td class="p-3">{{ $visit->client->name ?? 'N/A' }}</td>
                                    <td class="p-3">{{ $visit->caregiver->name ?? 'Unassigned' }}</td>
                                    <td class="p-3">{{ $visit->status ?? 'N/A' }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.visits.assign', $visit->id) }}"
                                           class="text-indigo-600 hover:underline">
                                            Assign Caregiver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-slate-600">No visits found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
