@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Client</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $client->email }}" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $client->phone }}">
        </div>

        <button type="submit" class="btn btn-success">Update Client</button>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
