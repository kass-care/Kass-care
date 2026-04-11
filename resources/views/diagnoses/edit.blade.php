@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">

<h1 class="text-2xl font-bold mb-6">Edit Diagnosis</h1>

<form action="{{ route('diagnoses.update', [$client, $diagnosis]) }}" method="POST" class="space-y-6">
@csrf
@method('PUT')

<div>
<label class="block text-sm font-medium text-gray-700">Diagnosis Name</label>
<input type="text"
       name="diagnosis_name"
       value="{{ old('diagnosis_name', $diagnosis->diagnosis_name) }}"
       class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
</div>

<div>
<label class="block text-sm font-medium text-gray-700">ICD Code</label>
<input type="text"
       name="icd_code"
       value="{{ old('icd_code', $diagnosis->icd_code) }}"
       class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
</div>

<div>
<label class="block text-sm font-medium text-gray-700">Status</label>
<select name="status"
        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">

<option value="active" {{ $diagnosis->status == 'active' ? 'selected' : '' }}>Active</option>
<option value="resolved" {{ $diagnosis->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
<option value="chronic" {{ $diagnosis->status == 'chronic' ? 'selected' : '' }}>Chronic</option>

</select>
</div>

<div>
<label class="block text-sm font-medium text-gray-700">Notes</label>
<textarea name="notes"
          rows="4"
          class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">{{ old('notes', $diagnosis->notes) }}</textarea>
</div>

<div class="flex justify-between">

<a href="{{ route('clients.show', $client) }}"
   class="px-4 py-2 bg-gray-200 rounded-lg">
Back
</a>

<button type="submit"
        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
Update Diagnosis
</button>

</div>

</form>

</div>
@endsection
