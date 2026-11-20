<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->enum('jenis_barang', ['lapangan', 'lab'])->default('lapangan');
            $table->string('deskripsi', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['nama_kategori', 'jenis_barang']);
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // Seed kategori awal berdasarkan standar operasional TSTH2
        $defaults = [
            // Warehouse Gudang (GA) - Lapangan
            ['nama_kategori' => 'Pupuk', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Pupuk organik, anorganik, NPK, dan nutrisi tanaman'],
            ['nama_kategori' => 'Bibit Tanaman', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Benih, bibit herbal, dan bibit tanaman hortikultura'],
            ['nama_kategori' => 'Alat Budidaya', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Peralatan pertanian, cangkul, sabit, sprayer, dll'],
            ['nama_kategori' => 'Logistik & Perkakas', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Perkakas lapangan, terpal, tali, pipa irigasi, dan logistik umum'],
            ['nama_kategori' => 'Lainnya', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Kebutuhan umum gudang lapangan lainnya'],

            // Warehouse Lab (RdI) - Laboratorium
            ['nama_kategori' => 'Reagen & Larutan', 'jenis_barang' => 'lab', 'deskripsi' => 'Reagen analisis, pelarut PA, buffer, indikator'],
            ['nama_kategori' => 'Bahan Kimia', 'jenis_barang' => 'lab', 'deskripsi' => 'Bahan kimia murni, pestisida lab, ekstrak simplisia'],
            ['nama_kategori' => 'Alat Gelas & Plastik Lab', 'jenis_barang' => 'lab', 'deskripsi' => 'Tabung reaksi, beaker glass, erlenmeyer, buret, labu ukur'],
            ['nama_kategori' => 'Instrumen Lab', 'jenis_barang' => 'lab', 'deskripsi' => 'Mikropipet, pH meter, timbangan analitik, vorteks, sentrifus'],
            ['nama_kategori' => 'Bahan Habis Pakai Lab', 'jenis_barang' => 'lab', 'deskripsi' => 'Kertas saring, tips pipet, eppendorf tube, sarung tangan nitril'],
            ['nama_kategori' => 'Lainnya', 'jenis_barang' => 'lab', 'deskripsi' => 'Kebutuhan laboratorium riset dan inovasi lainnya'],
        ];

        foreach ($defaults as $d) {
            DB::table('kategori_barangs')->insertOrIgnore(array_merge($d, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_barangs');
    }
};

