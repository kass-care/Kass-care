<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admission Packet</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 28px;
        }

        .header {
            border-bottom: 3px solid #312e81;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #312e81;
        }

        .subtitle {
            margin-top: 4px;
            color: #4b5563;
            font-size: 11px;
        }

        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 10px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 999px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .section {
            margin-top: 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: #eef2ff;
            color: #312e81;
            font-weight: bold;
            padding: 9px 12px;
            border-bottom: 1px solid #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .label {
            width: 32%;
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
        }

        .terms {
            padding: 14px;
            line-height: 1.7;
            white-space: pre-line;
        }

        .signature-grid {
            width: 100%;
            margin-top: 24px;
        }

        .signature-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 14px;
            min-height: 90px;
        }

        .signature-box.right {
            margin-left: 3%;
        }

        .signature-name {
            margin-top: 18px;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .signature-line {
            margin-top: 32px;
            border-bottom: 1px solid #111827;
        }

        .small {
            font-size: 10px;
            color: #6b7280;
            margin-top: 6px;
        }

        .footer {
            margin-top: 28px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="brand">KassCare</div>
    <div class="subtitle">Admissions & E-Signature Center</div>
    <div class="status">{{ $admissionPacket->status }}</div>
</div>

<h1>Resident Admission Agreement</h1>

<div class="section">
    <div class="section-title">Resident / Admission Information</div>
    <table>
        <tr>
            <td class="label">Resident Name</td>
            <td>{{ $admissionPacket->resident_name }}</td>
        </tr>
        <tr>
            <td class="label">Admission Date</td>
            <td>{{ optional($admissionPacket->admission_date)->format('M d, Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Representative</td>
            <td>{{ $admissionPacket->representative_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Relationship</td>
            <td>{{ $admissionPacket->representative_relationship ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Monthly Rate</td>
            <td>${{ $admissionPacket->monthly_rate ?? '0' }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Agreement Terms</div>
    <div class="terms">{{ $admissionPacket->document_text }}</div>
</div>

<div class="signature-grid">
    <div class="signature-box">
        <strong>Resident Signature</strong>

        @if($admissionPacket->status === 'signed')
            <div class="signature-name">{{ $admissionPacket->signature }}</div>
            <div class="small">
                Signed {{ optional($admissionPacket->signed_at)->format('M d, Y g:i A') }}
            </div>
        @else
            <div class="signature-line"></div>
            <div class="small">Resident signature</div>
        @endif
    </div>

    <div class="signature-box right">
        <strong>Representative Signature</strong>

        @if($admissionPacket->status === 'signed')
            <div class="signature-name">{{ $admissionPacket->signed_by }}</div>
            <div class="small">
                Signed {{ optional($admissionPacket->signed_at)->format('M d, Y g:i A') }}
            </div>
        @else
            <div class="signature-line"></div>
            <div class="small">Representative signature</div>
        @endif
    </div>
</div>

<div class="footer">
    Generated by KassCare SaaS • Admissions & E-Signature Workflow • Packet #{{ $admissionPacket->id }}
</div>

</body>
</html>
