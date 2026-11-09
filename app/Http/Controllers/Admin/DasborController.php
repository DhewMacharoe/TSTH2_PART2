<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DasborController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'web_admin') {
            return redirect()->route('web.management.home');
        }

        return view('dasbor', compact('user'));
    }

    public function logbook(Request $request)
    {
        if ($request->query('fallback')) {
            return view('catatan.index');
        }

        $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
        return redirect()->away($urlLogbook . '/dashboard');
    }
}
