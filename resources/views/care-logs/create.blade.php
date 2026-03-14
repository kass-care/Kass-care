@extends('layouts.layout')

@section('content')
<h1>Add New Care Log</h1>

<form action="{{ route('care-logs.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Patient</label>
        <select name="client_id" class="form-control">
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Provider</label>
        <select name="caregiver_id" class="form-control">
            @foreach($caregivers as $caregiver)
                <option value="{{ $caregiver->id }}">{{ $caregiver->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save Care Log</button>
</form>
@endsection
