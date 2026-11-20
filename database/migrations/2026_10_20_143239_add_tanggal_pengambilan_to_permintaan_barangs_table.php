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
            $table->timestamp('tanggal_pengambilan')->nullable()->after('jadwal_pengambilan');
            $table->timestamp('pending_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pengambilan', 'pending_at']);
        });
    }
};
