<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            return redirect('/'); // Atau arahkan ke /dashboard atau sesuai kebutuhan
        }

        return $next($request);
    }
}
