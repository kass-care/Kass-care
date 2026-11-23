<x-guest-layout>
    <a href="{{ url('/') }}" class="kc-brand">
        <span class="kc-brand-mark"><x-kc-icon name="pulse" style="color:#fff" /></span>
        <span class="kc-brand-name">KASS<span style="color:var(--kc-teal)">Care</span></span>
    </a>

    <h2>Sign in</h2>
    <p>Use the email your agency registered for you.</p>

    @if (session('status'))
        <div class="kc-flash kc-flash--ok" role="status" style="margin-bottom:16px">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="kc-field">
            <label for="email">Email</label>
            <input id="email" class="kc-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" inputmode="email">
            @error('email')<p class="kc-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="kc-field">
            <label for="password">Password</label>
            <input id="password" class="kc-input" type="password" name="password" required autocomplete="current-password">
            @error('password')<p class="kc-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="kc-row">
            <label for="remember_me" class="kc-check">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Keep me signed in</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="kc-btn kc-btn--primary kc-btn--block">Sign in</button>
    </form>

    @if (Route::has('register-facility') || Route::has('register-provider'))
        <p class="kc-auth-alt">
            New to KASSCare?
            @if (Route::has('register-facility'))<a href="{{ route('register-facility') }}">Register an agency</a>@endif
            @if (Route::has('register-facility') && Route::has('register-provider')) or @endif
            @if (Route::has('register-provider'))<a href="{{ route('register-provider') }}">join as a provider</a>@endif
        </p>
    @endif
</x-guest-layout>
