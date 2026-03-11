@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Caregivers</h2>

    <div style="margin-bottom:20px;">
        <a href="{{ route('caregivers.create') }}" class="btn btn-primary">
            Add Caregiver
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

        @forelse($caregivers as $caregiver)

            <tr>
                <td>{{ $caregiver->id }}</td>
                <td>{{ $caregiver->name }}</td>
                <td>{{ $caregiver->phone }}</td>
                <td>{{ $caregiver->email }}</td>
                <td>{{ $caregiver->status ?? 'Active' }}</td>
            </tr>

        @empty

            <tr>
                <td colspan="5">No caregivers found</td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
