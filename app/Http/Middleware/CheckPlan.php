<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlan
{
    public function handle(Request $request, Closure $next, ...$allowedPlans)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin bypass
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        $userPlan = $user->plan ?? null;

        if (!$userPlan || !in_array($userPlan, $allowedPlans)) {
            return redirect()->route('billing.notice')
                ->with('error', 'Your current plan does not allow access to this feature.');
        }

        return $next($request);
    }
}
