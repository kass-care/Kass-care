@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 800; color: #1e293b;">Visit Logs</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visitModal">+ NEW VISIT</button>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Patient</th>
                    <th>Caregiver</th>
                    <th>Activity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td><strong>{{ $log->patient }}</strong></td>
                    <td>{{ $log->caregiver }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="visitModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/visit-logs" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>New Visit Entry</h5></div>
            <div class="modal-body">
                <input type="text" name="patient" placeholder="Patient Name" class="form-control mb-3" required>
                <input type="text" name="caregiver" placeholder="Caregiver Name" class="form-control mb-3" required>
                <textarea name="activity" placeholder="Activity Description" class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Save Visit</button>
            </div>
        </form>
    </div>
</div>
@endsection
