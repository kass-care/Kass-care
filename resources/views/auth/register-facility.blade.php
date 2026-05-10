<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Facility | KASSCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-white/10">

        {{-- LEFT --}}
        <div class="bg-gradient-to-tr from-slate-950 via-indigo-950 to-cyan-950 p-10">
            <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-bold">
                KASS CARE SAAS
            </p>

            <h1 class="mt-4 text-4xl font-black leading-tight">
                Register Your Facility
            </h1>

            <p class="mt-5 text-sm text-slate-300 leading-7">
                Launch your facility on KASSCare and unlock clean operations for patients,
                caregivers, visits, charting, facility messaging, and subscription-based growth.
            </p>

            <div class="mt-8 space-y-4">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <h3 class="text-lg font-bold text-white">What happens next</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                        <li>• Facility profile is created</li>
                        <li>• Facility admin account is created</li>
                        <li>• You are logged in automatically</li>
                        <li>• You continue to Facility billing</li>
                    </ul>
                </div>

                <div class="rounded-2xl bg-cyan-500/10 border border-cyan-400/20 p-4">
                    <h3 class="text-lg font-bold text-cyan-200">Facility Plan</h3>
                    <p class="mt-2 text-sm text-cyan-100/90">
                        Facility onboarding uses the Facility subscription only.
                        Provider plans are handled separately through provider onboarding.
                    </p>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="bg-slate-900 p-8">
            <h2 class="text-2xl font-black mb-6">Facility Onboarding</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-red-300/20 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register-facility.store') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="plan" value="facility">

                <input
                    type="text"
                    name="facility_name"
                    value="{{ old('facility_name') }}"
                    placeholder="Facility Name"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                    required
                >

                <input
                    type="email"
                    name="facility_email"
                    value="{{ old('facility_email') }}"
                    placeholder="Facility Email"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                >

                <input
                    type="text"
                    name="admin_name"
                    value="{{ old('admin_name') }}"
                    placeholder="Admin Name"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                    required
                >

                <input
                    type="email"
                    name="admin_email"
                    value="{{ old('admin_email') }}"
                    placeholder="Admin Email"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                    required
                >

                <div class="rounded-2xl border border-indigo-400/20 bg-indigo-500/10 px-4 py-4">
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-200 font-bold">
                        Selected Plan
                    </p>
                    <p class="mt-2 text-2xl font-black text-white">
                        Facility — $79/month
                    </p>
                    <p class="mt-1 text-sm text-slate-300">
                        Includes clients, caregivers, visits, care logs, messaging, and facility dashboard access.
                    </p>
                </div>

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                    required
                >

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder:text-slate-400"
                    required
                >

             <div class="rounded-2xl border border-cyan-400/20 bg-cyan-500/10 p-4">
    <label class="flex items-start gap-3 text-sm text-slate-200 leading-6">
        <input
            type="checkbox"
            name="accept_terms"
            value="1"
            required
            class="mt-1 rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500"
        >

        <span>
            I have read, understood, and agree to the
            <a href="{{ url('/terms') }}" target="_blank" class="font-bold text-cyan-300 underline">
                Terms & Conditions
            </a>
            of KassCare and KASS MTV USA LLC. I understand that KassCare is a healthcare operations platform and that my facility is responsible for lawful use, accurate information, protecting patient/client data, user account security, subscription billing, and compliance with applicable healthcare, privacy, and workplace requirements. I also understand that access may be suspended or terminated for non-payment, misuse, fraud, abuse, or violation of these terms.
        </span>
    </label>

    @error('accept_terms')
        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
    @enderror
</div>
                <button
                    type="submit"
                    class="w-full rounded-2xl bg-indigo-600 py-3.5 font-bold text-white transition hover:bg-indigo-700"
                >
                    Create Facility & Continue
                </button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
