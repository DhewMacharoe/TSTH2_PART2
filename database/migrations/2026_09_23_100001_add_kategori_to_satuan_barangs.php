<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kategori ke satuan_barangs
        Schema::table('satuan_barangs', function (Blueprint $table) {
            $table->string('kategori', 50)->nullable()->after('nama_satuan');
        });

        // 2. Set kategori untuk satuan yang sudah ada
        $satuanMap = [
            // Pupuk & Bahan Kimia
            'kg'     => null, // berlaku untuk semua
            'gram'   => null,
            'liter'  => null,
            'ml'     => null,
            'sack'   => 'Pupuk',
            'botol'  => 'Bahan Kimia',
            // Alat
            'pcs'    => null,
            'unit'   => null,
            'set'    => 'Alat Budidaya',
            'dus'    => null,
        ];
        foreach ($satuanMap as $nama => $kategori) {
            DB::table('satuan_barangs')->where('nama_satuan', $nama)->update(['kategori' => $kategori]);
        }

        // 3. Tambah satuan baru yang spesifik per kategori
        $now = now();
        $newSatuan = [
            ['nama_satuan' => 'karung',    'kategori' => 'Pupuk',         'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nama_satuan' => 'biji',      'kategori' => 'Bibit Tanaman', 'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nama_satuan' => 'buah',      'kategori' => 'Alat Budidaya', 'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nama_satuan' => 'jerigen',   'kategori' => 'Bahan Kimia',   'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nama_satuan' => 'ampul',     'kategori' => 'Bahan Kimia',   'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
            ['nama_satuan' => 'pak',       'kategori' => 'Bibit Tanaman', 'created_by' => null, 'created_at' => $now, 'updated_at' => $now],
        ];
        foreach ($newSatuan as $s) {
            // Skip jika sudah ada
            if (!DB::table('satuan_barangs')->where('nama_satuan', $s['nama_satuan'])->exists()) {
                DB::table('satuan_barangs')->insert($s);
            }
        }
    }

    public function down(): void
    {
        Schema::table('satuan_barangs', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
