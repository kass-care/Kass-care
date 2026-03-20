@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2">Care Log</h1>
        <p class="text-gray-600 mb-6">
            Client: <strong>{{ $visit->client->name ?? 'N/A' }}</strong>
        </p>

        <form method="POST" action="{{ route('caregiver.carelog.store', $visit->id) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium mb-1">General Notes</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <h2 class="text-lg font-semibold pt-2">ADLs</h2>

            <div>
                <label class="block font-medium mb-1">Bathing</label>
                <select name="adl_bathing" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select status</option>
                    <option value="Independent">Independent</option>
                    <option value="Needs Assistance">Needs Assistance</option>
                    <option value="Dependent">Dependent</option>
                    <option value="Refused">Refused</option>
                    <option value="Not Done">Not Done</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Dressing</label>
                <select name="adl_dressing" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select status</option>
                    <option value="Independent">Independent</option>
                    <option value="Needs Assistance">Needs Assistance</option>
                    <option value="Dependent">Dependent</option>
                    <option value="Refused">Refused</option>
                    <option value="Not Done">Not Done</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Toileting</label>
                <select name="adl_toileting" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select status</option>
                    <option value="Independent">Independent</option>
                    <option value="Needs Assistance">Needs Assistance</option>
                    <option value="Dependent">Dependent</option>
                    <option value="Refused">Refused</option>
                    <option value="Not Done">Not Done</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Feeding</label>
                <select name="adl_feeding" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select status</option>
                    <option value="Independent">Independent</option>
                    <option value="Needs Assistance">Needs Assistance</option>
                    <option value="Dependent">Dependent</option>
                    <option value="Refused">Refused</option>
                    <option value="Not Done">Not Done</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Mobility</label>
                <select name="adl_mobility" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select status</option>
                    <option value="Independent">Independent</option>
                    <option value="Needs Assistance">Needs Assistance</option>
                    <option value="Dependent">Dependent</option>
                    <option value="Refused">Refused</option>
                    <option value="Not Done">Not Done</option>
                </select>
            </div>

            <h2 class="text-lg font-semibold pt-2">Vitals</h2>

            <div>
                <label class="block font-medium mb-1">Temperature</label>
                <input type="text" name="temperature" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 98.6°F">
            </div>

            <div>
                <label class="block font-medium mb-1">Heart Rate</label>
                <input type="text" name="heart_rate" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 72 bpm">
            </div>

            <div>
                <label class="block font-medium mb-1">Respiratory Rate</label>
                <input type="text" name="respiratory_rate" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 18/min">
            </div>

            <div>
                <label class="block font-medium mb-1">Oxygen Saturation</label>
                <input type="text" name="oxygen_saturation" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 98%">
            </div>

            <div>
                <label class="block font-medium mb-1">BP Systolic</label>
                <input type="text" name="blood_pressure_systolic" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 120">
            </div>

            <div>
                <label class="block font-medium mb-1">BP Diastolic</label>
                <input type="text" name="blood_pressure_diastolic" class="w-full border rounded-lg px-3 py-2" placeholder="e.g. 80">
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                    Save Care Log
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
