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
    <title>KASSCare | Home care, documented right</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,400i,700|bricolage-grotesque:500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/kasscare-theme.css') }}?v=3">
</head>
<body class="kc-body">
<div class="kc-auth">

    <section class="kc-auth-story" aria-hidden="false">
        <a href="{{ url('/') }}" class="kc-brand" style="padding:0">
            <span class="kc-brand-mark"><x-kc-icon name="pulse" style="color:#fff" /></span>
            <span class="kc-brand-name">KASS<span>Care</span></span>
        </a>

        <div>
            <h1>Every visit, every note, in one calm place.</h1>
            <p>KASSCare keeps home care teams in step, from the first check-in to the final claim.</p>

            <ul class="kc-auth-roles">
                <li><x-kc-icon name="heart" /><span><b>Caregivers</b> check in, log care and check out from their phone.</span></li>
                <li><x-kc-icon name="stethoscope" /><span><b>Nurses and providers</b> review care logs, write notes and act on alerts.</span></li>
                <li><x-kc-icon name="building" /><span><b>Agencies</b> see patients, staff, visits and revenue together.</span></li>
            </ul>
        </div>

        <svg class="kc-pulse" viewBox="0 0 600 90" preserveAspectRatio="none" fill="none" stroke="#8fd3c3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M0 55h220l16-34 20 58 18-76 18 52h308"/>
        </svg>

        <p class="kc-auth-small">&copy; {{ date('Y') }} KASS MTV USA LLC. Built with HIPAA-aware practices.</p>
    </section>

    <section class="kc-auth-form-wrap">
        <div class="kc-auth-form">
            {{ $slot }}
        </div>
    </section>

</div>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); });
    }
</script>
</body>
</html>
