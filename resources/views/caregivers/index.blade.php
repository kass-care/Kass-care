@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tight text-slate-900">
                    Caregivers
                </h1>
                <p class="text-slate-500 mt-2 text-sm">
                    Manage your caregiving team, monitor staff records, and prepare for visit assignments.
                </p>
            </div>

            <a href="{{ route('caregivers.create') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                + Add Caregiver
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 shadow-sm">
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 text-white shadow-xl">
                <p class="text-xs uppercase tracking-[0.25em] text-indigo-100">Total Caregivers</p>
                <h2 class="mt-4 text-4xl font-black">{{ $caregivers->count() }}</h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Active Staff</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $caregivers->where('status', 'active')->count() }}
                </h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">With Email</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $caregivers->filter(fn($c) => !empty($c->email))->count() }}
                </h2>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">With Phone</p>
                <h2 class="mt-4 text-3xl font-black text-slate-900">
                    {{ $caregivers->filter(fn($c) => !empty($c->phone))->count() }}
                </h2>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/40">
            @if($caregivers->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Caregiver</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Created</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @foreach($caregivers as $caregiver)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm font-semibold text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100 text-sm font-black uppercase text-indigo-700">
                                                {{ strtoupper(substr($caregiver->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">
                                                    {{ $caregiver->name }}
                                                </p>
                                                <p class="text-xs text-slate-400">
                                                    Staff Member
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-700">
                                        {{ $caregiver->email ?: '—' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-700">
                                        {{ $caregiver->phone ?: '—' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                            {{ $caregiver->status ?: 'Active' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $caregiver->created_at ? $caregiver->created_at->format('M d, Y h:i A') : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-8 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-indigo-100 text-3xl text-indigo-600">
                        👩‍⚕️
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">No caregivers yet</h2>
                    <p class="mt-2 text-slate-500">
                        Start building your care team by adding your first caregiver.
                    </p>
                    <a href="{{ route('caregivers.create') }}"
                       class="mt-6 inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                        + Add First Caregiver
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
