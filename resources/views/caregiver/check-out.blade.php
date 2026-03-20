@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2">Caregiver Check Out</h1>
        <p class="text-gray-600 mb-6">
            Client: <strong>{{ $visit->client->name ?? 'N/A' }}</strong>
        </p>

        <form method="POST" action="{{ route('caregiver.checkout.save', $visit->id) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium mb-1">Visit Summary</label>
                <textarea name="visit_summary" rows="4" class="w-full border rounded-lg px-3 py-2" placeholder="Summarize what happened during the visit..."></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Client Condition at End of Visit</label>
                <select name="client_condition" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select condition</option>
                    <option value="Stable">Stable</option>
                    <option value="Improved">Improved</option>
                    <option value="Needs Follow-Up">Needs Follow-Up</option>
                    <option value="Declined">Declined</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Tasks Completed</label>
                <textarea name="tasks_completed" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="List care tasks completed..."></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Follow-Up Concerns</label>
                <textarea name="follow_up_concerns" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Any issues provider/admin should know?"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Final Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Any final caregiver notes..."></textarea>
<div class="pt-6 text-center">
    <button type="submit" 
        class="bg-green-600 hover:bg-green-700 text-white text-lg font-bold px-10 py-4 rounded-xl shadow-lg">
        ✅ Complete Visit
    </button>
</div>            </div>

        </form>
    </div>
</div>
@endsection
