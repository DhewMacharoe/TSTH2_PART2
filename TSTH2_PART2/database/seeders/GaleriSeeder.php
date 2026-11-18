<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GaleriAlbum;

class GaleriSeeder extends Seeder
{
    public function run()
    {
        $albums = [
            [
                'title' => 'Foto Drone Kawasan SH',
                'description' => 'Pengambilan gambar udara menggunakan drone di area SH.',
                'photos' => ['galeri/foto-drone-sh-23-juni-2026.jpg'],
            ],
            [
                'title' => 'Foto Tanaman di SH',
                'description' => 'Dokumentasi pertumbuhan tanaman di lahan SH periode Maret 2026.',
                'photos' => ['galeri/foto-tanaman-sh-maret-2026.jpg'],
            ],
            [
                'title' => 'Foto dan Video Lebaran',
                'description' => 'Dokumentasi momen perayaan kebersamaan Idul Fitri.',
                'photos' => ['galeri/foto-lebaran-12-maret.jpg'],
            ],
            [
                'title' => 'Dokumentasi Acara 17-an',
                'description' => 'Keseruan perayaan kemerdekaan 17 Agustus.',
                'photos' => ['galeri/acara-17an-agustus-2026.jpg'],
            ],
        ];

        foreach ($albums as $item) {
            GaleriAlbum::updateOrCreate(
                ['title' => $item['title']],
                [
                    'description' => $item['description'],
                    'event_date'  => now(),
                    'photos'      => $item['photos'],
                ]
            );
        }
    }
}