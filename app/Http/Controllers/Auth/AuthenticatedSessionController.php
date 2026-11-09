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

        $hasRole = function ($roles) use ($user) {
            $roles = (array) $roles;
            if (method_exists($user, 'hasAnyRole')) {
                try {
                    if ($user->hasAnyRole($roles)) return true;
                } catch (\Throwable $e) {}
            }
            return in_array($user->role ?? '', $roles);
        };

        $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
        $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');

        // 1. Role Karyawan / Employee / Lapangan -> Logbook Portal
        if ($hasRole(['karyawan', 'employee', 'leader', 'kepala_divisi', 'kepala_departemen'])) {
            return redirect()->away($urlLogbook . '/dashboard');
        }

        // 2. Role PIC Gudang, Pimpinan, Agronom -> Sistem Gudang
        if ($hasRole(['pic_gudang', 'pimpinan', 'agronom'])) {
            return redirect()->away($urlGudang . '/dasbor');
        }

        // 3. Role Web Admin & Admin Katalog -> Web Management & Katalog
        if ($hasRole(['web_admin', 'admin_katalog'])) {
            return redirect()->intended(route('web.management.home'));
        }

        return redirect()->intended('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
