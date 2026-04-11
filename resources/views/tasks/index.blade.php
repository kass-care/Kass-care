
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
          
<div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-indigo-600">KASS CARE</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">Task Center</h1>
        <p class="mt-2 text-sm text-slate-600">
            Manage urgent tasks, assigned work, reviews, and completed items.
        </p>
    </div>

          @if (Route::has($routePrefix . 'tasks.create'))
    <a href="{{ route($routePrefix . 'tasks.create') }}">Illuminate\Support

           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
            + Create Task
        </a>
    @endif
</div>
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">Urgent Tasks</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $urgentCount }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-500">Non-Urgent</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $nonUrgentCount }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-sky-500">Assigned To Me</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $assignedCount }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-purple-500">Review</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $reviewCount }}</p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Completed</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ $completedCount }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <form method="GET" class="grid gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Priority</label>
                        <select name="priority" class="w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Filter
                        </button>
                           <a href="{{ url()->current() }}"
                           class="inline-flex rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $task->title }}</div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        @if($task->client)
                                            Client: {{ $task->client->first_name ?? '' }} {{ $task->client->last_name ?? '' }}
                                        @endif

                                        @if($task->visit)
                                            <span class="ml-2">Visit #{{ $task->visit->id }}</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @if($task->priority === 'urgent') bg-red-100 text-red-700
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-700
                                        @elseif($task->priority === 'medium') bg-amber-100 text-amber-700
                                        @else bg-slate-100 text-slate-700
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @if($task->status === 'completed') bg-emerald-100 text-emerald-700
                                        @elseif($task->status === 'review') bg-purple-100 text-purple-700
                                        @elseif($task->status === 'in_progress') bg-sky-100 text-sky-700
                                        @elseif($task->status === 'cancelled') bg-slate-200 text-slate-700
                                        @else bg-indigo-100 text-indigo-700
                                        @endif">
                                        {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $task->assignedUser->name ?? 'Unassigned' }}
                                </td>
                               <td class="px-6 py-4">
    <div class="flex flex-wrap items-center gap-2">

           <a href="{{ $task->open_url ?? '#' }}"
   class="inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
    Open
</a>
              @if (\Illuminate\Support\Facades\Route::has($routePrefix . 'tasks.update-status'))
    <form method="POST" action="{{ route($routePrefix . 'tasks.update-status', $task) }}">
                @csrf
                @method('PATCH')

                <select name="status"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 focus:border-indigo-500 focus:outline-none">
                    <option value="open" {{ $task->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        @else
            <span class="inline-flex rounded-lg bg-gray-100 px-3 py-2 text-xs font-semibold text-slate-500">
                Status route missing
            </span>
        @endif
    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                    No tasks found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
