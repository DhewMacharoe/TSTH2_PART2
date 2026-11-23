<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatusAndForceLogout
{
    /**
     * Handle an incoming request.
     * 
     * 1. Deteksi apakah Admin meminta force_logout (saat akun diapprove atau role diubah).
     *    Jika ya, sesi langsung di-logout otomatis dan user diminta login kembali.
     * 2. Deteksi apakah user berstatus 'pending'.
     *    Jika ya, kunci akses dan arahkan ke halaman pemberitahuan 'approval.pending'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Trigger Force Logout setelah Approval / Update Role oleh Admin
            if ($user->force_logout) {
                // Reset flag force_logout di database
                \App\Models\User::where('id', $user->id)->update(['force_logout' => false]);

                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                \Illuminate\Support\Facades\Cookie::queue(
                    \Illuminate\Support\Facades\Cookie::forget(\App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME, '/', null)
                );

                return redirect()->route('login')->with(
                    'info',
                    'Role atau akun Anda telah disetujui / diperbarui oleh Administrator. Silakan login kembali untuk masuk ke dashboard role baru Anda.'
                );
            }

            // 2. Akun masih Pending (Menunggu Persetujuan Admin)
            if (($user->status ?? 'approved') === 'pending') {
                if (!$request->routeIs('approval.pending') && !$request->routeIs('logout')) {
                    return redirect()->route('approval.pending');
                }
            }
        }

        return $next($request);
    }
}

