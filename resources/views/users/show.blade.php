@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">User Details</h1>

    <div class="card p-4">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Created:</strong> {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '' }}</p>
    </div>

    <br>

    <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
