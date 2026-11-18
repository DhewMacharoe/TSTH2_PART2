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
        // Alter barangs
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['stok', 'status']);
        });

        // Alter barang_batches
        Schema::table('barang_batches', function (Blueprint $table) {
            $table->string('satuan')->default('pcs')->after('stok');
            $table->string('kondisi')->default('Bagus')->after('satuan');
        });

        // Alter permintaan_barangs
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->date('tanggal_pengembalian')->nullable()->after('status');
            $table->string('foto_pengembalian')->nullable()->after('tanggal_pengembalian');
            $table->string('kondisi_pengembalian')->nullable()->after('foto_pengembalian');
            $table->string('satuan_diminta')->nullable()->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('stok')->default(0);
            $table->string('status')->default('Tersedia');
        });

        Schema::table('barang_batches', function (Blueprint $table) {
            $table->dropColumn(['satuan', 'kondisi']);
        });

        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_pengembalian',
                'foto_pengembalian',
                'kondisi_pengembalian',
                'satuan_diminta'
            ]);
        });
    }
};
