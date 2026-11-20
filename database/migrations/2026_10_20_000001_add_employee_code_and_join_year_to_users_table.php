<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            /*
            |------------------------------------------------------------------
            | employee_code
            |------------------------------------------------------------------
            | Kode unik karyawan dalam format DDDBBYYNNN.
            | Nullable agar akun lama (admin, pimpinan, dll.) tidak wajib
            | memiliki kode, dan unik agar tidak ada duplikasi.
            */
            $table->string('employee_code', 20)
                ->nullable()
                ->unique()
                ->after('division_id');

            /*
            |------------------------------------------------------------------
            | join_year
            |------------------------------------------------------------------
            | Tahun masuk karyawan (4 digit), digunakan sebagai YY pada kode.
            | Default-nya adalah tahun saat akun dibuat.
            */
            $table->unsignedSmallInteger('join_year')
                ->nullable()
                ->after('employee_code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_code', 'join_year']);
        });
    }
};
