<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! in_array($request->user()?->role, ['admin', 'staff'], true)) {
            abort(403);
        }

        return $next($request);
    }
}
