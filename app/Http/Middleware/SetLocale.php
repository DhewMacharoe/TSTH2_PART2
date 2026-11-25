<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Dual language is for all public visitor and catalog pages.
        // Internal dashboards, admin panels, warehouse, and management remain exclusively in Bahasa Indonesia.
        $isAdminDashboard = $request->is(
            'dasbor*',
            'admin*',
            'web-management*',
            'web_management*',
            'gudang*',
            'permintaan*',
            'catatan*'
        );

        if (!$isAdminDashboard) {
            // Cek session terlebih dahulu, jika belum ada cek cookie site_pref_lang atau googtrans
            $locale = Session::get('locale');
            if (!$locale) {
                $cookiePref = $request->cookie('site_pref_lang');
                $googTrans  = (string) $request->cookie('googtrans', '');
                if ($cookiePref === 'en' || str_contains($googTrans, '/id/en') || str_contains($googTrans, '/en')) {
                    $locale = 'en';
                } else {
                    $locale = 'id';
                }
                Session::put('locale', $locale);
            }

            if (!in_array($locale, ['id', 'en'])) {
                $locale = 'id';
            }
            App::setLocale($locale);
        } else {
            App::setLocale('id');
        }

        return $next($request);
    }
}
