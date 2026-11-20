<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        $cookieDomain = \App\Services\TsthUrl::cookieDomain();
        $authCookie = cookie(
            \App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME,
            \App\Http\Middleware\CrossPortAuthMiddleware::makeToken($user->email, $user->role ?? null),
            60 * 24 * 7,
            '/',
            $cookieDomain,
            false,
            false
        );

        $hasRole = function ($roles) use ($user) {
            $roles = (array) $roles;
            if (method_exists($user, 'hasAnyRole')) {
                try {
                    if ($user->hasAnyRole($roles)) return true;
                } catch (\Throwable $e) {}
            }
            return in_array($user->role ?? '', $roles);
        };

        $urlLogbook   = \App\Services\TsthUrl::logbook();
        $urlGudang    = \App\Services\TsthUrl::gudang();
        $urlPengunjung = \App\Services\TsthUrl::pengunjung();
        $urlKatalog   = \App\Services\TsthUrl::katalog();

        // Helper SSO Auth Redirect ke portal lain tanpa perlu login ulang
        $ssoRedirect = function (string $baseUrl, string $targetPath, string $email) use ($authCookie) {
            $secret = 'tsth2_shared_secret_hub_auth_2024';
            $ts = time();
            $sig = hash_hmac('sha256', "{$email}|{$ts}|{$targetPath}", $secret);
            $query = http_build_query([
                'email'  => $email,
                'ts'     => $ts,
                'target' => $targetPath,
                'sig'    => $sig,
            ]);
            return redirect()->away($baseUrl . '/hub-auth?' . $query)->withCookie($authCookie);
        };

        // Routing login terpadu berdasarkan Role Pengguna:
        // 1. Role Pimpinan -> Dashboard Pimpinan di Logbook
        if ($hasRole('pimpinan')) {
            return $ssoRedirect($urlLogbook, '/pimpinan/dashboard', $user->email);
        }

        // 2. Role Kepala Departemen -> Dashboard Departemen di Logbook
        if ($hasRole('kepala_departemen')) {
            return $ssoRedirect($urlLogbook, '/kepala-departemen/dashboard', $user->email);
        }

        // 3. Role Kepala Divisi -> Dashboard Divisi di Logbook
        if ($hasRole('kepala_divisi')) {
            return $ssoRedirect($urlLogbook, '/kepala-divisi/dashboard', $user->email);
        }

        // 4. Role Karyawan / Employee / Lapangan -> Logbook Karyawan
        if ($hasRole(['karyawan', 'employee', 'leader'])) {
            return $ssoRedirect($urlLogbook, '/dashboard', $user->email);
        }

        // 5. Role Logbook Admin -> Dashboard Admin Logbook
        if ($hasRole('admin')) {
            return $ssoRedirect($urlLogbook, '/admin/dashboard', $user->email);
        }

        // 6. Role PIC Gudang, PIC Lab, Agronom, Warehouse -> Sistem Gudang
        if ($hasRole(['pic_gudang', 'pic_lab', 'warehouse', 'agronom'])) {
            return $ssoRedirect($urlGudang, '/dasbor', $user->email);
        }

        // 7. Role Web Admin -> Web Management Home
        if ($hasRole('web_admin')) {
            return redirect()->intended(route('web.management.home'))->withCookie($authCookie);
        }

        // 8. Role Admin Katalog -> Manajemen Katalog
        if ($hasRole('admin_katalog')) {
            return $ssoRedirect($urlKatalog, '/admin/plants', $user->email);
        }

        return redirect()->intended('/')->withCookie($authCookie);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $cookieDomain = \App\Services\TsthUrl::cookieDomain();
        \Illuminate\Support\Facades\Cookie::queue(
            \Illuminate\Support\Facades\Cookie::forget(\App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME, '/', $cookieDomain)
        );

        return redirect('/');
    }
}
