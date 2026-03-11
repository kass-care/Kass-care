@extends('layouts.app')

@section('content')

<div class="container">

<h2 class="mb-4">Client Profile</h2>

<div class="card">
<div class="card-body">

<p><strong>ID:</strong> {{ $client->id }}</p>
<p><strong>Name:</strong> {{ $client->name }}</p>
<p><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
<p><strong>Phone:</strong> {{ $client->phone ?? 'N/A' }}</p>

<br>

<a href="{{ route('clients.index') }}" class="btn btn-secondary">
Back to Clients
</a>

</div>
</div>

</div>

@endsection
