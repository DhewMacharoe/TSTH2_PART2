<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $role = $request->user()->role;

        $normalizedRoles = [$role];
        if ($role === 'web_admin') {
            $normalizedRoles[] = 'admin';
        } elseif ($role === 'admin') {
            $normalizedRoles[] = 'web_admin';
        }
        if ($role === 'karyawan') {
            $normalizedRoles[] = 'employee';
        } elseif ($role === 'employee') {
            $normalizedRoles[] = 'karyawan';
        }

        $hasAccess = count(array_intersect($normalizedRoles, $roles)) > 0;
        if (!$hasAccess && method_exists($request->user(), 'hasAnyRole')) {
            $hasAccess = $request->user()->hasAnyRole($roles);
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}