@extends('layouts.layout')

@section('content')
<h1>Edit Care Log</h1>

<form action="{{ route('care-logs.update', $careLog->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Patient</label>
        <select name="client_id" class="form-control">
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ $careLog->client_id == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Provider</label>
        <select name="caregiver_id" class="form-control">
            @foreach($caregivers as $caregiver)
                <option value="{{ $caregiver->id }}" {{ $careLog->caregiver_id == $caregiver->id ? 'selected' : '' }}>
                    {{ $caregiver->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control">{{ $careLog->notes }}</textarea>
    </div>
    <button type="submit" class="btn btn-success">Update Care Log</button>
</form>
@endsection
