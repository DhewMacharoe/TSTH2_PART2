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

        // Queue shared cross-port auth cookie (berlaku di semua port localhost)
        \Illuminate\Support\Facades\Cookie::queue(
            \App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME,
            \App\Http\Middleware\CrossPortAuthMiddleware::makeToken($user->email, $user->role ?? null),
            60 * 24 * 7,
            '/',
            null,
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

        $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');
        $urlLogbook    = rtrim(config('app.url_logbook',    env('URL_LOGBOOK',    'http://localhost:8001')), '/');
        $urlGudang     = rtrim(config('app.url_gudang',     env('URL_GUDANG',     'http://localhost:8002')), '/');
        $urlKatalog    = rtrim(config('app.url_katalog',    env('URL_KATALOG',    'http://localhost:8000')), '/');

        // Jika status akun masih pending persetujuan admin
        if (($user->status ?? 'approved') === 'pending') {
            return redirect()->route('approval.pending');
        }

        // Routing login berdasarkan Spatie Role & Role Pengguna:
        // 1. Role Pimpinan -> Dashboard Pimpinan di Logbook
        if ($hasRole('pimpinan')) {
            return redirect()->intended(route('pimpinan.dashboard'));
        }

        // 2. Role Kepala Departemen -> Dashboard Departemen di Logbook
        if ($hasRole('kepala_departemen')) {
            return redirect()->intended(route('kepala_departemen.dashboard'));
        }

        // 3. Role Kepala Divisi -> Dashboard Divisi di Logbook
        if ($hasRole('kepala_divisi')) {
            return redirect()->intended(route('kepala_divisi.dashboard'));
        }

        // 4. Role Karyawan / Employee / Lapangan -> Logbook Dashboard
        if ($hasRole(['karyawan', 'employee', 'leader'])) {
            return redirect()->intended(route('employee.dashboard'));
        }

        // 5. Role Admin Logbook -> Dashboard Admin Logbook
        if ($hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // 6. Role PIC Gudang, PIC Lab, Warehouse, Agronom -> Sistem Gudang
        if ($hasRole(['pic_gudang', 'pic_lab', 'warehouse', 'agronom'])) {
            return redirect()->away($urlGudang . '/dasbor');
        }

        // 7. Role Web Admin -> Web Management Home
        if ($hasRole('web_admin')) {
            return redirect()->away($urlPengunjung . '/web-management/home');
        }

        // 8. Role Admin Katalog -> Katalog
        if ($hasRole('admin_katalog')) {
            return redirect()->away($urlKatalog . '/web-management/katalog');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Illuminate\Support\Facades\Cookie::queue(
            \Illuminate\Support\Facades\Cookie::forget(\App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME, '/', null)
        );

        return redirect()->route('login');
    }
}
