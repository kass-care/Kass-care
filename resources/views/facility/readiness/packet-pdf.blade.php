<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KassCare State Survey Packet</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        h2 { font-size: 17px; margin-top: 24px; border-bottom: 1px solid #cbd5e1; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 7px; text-align: left; vertical-align: top; }
        th { background: #f1f5f9; }
        .small { color: #64748b; font-size: 11px; }
        .badge { display: inline-block; padding: 3px 7px; border-radius: 6px; font-weight: bold; }
        .complete { background: #dcfce7; color: #166534; }
        .pending { background: #fef9c3; color: #854d0e; }
        .issue { background: #fee2e2; color: #991b1b; }
        .summary { margin-top: 16px; }
    </style>
</head>
<body>

<h1>KassCare State Survey Packet</h1>
<div class="small">
    Facility: {{ $facility->name ?? 'Facility' }}<br>
    Generated: {{ now()->format('M d, Y g:i A') }}
</div>

<h2>1. Readiness Summary</h2>
<table class="summary">
    <tr>
        <th>Readiness Score</th>
        <th>Total Items</th>
        <th>Complete</th>
        <th>Pending</th>
        <th>Issues</th>
    </tr>
    <tr>
        <td><strong>{{ $score }}%</strong></td>
        <td>{{ $total }}</td>
        <td>{{ $complete }}</td>
        <td>{{ $pending }}</td>
        <td>{{ $issues }}</td>
    </tr>
</table>

<h2>2. Inspection Readiness Checklist</h2>
<table>
    <tr>
        <th>Category</th>
        <th>Item</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Notes</th>
    </tr>
    @forelse($readinessItems as $item)
        <tr>
            <td>{{ $item->category }}</td>
            <td>
                <strong>{{ $item->title }}</strong><br>
                <span class="small">{{ $item->description }}</span>
            </td>
            <td>
                <span class="badge {{ $item->status }}">
                    {{ strtoupper($item->status) }}
                </span>
            </td>
            <td>{{ $item->due_date ? $item->due_date->format('M d, Y') : '-' }}</td>
            <td>{{ $item->notes ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="5">No readiness items recorded.</td></tr>
    @endforelse
</table>

<h2>3. Residents / Clients</h2>
<table>
    <tr>
        <th>Name</th>
        <th>DOB</th>
        <th>Room</th>
        <th>Allergies</th>
    </tr>
    @forelse($clients as $client)
        <tr>
            <td>{{ $client->name }}</td>
            <td>{{ $client->date_of_birth ? \Carbon\Carbon::parse($client->date_of_birth)->format('M d, Y') : '-' }}</td>
            <td>{{ $client->room ?? '-' }}</td>
            <td>{{ $client->allergies ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="4">No clients found.</td></tr>
    @endforelse
</table>

<h2>4. Medication Compliance Snapshot</h2>
<table>
    <tr>
        <th>Patient</th>
        <th>Medication</th>
        <th>Dose</th>
        <th>Status</th>
        <th>Approval</th>
    </tr>
    @forelse($medications as $medication)
        <tr>
            <td>{{ $medication->client->name ?? '-' }}</td>
            <td>{{ $medication->medication_name }}</td>
            <td>{{ $medication->dose ?? '-' }}</td>
            <td>{{ $medication->status ?? '-' }}</td>
            <td>{{ $medication->approval_status ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="5">No medications found.</td></tr>
    @endforelse
</table>

<h2>5. Staff / Caregivers</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
    @forelse($caregivers as $caregiver)
        <tr>
            <td>{{ $caregiver->name }}</td>
            <td>{{ $caregiver->email }}</td>
            <td>{{ $caregiver->role }}</td>
        </tr>
    @empty
        <tr><td colspan="3">No caregivers found.</td></tr>
    @endforelse
</table>

<h2>6. Recent Visits</h2>
<table>
    <tr>
        <th>Date</th>
        <th>Client</th>
        <th>Caregiver</th>
        <th>Status</th>
    </tr>
    @forelse($visits as $visit)
        <tr>
            <td>{{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : optional($visit->created_at)->format('M d, Y') }}</td>
            <td>{{ $visit->client->name ?? '-' }}</td>
            <td>{{ $visit->caregiver->name ?? '-' }}</td>
            <td>{{ $visit->status ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="4">No visits found.</td></tr>
    @endforelse
</table>

</body>
</html>
