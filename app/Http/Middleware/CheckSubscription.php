<?php

namespace App\Http\Middleware;

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

        $facility = $user->facility;

        if (!$facility || $facility->subscription_status !== 'active') {
            return redirect()->route('billing.notice')
                ->with('error', 'Facility subscription required.');
        }

        return $next($request);
    }
}
