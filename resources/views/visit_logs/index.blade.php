@extends('layouts.app')

@section('content')
<style>
    .visit-card { background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 25px; border: 1px solid #e2e8f0; }
    .table thead th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 15px; border: none; }
    .btn-new { background: #3b82f6; color: white; border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; transition: 0.3s; }
    .btn-new:hover { background: #2563eb; transform: translateY(-1px); }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 800; color: #0f172a;">Visit Logs</h2>
        <button class="btn-new shadow-sm" data-bs-toggle="modal" data-bs-target="#newLogModal">+ NEW VISIT</button>
    </div>

    <div class="visit-card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Caregiver</th>
                    <th>Activity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><strong>{{ $log->patient }}</strong></td>
                    <td>{{ $log->caregiver }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->created_at ? $log->created_at->format('Y-m-d') : date('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No logs found. Use the button to add one!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="newLogModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('visit-logs.store') }}" method="POST" class="modal-content" style="border-radius: 15px;">
            @csrf
            <div class="modal-header"><h5>New Visit Entry</h5></div>
            <div class="modal-body">
                <input type="text" name="patient" placeholder="Patient Name" class="form-control mb-3" required>
                <input type="text" name="caregiver" placeholder="Caregiver Name" class="form-control mb-3" required>
                <textarea name="activity" placeholder="Describe the visit activity..." class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Save Visit Log</button>
            </div>
        </form>
    </div>
</div>
@endsection
