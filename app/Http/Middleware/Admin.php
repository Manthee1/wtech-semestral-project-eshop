<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->role == 'Admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
