@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-4">Care Logs</h2>

    <div class="mb-3">
        <a href="{{ route('care-logs.create') }}" class="btn btn-primary">
            + Add Care Log
        </a>
    </div>

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Caregiver</th>
                <th>Date</th>
                <th>Notes</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>

        @forelse($careLogs as $log)

            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->client_id }}</td>
                <td>{{ $log->caregiver_id }}</td>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->notes ?? 'N/A' }}</td>

                <td>

                    <a href="{{ route('care-logs.show', $log->id) }}" class="btn btn-sm btn-info">
                        View
                    </a>

                    <a href="{{ route('care-logs.edit', $log->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('care-logs.destroy', $log->id) }}" method="POST" style="display:inline-block">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-danger">
                            Delete
                        </button>

                    </form>

                </td>
            </tr>

        @empty

            <tr>
                <td colspan="6" class="text-center">
                    No care logs found
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
