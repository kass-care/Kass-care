<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Provider | KASSCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-white">

<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-white/10">

        {{-- LEFT --}}
        <div class="bg-gradient-to-tr from-indigo-950 via-purple-950 to-slate-950 p-10">
            <p class="text-xs uppercase tracking-[0.35em] text-indigo-300 font-bold">
                KASS CARE PROVIDER
            </p>

            <h1 class="mt-4 text-4xl font-black leading-tight">
                Join as a Provider
            </h1>

            <p class="mt-5 text-sm text-slate-300 leading-7">
                Manage multiple facilities, reduce paperwork, and access intelligent
                clinical workflows designed for modern providers.
            </p>

            <div class="mt-8 space-y-4">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <h3 class="text-lg font-bold text-white">What you get</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                        <li>• Provider dashboard</li>
                        <li>• Clinical notes & coding</li>
                        <li>• Claims generation</li>
                        <li>• Multi-facility workflow</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="bg-slate-900 p-8">
            <h2 class="text-2xl font-black mb-6">Provider Registration</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-red-300/20 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register-provider.store') }}" class="space-y-4">
                @csrf


                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3"
                    required
                >

                {{-- PROVIDER ONLY PLANS --}}
                <select name="plan"
                        class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3"
                        required>
                    <option value="">Select Provider Plan</option>
                    <option value="provider_solo">Provider Solo — $99/month</option>
                    <option value="provider_pro">Provider Pro — $199/month</option>
                </select>

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3"
                    required
                >

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3"
                    required
                >
                 <div class="rounded-2xl border border-indigo-400/20 bg-indigo-500/10 p-4">
    <label class="flex items-start gap-3 text-sm text-slate-200 leading-6">
        <input
            type="checkbox"
            name="accept_terms"
            value="1"
            required
            class="mt-1 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500"
        >

        <span>
            I have read, understood, and agree to the
            <a href="{{ url('/terms') }}" target="_blank" class="font-bold text-indigo-300 underline">
                Terms & Conditions
            </a>
            of KassCare and KASS MTV USA LLC. I understand that KassCare is a healthcare operations platform and that I am responsible for lawful clinical use, protecting patient/client data, account security, accurate documentation, billing compliance, and professional use of the platform. I also understand that my 30-day provider trial may convert to a paid subscription and that access may be suspended or terminated for non-payment, misuse, fraud, abuse, or violation of these terms.
        </span>
    </label>

    @error('accept_terms')
        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
    @enderror
</div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-indigo-600 py-3.5 font-bold hover:bg-indigo-700"
                >
                    Register Provider & Continue
                </button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
