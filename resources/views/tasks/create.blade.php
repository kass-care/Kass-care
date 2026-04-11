@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-4xl px-4 py-8">

        <h1 class="text-2xl font-bold text-slate-900 mb-6">
            Create Task
        </h1>

        <form method="POST" action="{{ route($routePrefix . 'tasks.store') }}">
            @csrf

            <div class="bg-white p-6 rounded-2xl shadow space-y-4">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" name="title" required
                           class="w-full rounded-xl border-slate-300 mt-1">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description"
                              class="w-full rounded-xl border-slate-300 mt-1"></textarea>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Priority</label>
                    <select name="priority" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <!-- Assigned To -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Assign To</label>
                    <select name="assigned_to" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Client -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Client</label>
                    <select name="client_id" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->first_name ?? '' }} {{ $client->last_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Visit -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Visit</label>
                    <select name="visit_id" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="">Select Visit</option>
                        @foreach($visits as $visit)
                            <option value="{{ $visit->id }}">Visit #{{ $visit->id }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Facility -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Facility</label>
                    <select name="facility_id" class="w-full rounded-xl border-slate-300 mt-1">
                        <option value="">Select Facility</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Due Date</label>
                    <input type="datetime-local" name="due_date"
                           class="w-full rounded-xl border-slate-300 mt-1">
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full rounded-xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700">
                        Save Task
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
