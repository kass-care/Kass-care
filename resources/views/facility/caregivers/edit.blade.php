@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-10 bg-white shadow rounded-xl p-6">

<h1 class="text-xl font-bold mb-6">Edit Caregiver</h1>

<form method="POST" action="{{ route('facility.caregivers.update', $caregiver->id) }}">
@csrf
@method('PUT')

<div class="mb-4">
<label class="block text-sm font-medium">Name</label>
<input type="text"
       name="name"
       value="{{ $caregiver->name }}"
       class="w-full border rounded-lg p-2">
</div>

<div class="mb-4">
<label class="block text-sm font-medium">Email</label>
<input type="email"
       name="email"
       value="{{ $caregiver->email }}"
       class="w-full border rounded-lg p-2">
</div>

<div class="flex gap-3 mt-6">
<button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
Update Caregiver
</button>

<a href="{{ route('facility.caregivers.index') }}"
   class="bg-gray-200 px-4 py-2 rounded-lg">
Cancel
</a>
</div>

</form>

</div>

@endsection
