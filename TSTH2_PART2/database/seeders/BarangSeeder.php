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
        ]);
        BarangBatch::create([
            'barang_id' => $b1->id,
            'batch_no' => '001',
            'tanggal_kedatangan' => now()->subDays(10),
            'tanggal_kadaluarsa' => now()->addMonths(6),
            'stok' => 150,
            'satuan' => 'sack',
            'kondisi' => 'Tersegel',
            'stok_detail' => [
                ['satuan' => 'sack', 'kondisi' => 'Tersegel', 'stok' => 150]
            ]
        ]);

        $b2 = Barang::create([
            'nama_barang' => 'Cangkul Baja Hitam',
            'merk' => 'Krokodil',
            'kategori' => 'Alat Budidaya',
            'gambar' => 'barangs/cangkul_baja.png',
            'lokasi_blok' => 'Blok B - Rak 2',
        ]);
        BarangBatch::create([
            'barang_id' => $b2->id,
            'batch_no' => '001',
            'tanggal_kedatangan' => now()->subMonths(1),
            'tanggal_kadaluarsa' => null,
            'stok' => 25,
            'satuan' => 'unit',
            'kondisi' => 'Baru',
            'stok_detail' => [
                ['satuan' => 'unit', 'kondisi' => 'Baru', 'stok' => 25]
            ]
        ]);

        $b3 = Barang::create([
            'nama_barang' => 'Pestisida Pembasmi Hama',
            'merk' => 'Regent 50 SC',
            'kategori' => 'Bahan Kimia',
            'gambar' => 'barangs/pestisida_regent.png',
            'lokasi_blok' => 'Blok C - Rak 1',
        ]);
        BarangBatch::create([
            'barang_id' => $b3->id,
            'batch_no' => '001',
            'tanggal_kedatangan' => now()->subMonths(2),
            'tanggal_kadaluarsa' => now()->addMonths(12),
            'stok' => 0,
            'satuan' => 'botol',
            'kondisi' => 'Tersegel',
            'stok_detail' => [
                ['satuan' => 'botol', 'kondisi' => 'Tersegel', 'stok' => 0]
            ]
        ]);
        
        $b4 = Barang::create([
            'nama_barang' => 'Bibit Pohon Jati Unggul',
            'merk' => 'Perhutani',
            'kategori' => 'Bibit Tanaman',
            'gambar' => 'barangs/bibit_jati.png',
            'lokasi_blok' => 'Area Pembibitan Utara',
        ]);
        BarangBatch::create([
            'barang_id' => $b4->id,
            'batch_no' => '001',
            'tanggal_kedatangan' => now()->subDays(5),
            'tanggal_kadaluarsa' => null,
            'stok' => 500,
            'satuan' => 'bibit',
            'kondisi' => 'Bagus',
            'stok_detail' => [
                ['satuan' => 'bibit', 'kondisi' => 'Bagus', 'stok' => 500]
            ]
        ]);
    }
}
