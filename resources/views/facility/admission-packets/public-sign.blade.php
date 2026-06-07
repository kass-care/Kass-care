<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Admission Packet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 py-8">
<div class="mx-auto max-w-4xl px-4">
    <div class="rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black text-slate-900">Resident Admission Agreement</h1>
        <p class="mt-2 text-slate-500">Please review and sign below.</p>

        <div class="mt-6 rounded-2xl border bg-slate-50 p-5">
            <p><strong>Resident:</strong> {{ $admissionPacket->resident_name }}</p>
            <p><strong>Representative:</strong> {{ $admissionPacket->representative_name ?? 'N/A' }}</p>
            <p><strong>Monthly Rate:</strong> ${{ $admissionPacket->monthly_rate ?? '0' }}</p>
        </div>

        <div class="mt-6 whitespace-pre-line rounded-2xl border bg-slate-50 p-5 text-slate-700">
            {{ $admissionPacket->document_text }}
        </div>

        <form method="POST" action="{{ route('admission-packets.public-sign.store', $admissionPacket->public_token) }}" class="mt-6 space-y-4">
            @csrf

            <input name="signed_by" required placeholder="Type your full legal name"
                   class="w-full rounded-2xl border px-4 py-3">

            <input name="signature" required placeholder="Type your signature"
                   class="w-full rounded-2xl border px-4 py-3">

            <label class="block text-sm font-semibold text-slate-700">
                <input type="checkbox" name="agreement_acceptance" value="1" required class="mr-2">
                I have reviewed this admission agreement and agree to sign electronically.
            </label>

            <button class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white">
                Sign Document
            </button>
        </form>
    </div>
</div>
</body>
</html>
