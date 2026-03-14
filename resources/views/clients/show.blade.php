@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Client Details</h1>

    <p><strong>Name:</strong> {{ $client->name }}</p>
    <p><strong>Email:</strong> {{ $client->email }}</p>
    <p><strong>Phone:</strong> {{ $client->phone }}</p>

    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
