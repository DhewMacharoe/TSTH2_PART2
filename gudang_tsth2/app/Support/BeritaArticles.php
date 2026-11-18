<?php

namespace App\Support;

class BeritaArticles
{
    public static function all(): array
    {
        return [
            'kunjungan-menko' => [
                'slug' => 'kunjungan-menko',
                'category' => 'Press Release',
                'date' => '12 Agustus 2026',
                'title' => 'Kunjungan Menteri Koordinator Bidang Maritim dan Investasi ke Fasilitas Smart Greenhouse',
                'excerpt' => 'Menteri Koordinator meninjau langsung kesiapan panen raya benih kentang unggul hasil perbanyakan aeroponik di blok A TSTH2 Humbang Hasundutan.',
                'image' => 'assets/hero_bg_1.png',
                'body' => [
                    'Kunjungan kerja ini menandai komitmen pemerintah dalam memperkuat rantai pasok benih hortikultura unggul di Sumatera Utara. Rombongan meninjau blok A Smart Greenhouse TSTH2 yang saat ini dalam tahap persiapan panen raya kentang hasil perbanyakan aeroponik.',
                    'Direktur TSTH2, Sri Fatmawati, Ph.D, memaparkan integrasi sensor iklim, nutrisi presisi, dan protokol sanitasi yang menekan tingkat mortalitas bibit. Sistem ini dirancang agar dapat direplikasi oleh petani mitra setelah tahap inkubasi selesai.',
                    'Selain greenhouse, delegasi juga melihat ruang kontrol data dan rencana perluasan kapasitas produksi benih untuk musim tanam berikutnya. Hasil tinjauan akan menjadi masukan bagi program hilirisasi riset herbal dan hortikultura nasional.',
                ],
            ],
            'genom-kemenyan' => [
                'slug' => 'genom-kemenyan',
                'category' => 'Publikasi Riset',
                'date' => '05 Agustus 2026',
                'title' => 'Jurnal Internasional: Sekuensing Genom Kemenyan Toba Tembus Q1',
                'excerpt' => 'Tim peneliti biologi molekuler TSTH2 berhasil menerbitkan hasil riset pemetaan genom utuh tanaman Styrax sumatrana di jurnal ilmiah bergengsi.',
                'image' => 'assets/program.png',
                'body' => [
                    'Tim biologi molekuler TSTH2 merilis hasil sekuensing genom utuh Styrax sumatrana (kemenyan Toba) pada jurnal internasional bereputasi kuartil 1. Publikasi ini memetakan gen-gen terkait metabolit sekunder dan ketahanan penyakit.',
                    'Data genom akan menjadi fondasi program pemuliaan presisi, standarisasi ekstrak, dan konservasi plasma nutfah kemenyan di kawasan Danau Toba. Kolaborasi dilakukan bersama mitra perguruan tinggi dan laboratorium sekuensing mitra.',
                    'Publikasi lengkap dan dataset pendukung dapat diakses melalui portal riset TSTH2 setelah proses embargo jurnal selesai.',
                ],
            ],
            'mesin-hplc' => [
                'slug' => 'mesin-hplc',
                'category' => 'Pembaruan Fasilitas',
                'date' => '01 Agustus 2026',
                'title' => 'Instalasi Mesin HPLC Baru di Ruang Ekstraksi Selesai',
                'excerpt' => 'Kapasitas analisis senyawa metabolit sekunder meningkat 300% berkat tambahan fasilitas mutakhir.',
                'image' => 'assets/penelitian.png',
                'body' => [
                    'Instalasi High Performance Liquid Chromatography (HPLC) generasi terbaru di ruang ekstraksi TSTH2 telah selesai dikalibrasi. Peralatan ini menaikkan throughput analisis senyawa metabolit sekunder hingga sekitar tiga kali lipat.',
                    'Laboratorium kini dapat menjalankan metode green extraction dan standarisasi fraksi bioaktif kemenyan serta tanaman herbal mitra dengan waktu putar yang lebih singkat.',
                    'Pelatihan operator dan SOP keamanan laboratorium sudah dijalankan sebelum alat digunakan untuk layanan analisis internal maupun mitra industri.',
                ],
            ],
            'panen-bibit-kemenyan' => [
                'slug' => 'panen-bibit-kemenyan',
                'category' => 'Inovasi',
                'date' => '12 Agustus 2026',
                'title' => 'Panen Perdana Bibit Kemenyan Unggul',
                'excerpt' => 'Tim riset TSTH2 berhasil melakukan panen perdana bibit kemenyan menggunakan metode smart-farming yang menekan tingkat mortalitas hingga 5%.',
                'image' => 'assets/hero_bg.png',
                'fallback_image' => 'assets/hero_bg_1.png',
                'body' => [
                    'TSTH2 mencatat panen perdana bibit kemenyan unggul dari protokol smart-farming di greenhouse. Metode baru ini menekan mortalitas bibit hingga sekitar 5%, jauh lebih rendah dibanding pembibitan konvensional.',
                    'Bibit hasil panen akan didistribusikan bertahap ke petani mitra setelah lolos seleksi vigor dan kesehatan. Tim agronomi juga menyusun paket pendampingan budidaya untuk musim tanam berikutnya.',
                ],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }
}
