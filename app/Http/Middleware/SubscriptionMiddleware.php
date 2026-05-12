<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
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

        $facility = $user->facility;

        if (
            !$facility ||
            !in_array(($facility->subscription_status ?? 'inactive'), ['active', 'trialing'], true)
        ) {
            return redirect()
                ->route('billing.notice')
                ->with('error', 'Facility subscription or trial required.');
        }

        return $next($request);
    }
}
