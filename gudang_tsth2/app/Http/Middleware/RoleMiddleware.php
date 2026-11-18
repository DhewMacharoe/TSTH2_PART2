<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming permintaan.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $permintaan, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'Harap login terlebih dahulu.');
        }

        // Dukung penulisan 'role:a|b|c' maupun 'role:a,b,c'
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode('|', $role) as $r) {
                $allowedRoles[] = trim($r);
            }
        }

        if (!in_array(auth()->user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($permintaan);
    }
}
