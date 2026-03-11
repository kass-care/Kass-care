@extends('layouts.app')

@section('content')

<div class="container">

<h2>Care Log Details</h2>

<div class="card">
<div class="card-body">

<p><strong>ID:</strong> {{ $careLog->id }}</p>

<p><strong>Client:</strong> {{ $careLog->client_id }}</p>

<p><strong>Caregiver:</strong> {{ $careLog->caregiver_id }}</p>

<p><strong>Date:</strong> {{ $careLog->created_at }}</p>

<p><strong>Notes:</strong></p>

<p>{{ $careLog->notes }}</p>

<br>

<a href="{{ route('care-logs.index') }}" class="btn btn-secondary">
Back
</a>

</div>
</div>

</div>

@endsection
