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
        // Dual language is strictly for public visitor pages
        // Internal dashboards, admin panels, warehouse, and management remain exclusively in Bahasa Indonesia
        $isPublicVisitorPage = $request->is(
            '/',
            'profil-tsth2*',
            'layanan*',
            'penelitian*',
            'berita*',
            'galeri*',
            'katalog-tanaman*'
        );

        if ($isPublicVisitorPage) {
            // Cek session terlebih dahulu, jika belum ada cek cookie site_pref_lang atau googtrans
            $locale = Session::get('locale');
            if (!$locale) {
                $cookiePref = $request->cookie('site_pref_lang');
                $googTrans = $request->cookie('googtrans', '');
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
