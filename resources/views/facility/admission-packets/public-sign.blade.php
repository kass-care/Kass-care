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

        <h1 class="text-3xl font-black text-slate-900">
            Resident Admission Agreement
        </h1>

        <p class="mt-2 text-slate-500">
            Please review and sign below using your finger, mouse, or stylus.
        </p>

        <div class="mt-6 rounded-2xl border bg-slate-50 p-5">
            <p><strong>Resident:</strong> {{ $admissionPacket->resident_name }}</p>
            <p><strong>Representative:</strong> {{ $admissionPacket->representative_name ?? 'N/A' }}</p>
            <p><strong>Monthly Rate:</strong> ${{ $admissionPacket->monthly_rate ?? '0' }}</p>
        </div>

        <div class="mt-6 whitespace-pre-line rounded-2xl border bg-slate-50 p-5 text-slate-700">
            {{ $admissionPacket->document_text }}
        </div>

        <form method="POST"
              action="{{ route('admission-packets.public-sign.store', $admissionPacket->public_token) }}"
              class="mt-6 space-y-5"
              onsubmit="return prepareSignature();">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Full Legal Name
                </label>

                <input name="signed_by"
                       required
                       placeholder="Type your full legal name"
                       class="w-full rounded-2xl border px-4 py-3">
            </div>

            <input type="hidden" name="signature" id="signature_text">
            <input type="hidden" name="signature_image" id="signature_image">

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">
                    Draw Your Signature
                </label>

                <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-3">
                    <canvas id="signaturePad"
                            class="h-48 w-full rounded-xl bg-white"
                            style="touch-action: none;"></canvas>
                </div>

                <div class="mt-3 flex flex-wrap gap-3">
                    <button type="button"
                            onclick="clearSignature()"
                            class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-black text-slate-700">
                        Clear Signature
                    </button>

                    <p class="text-sm font-semibold text-slate-500">
                        Sign inside the box above.
                    </p>
                </div>
            </div>

            <label class="block text-sm font-semibold text-slate-700">
                <input type="checkbox"
                       name="agreement_acceptance"
                       value="1"
                       required
                       class="mr-2">
                I have reviewed this admission agreement and agree to sign electronically.
            </label>

            <button class="rounded-2xl bg-indigo-700 px-6 py-3 font-black text-white">
                Sign Document
            </button>
        </form>
    </div>
</div>

<script>
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');

    let drawing = false;
    let hasSignature = false;

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#111827';
    }

    window.addEventListener('load', resizeCanvas);
    window.addEventListener('resize', resizeCanvas);

    function getPosition(event) {
        const rect = canvas.getBoundingClientRect();

        if (event.touches && event.touches.length > 0) {
            return {
                x: event.touches[0].clientX - rect.left,
                y: event.touches[0].clientY - rect.top
            };
        }

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    }

    function startDrawing(event) {
        event.preventDefault();
        drawing = true;
        hasSignature = true;

        const pos = getPosition(event);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(event) {
        if (!drawing) return;

        event.preventDefault();
        const pos = getPosition(event);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function stopDrawing(event) {
        event.preventDefault();
        drawing = false;
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
    }

    function prepareSignature() {
        const signedBy = document.querySelector('[name="signed_by"]').value;

        if (!hasSignature) {
            alert('Please draw your signature before submitting.');
            return false;
        }

        document.getElementById('signature_text').value = signedBy;
        document.getElementById('signature_image').value = canvas.toDataURL('image/png');

        return true;
    }
</script>
</body>
</html>
