<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->subscribed('default')) {
            return redirect()->route('billing')
                ->with('error', 'Please subscribe to access your dashboard.');
        }

        return $next($request);
    }
}
