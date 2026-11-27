
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();

            // Pemilik OTP
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Email tujuan pengiriman OTP
            $table->string('email');

            // verify_new_email, change_password, forgot_password
            $table->string('purpose', 30);

            // OTP disimpan dalam bentuk hash
            $table->string('otp_hash');

            // Diisi jika OTP digunakan untuk mengganti email
            $table->string('new_email')->nullable();

            // Masa berlaku OTP
            $table->timestamp('expires_at');

            // Waktu terakhir OTP dikirim
            $table->timestamp('last_sent_at')->nullable();

            // Jumlah percobaan v7\Magang\TSTH2_PART2\logbook\app\Models\EmailOtp.phperifikasi
            $table->unsignedTinyInteger('attempts')->default(0);

            // Waktu OTP berhasil diverifikasi
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index(['email', 'purpose']);
            $table->index(['user_id', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_otps');
    }
};
