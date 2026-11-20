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
        // 1. Tambah tipe_barang pada barangs (lapangan vs lab)
        Schema::table('barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('barangs', 'tipe_barang')) {
                $table->string('tipe_barang', 20)->default('lapangan')->after('kategori');
                $table->index('tipe_barang');
            }
        });

        // 2. Tambah tipe_permintaan & verified_by pada permintaan_barangs
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('permintaan_barangs', 'tipe_permintaan')) {
                $table->string('tipe_permintaan', 20)->default('lapangan')->after('barang_id');
                $table->index('tipe_permintaan');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('staff_gudang_id');
            }
            if (!Schema::hasColumn('permintaan_barangs', 'verified_at')) {
                $table->dateTime('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('permintaan_barangs', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('permintaan_barangs', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('permintaan_barangs', 'tipe_permintaan')) {
                $table->dropColumn('tipe_permintaan');
            }
        });

        Schema::table('barangs', function (Blueprint $table) {
            if (Schema::hasColumn('barangs', 'tipe_barang')) {
                $table->dropColumn('tipe_barang');
            }
        });
    }
};

