<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangBatch;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $b1 = Barang::create([
            'nama_barang' => 'Pupuk NPK Mutiara 16-16-16',
            'merk' => 'YaraMila',
            'kategori' => 'Pupuk',
            'gambar' => 'barangs/pupuk_npk.png',
            'lokasi_blok' => 'Blok A - Rak 1',
            'stok' => 150,
            'status' => 'Tersedia',
        ]);
        BarangBatch::create([
            'barang_id' => $b1->id,
            'tanggal_kedatangan' => now()->subDays(10),
            'tanggal_kadaluarsa' => now()->addMonths(6),
            'stok' => 150
        ]);

        $b2 = Barang::create([
            'nama_barang' => 'Cangkul Baja Hitam',
            'merk' => 'Krokodil',
            'kategori' => 'Alat Budidaya',
            'gambar' => 'barangs/cangkul_baja.png',
            'lokasi_blok' => 'Blok B - Rak 2',
            'stok' => 25,
            'status' => 'Stok Kritis',
        ]);
        BarangBatch::create([
            'barang_id' => $b2->id,
            'tanggal_kedatangan' => now()->subMonths(1),
            'tanggal_kadaluarsa' => null,
            'stok' => 25
        ]);

        $b3 = Barang::create([
            'nama_barang' => 'Pestisida Pembasmi Hama',
            'merk' => 'Regent 50 SC',
            'kategori' => 'Bahan Kimia',
            'gambar' => 'barangs/pestisida_regent.png',
            'lokasi_blok' => 'Blok C - Rak 1',
            'stok' => 0,
            'status' => 'Habis',
        ]);
        BarangBatch::create([
            'barang_id' => $b3->id,
            'tanggal_kedatangan' => now()->subMonths(2),
            'tanggal_kadaluarsa' => now()->addMonths(12),
            'stok' => 0
        ]);
        
        $b4 = Barang::create([
            'nama_barang' => 'Bibit Pohon Jati Unggul',
            'merk' => 'Perhutani',
            'kategori' => 'Bibit Tanaman',
            'gambar' => 'barangs/bibit_jati.png',
            'lokasi_blok' => 'Area Pembibitan Utara',
            'stok' => 500,
            'status' => 'Tersedia',
        ]);
        BarangBatch::create([
            'barang_id' => $b4->id,
            'tanggal_kedatangan' => now()->subDays(5),
            'tanggal_kadaluarsa' => null,
            'stok' => 500
        ]);
    }
}
