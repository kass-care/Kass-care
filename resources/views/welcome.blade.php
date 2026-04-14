<!DOCTYPE html>
<html lang="en">
<style>
    @keyframes kasscareFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    @keyframes kasscarePulseGlow {
        0%, 100% {
            box-shadow: 0 0 0 rgba(34, 211, 238, 0.0), 0 0 0 rgba(59, 130, 246, 0.0);
            opacity: 0.9;
        }
        50% {
            box-shadow: 0 0 22px rgba(34, 211, 238, 0.18), 0 0 42px rgba(59, 130, 246, 0.16);
            opacity: 1;
        }
    }

    @keyframes kasscareTwinkle {
        0%, 100% { opacity: 0.18; transform: scale(1); }
        50% { opacity: 0.55; transform: scale(1.35); }
    }

    @keyframes kasscareDrift {
        0%   { transform: translateY(0px) translateX(0px); }
        50%  { transform: translateY(-14px) translateX(6px); }
        100% { transform: translateY(0px) translateX(0px); }
    }

    .kasscare-float {
        animation: kasscareFloat 6s ease-in-out infinite;
    }

    .kasscare-live-badge {
        animation: kasscarePulseGlow 2.4s ease-in-out infinite;
    }

    .kasscare-particle {
        position: absolute;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.35);
        animation: kasscareTwinkle 2.8s ease-in-out infinite, kasscareDrift 10s ease-in-out infinite;
        pointer-events: none;
    }

    .kasscare-particle:nth-child(2n) {
        animation-duration: 3.4s, 12s;
    }

    .kasscare-particle:nth-child(3n) {
        animation-duration: 4.1s, 14s;
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KassCare — Healthcare should not be a struggle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white antialiased">

    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.18),_transparent_22%),radial-gradient(circle_at_right,_rgba(99,102,241,0.18),_transparent_25%),linear-gradient(135deg,_#020617_0%,_#0f172a_48%,_#1d4ed8_100%)]">

        <!-- NAVBAR -->
      <!-- NAVBAR -->
<header class="sticky top-0 z-50 border-b border-white/8 bg-slate-950/55 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">

        <a href="/" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-cyan-400/20 bg-cyan-400/10 shadow-[0_0_20px_rgba(34,211,238,0.12)]">
                <span class="text-lg font-black text-cyan-200">K</span>
            </div>
            <div>
                <p class="text-xl font-extrabold tracking-tight text-white">KassCare</p>
                <p class="text-xs text-slate-300">Healthcare Management Platform</p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 text-sm font-semibold text-slate-200 md:flex">
            <a href="#platform" class="transition hover:text-cyan-300">Platform</a>
            <a href="#showcase" class="transition hover:text-cyan-300">Product</a>
            <a href="#solutions" class="transition hover:text-cyan-300">Solutions</a>
            <a href="#pricing" class="transition hover:text-cyan-300">Pricing</a>
            <a href="#security" class="transition hover:text-cyan-300">Security</a>
            <a href="{{ route('login') }}" class="transition hover:text-cyan-300">Sign In</a>
        </nav>

        <a href="{{ route('register-facility') }}"
           class="inline-flex items-center rounded-2xl border border-white/12 bg-white/6 px-5 py-3 text-sm font-bold text-white shadow-[0_10px_30px_rgba(15,23,42,0.35)] transition hover:border-cyan-300/40 hover:bg-cyan-400/10 hover:text-cyan-200">
            Register Facility
        </a>

    </div>
</header>

        <!-- HERO -->
        <section class="relative overflow-hidden">
            <div class="mx-auto grid max-w-7xl gap-16 px-6 pb-16 pt-16 lg:grid-cols-2 lg:px-8 lg:pb-24 lg:pt-24">
                <div class="flex flex-col justify-center">
                    <div class="mb-6 inline-flex w-fit items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-sm font-semibold text-cyan-200">
                        <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                        Healthcare should not be a struggle
                    </div>

                    <h1 class="max-w-3xl text-5xl font-black leading-[0.95] tracking-tight sm:text-6xl lg:text-7xl">
                        The modern healthcare operations platform for
                        <span class="bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent">
                            providers, facilities, and caregivers
                        </span>
                    </h1>

                    <p class="mt-8 max-w-2xl text-lg leading-8 text-slate-200">
                        KassCare brings care documentation, facility workflows, caregiver coordination,
                        provider intelligence, compliance tracking, and patient visibility into one powerful platform.
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('register-facility') }}"
                           class="rounded-2xl bg-indigo-500 px-6 py-3.5 text-sm font-black text-white shadow-2xl shadow-indigo-950/40 transition hover:bg-indigo-400">
                            Register Facility
                        </a>

                        <a href="{{ route('login') }}"
                           class="rounded-2xl border border-white/15 bg-white/10 px-6 py-3.5 text-sm font-black text-white transition hover:bg-white/20">
                            Sign In
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-6 text-sm text-slate-300">
                        <span>Built for providers</span>
                        <span>•</span>
                        <span>Built for facilities</span>
                        <span>•</span>
                        <span>Built for caregivers</span>
                    </div>
                </div>

                <!-- HERO VISUAL -->
                <div class="relative">
                    <div class="absolute -left-10 top-10 hidden h-28 w-28 rounded-full bg-cyan-400/20 blur-3xl lg:block"></div>
                    <div class="absolute -right-10 bottom-8 hidden h-40 w-40 rounded-full bg-indigo-500/20 blur-3xl lg:block"></div>

                    <div class="rounded-[30px] border border-white/10 bg-slate-900/70 p-5 shadow-2xl shadow-slate-950/60 backdrop-blur">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-200">KassCare Command View</p>
                                <p class="text-xs text-slate-400">Real-time care coordination and compliance insight</p>
                            </div>
                            <div class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">
                                Live workflow
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-blue-400/20 bg-blue-500/10 p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-200">Providers</p>
                                <p class="mt-3 text-3xl font-black">Workspace</p>
                                <p class="mt-3 text-sm leading-6 text-slate-300">
                                    Alerts, notes, pharmacy workflow, patient summaries, and compliance tracking.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-200">Facilities</p>
                                <p class="mt-3 text-3xl font-black">Operations</p>
                                <p class="mt-3 text-sm leading-6 text-slate-300">
                                    Manage caregivers, clients, visits, onboarding, and billing from one dashboard.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-fuchsia-400/20 bg-fuchsia-500/10 p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-fuchsia-200">Caregivers</p>
                                <p class="mt-3 text-3xl font-black">Visit Flow</p>
                                <p class="mt-3 text-sm leading-6 text-slate-300">
                                    Daily visit workflows, charting, care logs, and field accountability.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-200">Leadership</p>
                                <p class="mt-3 text-3xl font-black">Visibility</p>
                                <p class="mt-3 text-sm leading-6 text-slate-300">
                                    Gain platform-level insight for quality, growth, safety, and performance.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROBLEM / SOLUTION / OUTCOME -->
        <section id="platform" class="mx-auto max-w-7xl px-6 py-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The problem</p>
                    <h3 class="mt-4 text-3xl font-black leading-tight">Care operations are too fragmented</h3>
                    <p class="mt-5 text-sm leading-7 text-slate-300">
                        Providers, facility teams, and caregivers often work across disconnected notes, spreadsheets,
                        calls, texts, and memory. That slows care, hides risks, and creates compliance pressure.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The solution</p>
                    <h3 class="mt-4 text-3xl font-black leading-tight">KassCare centralizes the workflow</h3>
                    <p class="mt-5 text-sm leading-7 text-slate-300">
                        With KassCare, your team works from one modern healthcare operations platform designed
                        for patient visibility, visit coordination, provider decision support, and operational clarity.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">The outcome</p>
                    <h3 class="mt-4 text-3xl font-black leading-tight">More clarity. Less paperwork. Better care.</h3>
                    <p class="mt-5 text-sm leading-7 text-slate-300">
                        Teams move faster, documentation becomes cleaner, workflows become accountable, and leadership
                        gets the visibility needed to run healthcare operations with confidence.
                    </p>
                </div>
            </div>
        </section>
