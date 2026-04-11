@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-indigo-700">
            KASS Care Provider Compliance Dashboard
        </h1>

        <p class="text-gray-600 mt-2">
            Track provider facility visit compliance across your organization.
        </p>
    </div>


    <div class="grid grid-cols-4 gap-6 mb-10">

        <div class="bg-green-100 rounded-xl p-6">
            <p class="text-sm text-gray-600">Current</p>
            <h2 class="text-3xl font-bold text-green-700">{{ $current }}</h2>
        </div>

        <div class="bg-yellow-100 rounded-xl p-6">
            <p class="text-sm text-gray-600">Due Soon</p>
            <h2 class="text-3xl font-bold text-yellow-600">{{ $dueSoon }}</h2>
        </div>

        <div class="bg-orange-100 rounded-xl p-6">
            <p class="text-sm text-gray-600">Due Today</p>
            <h2 class="text-3xl font-bold text-orange-600">{{ $due }}</h2>
        </div>

        <div class="bg-red-100 rounded-xl p-6">
            <p class="text-sm text-gray-600">Overdue</p>
            <h2 class="text-3xl font-bold text-red-700">{{ $overdue }}</h2>
        </div>

    </div>


    <div class="bg-white shadow rounded-xl overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="text-left px-6 py-3">Facility</th>
                    <th class="text-left px-6 py-3">Provider</th>
                    <th class="text-left px-6 py-3">Last Visit</th>
                    <th class="text-left px-6 py-3">Next Due</th>
                    <th class="text-left px-6 py-3">Status</th>
                </tr>
            </thead>

            <tbody>

            @foreach($cycles as $cycle)

                <tr class="border-b">

                    <td class="px-6 py-4">
                        {{ $cycle->facility->name ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $cycle->provider->name ?? 'Unassigned' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $cycle->last_completed_at }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $cycle->next_due_at }}
                    </td>

                    <td class="px-6 py-4">

                        @if($cycle->computed_status == 'overdue')

                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">
                                Overdue
                            </span>

                        @elseif($cycle->computed_status == 'due')

                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs">
                                Due Today
                            </span>

                        @elseif($cycle->computed_status == 'due_soon')

                            <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs">
                                Due Soon
                            </span>

                        @else

                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                                Current
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
