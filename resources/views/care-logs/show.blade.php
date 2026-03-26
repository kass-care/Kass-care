@extends('layouts.layout')

@section('content')
<h1>Care Log Details</h1>

<p><strong>Patient:</strong> {{ $careLog->client->name ?? 'N/A' }}</p>
<p><strong>Provider:</strong> {{ $careLog->caregiver->name ?? 'N/A' }}</p>
<p><strong>Date:</strong> {{ $careLog->date }}</p>
<p><strong>Notes:</strong></p>
<p>{{ $careLog->notes }}</p>

<a href="{{ route('care-logs.index') }}" class="btn btn-secondary">Back</a>
<a href="{{ route('care-logs.edit', $careLog->id) }}" class="btn btn-warning">Edit</a>
@endsection
