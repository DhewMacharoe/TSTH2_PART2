<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $permintaan): RedirectResponse
    {
        if ($permintaan->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dasbor', absolute: false).'?verified=1');
        }

        if ($permintaan->user()->markEmailAsVerified()) {
            event(new Verified($permintaan->user()));
        }

        return redirect()->intended(route('dasbor', absolute: false).'?verified=1');
    }
}
