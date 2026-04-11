<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Admin pre-subscription access
        |--------------------------------------------------------------------------
        | Facility admins should still be able to:
        | - reach dashboard
        | - reach billing
        | - activate subscription
        */
        if ($user->role === 'admin') {
            if (
                $request->routeIs('admin.dashboard') ||
                $request->routeIs('dashboard') ||
                $request->routeIs('billing.*') ||
                $request->routeIs('facility.admin.home') ||
                $request->is('admin/dashboard') ||
                $request->is('billing') ||
                $request->is('billing/*') ||
                $request->is('facility-admin/home')
            ) {
                return $next($request);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve subscription from facility first
        |--------------------------------------------------------------------------
        */
        $facilityId = session('facility_id') ?? $user->facility_id ?? null;

        if ($facilityId) {
            $facility = Facility::find($facilityId);

            if ($facility && ($facility->subscription_status ?? 'inactive') === 'active') {
                return $next($request);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback to user subscription status
        |--------------------------------------------------------------------------
        */
        if (($user->subscription_status ?? 'inactive') === 'active') {
            return $next($request);
        }

        return redirect()->route('billing.notice');
    }
}
