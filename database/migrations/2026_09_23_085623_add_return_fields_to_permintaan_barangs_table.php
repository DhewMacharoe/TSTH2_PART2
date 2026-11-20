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
            $table->boolean('is_habis_pakai_saat_lapor')->default(false)->after('kondisi_pengembalian');
            $table->integer('jumlah_dikembalikan')->nullable()->after('is_habis_pakai_saat_lapor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['is_habis_pakai_saat_lapor', 'jumlah_dikembalikan']);
        });
    }
};