<!-- PRODUCT STRIP -->
<section id="showcase" class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="rounded-[32px] border border-white/10 bg-slate-900/45 p-8 shadow-2xl shadow-blue-950/20">
        <div class="mb-10 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-cyan-300">
                See KassCare in action
            </p>
            <h2 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl">
                A platform your team can actually work from
            </h2>
            <p class="mx-auto mt-4 max-w-3xl text-slate-300">
                Switch between provider, facility, and caregiver workflows to see how KassCare
                brings real healthcare operations into one connected platform.
            </p>
        </div>

        <div class="mb-8 flex flex-wrap justify-center gap-3">
            <button class="rounded-full border border-cyan-300/30 bg-cyan-400/15 px-5 py-2 text-sm font-bold text-cyan-200">
                Provider Workspace
            </button>
            <button class="rounded-full border border-white/10 bg-white/5 px-5 py-2 text-sm font-semibold text-slate-200">
                Facility Operations
            </button>
            <button class="rounded-full border border-white/10 bg-white/5 px-5 py-2 text-sm font-semibold text-slate-200">
                Caregiver Flow
            </button>
        </div>

        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-950/80 shadow-[0_20px_60px_rgba(15,23,42,0.45)]">
            <div class="flex items-center justify-between border-b border-white/8 px-5 py-3">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-rose-400/90"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-300/90"></span>
                    <span class="h-3 w-3 rounded-full bg-emerald-400/90"></span>
                </div>
                <div class="text-sm font-semibold text-slate-300">KassCare Live Product Preview</div>
                <div class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">
                    Live workflow
                </div>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-2">
                <div class="rounded-[24px] border border-blue-400/20 bg-blue-500/10 p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-300">Provider Workspace</p>
                        <span class="rounded-full bg-blue-400/15 px-3 py-1 text-xs font-bold text-blue-200">Provider</span>
                    </div>

                    <h3 class="text-3xl font-black text-white">Clinical command view</h3>

                    <div class="mt-5 grid grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-white/8 bg-slate-900/60 p-4">
                            <p class="text-[11px] uppercase text-slate-400">Flagged alerts</p>
                            <p class="mt-2 text-4xl font-black text-rose-200">12</p>
                        </div>
                        <div class="rounded-2xl border border-white/8 bg-slate-900/60 p-4">
                            <p class="text-[11px] uppercase text-slate-400">Notes queue</p>
                            <p class="mt-2 text-4xl font-black text-amber-200">5</p>
                        </div>
                        <div class="rounded-2xl border border-white/8 bg-slate-900/60 p-4">
                            <p class="text-[11px] uppercase text-slate-400">Compliance due</p>
                            <p class="mt-2 text-4xl font-black text-emerald-200">3</p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-white/8 bg-white/5 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-lg font-bold text-white">Patient summary snapshot</p>
                            <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">Stable</span>
                        </div>
                        <div class="grid gap-3 md:grid-cols-2">
                            <div class="rounded-xl bg-slate-900/60 p-4">
                                <p class="text-[11px] uppercase text-slate-400">Patient</p>
                                <p class="mt-2 font-bold text-white">Margaret J.</p>
                                <p class="mt-1 text-sm text-slate-300">Review recent notes and medication updates.</p>
                            </div>
                            <div class="rounded-xl bg-slate-900/60 p-4">
                                <p class="text-[11px] uppercase text-slate-400">Next action</p>
                                <p class="mt-2 font-bold text-white">Compliance review due today</p>
                                <p class="mt-1 text-sm text-slate-300">Provider follow-up recommended before close of day.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-cyan-300/15 bg-cyan-400/10 p-5">
                        <h4 class="text-2xl font-black text-white">Provider workflow stack</h4>
                        <div class="mt-4 space-y-3">
                            <div class="rounded-xl border border-white/8 bg-slate-900/55 p-4">
                                <p class="font-bold text-white">Smart alerts engine</p>
                                <p class="mt-1 text-sm text-slate-300">Surfacing flagged care logs and patient risk visibility.</p>
                            </div>
                            <div class="rounded-xl border border-white/8 bg-slate-900/55 p-4">
                                <p class="font-bold text-white">Patient workspace</p>
                                <p class="mt-1 text-sm text-slate-300">Notes, diagnoses, medications, history, and compliance in one flow.</p>
                            </div>
                            <div class="rounded-xl border border-white/8 bg-slate-900/55 p-4">
                                <p class="font-bold text-white">Rounds + compliance</p>
                                <p class="mt-1 text-sm text-slate-300">Track upcoming provider actions and facility review cycles.</p>
                            </div>
                            <div class="rounded-xl border border-white/8 bg-slate-900/55 p-4">
                                <p class="font-bold text-white">Pharmacy coordination</p>
                                <p class="mt-1 text-sm text-slate-300">See medication workflow and documentation readiness.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- INTERACTIVE PRODUCT PREVIEW -->
        <section id="showcase" class="mx-auto max-w-7xl px-6 py-20 lg:px-8" x-data="{ tab: 'provider' }">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">See KassCare in motion</p>
                <h2 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">A platform your team can actually work from</h2>
                <p class="mt-5 text-slate-300">
                    Switch between provider, facility, and caregiver workflows to see how KassCare brings real healthcare operations into one connected platform.
                </p>
            </div>

            <!-- Tabs -->
            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <button
                    type="button"
                    @click="tab = 'provider'"
                    :class="tab === 'provider'
                        ? 'bg-cyan-400/20 text-cyan-200 border-cyan-300/40'
                        : 'bg-white/5 text-slate-300 border-white/10 hover:bg-white/10'"
                    class="rounded-2xl border px-5 py-3 text-sm font-bold transition"
                >
                    Provider Workspace
                </button>

                <button
                    type="button"
                    @click="tab = 'facility'"
                    :class="tab === 'facility'
                        ? 'bg-emerald-400/20 text-emerald-200 border-emerald-300/40'
                        : 'bg-white/5 text-slate-300 border-white/10 hover:bg-white/10'"
                    class="rounded-2xl border px-5 py-3 text-sm font-bold transition"
                >
                    Facility Operations
                </button>

                <button
                    type="button"
                    @click="tab = 'caregiver'"
                    :class="tab === 'caregiver'
                        ? 'bg-fuchsia-400/20 text-fuchsia-200 border-fuchsia-300/40'
                        : 'bg-white/5 text-slate-300 border-white/10 hover:bg-white/10'"
                    class="rounded-2xl border px-5 py-3 text-sm font-bold transition"
                >
                    Caregiver Flow
                </button>
            </div>

            <!-- Preview Panel -->
            <div class="relative mt-12">
                <div class="absolute inset-0 rounded-[36px] bg-cyan-400/10 blur-3xl"></div>

                <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-slate-950/70 shadow-2xl shadow-slate-950/60 backdrop-blur">
                    <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                            <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                            <span class="ml-3 text-sm font-semibold text-slate-300">KassCare Live Product Preview</span>
                        </div>

                        <div class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">
                            Live workflow
                        </div>
                    </div>

                    <!-- PROVIDER TAB -->
                    <div x-show="tab === 'provider'" x-transition.opacity.duration.300ms class="p-6 lg:p-8">
                        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="space-y-6">
                                <div class="rounded-3xl border border-blue-400/20 bg-blue-500/10 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-200">Provider workspace</p>
                                            <h3 class="mt-3 text-3xl font-black text-white">Clinical command view</h3>
                                        </div>
                                        <span class="rounded-full bg-blue-400/10 px-3 py-1 text-xs font-bold text-blue-200">Provider</span>
                                    </div>

                                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Flagged alerts</p>
                                            <p class="mt-2 text-3xl font-black text-rose-300">12</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Notes queue</p>
                                            <p class="mt-2 text-3xl font-black text-amber-300">5</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Compliance due</p>
                                            <p class="mt-2 text-3xl font-black text-cyan-300">3</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xl font-black text-white">Patient summary snapshot</h4>
                                        <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">Stable</span>
                                    </div>

                                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                        <div class="rounded-2xl bg-white/5 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Patient</p>
                                            <p class="mt-2 font-bold text-white">Margaret J.</p>
                                            <p class="mt-1 text-sm text-slate-300">Review recent notes and medication updates.</p>
                                        </div>
                                        <div class="rounded-2xl bg-white/5 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Next action</p>
                                            <p class="mt-2 font-bold text-white">Compliance review due today</p>
                                            <p class="mt-1 text-sm text-slate-300">Provider follow-up recommended before close of day.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                <h4 class="text-xl font-black text-white">Provider workflow stack</h4>
                                <div class="mt-5 space-y-4">
                                    <div class="rounded-2xl border border-cyan-400/20 bg-cyan-400/10 p-4">
                                        <p class="font-bold text-white">Smart alerts engine</p>
                                        <p class="mt-1 text-sm text-slate-300">Surfacing flagged care logs and patient risk visibility.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Patient workspace</p>
                                        <p class="mt-1 text-sm text-slate-300">Notes, diagnoses, medications, history, and compliance in one flow.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Rounds + compliance</p>
                                        <p class="mt-1 text-sm text-slate-300">Track upcoming provider actions and facility review cycles.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Pharmacy coordination</p>
                                        <p class="mt-1 text-sm text-slate-300">See medication workflow and documentation readiness.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FACILITY TAB -->
                    <div x-show="tab === 'facility'" x-transition.opacity.duration.300ms class="p-6 lg:p-8">
                        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="space-y-6">
                                <div class="rounded-3xl border border-emerald-400/20 bg-emerald-500/10 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-200">Facility operations</p>
                                            <h3 class="mt-3 text-3xl font-black text-white">Operations dashboard</h3>
                                        </div>
                                        <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-200">Facility</span>
                                    </div>

                                    <div class="mt-6 grid gap-4 sm:grid-cols-4">
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Caregivers</p>
                                            <p class="mt-2 text-3xl font-black text-emerald-300">8</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Visits today</p>
                                            <p class="mt-2 text-3xl font-black text-cyan-300">24</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Clients</p>
                                            <p class="mt-2 text-3xl font-black text-indigo-300">31</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Billing</p>
                                            <p class="mt-2 text-2xl font-black text-amber-300">Active</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                    <h4 class="text-xl font-black text-white">Facility activity</h4>
                                    <div class="mt-5 space-y-3">
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Morning visits assigned</span>
                                            <span class="text-sm font-bold text-cyan-300">Complete</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Caregiver attendance review</span>
                                            <span class="text-sm font-bold text-amber-300">Pending</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Facility onboarding setup</span>
                                            <span class="text-sm font-bold text-emerald-300">Ready</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                <h4 class="text-xl font-black text-white">Facility workflow stack</h4>
                                <div class="mt-5 space-y-4">
                                    <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-4">
                                        <p class="font-bold text-white">Patients + caregivers</p>
                                        <p class="mt-1 text-sm text-slate-300">Manage the daily team and resident flow from one dashboard.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Visits scheduling</p>
                                        <p class="mt-1 text-sm text-slate-300">Track assigned, completed, and in-progress visit activity.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Onboarding engine</p>
                                        <p class="mt-1 text-sm text-slate-300">Facility setup, admin access, and subscription control.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Billing visibility</p>
                                        <p class="mt-1 text-sm text-slate-300">Simple plan structure for growing healthcare operations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CAREGIVER TAB -->
                    <div x-show="tab === 'caregiver'" x-transition.opacity.duration.300ms class="p-6 lg:p-8">
                        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="space-y-6">
                                <div class="rounded-3xl border border-fuchsia-400/20 bg-fuchsia-500/10 p-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-fuchsia-200">Caregiver flow</p>
                                            <h3 class="mt-3 text-3xl font-black text-white">Daily visit execution</h3>
                                        </div>
                                        <span class="rounded-full bg-fuchsia-400/10 px-3 py-1 text-xs font-bold text-fuchsia-200">Caregiver</span>
                                    </div>

                                    <div class="mt-6 grid gap-4 sm:grid-cols-4">
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Assigned</p>
                                            <p class="mt-2 text-3xl font-black text-cyan-300">6</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Completed</p>
                                            <p class="mt-2 text-3xl font-black text-emerald-300">4</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">In progress</p>
                                            <p class="mt-2 text-3xl font-black text-amber-300">1</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                            <p class="text-xs uppercase tracking-wider text-slate-400">Pending notes</p>
                                            <p class="mt-2 text-3xl font-black text-rose-300">1</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                    <h4 class="text-xl font-black text-white">Today’s visit checklist</h4>
                                    <div class="mt-5 space-y-3">
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Check-in completed</span>
                                            <span class="text-sm font-bold text-emerald-300">Done</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">ADL charting entered</span>
                                            <span class="text-sm font-bold text-emerald-300">Done</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Vitals documentation</span>
                                            <span class="text-sm font-bold text-amber-300">In progress</span>
                                        </div>
                                        <div class="flex items-center justify-between rounded-2xl bg-white/5 p-4">
                                            <span class="text-sm text-slate-200">Final care note submission</span>
                                            <span class="text-sm font-bold text-slate-300">Pending</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                                <h4 class="text-xl font-black text-white">Caregiver workflow stack</h4>
                                <div class="mt-5 space-y-4">
                                    <div class="rounded-2xl border border-fuchsia-400/20 bg-fuchsia-400/10 p-4">
                                        <p class="font-bold text-white">Assigned visit cards</p>
                                        <p class="mt-1 text-sm text-slate-300">Clean daily workflow visibility for caregivers in the field.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Structured charting</p>
                                        <p class="mt-1 text-sm text-slate-300">Support documentation with clear steps and reduced errors.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Vitals + care logs</p>
                                        <p class="mt-1 text-sm text-slate-300">Capture support data with accountability and visibility.</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                                        <p class="font-bold text-white">Visit completion flow</p>
                                        <p class="mt-1 text-sm text-slate-300">Clear visit progress from check-in through documentation closeout.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <!-- FINAL MISSION SECTION -->
