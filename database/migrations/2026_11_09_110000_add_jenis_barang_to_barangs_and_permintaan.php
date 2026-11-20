<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah jenis_barang di tabel barangs (lapangan vs lab)
        Schema::table('barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('barangs', 'jenis_barang')) {
                $table->string('jenis_barang')->default('lapangan')->after('kategori');
                $table->index('jenis_barang');
            }
        });

        // 2. Tambah jenis_barang di tabel permintaan_barangs
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('permintaan_barangs', 'jenis_barang')) {
                $table->string('jenis_barang')->default('lapangan')->after('barang_id');
                $table->index('jenis_barang');
            }
        });

        // 3. Update data awal: sesuaikan kategori lab vs lapangan
        // Misal Bahan Kimia -> lab, Pupuk / Bibit / Alat Budidaya -> lapangan
        DB::table('barangs')->where('kategori', 'like', '%Kimia%')->update(['jenis_barang' => 'lab']);
        DB::table('barangs')->where('kategori', 'like', '%Lab%')->update(['jenis_barang' => 'lab']);
        DB::table('barangs')->where('nama_barang', 'like', '%Reagen%')->update(['jenis_barang' => 'lab']);
        DB::table('barangs')->where('nama_barang', 'like', '%Mikroskop%')->update(['jenis_barang' => 'lab']);

        // Update jenis_barang di permintaan_barangs berdasarkan barangs
        DB::statement("
            UPDATE permintaan_barangs p
            JOIN barangs b ON p.barang_id = b.id
            SET p.jenis_barang = b.jenis_barang
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('permintaan_barangs', 'jenis_barang')) {
                $table->dropIndex(['jenis_barang']);
                $table->dropColumn('jenis_barang');
            }
        });

        Schema::table('barangs', function (Blueprint $table) {
            if (Schema::hasColumn('barangs', 'jenis_barang')) {
                $table->dropIndex(['jenis_barang']);
                $table->dropColumn('jenis_barang');
            }
        });
    }
};
