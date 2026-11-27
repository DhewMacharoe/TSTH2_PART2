<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $purpose
    ) {}

    public function build()
    {
        $purposeText = match ($this->purpose) {
            'verify_new_email' => 'Verifikasi Email Baru',
            'change_password' => 'Perubahan Password',
            'forgot_password' => 'Pemulihan Password',
            default => 'Verifikasi Akun',
        };

        return $this->subject('Kode OTP - Logbook Karyawan')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp,
                'purposeText' => $purposeText,
            ]);
    }
}
