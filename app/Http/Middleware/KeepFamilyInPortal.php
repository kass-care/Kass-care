<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Family members may only use the family portal, their profile and sign out.
 * Runs on every web request, so no other page can leak agency data to them.
 */
class KeepFamilyInPortal
{
    private const ALLOWED_PREFIXES = ['family.', 'profile.', 'password.', 'verification.'];
    private const ALLOWED_ROUTES = ['logout', 'dashboard', 'terms', 'login'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'family') {
            return $next($request);
        }

        $name = $request->route()?->getName() ?? '';

        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return $next($request);
            }
        }

        if (in_array($name, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        return redirect()->route('family.dashboard');
    }
}
