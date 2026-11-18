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
        // Delete any existing "Umum" (kategori = null) to prevent unique violations when recreating them per category
        DB::table('satuan_barangs')->whereNull('kategori')->delete();

        Schema::table('satuan_barangs', function (Blueprint $table) {
            $table->dropUnique('satuan_barangs_nama_satuan_unique');
            $table->unique(['nama_satuan', 'kategori']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satuan_barangs', function (Blueprint $table) {
            $table->dropUnique(['nama_satuan', 'kategori']);
            $table->unique('nama_satuan');
        });
    }
};
