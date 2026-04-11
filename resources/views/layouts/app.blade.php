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
    @endphp
@php
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
@endphp

    <nav class="bg-white border-b-2 border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <div class="flex items-center gap-8">
              <a href="{{ $role === 'admin' && auth()->user()->facility_id
        ? route('admin.facilities.show', auth()->user()->facility_id)
        : route($dashboardRoute) }}"
   class="text-2xl font-extrabold tracking-tight {{ $theme['brand_text'] }}">
                        KASSCare
                    </a>
<style>
@keyframes marquee {
0% { transform: translateX(100%); }
100% { transform: translateX(-100%); }
}

.animate-marquee {
display:inline-block;
padding-left:100%;
animation: marquee 35s linear infinite;
}
</style>

          <div class="flex items-center space-x-6">


    <div class="overflow-hidden whitespace-nowrap w-80">
        <div class="animate-marquee text-sm text-indigo-600 font-medium">
🩺 Healthcare should not be a struggle — KASS Care empowers providers and facilities to manage patients with clarity, speed, and confidence.
        </div>
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
                        <div class="relative">
                            <button id="notifBtn"
                                    type="button"
                                    class="relative inline-flex items-center justify-center w-10 h-10 rounded-full transition bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
                                <span class="text-xl">🔔</span>
                                <span id="notifCount"
                                      class="hidden absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-red-600 text-white text-xs rounded-full flex items-center justify-center">
                                </span>
                            </button>

                            <div id="notifDropdown"
                                 class="hidden absolute right-0 mt-3 w-96 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden z-50">
                                <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
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

                                <div class="px-4 py-3 border-t bg-gray-50">
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
                            {{ auth()->user()->name }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="bg-rose-50 text-rose-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-rose-100 transition">
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

        @yield('content')
    </main>

    @auth
    <script>
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifCount = document.getElementById('notifCount');
        const notifList = document.getElementById('notifList');
        const markAllReadBtn = document.getElementById('markAllRead');

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
                    <div class="text-sm text-gray-600 mt-1">${message}</div>
                    <div class="text-xs text-gray-400 mt-2">${createdAt}</div>
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
            fetch("{{ route('notifications.fetch') }}", {
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
                   fetch("{{ route('notifications.readAll') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(() => fetchNotifications());
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
        });

        fetchNotifications();
        setInterval(fetchNotifications, 10000);
    </script>
    @endauth
<footer class="mt-20 border-t bg-gray-50">
    
<div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-600">

<div class="font-semibold text-gray-700">
KASS CARE PLATFORM
</div>

<div class="mt-1">
© {{ date('Y') }} KASS MTV USA LLC
</div>

<div class="mt-2 text-xs text-gray-500">
HIPAA-aware platform for healthcare documentation and care coordination.
</div>

<div class="mt-4 flex justify-center space-x-6">

<a href="{{ route('terms') }}" class="hover:text-indigo-600">
Terms
</a>

<a href="#" class="hover:text-indigo-600">
Privacy
</a>

<a href="#" class="hover:text-indigo-600">
System Status
</a>

<span class="text-gray-400">
Version 1.0
</span>

</div>

</div>

</footer>

</body>
</html>
