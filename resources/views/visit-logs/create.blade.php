@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-10">

<h1 class="text-2xl font-bold mb-6">Add Visit Log</h1>

<form action="{{ route('visit-logs.store') }}" method="POST">
@csrf

<div class="mb-4">
<label class="block text-sm font-semibold">Patient</label>
<input type="text" name="patient" class="w-full border rounded-lg px-3 py-2">
</div>

<div class="mb-4">
<label class="block text-sm font-semibold">Caregiver</label>
<input type="text" name="caregiver" class="w-full border rounded-lg px-3 py-2">
</div>

<div class="mb-4">
<label class="block text-sm font-semibold">Activity</label>
<textarea name="activity" class="w-full border rounded-lg px-3 py-2"></textarea>
</div>

<div class="mb-4">
<label class="block text-sm font-semibold">Date</label>
<input type="date" name="date" class="w-full border rounded-lg px-3 py-2">
</div>

<button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
Save Visit Log
</button>

</form>

</div>
@endsection
