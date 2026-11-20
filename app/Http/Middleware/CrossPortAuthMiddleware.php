<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CrossPortAuthMiddleware
{
    public const SECRET = 'tsth2_shared_cross_port_secret_key_2024';
    public const COOKIE_NAME = 'tsth2_auth_token';

    public static function makeToken(string $email, ?string $role = null): string
    {
        $payload = [
            'email' => $email,
            'role'  => $role,
            'exp'   => time() + (86400 * 7),
        ];
        $json = base64_encode(json_encode($payload));
        $sig  = hash_hmac('sha256', $json, self::SECRET);
        return $json . '.' . $sig;
    }

    public static function verifyToken(?string $token): ?array
    {
        if (!$token || !str_contains($token, '.')) {
            return null;
        }
        [$json, $sig] = explode('.', $token, 2);
        if (!hash_equals(hash_hmac('sha256', $json, self::SECRET), $sig)) {
            return null;
        }
        $data = json_decode(base64_decode($json), true);
        if (!is_array($data) || empty($data['email']) || ($data['exp'] ?? 0) < time()) {
            return null;
        }
        return $data;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek jika belum login, autentikasi otomatis lewat cookie lintas port
        $token = $request->cookie(self::COOKIE_NAME);
        $payload = self::verifyToken($token);

        if ($payload) {
            $email = $payload['email'];
            $user = \App\Models\User::where('email', $email)->first() ?: \App\Models\User::first();

            if ($user && !Auth::check()) {
                Auth::login($user, true);
            }
            if ($user && !$request->session()->has('admin_authenticated')) {
                $request->session()->put('admin_authenticated', true);
                $request->session()->put('admin_user_id', $user->id);
            }
        }

        $response = $next($request);

        // 2. Jika user sedang terotentikasi, pastikan cookie lintas port aktif
        if (Auth::check() || $request->session()->get('admin_authenticated')) {
            $user = Auth::user() ?: \App\Models\User::first();
            if ($user) {
                $token = self::makeToken($user->email, $user->role ?? 'admin');
                $domain = \App\Services\TsthUrl::cookieDomain();
                \Illuminate\Support\Facades\Cookie::queue(self::COOKIE_NAME, $token, 60 * 24 * 7, '/', $domain, false, false);
            }
        }

        return $response;
    }
}
