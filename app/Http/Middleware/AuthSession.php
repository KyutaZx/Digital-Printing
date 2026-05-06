<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthSession
{
    /**
     * Pastikan user sudah login (ada session token).
     * Jika belum, redirect ke halaman login.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!session('token') || !session('user')) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Jika ada role yang dicek
        if (!empty($roles)) {
            $userRole = session('user.role', 'customer');
            if (!in_array($userRole, $roles)) {
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
