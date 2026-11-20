<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek sesi lokal katalog
        if ((bool) $request->session()->get('admin_authenticated', false)) {
            return $next($request);
        }

        // 2. Cek cookie lintas port (tsth2_auth_token)
        $token = $request->cookie(CrossPortAuthMiddleware::COOKIE_NAME);
        $payload = CrossPortAuthMiddleware::verifyToken($token);
        if ($payload) {
            $user = \App\Models\User::where('email', $payload['email'])->first() ?: \App\Models\User::first();
            if ($user) {
                if (!\Illuminate\Support\Facades\Auth::check()) {
                    \Illuminate\Support\Facades\Auth::login($user, true);
                }
                $request->session()->put('admin_authenticated', true);
                $request->session()->put('admin_user_id', $user->id);
                return $next($request);
            }
        }

        // 3. Cek Auth lokal
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $request->session()->put('admin_authenticated', true);
            $request->session()->put('admin_user_id', $user->id);
            return $next($request);
        }

        return redirect()->guest(route('admin.login'));
    }
}
