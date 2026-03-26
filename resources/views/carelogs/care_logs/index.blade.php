@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Care Logs - {{ $client->name }}</h2>

    <div style="margin-bottom:15px;">
        <a href="/clients/{{ $client->id }}/care-logs/create">Add Care Log</a>
        &nbsp; | &nbsp;
        <a href="/clients/{{ $client->id }}">Back to Client</a>
        &nbsp; | &nbsp;
        <a href="/clients">Back to Clients</a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda;padding:10px;border:1px solid #c3e6cb;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Caregiver</th>
                <th>BM</th>
                <th>Meals %</th>
                <th>Shower</th>
                <th>Meds</th>
                <th>Mood</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->care_date }}</td>
                <td>{{ $log->caregiver?->name ?? '-' }}</td>
                <td>{{ $log->bm ?? '-' }}</td>
                <td>{{ $log->meals_percent ?? '-' }}</td>
                <td>{{ $log->shower ? 'Yes' : 'No' }}</td>
                <td>{{ $log->meds_given ? 'Yes' : 'No' }}</td>
                <td>{{ $log->mood ?? '-' }}</td>
                <td>{{ $log->notes ?? '-' }}</td>
                <td>
                    <a href="/care-logs/{{ $log->id }}/edit">Edit</a>
                    |
                    <a href="/care-logs/{{ $log->id }}/delete"
                       onclick="return confirm('Delete this care log?')">Delete</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center;">No care logs yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
