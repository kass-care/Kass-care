<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'KASSCare') }} - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes rotateGlobe {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        @keyframes sparklePulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.85;
                filter: drop-shadow(0 0 6px rgba(250, 204, 21, 0.35));
            }
            50% {
                transform: scale(1.18);
                opacity: 1;
                filter: drop-shadow(0 0 14px rgba(250, 204, 21, 0.7));
            }
        }

        @keyframes signalPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.35);
            }
            50% {
                transform: scale(1.1);
                opacity: 0.9;
                box-shadow: 0 0 0 8px rgba(74, 222, 128, 0);
            }
        }

        .admin-globe-spin {
            animation: rotateGlobe 10s linear infinite;
            transform-origin: center;
        }

        .admin-sparkle {
            animation: sparklePulse 1.8s ease-in-out infinite;
        }

        .admin-signal {
            animation: signalPulse 1.8s ease-in-out infinite;
        }

        .admin-sidebar-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .admin-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .admin-sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.22);
            border-radius: 9999px;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">

        {{-- LEFT SIDEBAR --}}
        <aside class="w-72 bg-slate-950 text-white flex flex-col border-r border-slate-800 shadow-2xl">

            {{-- BRAND --}}
            <div class="px-6 py-6 border-b border-slate-800">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-black tracking-wide text-white leading-none">
                                KASSCare
                            </h1>

                            {{-- ROTATING GLOBE + GOLD SPARKLE --}}
                            <div class="relative w-8 h-8 shrink-0">
                                <svg viewBox="0 0 100 100" class="admin-globe-spin w-8 h-8">
                                    <circle cx="50" cy="50" r="30" fill="none" stroke="#67e8f9" stroke-width="4"></circle>
                                    <ellipse cx="50" cy="50" rx="12" ry="30" fill="none" stroke="#67e8f9" stroke-width="3"></ellipse>
                                    <ellipse cx="50" cy="50" rx="24" ry="30" fill="none" stroke="#67e8f9" stroke-width="2.5"></ellipse>
                                    <path d="M20 50h60" fill="none" stroke="#67e8f9" stroke-width="3"></path>
                                    <path d="M26 36c8 4 40 4 48 0" fill="none" stroke="#67e8f9" stroke-width="2.5"></path>
                                    <path d="M26 64c8-4 40-4 48 0" fill="none" stroke="#67e8f9" stroke-width="2.5"></path>
                                </svg>

                                <div class="admin-sparkle absolute -top-1 -right-1 text-yellow-300 text-sm leading-none">
                                    ✨
                                </div>
                            </div>
                        </div>

                        <p class="mt-2 text-xs uppercase tracking-[0.28em] text-cyan-400">
                            Super Admin Control
                        </p>

                        <p class="mt-2 text-[11px] tracking-[0.18em] uppercase text-amber-300 font-semibold">
                            We are not helpless ✨
                        </p>
                    </div>

                    <div class="h-3 w-3 rounded-full bg-emerald-400 admin-signal shrink-0 mt-1"></div>
                </div>
            </div>

            {{-- ADMIN PROFILE --}}
            <div class="px-6 py-5 border-b border-slate-800 bg-slate-900/60">
                <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Logged in as</p>

                <div class="mt-2 flex items-center gap-3">
                    <div class="h-11 w-11 rounded-2xl bg-cyan-500/20 border border-cyan-400/30 flex items-center justify-center text-cyan-200 font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-white">
                            {{ auth()->user()->name ?? 'Super Admin' }}
                        </p>
                        <p class="text-xs text-slate-400">
                           {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- NAVIGATION --}}
            <div class="flex-1 overflow-y-auto admin-sidebar-scroll px-4 py-5 space-y-6">

                {{-- SYSTEM --}}
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-[0.28em] text-slate-500">
                        System
                    </p>

                    <div class="space-y-1.5">
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                           {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                            <span>🖥️</span>
                            <span>Command Center</span>
                        </a>

                        @if(Route::has('admin.audit.index'))
                            <a href="{{ route('admin.audit.index') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('admin.audit.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>📜</span>
                                <span>Audit Logs</span>
                            </a>
                        @endif

                        @if(Route::has('billing.index'))
                            <a href="{{ route('billing.index') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('billing.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>💳</span>
                                <span>Billing</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- OPERATIONS --}}
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-[0.28em] text-slate-500">
                        Operations
                    </p>

                    <div class="space-y-1.5">
                        <a href="{{ route('admin.caregivers.index') }}"
                           class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                           {{ request()->routeIs('admin.facilities.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                            <span>🏥</span>
                            <span>Facilities</span>
                        </a>
                        <a href="{{ route('admin.providers.index') }}"
   class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
   {{ request()->routeIs('admin.providers.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
    <span>🩺</span>
    <span>Providers</span>
</a>

                           <a href="{{ route('admin.caregivers.index') }}"
   class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
   {{ request()->routeIs('admin.caregivers.*') 
      ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' 
      : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
    <span>👨‍⚕️</span>
    <span>Caregivers</span>
</a>
                        <a href="{{ route('admin.clients.index') }}"
                           class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                           {{ request()->routeIs('admin.clients.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                            <span>📋</span>
                            <span>Patients</span>
                        </a>

                        <a href="{{ route('admin.visits.index') }}"
                           class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                           {{ request()->routeIs('visits.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                            <span>📅</span>
                            <span>Visits</span>
                        </a>

                        @if(Route::has('admin.tasks.index'))
                            <a href="{{ route('admin.tasks.index') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('admin.tasks.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>✅</span>
                                <span>Tasks</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- CLINICAL --}}
                <div>
                    <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-[0.28em] text-slate-500">
                        Clinical
                    </p>

                    <div class="space-y-1.5">
                        @if(Route::has('compliance.dashboard'))
                            <a href="{{ route('compliance.dashboard') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('compliance.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>🛡️</span>
                                <span>Compliance</span>
                            </a>
                        @endif

                        @if(Route::has('facility-provider-cycles.index'))
                            <a href="{{ route('facility-provider-cycles.index') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('facility-provider-cycles.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>🔄</span>
                                <span>Facility Cycles</span>
                            </a>
                        @endif

                        @if(Route::has('alerts.index'))
                            <a href="{{ route('alerts.index') }}"
                               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                               {{ request()->routeIs('alerts.*') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-500/20' : 'text-slate-200 hover:bg-slate-900 hover:text-white' }}">
                                <span>🚨</span>
                                <span>Alerts</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="border-t border-slate-800 p-4 bg-slate-950">
                <div class="rounded-2xl border border-cyan-500/20 bg-slate-900 px-4 py-4">
                    <p class="text-[11px] uppercase tracking-[0.28em] text-cyan-400">Mission Status</p>
                    <p class="mt-2 text-sm font-semibold text-white">Platform online</p>
                    <p class="mt-1 text-xs text-slate-400">Super admin engineering panel active.</p>
                </div>
            </div>
        </aside>

        {{-- RIGHT CONTENT AREA --}}
        <div class="flex-1 flex flex-col min-h-screen">

            {{-- TOP BAR --}}
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">KASS Care SaaS</p>
                        <h2 class="mt-1 text-2xl font-black text-slate-900">
                            @yield('page_title', 'Super Admin Command Center')
                        </h2>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Main Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center rounded-2xl bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- FLASH MESSAGES --}}
            <div class="px-8 pt-6">
                @if(session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">
                        <p class="text-sm font-bold text-red-700">Please fix the following:</p>
                        <ul class="mt-2 list-disc pl-5 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 px-8 pb-8 pt-2">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
