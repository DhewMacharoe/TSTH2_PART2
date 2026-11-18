<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $permintaan): RedirectResponse|View
    {
        return $permintaan->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dasbor', absolute: false))
                    : view('auth.verify-email');
    }
}
