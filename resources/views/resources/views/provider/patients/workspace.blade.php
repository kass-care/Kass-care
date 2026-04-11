@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-10 px-6">

@if(isset($client))

<div class="bg-white shadow rounded-2xl p-6 mb-8">

<h1 class="text-3xl font-bold mb-4">
Patient Snapshot
</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

<div>
<p class="text-xs text-gray-500 uppercase">Name</p>
<p class="text-lg font-semibold">{{ $client->name }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Age</p>
<p class="text-lg font-semibold">{{ $snapshot['age'] ?? '—' }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Room</p>
<p class="text-lg font-semibold">{{ $client->room ?? '—' }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Facility</p>
<p class="text-lg font-semibold">{{ $client->facility->name ?? '—' }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Provider</p>
<p class="text-lg font-semibold">{{ $client->provider->name ?? '—' }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Diagnoses</p>
<p class="text-lg font-semibold">{{ $snapshot['diagnosisCount'] }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Medications</p>
<p class="text-lg font-semibold">{{ $snapshot['medicationCount'] }}</p>
</div>

<div>
<p class="text-xs text-gray-500 uppercase">Last Visit</p>
<p class="text-lg font-semibold">
{{ $snapshot['lastVisit']->visit_date ?? '—' }}
</p>
</div>

</div>

</div>

@endif

</div>

@endsection
