<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = strtolower(Auth::user()->role); // Ubah ke huruf kecil
        $allowedRoles = array_map('strtolower', $roles); // Semua role juga ke huruf kecil

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }


}