<section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">

    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-950 p-12 text-center shadow-2xl">

        <p class="text-xs font-bold uppercase tracking-[0.3em] text-cyan-300">
            Why KassCare exists
        </p>

        <h2 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-5xl">
            Healthcare teams should spend more time caring  
            and less time chasing paperwork
        </h2>

        <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-300">
            KassCare was built to help providers, facilities, and caregivers work
            with clarity, faster coordination, and stronger accountability
            across real healthcare operations.
        </p>

        <div class="mt-10 text-xl font-bold text-emerald-300">
            We Are Not Helpless ✨
        </div>

        <div class="mt-12 flex flex-wrap justify-center gap-4">

            <a href="/register-facility"
               class="rounded-xl bg-cyan-400 px-6 py-3 font-bold text-slate-900 shadow-lg hover:bg-cyan-300 transition">
                Launch your facility on KassCare
            </a>

            <a href="/login"
               class="rounded-xl border border-white/20 px-6 py-3 font-semibold text-white hover:bg-white/10 transition">
                Access platform
            </a>

        </div>

    </div>

</section>
        </section>

<!-- TRUST STRIP -->
<section id="security" class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
    <div class="grid gap-6 md:grid-cols-4">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
            <div class="text-2xl">🔒</div>
            <p class="mt-3 font-bold text-white">Structured access</p>
            <p class="mt-2 text-sm text-slate-300">Role-based control and cleaner operational safety.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
            <div class="text-2xl">🏥</div>
            <p class="mt-3 font-bold text-white">Facility ready</p>
            <p class="mt-2 text-sm text-slate-300">Built for real facility workflows, teams, and coordination.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
            <div class="text-2xl">🧑‍⚕️</div>
            <p class="mt-3 font-bold text-white">Provider focused</p>
            <p class="mt-2 text-sm text-slate-300">Clinical visibility, alerts, notes, and patient workflow support.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
            <div class="text-2xl">📊</div>
            <p class="mt-3 font-bold text-white">Operational intelligence</p>
            <p class="mt-2 text-sm text-slate-300">See what is happening across care activity and compliance.</p>
        </div>
    </div>
</section>
<!-- FOOTER -->
<footer class="mx-auto max-w-7xl px-6 py-16 lg:px-8 border-t border-white/10 bg-black/30 backdrop-blur-xl rounded-t-3xl">

    <div class="grid gap-10 md:grid-cols-4">

        <div>
            <h3 class="text-lg font-bold text-white">KassCare</h3>
            <p class="mt-3 text-sm text-slate-400">
                Healthcare operations platform designed for providers,
                facilities, and caregivers to work with clarity,
                coordination, and accountability.
            </p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-white">Platform</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-400">
                <li>Provider workspace</li>
                <li>Facility operations</li>
                <li>Caregiver workflows</li>
                <li>Compliance tracking</li>
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-white">Company</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-400">
                <li>About KassCare</li>
                <li>Security</li>
                <li>Pricing</li>
                <li>Contact</li>
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-white">Legal</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-400">
                <li>Terms of Service</li>
                <li>Privacy Policy</li>
                <li>HIPAA Awareness</li>
            </ul>
        </div>

    </div>

    <div class="mt-12 border-t border-white/10 pt-6 text-sm text-slate-500 text-center">
        © {{ date('Y') }} KassCare Platform — Healthcare should not be a struggle.
    </div>

</footer>
