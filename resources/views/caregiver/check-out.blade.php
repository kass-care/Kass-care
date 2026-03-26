@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6 border border-indigo-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold mb-2 text-gray-900">Caregiver Check Out</h1>
                <p class="text-gray-600">
                    Client: <strong>{{ $visit->client->name ?? 'N/A' }}</strong>
                </p>
            </div>

            <a href="{{ route('caregiver.dashboard') }}"
               class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('caregiver.checkout.save', $visit->id) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium mb-2">Visit Summary</label>
                <textarea name="visit_summary" rows="4" class="w-full border rounded-lg px-3 py-2" placeholder="Summarize the visit"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-2">Client Condition at End of Visit</label>
                <select name="client_condition" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select condition</option>
                    <option value="Stable">Stable</option>
                    <option value="Improved">Improved</option>
                    <option value="Needs Follow-Up">Needs Follow-Up</option>
                    <option value="Declined">Declined</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-2">Tasks Completed</label>
                <textarea name="tasks_completed" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="List tasks completed"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-2">Follow-Up Concerns</label>
                <textarea name="follow_up_concerns" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Any concerns to note"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-2">Final Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Any final caregiver notes"></textarea>
            </div>

            <div class="pt-3">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white text-lg font-bold px-8 py-3 rounded-xl shadow-lg">
                    Complete Visit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
