<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kass Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100 text-gray-900 font-sans antialiased">
@php
    $role = auth()->check() ? (auth()->user()->role ?? 'admin') : 'guest';
    $theme = match ($role) {
        'provider' => [
            'brand_text' => 'text-indigo-700',
            'nav_hover_text' => 'hover:text-indigo-700',
            'nav_hover_bg' => 'hover:bg-indigo-50',
            'primary_bg' => 'bg-indigo-600',
            'primary_hover' => 'hover:bg-indigo-700',
            'soft_bg' => 'bg-indigo-50',
            'soft_text' => 'text-indigo-700',
            'soft_border' => 'border-indigo-200',
            'dropdown_button_text' => 'text-indigo-600',
            'dropdown_button_hover' => 'hover:text-indigo-800',
            'flash_bg' => 'bg-indigo-50',
            'flash_border' => 'border-indigo-200',
            'flash_text' => 'text-indigo-800',
        ],
        'caregiver' => [
            'brand_text' => 'text-green-700',
            'nav_hover_text' => 'hover:text-green-700',
            'nav_hover_bg' => 'hover:bg-green-50',
            'primary_bg' => 'bg-green-600',
            'primary_hover' => 'hover:bg-green-700',
            'soft_bg' => 'bg-green-50',
            'soft_text' => 'text-green-700',
            'soft_border' => 'border-green-200',
            'dropdown_button_text' => 'text-green-600',
            'dropdown_button_hover' => 'hover:text-green-800',
            'flash_bg' => 'bg-green-50',
            'flash_border' => 'border-green-200',
            'flash_text' => 'text-green-800',
        ],
        default => [
            'brand_text' => 'text-blue-700',
            'nav_hover_text' => 'hover:text-blue-700',
            'nav_hover_bg' => 'hover:bg-blue-50',
            'primary_bg' => 'bg-blue-600',
            'primary_hover' => 'hover:bg-blue-700',
            'soft_bg' => 'bg-blue-50',
            'soft_text' => 'text-blue-700',
            'soft_border' => 'border-blue-200',
            'dropdown_button_text' => 'text-blue-600',
            'dropdown_button_hover' => 'hover:text-blue-800',
            'flash_bg' => 'bg-blue-50',
            'flash_border' => 'border-blue-200',
            'flash_text' => 'text-blue-800',
        ],
    };

    $role = auth()->check() ? auth()->user()->role : null;

    $dashboardRoute = match ($role) {
        'super_admin' => 'admin.dashboard',
        'admin' => !empty(auth()->user()->facility_id)
            ? 'admin.facilities.show'
            : 'admin.dashboard',
        'provider' => 'provider.dashboard',
        'caregiver' => 'caregiver.dashboard',
        default => 'dashboard',
    };


    $unreadProviderMessages = 0;

    if (auth()->check() && $role === 'provider' && class_exists(\App\Models\ProviderMessage::class)) {
        $unreadProviderMessages = \App\Models\ProviderMessage::where('provider_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    $selectedFacilityId = session('facility_id');
    $selectedFacility = null;
    $providerFacilities = collect();

    if (auth()->check() && $role === 'provider') {
        $providerFacilities = \App\Models\Facility::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($selectedFacilityId) {
            $selectedFacility = $providerFacilities->firstWhere('id', (int) $selectedFacilityId);
        }

        if (!$selectedFacility && !empty(auth()->user()->facility_id)) {
            $selectedFacility = \App\Models\Facility::find(auth()->user()->facility_id);
        }
    }
@endphp

<style>
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .animate-marquee {
        display: inline-block;
        padding-left: 100%;
        animation: marquee 35s linear infinite;
    }
</style>

<nav class="bg-white border-b-2 border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-8">
                <a href="{{ $role === 'admin' && auth()->user()->facility_id
                    ? route('admin.facilities.show', auth()->user()->facility_id)
                    : route($dashboardRoute) }}"
                   class="text-2xl font-extrabold tracking-tight {{ $theme['brand_text'] }}">
                    KASSCare
                </a>

                <div class="hidden lg:block overflow-hidden whitespace-nowrap w-80">
                    <div class="animate-marquee text-sm text-indigo-600 font-medium">
                        Healthcare should not be a struggle — KASS Care empowers providers and facilities to manage patients with clarity, speed, and confidence.
                    </div>
                </div>

                @auth
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg transition {{ $theme['nav_hover_text'] }} {{ $theme['nav_hover_bg'] }}">
                            Dashboard
                        </a>

                        @if(in_array($role, ['admin', 'super_admin']) && Route::has('billing.index'))
                            <a href="{{ route('billing.index') }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg transition {{ $theme['nav_hover_text'] }} {{ $theme['nav_hover_bg'] }}">
                                Billing
                            </a>
                        @endif

                        @if(Route::has('alerts.index'))
                            <a href="{{ route('alerts.index') }}"
                               class="px-3 py-2 text-sm font-semibold text-gray-700 rounded-lg transition {{ $theme['nav_hover_text'] }} {{ $theme['nav_hover_bg'] }}">
                                Alerts
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center gap-4">
                @auth
                    @if($role === 'provider')
                        <div class="relative" id="facilitySwitcherWrap">
                            <button type="button"
                                    id="facilitySwitcherBtn"
                                    class="inline-flex items-center gap-2 rounded-xl border {{ $theme['soft_border'] }} {{ $theme['soft_bg'] }} px-4 py-2 text-sm font-semibold {{ $theme['soft_text'] }} shadow-sm hover:shadow transition">
                                <span>Facility:</span>
                                <span>{{ $selectedFacility?->name ?? 'Select Facility' }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="facilitySwitcherMenu"
                                 class="hidden absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                                <div class="border-b bg-gray-50 px-4 py-3">
                                    <h3 class="text-sm font-bold text-gray-800">Select Facility</h3>
                                    <p class="mt-1 text-xs text-gray-500">Choose the facility context for provider workflow.</p>
                                </div>

                                <div class="max-h-72 overflow-y-auto py-2">
                                    @forelse($providerFacilities as $facility)
                                        <form method="POST" action="{{ route('select.facility', $facility->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex w-full items-center justify-between px-4 py-3 text-left text-sm transition hover:bg-gray-50">
                                                <span class="font-medium text-gray-700">{{ $facility->name }}</span>

                                                @if((int) $selectedFacilityId === (int) $facility->id || (!$selectedFacilityId && auth()->user()->facility_id == $facility->id))
                                                    <span class="rounded-full {{ $theme['soft_bg'] }} px-2 py-1 text-xs font-semibold {{ $theme['soft_text'] }}">
                                                        Active
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            No facilities available.
                                        </div>
                                    @endforelse
                                </div>

                                <div class="border-t bg-gray-50 px-4 py-3">
                                    <form method="POST" action="{{ route('clear.facility') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full rounded-lg bg-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-300">
                                            Clear facility context
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if($role === 'provider' && Route::has('provider.messages.index'))
                        <a href="{{ route('provider.messages.index') }}"
                           class="relative inline-flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-gray-100"
                           title="Facility Messages">
                            <span class="text-xl">✉️</span>

                            @if($unreadProviderMessages > 0)
                                <span class="absolute -top-1 -right-1 min-w-[20px] rounded-full bg-red-600 px-1.5 py-0.5 text-center text-xs font-bold text-white">
                                    {{ $unreadProviderMessages }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <div class="relative">
                        <button type="button"
                                id="notifBtn"
                                class="relative inline-flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-gray-100">
                            <span class="text-xl">🔔</span>
                            <span id="notifCount"
                                  class="hidden absolute -top-1 -right-1 min-w-[20px] rounded-full bg-red-600 px-1.5 py-0.5 text-center text-xs font-bold text-white">
                            </span>
                        </button>

                        <div id="notifDropdown"
     class="hidden absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl z-50">
                            <div class="flex items-center justify-between border-b bg-gray-50 px-4 py-3">
                                <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                                <button id="markAllRead"
                                        type="button"
                                        class="text-xs font-semibold {{ $theme['dropdown_button_text'] }} {{ $theme['dropdown_button_hover'] }}">
                                    Mark all as read
                                </button>
                            </div>

                            <div id="notifList" class="max-h-96 overflow-y-auto">
                                <div class="p-4 text-sm text-gray-500">Loading...</div>
                            </div>

                            <div class="border-t bg-gray-50 px-4 py-3">
                                @if(Route::has('alerts.index'))
                                    <a href="{{ route('alerts.index') }}"
                                       class="text-sm font-semibold {{ $theme['dropdown_button_text'] }} {{ $theme['dropdown_button_hover'] }}">
                                        View all alerts
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <span class="hidden md:inline text-sm font-medium text-gray-600">
                        <span>{{ auth()->user()?->name ?? 'Guest' }}</span>
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-lg bg-rose-50 px-4 py-2 text-sm font-bold text-rose-700 transition hover:bg-rose-100">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg text-white text-sm font-bold transition {{ $theme['primary_bg'] }} {{ $theme['primary_hover'] }}">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border px-4 py-3 text-sm font-medium {{ $theme['flash_border'] }} {{ $theme['flash_bg'] }} {{ $theme['flash_text'] }}">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <div class="font-bold mb-2">Please fix the following:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
                           @include('layouts.navigation')

@if(auth()->check())
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800 text-white text-sm">
        <div class="mx-auto max-w-7xl px-4 py-2 text-center font-medium">
            🚀 KASSCare Pilot Program — Thank you for helping shape the future of healthcare documentation.
        </div>
    </div>
@endif


    @yield('content')
</main>

<footer class="mt-20 border-t bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">
        <div class="font-semibold text-gray-700">
            KASS CARE PLATFORM
        </div>

        <div class="mt-1">
            &copy; {{ date('Y') }} KASS MTV USA LLC
        </div>

        <div class="mt-2 text-xs text-gray-500">
            HIPAA-aware platform for healthcare documentation and care coordination.
        </div>

        <div class="mt-4 flex justify-center space-x-6">
            <a href="{{ route('terms') }}" class="hover:text-indigo-600">Terms</a>
            <a href="#" class="hover:text-indigo-600">Privacy</a>
            <a href="#" class="hover:text-indigo-600">System Status</a>
        </div>

        <span class="mt-4 inline-block text-gray-400">
            Version 1.0
        </span>
    </div>
</footer>

@auth
<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifCount = document.getElementById('notifCount');
    const notifList = document.getElementById('notifList');
    const markAllReadBtn = document.getElementById('markAllRead');

    const facilitySwitcherBtn = document.getElementById('facilitySwitcherBtn');
    const facilitySwitcherMenu = document.getElementById('facilitySwitcherMenu');
    const facilitySwitcherWrap = document.getElementById('facilitySwitcherWrap');

    function escapeHtml(value) {
        if (value === null || value === undefined) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function alertCard(alert) {
        const unreadClass = alert.read_at ? 'bg-white' : '{{ $theme['soft_bg'] }}';
        const typeLabel = escapeHtml(alert.type ?? 'Alert');
        const message = escapeHtml(alert.message ?? '');
        const createdAt = escapeHtml(alert.created_at ?? '');

        return `
            <div class="px-4 py-3 border-b border-gray-100 ${unreadClass}">
                <div class="text-sm font-bold text-gray-800 capitalize">${typeLabel}</div>
                <div class="mt-1 text-sm text-gray-600">${message}</div>
                <div class="mt-2 text-xs text-gray-400">${createdAt}</div>
            </div>
        `;
    }

    function renderNotifications(data) {
        if (!notifCount || !notifList) return;

        if (data.unread > 0) {
            notifCount.textContent = data.unread;
            notifCount.classList.remove('hidden');
        } else {
            notifCount.textContent = '';
            notifCount.classList.add('hidden');
        }

        if (!data.alerts || data.alerts.length === 0) {
            notifList.innerHTML = '<div class="p-4 text-sm text-gray-500">No notifications yet.</div>';
            return;
        }

        notifList.innerHTML = data.alerts.map(alertCard).join('');
    }

    function fetchNotifications() {
        fetch('{{ route('notifications.fetch') }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => renderNotifications(data))
        .catch(() => {
            if (notifList) {
                notifList.innerHTML = '<div class="p-4 text-sm text-red-500">Could not load notifications.</div>';
            }
        });
    }

    if (notifBtn) {
        notifBtn.addEventListener('click', function () {
            notifDropdown.classList.toggle('hidden');

            if (!notifDropdown.classList.contains('hidden')) {
                fetchNotifications();
            }
        });
    }

    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function () {
            fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(() => fetchNotifications());
        });
    }

    if (facilitySwitcherBtn) {
        facilitySwitcherBtn.addEventListener('click', function () {
            facilitySwitcherMenu.classList.toggle('hidden');
        });
    }

    document.addEventListener('click', function (event) {
        if (
            notifDropdown &&
            notifBtn &&
            !notifDropdown.contains(event.target) &&
            !notifBtn.contains(event.target)
        ) {
            notifDropdown.classList.add('hidden');
        }

        if (
            facilitySwitcherWrap &&
            facilitySwitcherMenu &&
            !facilitySwitcherWrap.contains(event.target)
        ) {
            facilitySwitcherMenu.classList.add('hidden');
        }
    });

    fetchNotifications();
    setInterval(fetchNotifications, 10000);
</script>
@endauth
</body>
</html>
