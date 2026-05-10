<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProviderRegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'plan' => ['required', 'in:provider_solo,provider_pro'],
            'accept_terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => 'provider',
            'plan' => $request->plan,
            'subscription_status' => 'trialing',
            'trial_ends_at' => now()->addDays(30),
            'subscription_starts_at' => now(),
            'subscription_ends_at' => now()->addDays(30),
        ]);

        Auth::login($user);

        return redirect()
            ->route('provider.dashboard')
            ->with('success', 'Welcome to KassCare. Your 30-day provider trial has started.');
    }
}
