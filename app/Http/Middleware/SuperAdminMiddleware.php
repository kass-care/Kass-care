<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (($user->role ?? null) !== 'super_admin') {
            abort(403, 'Unauthorized. Super admin access only.');
        }

        return $next($request);
    }
}
