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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication permintaan.
     */
    public function store(LoginRequest $permintaan): RedirectResponse
    {
        $permintaan->authenticate();

        $permintaan->session()->regenerate();

        return redirect()->intended(route('dasbor', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $permintaan): RedirectResponse
    {
        Auth::guard('web')->logout();

        $permintaan->session()->invalidate();

        $permintaan->session()->regenerateToken();

        return redirect('/');
    }
}
