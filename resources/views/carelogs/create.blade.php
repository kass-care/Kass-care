@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-4">Create Care Log</h2>

    <form action="{{ route('care-logs.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label class="form-label">Client</label>

            <select name="client_id" class="form-control">

                @foreach($clients as $client)

                    <option value="{{ $client->id }}">
                        {{ $client->id }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Caregiver</label>

            <select name="caregiver_id" class="form-control">

                @foreach($caregivers as $caregiver)

                    <option value="{{ $caregiver->id }}">
                        {{ $caregiver->id }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>

            <textarea name="notes" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-primary">
            Save Care Log
        </button>

        <a href="{{ route('care-logs.index') }}" class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection
