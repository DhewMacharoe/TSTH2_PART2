<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed News (Berita)
        $news = [
            [
                'category' => 'Kunjungan',
                'title' => 'Kunjungan Delegasi UGM ke TSTH2',
                'description' => 'Universitas Gadjah Mada (UGM) melakukan kunjungan resmi ke TSTH2 untuk membahas potensi kerja sama riset dan pengembangan teknologi pertanian modern.',
                'date' => '2026-08-31',
            ],
            [
                'category' => 'Pertanian',
                'title' => 'Panen Tomat Berhasil di Kawasan TSTH2',
                'description' => 'Kegiatan panen tomat yang melimpah sukses dilakukan. Ini merupakan bukti nyata dari hasil riset budidaya hortikultura presisi di lingkungan terpadu.',
                'date' => '2026-01-13',
            ],
            [
                'category' => 'Pertanian',
                'title' => 'Panen Bawang Putih di Parsingguran',
                'description' => 'Tim TSTH2 merayakan keberhasilan panen komoditas bawang putih di lahan Parsingguran, membuktikan kecocokan iklim setempat untuk varietas unggulan.',
                'date' => '2026-02-24',
            ],
            [
                'category' => 'Kunjungan',
                'title' => 'Kunjungan Tim China untuk Riset Global',
                'description' => 'Tim peneliti dari China mengunjungi fasilitas TSTH2 dalam rangka studi banding dan perluasan jaringan riset inovasi global.',
                'date' => '2026-07-28',
            ],
            [
                'category' => 'Pendidikan',
                'title' => 'Pelatihan Standar ISO untuk Staf TSTH2',
                'description' => 'Untuk meningkatkan kualitas layanan dan standar operasional berkelas dunia, seluruh jajaran staf TSTH2 mengikuti program pelatihan sertifikasi ISO.',
                'date' => '2026-02-06',
            ],
        ];

        foreach ($news as $n) {
            \App\Models\News::create(array_merge($n, ['image_path' => 'placeholder.jpg']));
        }

        // 2. Seed Researches (Riset & Fasilitas)
        $researches = [
            [
                'title' => 'Fasilitas Genolab',
                'description' => 'Laboratorium genetika modern berstandar internasional yang mendukung penelitian mendalam terkait rekayasa genetik dan pemuliaan bibit unggul.',
            ],
            [
                'title' => 'Riset Pemuliaan Kopi TSTH2',
                'description' => 'Lahan percobaan untuk pengembangan varietas kopi tahan cuaca ekstrem. Proses pemasangan pancang batas lahan penelitian telah diselesaikan secara presisi.',
            ],
            [
                'title' => 'Riset Budidaya Hortikultura',
                'description' => 'Penelitian intensif terkait laju pertumbuhan berbagai jenis tanaman hortikultura di dalam fasilitas Screen House terkendali dan sistem monitoring canggih.',
            ],
        ];

        foreach ($researches as $r) {
            \App\Models\Research::create(array_merge($r, ['image_path' => 'placeholder.jpg']));
        }

        // 3. Seed Programs
        $programs = [
            [
                'title' => 'Ketahanan Pangan Berkelanjutan',
                'description' => 'Program strategis yang berfokus pada ketahanan pangan nasional melalui budidaya komoditas unggulan secara modern, efisien, dan terukur.',
            ],
            [
                'title' => 'Kolaborasi Edukasi dan Sains Global',
                'description' => 'TSTH2 membuka pintu selebar-lebarnya bagi akademisi, institusi riset, dan peneliti internasional untuk saling bertukar ilmu dan berinovasi bersama.',
            ],
        ];

        foreach ($programs as $p) {
            \App\Models\Program::create($p); // Program doesn't have image_path
        }

        // 4. Seed Galleries (Galeri Foto)
        $galleries = [
            [
                'title' => 'Pemandangan Udara TSTH2 (Drone)',
                'description' => 'Tangkapan kamera drone memperlihatkan luasnya kawasan TSTH2 dari ketinggian.',
            ],
            [
                'title' => 'Kegiatan Lebaran Keluarga TSTH2',
                'description' => 'Momen hangat kebersamaan dan silaturahmi seluruh staf TSTH2 pada saat perayaan Idul Fitri.',
            ],
            [
                'title' => 'Penyambutan Tamu Belanda',
                'description' => 'Dokumentasi kegiatan kunjungan kenegaraan dan kolaborasi dari pakar asal Belanda.',
            ],
            [
                'title' => 'Rapat Kerja Tahunan TSTH2',
                'description' => 'Suasana rapat kerja strategis tim TSTH2 untuk merumuskan target riset ke depan.',
            ],
        ];

        foreach ($galleries as $g) {
            \App\Models\Gallery::create(array_merge($g, ['image_path' => 'placeholder.jpg']));
        }
    }
}
