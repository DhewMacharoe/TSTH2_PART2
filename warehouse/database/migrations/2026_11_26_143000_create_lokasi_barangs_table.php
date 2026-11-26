<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 100);
            $table->enum('jenis_barang', ['lapangan', 'lab'])->default('lapangan');
            $table->string('deskripsi', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['nama_lokasi', 'jenis_barang']);
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        $defaults = [
            // Warehouse Gudang (GA) - Lapangan
            ['nama_lokasi' => 'Blok A - Rak 1', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Rak penyimpanan utama Blok A'],
            ['nama_lokasi' => 'Blok B - Rak 2', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Rak penyimpanan Blok B'],
            ['nama_lokasi' => 'Blok C - Rak 1', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Rak penyimpanan Blok C'],
            ['nama_lokasi' => 'Area Pembibitan Utara', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Area pembibitan sisi utara'],
            ['nama_lokasi' => 'Lahan Percobaan Timur', 'jenis_barang' => 'lapangan', 'deskripsi' => 'Lahan percobaan sisi timur'],

            // Warehouse Lab (RdI) - Laboratorium
            ['nama_lokasi' => 'Lemari Asam B3', 'jenis_barang' => 'lab', 'deskripsi' => 'Penyimpanan bahan kimia berbahaya / asam kuat'],
            ['nama_lokasi' => 'Kulkas Reagen 4°C', 'jenis_barang' => 'lab', 'deskripsi' => 'Pendingin reagen suhu 4°C'],
            ['nama_lokasi' => 'Freezer -20°C', 'jenis_barang' => 'lab', 'deskripsi' => 'Freezer suhu -20°C'],
            ['nama_lokasi' => 'Rak Alat Kaca L-1', 'jenis_barang' => 'lab', 'deskripsi' => 'Rak alat gelas laboratorium L-1'],
            ['nama_lokasi' => 'Rak Alat Kaca L-2', 'jenis_barang' => 'lab', 'deskripsi' => 'Rak alat gelas laboratorium L-2'],
            ['nama_lokasi' => 'Meja Preparasi Lab 1', 'jenis_barang' => 'lab', 'deskripsi' => 'Area preparasi sampel Lab 1'],
            ['nama_lokasi' => 'Desikator Kimia', 'jenis_barang' => 'lab', 'deskripsi' => 'Penyimpanan bahan higroskopis'],
        ];

        foreach ($defaults as $d) {
            DB::table('lokasi_barangs')->insertOrIgnore(array_merge($d, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Sinkronkan lokasi yang sudah terlanjur dipakai di tabel barangs
        if (Schema::hasTable('barangs')) {
            $existing = DB::table('barangs')
                ->select('lokasi_blok', 'jenis_barang')
                ->whereNotNull('lokasi_blok')
                ->where('lokasi_blok', '!=', '')
                ->distinct()
                ->get();

            foreach ($existing as $row) {
                DB::table('lokasi_barangs')->insertOrIgnore([
                    'nama_lokasi'  => trim($row->lokasi_blok),
                    'jenis_barang' => in_array($row->jenis_barang, ['lapangan', 'lab']) ? $row->jenis_barang : 'lapangan',
                    'deskripsi'    => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_barangs');
    }
};

