<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KassCare Premium ePrescription</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 24px;
        }

        .header {
            border-bottom: 3px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .doc-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 4px;
        }

        .meta {
            font-size: 10px;
            color: #374151;
            margin-top: 4px;
        }

        .section {
            border: 1px solid #111827;
            margin-top: 12px;
        }

        .section-title {
            background: #111827;
            color: white;
            padding: 6px 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
            vertical-align: top;
        }

        .label {
            width: 25%;
            font-weight: bold;
            background: #f3f4f6;
            color: #374151;
        }

        .value {
            width: 25%;
        }

        .medication {
            font-size: 18px;
            font-weight: bold;
        }

        .directions {
            min-height: 50px;
            line-height: 1.5;
            font-size: 12px;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
        }

        .signature-box {
            height: 70px;
            border-top: 1px solid #111827;
            margin-top: 35px;
            padding-top: 8px;
            font-size: 10px;
        }

        .notice {
            margin-top: 12px;
            border: 1px solid #9ca3af;
            padding: 8px;
            font-size: 9.5px;
            line-height: 1.4;
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>

<body>
@php
    $clientName = $order->client->name
        ?? trim(($order->client->first_name ?? '') . ' ' . ($order->client->last_name ?? ''))
        ?: 'N/A';

    $providerName = $order->provider->name
        ?? trim(($order->provider->first_name ?? '') . ' ' . ($order->provider->last_name ?? ''))
        ?: 'N/A';

    $facilityName = $order->facility->name
        ?? $order->client->facility->name
        ?? 'N/A';

    $clientDob = $order->client->date_of_birth
        ?? $order->client->dob
        ?? $order->client->birth_date
        ?? null;

    $clientDobFormatted = $clientDob
        ? \Carbon\Carbon::parse($clientDob)->format('m/d/Y')
        : 'Not provided';

    $clientAddress = $order->client->address
        ?? $order->client->street_address
        ?? $order->client->full_address
        ?? 'Not provided';

    $prescriptionDate = $order->prescribed_at
        ? \Carbon\Carbon::parse($order->prescribed_at)->format('m/d/Y g:i A')
        : now()->format('m/d/Y g:i A');

    $rxNumber = 'RX-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
@endphp

<div class="header">
    <div class="brand">KassCare</div>
    <div class="doc-title">Premium ePrescription Record</div>
    <div class="meta">
        RX #: {{ $rxNumber }} |
        Generated: {{ now()->format('m/d/Y g:i A') }} |
        Status: <span class="status">{{ $order->status ?? 'Pending' }}</span>
    </div>
</div>

<div class="section">
    <div class="section-title">Patient Demographics</div>
    <table>
        <tr>
            <td class="label">Patient Name</td>
            <td class="value">{{ ucwords(strtolower($clientName)) }}</td>
            <td class="label">Date of Birth</td>
            <td class="value">{{ $clientDobFormatted }}</td>
        </tr>
        <tr>
            <td class="label">Patient Address</td>
            <td class="value">{{ $clientAddress }}</td>
            <td class="label">Facility</td>
            <td class="value">{{ $facilityName }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Medication Order</div>
    <table>
        <tr>
            <td class="label">Medication</td>
            <td colspan="3" class="medication">{{ $order->medication_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Dosage</td>
            <td>{{ $order->dosage ?? 'N/A' }}</td>
            <td class="label">Route</td>
            <td>{{ $order->route ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Frequency</td>
            <td>{{ $order->frequency ?? 'N/A' }}</td>
            <td class="label">Quantity</td>
            <td>{{ $order->quantity ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Refills</td>
            <td>{{ $order->refills ?? 0 }}</td>
            <td class="label">Prescription Date</td>
            <td>{{ $prescriptionDate }}</td>
        </tr>
        <tr>
            <td class="label">Directions / SIG</td>
            <td colspan="3" class="directions">
                {{ $order->instructions ?? 'No additional directions provided.' }}
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Pharmacy Destination</div>
    <table>
        <tr>
            <td class="label">Pharmacy Name</td>
            <td>{{ $order->pharmacy_name ?? 'N/A' }}</td>
            <td class="label">Phone</td>
            <td>{{ $order->pharmacy_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>{{ $order->pharmacy_email ?? 'N/A' }}</td>
            <td class="label">Fax</td>
            <td>{{ $order->pharmacy_fax ?? 'N/A' }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Prescribing Provider</div>
    <table>
        <tr>
            <td class="label">Provider</td>
            <td>{{ $providerName }}</td>
            <td class="label">NPI</td>
            <td>{{ $order->provider->npi ?? 'N/A' }}</td>
        </tr>
    </table>

    <div style="padding: 12px;">
        <div class="signature-box">
            Provider Signature / Electronic Authorization<br>
            Generated electronically through KassCare provider workflow.
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">KassCare Verification</div>
    <table>
        <tr>
            <td class="label">Verification ID</td>
            <td>{{ $rxNumber }}-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Workflow</td>
            <td>Provider to Pharmacy</td>
        </tr>
    </table>
</div>

<div class="notice">
    This document is generated from structured data entered into KassCare.
    Pharmacy staff and provider must verify patient identity, medication, dosage,
    quantity, refills, pharmacy destination, and provider authorization before dispensing.
</div>

<div class="footer">
    Generated by KassCare SaaS • Premium ePrescription Workflow • {{ $rxNumber }}
</div>

</body>
</html>
