<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height:1.6; color:#111827;">

<h2>KassCare Admission Agreement</h2>

<p>Hello,</p>

<p>
Please review and sign the admission agreement for
<strong>{{ $packet->resident_name }}</strong>.
</p>

<p>
Click the secure signing link below:
</p>

<p>
<a href="{{ route('admission-packets.public-sign', $packet->public_token) }}"
   style="display:inline-block;background:#4338ca;color:#ffffff;padding:12px 18px;text-decoration:none;border-radius:8px;font-weight:bold;">
    Sign Admission Agreement
</a>
</p>

<p>
If the button does not work, copy and paste this link into your browser:
</p>

<p style="word-break:break-all;">
{{ route('admission-packets.public-sign', $packet->public_token) }}
</p>

<p>
If you did not expect this email, please contact the facility directly.
</p>

<p>
Thank you,<br>
KASSCare Admissions Center
</p>

</body>
</html>
