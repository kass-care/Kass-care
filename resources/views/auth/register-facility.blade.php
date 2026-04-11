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
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border border-slate-800 bg-slate-900">

            {{-- Left side --}}
            <div class="bg-gradient-to-tr from-slate-950 via-indigo-950 to-cyan-950 p-10 lg:p-12">
                <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-bold">KASS CARE SAAS</p>

                <h1 class="mt-4 text-4xl font-black leading-tight">
                    Register Your Facility
                </h1>

                <p class="mt-5 text-sm md:text-base text-slate-300 leading-7">
                    Launch your facility on KASSCare and unlock clean operations for patients, caregivers,
                    providers, visits, charting, and subscription-based growth.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                        <h3 class="text-lg font-bold text-white">What happens next</h3>
                        <ul class="mt-3 space-y-2 text-sm text-slate-300">
                            <li>• Your facility profile is created</li>
                            <li>• Your facility admin account is created</li>
                            <li>• You are signed in automatically</li>
                            <li>• You continue to billing activation</li>
                        </ul>
                    </div>

                    <div class="rounded-2xl bg-cyan-500/10 border border-cyan-400/20 p-4">
                        <h3 class="text-lg font-bold text-cyan-200">Built for growth</h3>
                        <p class="mt-2 text-sm text-cyan-100/90">
                            Start small, then expand to multiple facilities under your subscription plan.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-indigo-500/10 border border-indigo-400/20 p-4">
                        <h3 class="text-lg font-bold text-indigo-200">Legal onboarding</h3>
                        <p class="mt-2 text-sm text-slate-300 leading-6">
                            By registering, the facility agrees to the KASSCare Terms &amp; Conditions and
                            acknowledges platform onboarding under <span class="font-semibold text-white">KASS MTV USA LLC</span>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right side --}}
            <div class="bg-slate-900 p-8 lg:p-10">
                <h2 class="text-2xl font-black text-white mb-6">Facility Onboarding</h2>

                @if(session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-300/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-300/20 bg-rose-500/10 px-4 py-4 text-sm text-rose-200">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register-facility.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Facility Name</label>
                        <input
                            type="text"
                            name="facility_name"
                            value="{{ old('facility_name') }}"
                            placeholder="Sunrise AFH 4"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Facility Email</label>
                        <input
                            type="email"
                            name="facility_email"
                            value="{{ old('facility_email') }}"
                            placeholder="facility@example.com"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Admin Name</label>
                        <input
                            type="text"
                            name="admin_name"
                            value="{{ old('admin_name') }}"
                            placeholder="Facility Admin"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Admin Email</label>
                        <input
                            type="email"
                            name="admin_email"
                            value="{{ old('admin_email') }}"
                            placeholder="admin@example.com"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Choose Plan</label>
                        <select
                            name="plan"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white focus:border-indigo-500 focus:outline-none"
                            required
                        >
                            <option value="">Select a plan</option>
                            @foreach($plans as $key => $plan)
                                <option value="{{ $key }}" {{ old('plan') === $key ? 'selected' : '' }}>
                                    {{ $plan['name'] }} — {{ $plan['price_label'] }} — {{ $plan['facility_limit'] }} facility limit
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Password</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Create a secure password"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm password"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 py-3 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none"
                            required
                        >
                    </div>

                    {{-- Terms and Conditions --}}
                    <div class="rounded-2xl border border-slate-700 bg-slate-800/70 px-4 py-4">
                        <label class="flex items-start gap-3">
                            <input
                                type="checkbox"
                                name="accept_terms"
                                value="1"
                                {{ old('accept_terms') ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-slate-500 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                required
                            >
                            <span class="text-sm text-slate-300 leading-6">
                                I agree to the
                                <a href="{{ route('terms') }}" target="_blank" class="font-semibold text-cyan-300 hover:text-cyan-200 underline underline-offset-4">
                                    KASSCare Terms &amp; Conditions
                                </a>
                                and acknowledge that this onboarding is for services provided under
                                <span class="font-semibold text-white">KASS MTV USA LLC</span>.
                            </span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-indigo-500"
                    >
                        Create Facility &amp; Continue
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
