<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // default ke 'anggota' agar konsisten
        $userRole = auth()->user()->role ?? 'anggota';

        // samakan case agar tidak sensitif huruf besar/kecil
        $userRole = strtolower($userRole);
        $roles    = array_map('strtolower', $roles);

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Forbidden (role mismatch).');
        }

        return $next($request);
    }
}
