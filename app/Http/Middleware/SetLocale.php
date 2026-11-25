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
        $isPublicVisitorPage = !$request->is('admin*');

        if ($isPublicVisitorPage) {
            $locale = Session::get('locale');
            if (!$locale) {
                $cookiePref = $request->cookie('site_pref_lang');
                if ($cookiePref === 'en') {
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

