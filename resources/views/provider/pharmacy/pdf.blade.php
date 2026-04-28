<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KASS Care Prescription</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 28px;
        }

        .top-bar {
            background: #312e81;
            color: #ffffff;
            padding: 18px 22px;
            border-radius: 8px;
        }

        .brand {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 12px;
            margin-top: 4px;
            color: #e0e7ff;
        }

        .rx-box {
            margin-top: 14px;
            border: 2px solid #312e81;
            padding: 14px 18px;
            border-radius: 8px;
        }

        .rx-title {
            font-size: 20px;
            font-weight: bold;
            color: #312e81;
            margin-bottom: 6px;
        }

        .meta {
            font-size: 11px;
            color: #4b5563;
        }

        .section {
            margin-top: 18px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: #eef2ff;
            color: #312e81;
            font-weight: bold;
            padding: 9px 12px;
            font-size: 13px;
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

        .value {
            color: #111827;
        }

        .medication-name {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .instructions-box {
            padding: 12px;
            min-height: 55px;
            line-height: 1.6;
        }

        .two-column {
            width: 100%;
            margin-top: 18px;
        }

        .col {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .col-right {
            margin-left: 3%;
        }

        .signature {
            margin-top: 28px;
            border-top: 1px solid #111827;
            padding-top: 7px;
            font-size: 11px;
        }

        .notice {
            margin-top: 20px;
            background: #fefce8;
            border: 1px solid #fde68a;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 10.5px;
            line-height: 1.5;
            color: #713f12;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
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

    $prescriptionDate = $order->prescribed_at
        ? \Carbon\Carbon::parse($order->prescribed_at)->format('F j, Y g:i A')
        : now()->format('F j, Y g:i A');

    $rxNumber = 'RX-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
@endphp

<div class="top-bar">
    <div class="brand">KASS Care</div>
    <div class="subtitle">Prescription Communication • Provider to Pharmacy Workflow</div>
</div>

<div class="rx-box">
    <div class="rx-title">Prescription Order</div>
    <div class="meta">
        <strong>Prescription #:</strong> {{ $rxNumber }}<br>
        <strong>Date Generated:</strong> {{ now()->format('F j, Y g:i A') }}<br>
        <strong>Status:</strong> <span class="status">{{ $order->status ?? 'Pending' }}</span>
    </div>
</div>

<div class="section">
    <div class="section-title">Patient Information</div>
    <table>
        <tr>
            <td class="label">Patient Name</td>
            <td class="value">{{ ucwords(strtolower($clientName)) }}</td>
        </tr>
        <tr>
            <td class="label">Facility</td>
            <td class="value">{{ $facilityName }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Medication / Prescription Details</div>
    <table>
        <tr>
            <td class="label">Medication</td>
            <td class="value medication-name">{{ $order->medication_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Dosage</td>
            <td class="value">{{ $order->dosage ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Frequency</td>
            <td class="value">{{ $order->frequency ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Route</td>
            <td class="value">{{ $order->route ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Dispense Quantity</td>
            <td class="value">{{ $order->quantity ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Refills</td>
            <td class="value">{{ $order->refills ?? 0 }}</td>
        </tr>
        <tr>
            <td class="label">Prescription Date</td>
            <td class="value">{{ $prescriptionDate }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Special Instructions</div>
    <div class="instructions-box">
        {{ $order->instructions ?? 'No additional instructions provided.' }}
    </div>
</div>

<div class="section">
    <div class="section-title">Pharmacy Destination</div>
    <table>
        <tr>
            <td class="label">Pharmacy Name</td>
            <td class="value">{{ $order->pharmacy_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Pharmacy Email</td>
            <td class="value">{{ $order->pharmacy_email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Phone</td>
            <td class="value">{{ $order->pharmacy_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Fax</td>
            <td class="value">{{ $order->pharmacy_fax ?? 'N/A' }}</td>
        </tr>
    </table>
</div>

<div class="two-column">
    <div class="col">
        <div class="section">
            <div class="section-title">Prescribing Provider</div>
            <table>
                <tr>
                    <td class="label">Provider</td>
                    <td class="value">{{ $providerName }}</td>
                </tr>
                <tr>
                    <td class="label">NPI</td>
                    <td class="value">{{ $order->provider->npi ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col col-right">
        <div class="section">
            <div class="section-title">Authorization</div>
            <div style="padding: 14px;">
                <div class="signature">
                    Provider Signature / Authorization
                </div>
                <div style="margin-top: 12px; font-size: 10px; color: #6b7280;">
                    Generated electronically through KASS Care.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="notice">
    This prescription communication was generated from structured data entered into KASS Care.
    Please verify patient, medication, dosage, quantity, refills, and provider information before dispensing.
</div>

<div class="footer">
    Generated by KASS Care SaaS • Prescription Workflow • {{ $rxNumber }}
</div>

</body>
</html>
