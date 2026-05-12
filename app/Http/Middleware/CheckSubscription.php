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

        if ($user->role === 'super_admin') {
            return $next($request);
        }

        $allowedStatuses = ['active', 'trialing'];

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

        $facilityId = session('facility_id') ?? $user->facility_id ?? null;

        if ($facilityId) {
            $facility = Facility::find($facilityId);

            if ($facility && in_array(($facility->subscription_status ?? 'inactive'), $allowedStatuses, true)) {
                return $next($request);
            }
        }

        if (in_array(($user->subscription_status ?? 'inactive'), $allowedStatuses, true)) {
            if (($user->subscription_status ?? null) === 'trialing') {
                if (!$user->trial_ends_at || now()->lessThanOrEqualTo($user->trial_ends_at)) {
                    return $next($request);
                }
            } else {
                return $next($request);
            }
        }

        return redirect()->route('billing.notice');
    }
}
