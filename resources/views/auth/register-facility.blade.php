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
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden rounded-3xl shadow-2xl border">

        {{-- LEFT --}}
        <div class="bg-gradient-to-tr from-slate-950 via-indigo-950 to-cyan-950 p-10">
            <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-bold">KASS CARE SAAS</p>

            <h1 class="mt-4 text-4xl font-black leading-tight">
                Register Your Facility
            </h1>

            <p class="mt-5 text-sm text-slate-300 leading-7">
                Launch your facility on KASSCare and unlock clean operations for patients, caregivers,
                providers, visits, charting, and subscription-based growth.
            </p>

            <div class="mt-8 space-y-4">
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <h3 class="text-lg font-bold text-white">What happens next</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                        <li>• Facility created</li>
                        <li>• Admin account created</li>
                        <li>• Auto login</li>
                        <li>• Redirect to billing</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="bg-slate-900 p-8">
            <h2 class="text-2xl font-black mb-6">Facility Onboarding</h2>

            {{-- ERROR DISPLAY --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-500 text-white p-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register-facility.store') }}" class="space-y-4">
                @csrf

                {{-- Required because controller expects it --}}
                <input type="hidden" name="accept_terms" value="1">

                <input type="text" name="facility_name" placeholder="Facility Name"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3" required>

                <input type="email" name="facility_email" placeholder="Facility Email"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3">

                <input type="text" name="admin_name" placeholder="Admin Name"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3" required>

                <input type="email" name="admin_email" placeholder="Admin Email"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3" required>

                {{-- NEW PLAN STRUCTURE --}}
                <select name="plan"
                        class="w-full rounded-2xl bg-slate-800 px-4 py-3"
                        required>
                    <option value="">Select a plan</option>
                    <option value="facility">Facility — $79/month</option>
                    <option value="provider_solo">Provider Solo — $99/month</option>
                    <option value="provider_pro">Provider Pro — $199/month</option>
                </select>

                <input type="password" name="password" placeholder="Password"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3" required>

                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                       class="w-full rounded-2xl bg-slate-800 px-4 py-3" required>

                <button type="submit"
                        class="w-full bg-indigo-600 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition">
                    Create Facility & Continue
                </button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
