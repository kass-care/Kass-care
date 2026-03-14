@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Caregiver Details</h1>

    <p><strong>Name:</strong> {{ $caregiver->name }}</p>
    <p><strong>Email:</strong> {{ $caregiver->email }}</p>
    <p><strong>Phone:</strong> {{ $caregiver->phone }}</p>

    <a href="{{ route('caregivers.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('caregivers.edit', $caregiver) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
