<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
     public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    if (!$user) {
        abort(403, 'Unauthorized');
    }

    // SUPER ADMIN
    if ($user->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }

    // FACILITY ADMIN
    if ($user->role === 'admin') {

        if (!empty($user->facility_id) && Facility::where('id', $user->facility_id)->exists()) {
            $request->session()->put('facility_id', $user->facility_id);
        }

        return redirect()->route('facility.admin.home');
    }

    // PROVIDER
    if ($user->role === 'provider') {
        return redirect()->route('provider.dashboard');
    }

    // CAREGIVER
    if ($user->role === 'caregiver') {
        return redirect()->route('caregiver.dashboard');
    }

    Auth::logout();

    abort(403, 'Unauthorized');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
