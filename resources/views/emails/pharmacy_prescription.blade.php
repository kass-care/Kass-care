<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KASS Care Prescription</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: Arial, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 18px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:linear-gradient(90deg, #4f46e5, #4338ca); padding:24px 30px; color:#ffffff;">
                            <div style="font-size:28px; font-weight:700; letter-spacing:0.5px;">KASS Care</div>
                            <div style="font-size:14px; opacity:0.9; margin-top:4px;">
                                Prescription Communication
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 16px; font-size:15px;">
                                Hello,
                            </p>

                            <p style="margin:0 0 20px; font-size:15px; line-height:1.6;">
                                Please process the following prescription from <strong>KASS Care</strong>.
                                A PDF prescription is attached for your records.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:22px;">
                                <tr>
                                    <td colspan="2" style="font-size:18px; font-weight:700; color:#4338ca; padding-bottom:12px;">
                                        Patient & Prescription Details
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; width:220px; font-weight:600;">Client</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->client->name ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Medication</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->medication_name ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Dosage</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->dosage ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Frequency</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->frequency ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Quantity</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->quantity ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Refills</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->refills ?? 0 }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Instructions</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->instructions ?? 'None provided' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Prescription Date</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->prescribed_at ?? now() }}</td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:22px;">
                                <tr>
                                    <td colspan="2" style="font-size:18px; font-weight:700; color:#4338ca; padding-bottom:12px;">
                                        Pharmacy Information
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; width:220px; font-weight:600;">Pharmacy Name</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->pharmacy_name ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Pharmacy Phone</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->pharmacy_phone ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Pharmacy Fax</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->pharmacy_fax ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 12px; background:#f9fafb; border:1px solid #e5e7eb; font-weight:600;">Pharmacy Email</td>
                                    <td style="padding:10px 12px; border:1px solid #e5e7eb;">{{ $order->pharmacy_email ?? 'N/A' }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:24px; padding:16px; background:#eef2ff; border-left:4px solid #4338ca; font-size:14px; line-height:1.6;">
                                This message was generated by <strong>KASS Care</strong>. Please see the attached PDF prescription for a clean printable version.
                            </div>

                            <p style="margin:24px 0 0; font-size:15px; line-height:1.7;">
                                Thank you,<br>
                                <strong>KASS Care Team</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 30px; background:#f9fafb; color:#6b7280; font-size:12px; text-align:center;">
                            KASS Care SaaS • Secure Prescription Workflow
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
