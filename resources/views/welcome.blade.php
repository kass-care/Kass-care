<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KassCare — Healthcare should not be a struggle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white antialiased">

    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.30),_transparent_35%),linear-gradient(135deg,_#020617_0%,_#0f172a_45%,_#1d4ed8_100%)]">

        <!-- NAVBAR -->
        <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/60 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
                <a href="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400/20 text-cyan-300 shadow-lg shadow-cyan-500/10">
                        <span class="text-lg font-bold">K</span>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold tracking-tight">KassCare</p>
                        <p class="text-xs text-slate-300">Healthcare Management Platform</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-8 text-sm font-medium text-slate-200 md:flex">
                    <a href="#platform" class="transition hover:text-cyan-300">Platform</a>
                    <a href="#solutions" class="transition hover:text-cyan-300">Solutions</a>
                    <a href="#pricing" class="transition hover:text-cyan-300">Pricing</a>
                    <a href="#security" class="transition hover:text-cyan-300">Security</a>
                    <a href="{{ route('login') }}" class="transition hover:text-cyan-300">Sign In</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="{{ route('register-facility') }}"
                       class="rounded-2xl border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">
                        Register Facility
                    </a>
                </div>
            </div>
        </header>

        <!-- HERO -->
        <section class="relative overflow-hidden">
            <div class="mx-auto grid max-w-7xl gap-14 px-6 py-20 lg:grid-cols-2 lg:px-8 lg:py-28">
                <div class="flex flex-col justify-center">
                    <div class="mb-6 inline-flex w-fit items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-sm font-semibold text-cyan-200">
                        <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                        Healthcare should not be a struggle
                    </div>

                    <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-7xl">
                        The modern healthcare operations platform for
                        <span class="text-cyan-300">providers, facilities, and caregivers</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-200">
                        KassCare brings care documentation, facility workflows, caregiver coordination,
                        provider intelligence, compliance tracking, and patient visibility into one powerful platform.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register-facility') }}"
                           class="rounded-2xl bg-indigo-500 px-6 py-3 text-sm font-bold text-white shadow-xl shadow-indigo-900/40 transition hover:bg-indigo-400">
                            Register Facility
                        </a>

                        <a href="{{ route('login') }}"
                           class="rounded-2xl border border-white/15 bg-white/10 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                            Sign In
                        </a>
                    </div>

                    <p class="mt-6 text-sm text-slate-300">
                        Built for real healthcare providers and facilities.
                    </p>
                </div>

                <!-- HERO VISUAL -->
                <div class="relative">
                    <div class="absolute -left-10 top-8 hidden h-28 w-28 rounded-full bg-cyan-400/20 blur-3xl lg:block"></div>
                    <div class="absolute -right-8 bottom-0 hidden h-36 w-36 rounded-full bg-indigo-500/20 blur-3xl lg:block"></div>

                    <div class="rounded-[28px] border border-white/10 bg-slate-900/70 p-5 shadow-2xl shadow-slate-950/50 backdrop-blur">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-300">KassCare Command View</p>
                                <p class="text-xs text-slate-400">Real-time care coordination and compliance insight</p>
                            </div>
                            <div class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                                Live workflow
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-blue-400/20 bg-blue-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-blue-200">Providers</p>
                                <p class="mt-2 text-2xl font-black">Workspace</p>
                                <p class="mt-2 text-sm text-slate-300">
                                    Alerts, notes, pharmacy workflow, patient summaries, and compliance tracking.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-emerald-200">Facilities</p>
                                <p class="mt-2 text-2xl font-black">Operations</p>
                                <p class="mt-2 text-sm text-slate-300">
                                    Manage caregivers, clients, visits, onboarding, and billing from one dashboard.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-fuchsia-400/20 bg-fuchsia-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-fuchsia-200">Caregivers</p>
                                <p class="mt-2 text-2xl font-black">Visit Flow</p>
                                <p class="mt-2 text-sm text-slate-300">
                                    Daily visit workflows, charting, care logs, and field accountability.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-amber-200">Leadership</p>
                                <p class="mt-2 text-2xl font-black">Visibility</p>
                                <p class="mt-2 text-sm text-slate-300">
                                    Gain platform-level insight for quality, growth, safety, and performance.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STORY -->
        <section id="platform" class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The problem</p>
                    <h3 class="mt-4 text-2xl font-bold">Care operations are too fragmented</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Providers, facility teams, and caregivers often work across disconnected notes,
                        spreadsheets, calls, texts, and memory. That slows care, hides risks, and creates compliance pressure.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The solution</p>
                    <h3 class="mt-4 text-2xl font-bold">KassCare centralizes the workflow</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        With KassCare, your team works from one modern healthcare operations platform
                        designed for patient visibility, visit coordination, provider decision support, and operational clarity.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The outcome</p>
                    <h3 class="mt-4 text-2xl font-bold">More clarity. Less paperwork. Better care.</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Teams move faster, documentation becomes cleaner, workflows become accountable,
                        and leadership gets the visibility needed to run healthcare operations with confidence.
                    </p>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="mb-12 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Core platform value</p>
                <h2 class="mt-4 text-3xl font-black tracking-tight sm:text-5xl">One platform for healthcare operations</h2>
                <p class="mx-auto mt-4 max-w-3xl text-slate-300">
                    KassCare is designed to reduce paperwork, improve coordination, and give providers and facilities
                    the tools they need to manage care with confidence.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Care operations</p>
                    <h3 class="mt-4 text-2xl font-bold">Caregiver workflows and visit documentation</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Streamline scheduled visits, EVV documentation, care logs, and daily patient support in one place.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-200">
                        <li>• Visit scheduling and tracking</li>
                        <li>• Care logs and documentation</li>
                        <li>• Patient activity visibility</li>
                        <li>• Better operational accountability</li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Clinical intelligence</p>
                    <h3 class="mt-4 text-2xl font-bold">Provider workspace, alerts, and compliance</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Give providers a smarter way to review patients, manage notes, monitor alerts,
                        and stay ahead of rounds and compliance.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-200">
                        <li>• Provider dashboard and workspace</li>
                        <li>• Smart clinical alert visibility</li>
                        <li>• Compliance and rounds tracking</li>
                        <li>• Faster patient review workflows</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- SOLUTIONS -->
        <section id="solutions" class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
            <div class="mb-12 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Solutions</p>
                <h2 class="mt-4 text-3xl font-black tracking-tight sm:text-5xl">Built for the people who keep care moving</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl shadow-slate-950/40">
                    <h3 class="text-2xl font-bold">Providers</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Access patient intelligence, notes, alerts, and compliance views from one clinical workspace.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl shadow-slate-950/40">
                    <h3 class="text-2xl font-bold">Facilities</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Manage patients, caregivers, visits, and provider coordination without drowning in paperwork.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl shadow-slate-950/40">
                    <h3 class="text-2xl font-bold">Caregivers</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Stay organized with daily visit workflows, charting, and clear task visibility in the field.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-xl shadow-slate-950/40">
                    <h3 class="text-2xl font-bold">Multi-Facility Teams</h3>
                    <p class="mt-4 text-sm leading-7 text-slate-300">
                        Gain platform-level oversight for growth, performance, compliance, and facility operations.
                    </p>
                </div>
            </div>
        </section>

        <!-- PRICING PREVIEW -->
        <section id="pricing" class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="mb-12 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Pricing</p>
                <h2 class="mt-4 text-3xl font-black tracking-tight sm:text-5xl">Simple plans for growing care operations</h2>
                <p class="mx-auto mt-4 max-w-3xl text-slate-300">
                    Choose the plan that matches your facility footprint and growth stage.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8">
                    <p class="text-sm font-semibold text-cyan-300">Starter</p>
                    <p class="mt-4 text-4xl font-black">$49<span class="text-base font-medium text-slate-300">/mo</span></p>
                    <p class="mt-4 text-sm text-slate-300">Ideal for a single facility getting started.</p>
                </div>

                <div class="rounded-3xl border border-indigo-400/30 bg-indigo-500/10 p-8 shadow-2xl shadow-indigo-950/30">
                    <p class="text-sm font-semibold text-indigo-200">Growth</p>
                    <p class="mt-4 text-4xl font-black">$99<span class="text-base font-medium text-slate-300">/mo</span></p>
                    <p class="mt-4 text-sm text-slate-300">For growing teams managing multiple workflows.</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8">
                    <p class="text-sm font-semibold text-cyan-300">Enterprise</p>
                    <p class="mt-4 text-4xl font-black">$199<span class="text-base font-medium text-slate-300">/mo</span></p>
                    <p class="mt-4 text-sm text-slate-300">Built for expansion, oversight, and serious operations.</p>
                </div>
            </div>
        </section>

        <!-- SECURITY -->
        <section id="security" class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
            <div class="rounded-[32px] border border-white/10 bg-white/5 px-8 py-10 text-center backdrop-blur">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">Security + control</p>
                <h2 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                    Built with healthcare accountability in mind
                </h2>
                <p class="mx-auto mt-4 max-w-3xl text-slate-300">
                    KassCare is designed for structured operations, cleaner access control, and safer coordination
                    across facilities, providers, and caregivers.
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register-facility') }}"
                       class="rounded-2xl bg-indigo-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-indigo-400">
                        Launch your facility on KassCare
                    </a>
                    <a href="{{ route('login') }}"
                       class="rounded-2xl border border-white/15 bg-white/10 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                        Access platform
                    </a>
                </div>
            </div>
        </section>

        <footer class="mx-auto mt-16 max-w-7xl px-6 pb-12 pt-4 text-center text-sm text-slate-400 lg:px-8">
            KassCare © {{ date('Y') }} · Built for providers, facilities, caregivers, and platform admins.
        </footer>

    </div>
</body>
</html>
