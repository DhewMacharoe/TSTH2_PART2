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

        // Di portal Warehouse: seluruh pengguna yang login diarahkan ke modul gudang/dasbor
        if ($user->isPicGudang() || $user->isPicLab()) {
            return redirect()->intended(route('dasbor'));
        }

        if ($user->role === 'pimpinan') {
            return redirect()->intended(route('permintaan.index'));
        }

        return redirect()->intended(route('gudang'));
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
