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
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['keterangan_staff', 'keterangan_pic']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->text('keterangan_staff')->nullable();
            $table->text('keterangan_pic')->nullable();
        });
    }
};
