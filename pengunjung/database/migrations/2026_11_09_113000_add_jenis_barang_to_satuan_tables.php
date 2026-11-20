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
        // 1. Tambah jenis_barang di tabel satuan_barangs (lapangan vs lab)
        Schema::table('satuan_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('satuan_barangs', 'jenis_barang')) {
                $table->string('jenis_barang', 20)->default('lapangan')->after('kategori');
                $table->index('jenis_barang');
            }
        });

        // 2. Tambah jenis_barang di tabel satuan_konversis
        Schema::table('satuan_konversis', function (Blueprint $table) {
            if (!Schema::hasColumn('satuan_konversis', 'jenis_barang')) {
                $table->string('jenis_barang', 20)->default('lapangan')->after('kategori');
                $table->index('jenis_barang');
            }
        });

        // 3. Update data awal berdasarkan kategori yang ada
        DB::table('satuan_barangs')
            ->where('kategori', 'like', '%Kimia%')
            ->orWhere('kategori', 'like', '%Lab%')
            ->orWhere('kategori', 'like', '%Reagen%')
            ->update(['jenis_barang' => 'lab']);

        DB::table('satuan_konversis')
            ->where('kategori', 'like', '%Kimia%')
            ->orWhere('kategori', 'like', '%Lab%')
            ->orWhere('kategori', 'like', '%Reagen%')
            ->update(['jenis_barang' => 'lab']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satuan_konversis', function (Blueprint $table) {
            if (Schema::hasColumn('satuan_konversis', 'jenis_barang')) {
                $table->dropIndex(['jenis_barang']);
                $table->dropColumn('jenis_barang');
            }
        });

        Schema::table('satuan_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('satuan_barangs', 'jenis_barang')) {
                $table->dropIndex(['jenis_barang']);
                $table->dropColumn('jenis_barang');
            }
        });
    }
};

