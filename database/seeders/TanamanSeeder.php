<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class TanamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'nama_barang' => 'Jahe Merah',
                'merk' => 'Zingiber officinale var. rubrum',
                'kategori' => 'Rimpang',
                'lokasi_blok' => 'Blok A-12',
                'stok' => 450,
                'status' => 'Tersedia',
            ],
            [
                'nama_barang' => 'Kumis Kucing',
                'merk' => 'Orthosiphon aristatus',
                'kategori' => 'Daun',
                'lokasi_blok' => 'Blok B-05',
                'stok' => 15,
                'status' => 'Stok Kritis',
            ],
            [
                'nama_barang' => 'Temulawak',
                'merk' => 'Curcuma zanthorrhiza',
                'kategori' => 'Rimpang',
                'lokasi_blok' => 'Blok A-14',
                'stok' => 320,
                'status' => 'Tersedia',
            ],
            [
                'nama_barang' => 'Pegagan',
                'merk' => 'Centella asiatica',
                'kategori' => 'Daun',
                'lokasi_blok' => 'Blok C-02',
                'stok' => 210,
                'status' => 'Tersedia',
            ],
            [
                'nama_barang' => 'Mengkudu',
                'merk' => 'Morinda citrifolia',
                'kategori' => 'Buah',
                'lokasi_blok' => 'Blok D-01',
                'stok' => 5,
                'status' => 'Stok Kritis',
            ],
        ];

        foreach ($barangs as $b) {
            Barang::create($b);
        }
    }
}
