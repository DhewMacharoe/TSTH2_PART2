<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class EmailOtpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. VERIFIKASI EMAIL BARU MELALUI PROFIL
    |--------------------------------------------------------------------------
    */

    public function sendNewEmailOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $user = $request->user();

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        if ($validated['email'] === $user->email) {
            return back()->withErrors([
                'email' => 'Email tersebut sama dengan email akun saat ini.',
            ]);
        }

        $otpService->send(
            $user,
            $validated['email'],
            'verify_new_email',
            $validated['email']
        );

        session([
            'pending_new_email' => $validated['email'],
        ]);

        return redirect()
            ->route('profile.email-otp.form')
            ->with('status', 'Kode OTP telah dikirim ke email baru kamu.');
    }

    public function showNewEmailOtp(Request $request): View
    {
        if (!session()->has('pending_new_email')) {
            abort(403, 'Tidak ada proses perubahan email yang aktif.');
        }

        return view('profile.verify-email-otp', [
            'email' => session('pending_new_email'),
        ]);
    }

    public function verifyNewEmailOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        $email = session('pending_new_email');

        if (!$email) {
            abort(403, 'Sesi perubahan email sudah berakhir.');
        }

        $record = $otpService->verify(
            $user,
            $email,
            'verify_new_email',
            $validated['otp']
        );

        $user->email = $record->new_email;
        $user->email_verified_at = now();
        $user->save();

        $otpService->consume($record);

        session()->forget('pending_new_email');

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Email berhasil diverifikasi dan diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | 2. UBAH PASSWORD MELALUI PROFIL
    |--------------------------------------------------------------------------
    */

    public function sendProfilePasswordOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $user = $request->user();

        $otpService->send(
            $user,
            $user->email,
            'change_password'
        );

        return redirect()
            ->route('profile.password-otp.form')
            ->with('status', 'Kode OTP telah dikirim ke email akun kamu.');
    }

    public function showProfilePasswordOtp(): View
    {
        return view('profile.verify-password-otp');
    }

    public function verifyProfilePasswordOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        $record = $otpService->verify(
            $user,
            $user->email,
            'change_password',
            $validated['otp']
        );

        session([
            'profile_password_otp_verified_until' => now()
                ->addMinutes(10)
                ->timestamp,
        ]);

        $otpService->consume($record);

        return redirect()
            ->route('profile.password.change.form');
    }

    public function showProfilePasswordChange(): View
    {
        $verifiedUntil = session('profile_password_otp_verified_until');

        if (!$verifiedUntil || now()->timestamp > $verifiedUntil) {
            abort(403, 'Silakan lakukan verifikasi OTP terlebih dahulu.');
        }

        return view('profile.change-password-otp');
    }

    public function updateProfilePassword(
        Request $request
    ): RedirectResponse {
        $verifiedUntil = session('profile_password_otp_verified_until');

        if (!$verifiedUntil || now()->timestamp > $verifiedUntil) {
            session()->forget('profile_password_otp_verified_until');

            abort(403, 'Verifikasi OTP sudah berakhir. Silakan ulangi proses.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        $user->password = $validated['password'];
        $user->save();

        session()->forget('profile_password_otp_verified_until');

        // Notifikasi password berhasil diubah.
        try {
            Mail::raw(
                'Password akun Logbook Karyawan kamu telah berhasil diubah. '
                . 'Jika perubahan ini bukan dilakukan oleh kamu, segera hubungi administrator.',
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Notifikasi Perubahan Password - Logbook Karyawan');
                }
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Password berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | 3. LUPA PASSWORD
    |--------------------------------------------------------------------------
    */

    public function sendForgotPasswordOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        session()->forget([
            'forgot_password_user_id',
            'forgot_password_email',
            'forgot_password_verified_until',
        ]);

        if ($user) {
            $otpService->send(
                $user,
                $user->email,
                'forgot_password'
            );

            session([
                'forgot_password_user_id' => $user->id,
                'forgot_password_email' => $user->email,
            ]);
        }

        return redirect()
            ->route('password.otp.form')
            ->with(
                'status',
                'Jika email terdaftar, kode verifikasi akan dikirim. Silakan periksa Inbox atau Spam.'
            );
    }

    public function showForgotPasswordOtp(): View
    {
        return view('auth.verify-forgot-password-otp', [
            'email' => session('forgot_password_email'),
        ]);
    }

    public function verifyForgotPasswordOtp(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('forgot_password_user_id');
        $email = session('forgot_password_email');

        if (!$userId || !$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Sesi pemulihan password sudah berakhir. Silakan ulangi.',
                ]);
        }

        $user = User::findOrFail($userId);

        $record = $otpService->verify(
            $user,
            $email,
            'forgot_password',
            $validated['otp']
        );

        session([
            'forgot_password_verified_until' => now()
                ->addMinutes(10)
                ->timestamp,
        ]);

        // OTP tetap disimpan sampai password berhasil diubah.
        return redirect()->route('password.otp.reset');
    }

    public function showForgotPasswordReset(): View
    {
        $verifiedUntil = session('forgot_password_verified_until');

        if (!$verifiedUntil || now()->timestamp > $verifiedUntil) {
            abort(403, 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password-otp');
    }

    public function resetForgotPassword(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $verifiedUntil = session('forgot_password_verified_until');

        if (!$verifiedUntil || now()->timestamp > $verifiedUntil) {
            session()->forget([
                'forgot_password_user_id',
                'forgot_password_email',
                'forgot_password_verified_until',
            ]);

            abort(403, 'Verifikasi OTP sudah berakhir. Silakan ulangi proses.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail(session('forgot_password_user_id'));

        $record = EmailOtp::where('user_id', $user->id)
            ->where('purpose', 'forgot_password')
            ->whereNotNull('verified_at')
            ->latest('id')
            ->first();

        if (!$record) {
            abort(403, 'Verifikasi OTP tidak ditemukan.');
        }

        $user->password = $validated['password'];
        $user->save();

        $otpService->consume($record);

        // Hapus data proses pemulihan dari session.
        session()->forget([
            'forgot_password_user_id',
            'forgot_password_email',
            'forgot_password_verified_until',
        ]);

        // Notifikasi password berhasil diubah.
        try {
            Mail::raw(
                'Password akun Logbook Karyawan kamu telah berhasil diubah. '
                . 'Jika perubahan ini bukan dilakukan oleh kamu, segera hubungi administrator.',
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Notifikasi Perubahan Password - Logbook Karyawan');
                }
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login menggunakan password baru.');
    }
}
