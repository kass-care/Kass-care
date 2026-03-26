@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">User Management</h1>
            <p class="text-slate-600 mt-2">
                Super Admin can view all platform users and their roles.
            </p>
        </div>

        <a href="{{ route('super_admin.dashboard') }}"
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded-xl hover:bg-gray-300">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Facility ID</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $user->role }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $user->facility_id ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
