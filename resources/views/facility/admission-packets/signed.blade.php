<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document Signed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-green-50 flex items-center justify-center px-4">
    <div class="max-w-xl rounded-3xl bg-white p-8 text-center shadow-xl">
        <h1 class="text-3xl font-black text-green-800">✅ Document Signed</h1>
        <p class="mt-3 text-slate-600">
            Thank you. The admission agreement for {{ $admissionPacket->resident_name }} has been signed successfully.
        </p>
        <p class="mt-4 text-sm font-semibold text-slate-500">
            Signed by {{ $admissionPacket->signed_by }} on {{ $admissionPacket->signed_at->format('M d, Y g:i A') }}
        </p>
    </div>
</body>
</html>
