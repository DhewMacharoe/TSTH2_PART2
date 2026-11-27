<?php

namespace App\Services;

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EmailOtpService
{
    /**
     * Membuat dan mengirim OTP.
     */
    public function send(
        User $user,
        string $email,
        string $purpose,
        ?string $newEmail = null
    ): EmailOtp {
        $record = EmailOtp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->latest('id')
            ->first();

        if (
            $record &&
            $record->last_sent_at &&
            $record->last_sent_at->diffInSeconds(now()) < 60
        ) {
            throw ValidationException::withMessages([
                'otp' => 'Silakan tunggu 60 detik sebelum meminta OTP baru.',
            ]);
        }

        if (
            $record &&
            $record->attempts >= 5 &&
            $record->expires_at->isFuture()
        ) {
            throw ValidationException::withMessages([
                'otp' => 'Batas percobaan OTP telah tercapai. Silakan coba kembali setelah masa berlaku OTP berakhir.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        $record = EmailOtp::updateOrCreate(
            [
                'user_id' => $user->id,
                'purpose' => $purpose,
            ],
            [
                'email' => $email,
                'new_email' => $newEmail,
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'last_sent_at' => now(),
                'attempts' => 0,
                'verified_at' => null,
            ]
        );

        Mail::to($email)->send(
            new SendOtpMail($otp, $purpose)
        );

        return $record;
    }

    public function verify(
        User $user,
        string $email,
        string $purpose,
        string $otp
    ): EmailOtp {
        $record = EmailOtp::where('user_id', $user->id)
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->latest('id')
            ->first();

        if (!$record || $record->verified_at) {
            throw ValidationException::withMessages([
                'otp' => 'OTP tidak ditemukan atau sudah digunakan.',
            ]);
        }

        if ($record->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        if ($record->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp' => 'Batas percobaan OTP telah tercapai.',
            ]);
        }

        $record->increment('attempts');

        if (!Hash::check($otp, $record->otp_hash)) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP yang kamu masukkan salah.',
            ]);
        }

        $record->update([
            'verified_at' => now(),
        ]);

        return $record;
    }

    public function consume(EmailOtp $record): void
    {
        $record->delete();
    }
}
