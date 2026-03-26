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

            <a href="{{ route('admin.facilities.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Add Facility
            </a>
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
        @if(auth()->user()->role === 'provider')
            <div class="mb-4">
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    Active Subscription ✅
                </span>
            </div>

            <div class="mb-4 text-sm text-gray-600">
                You are using <strong>{{ $facilities->count() }}</strong> /
                <strong>{{ auth()->user()->facility_limit ?? 1 }}</strong> facilities
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    Plan:
                    <strong>{{ ucfirst(auth()->user()->plan ?? 'starter') }}</strong>
                    <span class="mx-2">|</span>
                    Facility Limit:
                    <strong>{{ auth()->user()->facility_limit ?? 1 }}</strong>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if(Route::has('billing.plans'))
                        <a href="{{ route('billing.plans') }}"
                           class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 text-sm font-semibold">
                            Upgrade Plan
                        </a>
                    @endif

                    @if(Route::has('billing.portal'))
                        <a href="{{ route('billing.portal') }}"
                           class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 text-sm font-semibold">
                            Manage Billing
                        </a>
                    @endif

                    @if(Route::has('billing.cancel'))
                        <form method="POST" action="{{ route('billing.cancel') }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to cancel your plan?')"
                                    class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 text-sm font-semibold">
                                Cancel Plan
                            </button>
                        </form>
                    @endif
                </div>
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
                        <td class="p-3">{{ $facility->address ?? '-' }}</td>
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">
                            No facilities found. Click "Add Facility" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
