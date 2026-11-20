<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        $berita = [
            [
                'category' => 'Kunjungan',
                'title' => 'Kunjungan Ketua DEN dan Mendikti',
                'description' => 'Dokumentasi kunjungan kerja Ketua Dewan Energi Nasional (DEN) dan Mendikti.',
                'date' => '2026-09-26',
                'image_path' => 'berita/kunjungan-ketua-den-mendikti.jpg',
                'status' => 'published',
            ],
            [
                'category' => 'Kegiatan',
                'title' => 'Panen Bawang Putih di Parsingguran',
                'description' => 'Kegiatan panen bawang putih bersama tim di wilayah Parsingguran.',
                'date' => '2026-02-24',
                'image_path' => 'berita/panen-bawang-putih.jpg',
                'status' => 'published',
            ],
            [
                'category' => 'Kunjungan',
                'title' => 'Kunjungan Tim China',
                'description' => 'Penerimaan kunjungan kerja dari Tim China untuk pembahasan kerjasama.',
                'date' => '2026-07-28',
                'image_path' => 'berita/kunjungan-china.jpg',
                'status' => 'published',
            ],
            [
                'category' => 'Kegiatan',
                'title' => 'Pelatihan ISO',
                'description' => 'Dokumentasi kegiatan pelatihan ISO bagi para staf dan karyawan.',
                'date' => '2026-02-06',
                'image_path' => 'berita/pelatihan-iso.jpg',
                'status' => 'published',
            ],
            [
                'category' => 'Kegiatan',
                'title' => 'Panen Tomat',
                'description' => 'Kegiatan panen tomat hasil pengembangan di lahan pertanian.',
                'date' => '2026-01-13',
                'image_path' => 'berita/panen-tomat.jpg',
                'status' => 'published',
            ]
        ];

        foreach ($berita as $item) {
            Berita::create($item);
        }
    }
}