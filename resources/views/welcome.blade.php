<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0f2e33">
    <title>KASSCare | Home care software for agencies, nurses, caregivers and families</title>
    <meta name="description" content="KASSCare keeps home care teams in step: visit check-ins, care logs, vitals alerts, medications, admissions, compliance and family updates in one place.">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/kasscare-192.png">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,700|bricolage-grotesque:500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kasscare-theme.css') }}?v=4">
    <style>
        .lp { color: var(--kc-text); }
        .lp a:not(.kc-btn) { color: inherit; }
        .lp .kc-btn--primary { color: #fff; }
        .lp-nav-cta a { white-space: nowrap; }
        .lp-wrap { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
        .lp-display { font-family: var(--kc-font-display); letter-spacing: -0.025em; color: var(--kc-ink); }

        /* Nav */
        .lp-nav { position: sticky; top: 0; z-index: 30; background: rgba(245, 248, 247, .92); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-bottom: 1px solid var(--kc-line); }
        .lp-nav .lp-wrap { display: flex; align-items: center; gap: 24px; height: 68px; }
        .lp-nav .kc-brand { padding: 0; color: var(--kc-ink); }
        .lp-nav .kc-brand-name span { color: var(--kc-teal); }
        .lp-links { display: none; gap: 22px; margin-left: 12px; }
        .lp-links a { text-decoration: none; font-weight: 700; font-size: 15px; color: var(--kc-muted); }
        .lp-links a:hover { color: var(--kc-ink); }
        .lp-nav-cta { margin-left: auto; display: flex; gap: 8px; }
        .lp-signin { display: inline-flex; align-items: center; min-height: 44px; padding: 0 14px; font-weight: 700; text-decoration: none; color: var(--kc-ink) !important; }
        @media (min-width: 960px) { .lp-links { display: flex; } }
        @media (max-width: 520px) { .lp-nav .lp-wrap { gap: 10px; } .lp-nav .kc-btn { padding: 0 12px; font-size: 14px; } .lp-signin { padding: 0 6px; } .lp-nav .kc-brand-name { font-size: 19px; } }

        /* Hero */
        .lp-hero { padding: 48px 0 64px; }
        .lp-hero .lp-wrap { display: grid; gap: 48px; align-items: center; }
        @media (min-width: 960px) { .lp-hero { padding: 72px 0 88px; } .lp-hero .lp-wrap { grid-template-columns: 1.1fr .9fr; } }
        .lp-hero h1 { font-size: clamp(40px, 6vw, 68px); line-height: 1.02; margin: 0; max-width: 13ch; }
        .lp-hero p.lead { font-size: 19px; line-height: 1.55; color: var(--kc-muted); max-width: 46ch; margin: 22px 0 0; }
        .lp-hero-cta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 30px; }
        .lp-hero-cta .kc-btn { min-height: 54px; padding: 0 24px; font-size: 17px; }
        .lp-small { margin-top: 14px; font-size: 14px; color: var(--kc-muted); }

        /* The visit timeline (signature element) */
        .lp-visit { background: #fff; border: 1px solid var(--kc-line); border-radius: 26px; box-shadow: 0 30px 60px -30px rgba(15, 46, 51, .35); overflow: hidden; max-width: 460px; justify-self: center; width: 100%; }
        .lp-visit-head { background: var(--kc-ink); color: #fff; padding: 20px 22px; }
        .lp-visit-head small { color: #8fd3c3; font-weight: 700; font-size: 13px; }
        .lp-visit-head strong { display: block; font-family: var(--kc-font-display); font-size: 22px; margin-top: 2px; }
        .lp-visit-head span { color: #b9cfca; font-size: 14px; }
        .lp-steps { list-style: none; margin: 0; padding: 10px 22px 18px; }
        .lp-steps li { position: relative; display: grid; grid-template-columns: 22px 1fr auto; gap: 12px; padding: 14px 0; border-top: 1px solid var(--kc-line); }
        .lp-steps li:first-child { border-top: 0; }
        .lp-steps .dot { width: 14px; height: 14px; border-radius: 50%; margin-top: 4px; background: var(--kc-teal); box-shadow: 0 0 0 4px var(--kc-mint); }
        .lp-steps .dot--warn { background: var(--kc-amber); box-shadow: 0 0 0 4px var(--kc-amber-soft); }
        .lp-steps b { display: block; font-size: 15px; }
        .lp-steps p { margin: 2px 0 0; font-size: 14px; color: var(--kc-muted); }
        .lp-steps time { font-size: 13px; color: var(--kc-muted); font-weight: 700; white-space: nowrap; }
        .lp-who { display: inline-block; margin-top: 6px; font-size: 12px; font-weight: 700; color: var(--kc-teal-deep); background: var(--kc-mint); padding: 2px 8px; border-radius: 999px; }
        @media (prefers-reduced-motion: no-preference) {
            .lp-steps li { opacity: 0; transform: translateY(6px); animation: lp-in .5s ease forwards; }
            .lp-steps li:nth-child(1) { animation-delay: .15s; } .lp-steps li:nth-child(2) { animation-delay: .55s; }
            .lp-steps li:nth-child(3) { animation-delay: .95s; } .lp-steps li:nth-child(4) { animation-delay: 1.35s; }
            .lp-steps li:nth-child(5) { animation-delay: 1.75s; }
            @keyframes lp-in { to { opacity: 1; transform: none; } }
        }

        /* Sections */
        .lp-section { padding: 72px 0; }
        .lp-section--white { background: #fff; border-top: 1px solid var(--kc-line); border-bottom: 1px solid var(--kc-line); }
        .lp-section--ink { background: var(--kc-ink); color: #d9e7e4; }
        .lp-section--ink .lp-display { color: #fff; }
        .lp-h2 { font-size: clamp(30px, 4vw, 44px); line-height: 1.08; margin: 0; max-width: 20ch; }
        .lp-intro { font-size: 18px; color: var(--kc-muted); max-width: 58ch; margin: 14px 0 0; }
        .lp-section--ink .lp-intro { color: #b9cfca; }

        .lp-roles { display: grid; gap: 16px; margin-top: 40px; }
        @media (min-width: 720px) { .lp-roles { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 1080px) { .lp-roles { grid-template-columns: repeat(4, 1fr); } }
        .lp-role { padding: 24px; border-radius: 18px; background: var(--kc-paper); border: 1px solid var(--kc-line); }
        .lp-role svg { width: 28px; height: 28px; color: var(--kc-teal); }
        .lp-role h3 { font-family: var(--kc-font-display); font-size: 22px; margin: 14px 0 6px; color: var(--kc-ink); }
        .lp-role > p { margin: 0 0 12px; color: var(--kc-muted); }
        .lp-role ul { margin: 0; padding: 0; list-style: none; display: grid; gap: 8px; font-size: 15px; }
        .lp-role li { display: flex; gap: 8px; align-items: flex-start; }
        .lp-role li svg { width: 18px; height: 18px; flex: none; margin-top: 2px; }

        .lp-features { display: grid; gap: 28px 32px; margin-top: 44px; }
        @media (min-width: 760px) { .lp-features { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 1080px) { .lp-features { grid-template-columns: repeat(3, 1fr); } }
        .lp-feature h3 { display: flex; align-items: center; gap: 10px; font-family: var(--kc-font-display); font-size: 20px; margin: 0 0 8px; color: var(--kc-ink); }
        .lp-feature h3 svg { width: 22px; height: 22px; color: var(--kc-teal); }
        .lp-feature p { margin: 0; color: var(--kc-muted); }

        .lp-trust { display: grid; gap: 24px; margin-top: 40px; }
        @media (min-width: 860px) { .lp-trust { grid-template-columns: repeat(3, 1fr); } }
        .lp-trust div { border-top: 2px solid #2b5a60; padding-top: 18px; }
        .lp-trust h3 { color: #fff; font-family: var(--kc-font-display); font-size: 20px; margin: 0 0 8px; }
        .lp-trust p { margin: 0; color: #b9cfca; }

        .lp-plans { display: grid; gap: 16px; margin-top: 40px; }
        @media (min-width: 860px) { .lp-plans { grid-template-columns: repeat(3, 1fr); } }
        .lp-plan { background: #fff; border: 1px solid var(--kc-line); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; }
        .lp-plan--main { border: 2px solid var(--kc-teal); }
        .lp-plan h3 { font-family: var(--kc-font-display); font-size: 22px; margin: 0; color: var(--kc-ink); }
        .lp-plan .who { color: var(--kc-muted); margin: 4px 0 18px; }
        .lp-price { font-family: var(--kc-font-display); font-size: 46px; font-weight: 700; color: var(--kc-ink); line-height: 1; }
        .lp-price small { font-size: 16px; font-weight: 500; color: var(--kc-muted); font-family: var(--kc-font-body); }
        .lp-plan .kc-btn { margin-top: 24px; }

        .lp-final { text-align: center; }
        .lp-final .lp-h2 { margin: 0 auto; }
        .lp-final .lp-intro { margin: 14px auto 0; }
        .lp-final .lp-hero-cta { justify-content: center; }

        .lp-foot { padding: 40px 0; font-size: 14px; color: var(--kc-muted); }
        .lp-foot .lp-wrap { display: flex; flex-wrap: wrap; gap: 16px 28px; justify-content: space-between; align-items: center; }
        .lp-foot nav { display: flex; gap: 20px; flex-wrap: wrap; }
        .lp-foot a { text-decoration: none; font-weight: 700; }
    </style>
</head>
<body class="kc-body lp">

<a href="#main" class="kc-skip">Skip to content</a>

<header class="lp-nav">
    <div class="lp-wrap">
        <a href="/" class="kc-brand">
            <span class="kc-brand-mark"><x-kc-icon name="pulse" style="color:#fff" /></span>
            <span class="kc-brand-name">KASS<span>Care</span></span>
        </a>
        <nav class="lp-links" aria-label="Page sections">
            <a href="#how">How it works</a>
            <a href="#features">Features</a>
            <a href="#security">Privacy</a>
            <a href="#pricing">Pricing</a>
        </nav>
        <div class="lp-nav-cta">
            @auth
                <a href="{{ route('dashboard') }}" class="kc-btn kc-btn--primary kc-btn--sm">Open KASSCare</a>
            @else
                <a href="{{ route('login') }}" class="lp-signin">Sign in</a>
                @if(Route::has('register-facility'))
                    <a href="{{ route('register-facility') }}" class="kc-btn kc-btn--primary kc-btn--sm">Get started</a>
                @endif
            @endauth
        </div>
    </div>
</header>

<main id="main">

    {{-- Hero --}}
    <section class="lp-hero">
        <div class="lp-wrap">
            <div>
                <h1 class="lp-display">Home care, documented right.</h1>
                <p class="lead">
                    KASSCare connects caregivers, nurses, agencies and families around every visit.
                    Check-ins, care notes, vitals, medications and compliance live in one calm place,
                    on a phone or a computer.
                </p>
                <div class="lp-hero-cta">
                    @if(Route::has('register-facility'))
                        <a href="{{ route('register-facility') }}" class="kc-btn kc-btn--primary">Register your agency</a>
                    @endif
                    <a href="{{ route('login') }}" class="kc-btn kc-btn--ghost">Sign in</a>
                </div>
                <p class="lp-small">Set up your agency online in a few minutes.</p>
            </div>

            {{-- One visit, as everyone sees it --}}
            <div class="lp-visit" role="img" aria-label="Example of one home care visit in KASSCare">
                <div class="lp-visit-head">
                    <small>Visit in progress</small>
                    <strong>Margaret, 10:00 AM to 12:00 PM</strong>
                    <span>Caregiver: Amina</span>
                </div>
                <ol class="lp-steps">
                    <li>
                        <span class="dot"></span>
                        <div><b>Checked in at the home</b><p>Location confirmed for visit verification.</p><span class="lp-who">Caregiver</span></div>
                        <time>10:04</time>
                    </li>
                    <li>
                        <span class="dot"></span>
                        <div><b>Morning medication given</b><p>Recorded on the eMAR.</p><span class="lp-who">Caregiver</span></div>
                        <time>10:20</time>
                    </li>
                    <li>
                        <span class="dot dot--warn"></span>
                        <div><b>Blood pressure 152/96</b><p>Flagged for the nurse automatically.</p><span class="lp-who">Nurse alerted</span></div>
                        <time>10:31</time>
                    </li>
                    <li>
                        <span class="dot"></span>
                        <div><b>Nurse reviewed and added a note</b><p>Recheck at the next visit.</p><span class="lp-who">Nurse</span></div>
                        <time>10:48</time>
                    </li>
                    <li>
                        <span class="dot"></span>
                        <div><b>Daughter sees the update</b><p>Bathing, lunch and a short walk today.</p><span class="lp-who">Family</span></div>
                        <time>11:58</time>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Roles --}}
    <section class="lp-section lp-section--white" id="how">
        <div class="lp-wrap">
            <h2 class="lp-display lp-h2">Everyone around the visit, kept in step.</h2>
            <p class="lp-intro">Each person signs in and sees only what they need, in plain language, with big buttons that work on a phone.</p>

            <div class="lp-roles">
                <article class="lp-role">
                    <x-kc-icon name="heart" />
                    <h3>Caregivers</h3>
                    <p>One clear next step, all day.</p>
                    <ul>
                        <li><x-kc-icon name="check" /> Check in and out with location</li>
                        <li><x-kc-icon name="check" /> Care logs, vitals and ADLs</li>
                        <li><x-kc-icon name="check" /> Medications on the eMAR</li>
                        <li><x-kc-icon name="check" /> Shifts and incident reports</li>
                    </ul>
                </article>
                <article class="lp-role">
                    <x-kc-icon name="stethoscope" />
                    <h3>Nurses and providers</h3>
                    <p>The patients who need you, first.</p>
                    <ul>
                        <li><x-kc-icon name="check" /> Abnormal vitals flagged automatically</li>
                        <li><x-kc-icon name="check" /> Notes, smart phrases and diagnoses</li>
                        <li><x-kc-icon name="check" /> Medication approvals and pharmacy</li>
                        <li><x-kc-icon name="check" /> Rounds, claims and coding help</li>
                    </ul>
                </article>
                <article class="lp-role">
                    <x-kc-icon name="building" />
                    <h3>Agencies</h3>
                    <p>See problems before they grow.</p>
                    <ul>
                        <li><x-kc-icon name="check" /> Late check-ins and open visits</li>
                        <li><x-kc-icon name="check" /> Admission packets with e-signatures</li>
                        <li><x-kc-icon name="check" /> Compliance documents and readiness</li>
                        <li><x-kc-icon name="check" /> Staff credentials and expiry alerts</li>
                    </ul>
                </article>
                <article class="lp-role">
                    <x-kc-icon name="users" />
                    <h3>Families</h3>
                    <p>Peace of mind, without phone calls.</p>
                    <ul>
                        <li><x-kc-icon name="check" /> Know when care is happening</li>
                        <li><x-kc-icon name="check" /> Read notes from each visit</li>
                        <li><x-kc-icon name="check" /> See upcoming visits</li>
                        <li><x-kc-icon name="check" /> Only ever their own loved one</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="lp-section" id="features">
        <div class="lp-wrap">
            <h2 class="lp-display lp-h2">Everything a home care agency runs on.</h2>
            <p class="lp-intro">Replace the paper binders, group chats and spreadsheets with one system your whole team can use.</p>

            <div class="lp-features">
                <div class="lp-feature">
                    <h3><x-kc-icon name="calendar" /> Visits and shifts</h3>
                    <p>Schedule visits, assign caregivers and see who has checked in. Electronic visit verification is built in.</p>
                </div>
                <div class="lp-feature">
                    <h3><x-kc-icon name="activity" /> Clinical oversight</h3>
                    <p>Vitals are checked as they're logged. High blood pressure, low oxygen, fever and pulse problems reach the nurse right away.</p>
                </div>
                <div class="lp-feature">
                    <h3><x-kc-icon name="pill" /> Medications</h3>
                    <p>eMAR for caregivers, MAR for the agency, and prescriber approvals and pharmacy orders for providers.</p>
                </div>
                <div class="lp-feature">
                    <h3><x-kc-icon name="file-text" /> Admissions</h3>
                    <p>Send admission packets to families, collect handwritten signatures online and keep every document with the patient.</p>
                </div>
                <div class="lp-feature">
                    <h3><x-kc-icon name="shield" /> Compliance</h3>
                    <p>Track documents and staff credentials with expiry warnings, and check inspection readiness before the inspector does.</p>
                </div>
                <div class="lp-feature">
                    <h3><x-kc-icon name="receipt" /> Billing and revenue</h3>
                    <p>Create claims from completed care, follow what's paid, pending or denied, and see revenue by facility.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Privacy --}}
    <section class="lp-section lp-section--ink" id="security">
        <div class="lp-wrap">
            <h2 class="lp-display lp-h2">Patient information stays where it belongs.</h2>
            <p class="lp-intro">KASSCare is built with HIPAA-aware practices from the ground up.</p>
            <div class="lp-trust">
                <div>
                    <h3>Access by role</h3>
                    <p>Caregivers, nurses, agency staff and families each reach only their own screens and their own patients.</p>
                </div>
                <div>
                    <h3>Each agency kept separate</h3>
                    <p>Every agency's patients, staff and records are walled off from every other agency on the platform.</p>
                </div>
                <div>
                    <h3>Nothing stored on phones</h3>
                    <p>The phone app always loads patient information fresh from the server and keeps none of it on the device.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="lp-section" id="pricing">
        <div class="lp-wrap">
            <h2 class="lp-display lp-h2">Simple monthly pricing.</h2>
            <p class="lp-intro">Choose the plan that fits the number of facilities you run. Billed monthly.</p>
            <div class="lp-plans">
                <div class="lp-plan">
                    <h3>Starter</h3>
                    <p class="who">1 facility</p>
                    <div class="lp-price">$29 <small>per month</small></div>
                    @if(Route::has('register-facility'))<a class="kc-btn kc-btn--ghost" href="{{ route('register-facility') }}">Register your agency</a>@endif
                </div>
                <div class="lp-plan lp-plan--main">
                    <h3>Growth</h3>
                    <p class="who">Up to 3 facilities</p>
                    <div class="lp-price">$79 <small>per month</small></div>
                    @if(Route::has('register-facility'))<a class="kc-btn kc-btn--primary" href="{{ route('register-facility') }}">Register your agency</a>@endif
                </div>
                <div class="lp-plan">
                    <h3>Enterprise</h3>
                    <p class="who">Unlimited facilities</p>
                    <div class="lp-price">$199 <small>per month</small></div>
                    @if(Route::has('register-facility'))<a class="kc-btn kc-btn--ghost" href="{{ route('register-facility') }}">Register your agency</a>@endif
                </div>
            </div>
        </div>
    </section>

    {{-- Final call --}}
    <section class="lp-section lp-section--white lp-final">
        <div class="lp-wrap">
            <h2 class="lp-display lp-h2">Healthcare should not be a struggle.</h2>
            <p class="lp-intro">Give your team one place to work, and give families a reason to worry less.</p>
            <div class="lp-hero-cta">
                @if(Route::has('register-facility'))
                    <a href="{{ route('register-facility') }}" class="kc-btn kc-btn--primary">Register your agency</a>
                @endif
                <a href="{{ route('login') }}" class="kc-btn kc-btn--ghost">Sign in</a>
            </div>
        </div>
    </section>
</main>

<footer class="lp-foot">
    <div class="lp-wrap">
        <span>&copy; {{ date('Y') }} KASS MTV USA LLC</span>
        <nav aria-label="Legal">
            @if(Route::has('terms'))<a href="{{ route('terms') }}">Terms of service</a>@endif
            @if(Route::has('privacy'))<a href="{{ route('privacy') }}">Privacy policy</a>@endif
            <a href="{{ route('login') }}">Sign in</a>
        </nav>
    </div>
</footer>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () { navigator.serviceWorker.register('/sw.js').catch(function () {}); });
    }
</script>
</body>
</html>
