<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f2e33">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/kasscare-192.png">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="KASSCare">

    @php
        $user = auth()->user();
        $role = $user->role ?? null;
        $nav = \App\Support\KassNav::for($role);
        $tabs = \App\Support\KassNav::tabs($role, 4);
        $homeUrl = \App\Support\KassNav::homeUrl($role);
        $pageTitle = trim($__env->yieldContent('title')) ?: \App\Support\KassNav::pageTitle($role);
        $initials = collect(explode(' ', trim($user->name ?? 'K')))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
        $alertsUrl = $role === 'family' ? null : collect(['provider.alerts.index', 'alerts.index'])->first(fn ($n) => Route::has($n));
        $unread = 0;
        try {
            if ($user && method_exists($user, 'unreadNotifications')) {
                $unread = $user->unreadNotifications()->count();
            }
        } catch (\Throwable $e) {
            $unread = 0;
        }
    @endphp

    <title>{{ $pageTitle }} | KASSCare</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,400i,700|bricolage-grotesque:500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/kasscare-theme.css') }}?v=2">
    @stack('styles')
</head>

<body class="kc-body">
<a href="#main" class="kc-skip">Skip to content</a>

<div class="kc-shell">

    @auth
    {{-- ===== Sidebar (always visible on computers, slides in on phones) ===== --}}
    <aside class="kc-sidebar" id="kc-sidebar" aria-label="Main menu">
        <a href="{{ $homeUrl }}" class="kc-brand">
            <span class="kc-brand-mark"><x-kc-icon name="pulse" style="color:#fff" /></span>
            <span class="kc-brand-name">KASS<span>Care</span></span>
        </a>

        @if(session('facility_name'))
            <div class="kc-context">
                Working in
                <strong>{{ session('facility_name') }}</strong>
                @if(Route::has('clear.facility') && $role === 'super_admin')
                    <form method="POST" action="{{ route('clear.facility') }}">
                        @csrf
                        <button type="submit">Switch facility</button>
                    </form>
                @endif
            </div>
        @endif

        <nav class="kc-nav">
            @foreach($nav as $group => $items)
                <div class="kc-nav-group">
                    <div class="kc-nav-heading">{{ $group }}</div>
                    @foreach($items as $item)
                        <a href="{{ $item['url'] }}" @if($item['active']) aria-current="page" @endif>
                            <x-kc-icon :name="$item['icon']" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <div class="kc-user">
            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="kc-avatar" title="Your profile" style="text-decoration:none">{{ $initials ?: 'K' }}</a>
            <div class="kc-user-meta">
                <div class="kc-user-name">{{ $user->name ?? 'User' }}</div>
                <div class="kc-user-role">{{ \App\Support\KassNav::roleLabel($role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="kc-icon-btn" title="Sign out" aria-label="Sign out">
                    <x-kc-icon name="logout" />
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <div class="kc-content">
        {{-- ===== Top bar ===== --}}
        <header class="kc-topbar">
            @auth
                <button type="button" class="kc-icon-btn kc-menu-btn" data-kc-open aria-controls="kc-sidebar" aria-expanded="false" aria-label="Open menu">
                    <x-kc-icon name="menu" />
                </button>
            @else
                <a href="{{ url('/') }}" class="kc-brand" style="padding:0;color:var(--kc-ink)">
                    <span class="kc-brand-mark"><x-kc-icon name="pulse" style="color:#fff" /></span>
                    <span class="kc-brand-name">KASS<span style="color:var(--kc-teal)">Care</span></span>
                </a>
            @endauth

            <div class="kc-topbar-title">{{ auth()->check() ? $pageTitle : '' }}</div>

            @auth
                @if($alertsUrl)
                    <a href="{{ route($alertsUrl) }}" class="kc-icon-btn" aria-label="Alerts{{ $unread ? ' ('.$unread.' new)' : '' }}">
                        <x-kc-icon name="bell" />
                        @if($unread)<span class="kc-dot"></span>@endif
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="kc-btn kc-btn--primary kc-btn--sm">Sign in</a>
            @endauth
        </header>

        <main id="main" class="kc-main">
            @auth
            <div class="kc-install" id="kc-install" role="region" aria-label="Install app">
                <img src="/icons/kasscare-192.png" width="40" height="40" alt="" style="border-radius:10px">
                <p id="kc-install-text">Add KASSCare to your home screen for one-tap access.</p>
                <button type="button" class="kc-btn kc-btn--primary kc-btn--sm" id="kc-install-btn">Install</button>
                <button type="button" class="kc-icon-btn" id="kc-install-close" aria-label="Not now"><x-kc-icon name="x" /></button>
            </div>
            @endauth
            @if(session('success'))
                <div class="kc-flash-wrap">
                    <div class="kc-flash kc-flash--ok" role="status"><x-kc-icon name="check" />{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="kc-flash-wrap">
                    <div class="kc-flash kc-flash--err" role="alert"><x-kc-icon name="alert" />{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
            {{ $slot ?? '' }}

            <footer class="kc-footer">
                <span>&copy; {{ date('Y') }} KASS MTV USA LLC</span>
                <span>
                    @if(Route::has('terms'))<a href="{{ route('terms') }}">Terms</a>@endif
                </span>
            </footer>
        </main>
    </div>

    @auth
    {{-- ===== Phone bottom bar ===== --}}
    @if(count($tabs))
        <nav class="kc-tabbar" aria-label="Quick menu">
            @foreach($tabs as $tab)
                <a href="{{ $tab['url'] }}" @if($tab['active']) aria-current="page" @endif>
                    <x-kc-icon :name="$tab['icon']" />
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
            <button type="button" data-kc-open aria-controls="kc-sidebar" aria-label="Open full menu">
                <x-kc-icon name="more" />
                <span>More</span>
            </button>
        </nav>
    @endif
    @endauth
</div>

<script>
    (function () {
        var sidebar = document.getElementById('kc-sidebar');
        if (!sidebar) return;
        function setOpen(open) {
            sidebar.classList.toggle('is-open', open);
            document.querySelectorAll('[data-kc-open]').forEach(function (b) { b.setAttribute('aria-expanded', open); });
        }
        document.querySelectorAll('[data-kc-open]').forEach(function (b) {
            b.addEventListener('click', function (e) { e.stopPropagation(); setOpen(!sidebar.classList.contains('is-open')); });
        });
        document.addEventListener('click', function (e) {
            if (sidebar.classList.contains('is-open') && !sidebar.contains(e.target)) setOpen(false);
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    })();
</script>
<script>
    (function () {
        var box = document.getElementById('kc-install');
        if (!box) return;
        var standalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
        var dismissed = false;
        try { dismissed = localStorage.getItem('kc-install-dismissed') === '1'; } catch (e) {}
        if (standalone || dismissed) return;

        var btn = document.getElementById('kc-install-btn');
        var deferred = null;
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault(); deferred = e; box.classList.add('is-shown');
        });
        var isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
        if (isIos) {
            document.getElementById('kc-install-text').textContent = 'Install KASSCare: tap the Share button, then "Add to Home Screen".';
            btn.style.display = 'none';
            box.classList.add('is-shown');
        }
        btn.addEventListener('click', function () {
            if (!deferred) return;
            deferred.prompt();
            deferred.userChoice.finally(function () { box.classList.remove('is-shown'); deferred = null; });
        });
        document.getElementById('kc-install-close').addEventListener('click', function () {
            box.classList.remove('is-shown');
            try { localStorage.setItem('kc-install-dismissed', '1'); } catch (e) {}
        });
    })();
</script>
@stack('scripts')
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); });
    }
</script>
</body>
</html>
