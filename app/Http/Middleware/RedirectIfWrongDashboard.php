<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfWrongDashboard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Super admin
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Facility admin
        |--------------------------------------------------------------------------
        | Admin users with a facility_id must never live inside the super admin
        | dashboard area. If they somehow land there, force them back home.
        */
        if ($user->role === 'admin' && ! empty($user->facility_id)) {
            if (str_starts_with($routeName, 'admin.')) {
                return redirect()->route('facility.admin.home');
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Provider
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'provider') {
            if (str_starts_with($routeName, 'admin.') || str_starts_with($routeName, 'facility.')) {
                return redirect()->route('provider.dashboard');
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Caregiver
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'caregiver') {
            if (
                str_starts_with($routeName, 'admin.')
                || str_starts_with($routeName, 'facility.')
                || str_starts_with($routeName, 'provider.')
            ) {
                return redirect()->route('caregiver.dashboard');
            }

            return $next($request);
        }

        return $next($request);
    }
}
