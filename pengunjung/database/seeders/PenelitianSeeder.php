<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penelitian;

class PenelitianSeeder extends Seeder
{
    public function run()
    {
        $penelitian = [
            [
                'title' => 'Pengembangan Genolab',
                'description' => 'Riset dan dokumentasi aktivitas pengujian yang dilakukan di Genolab.',
                'image_path' => null,
            ],
            [
                'title' => 'Pemasangan Pancang di Lahan Kopi',
                'description' => 'Persiapan lahan dan pemasangan pancang untuk riset penanaman bibit kopi unggulan.',
                'image_path' => null,
            ],
            [
                'title' => 'FGD Pengembangan Lahan',
                'description' => 'Focus Group Discussion terkait riset dan optimalisasi lahan pertanian.',
                'image_path' => null,
            ],
            [
                'title' => 'Kajian TSTH2',
                'description' => 'Dokumentasi riset dan pengembangan Taman Sains dan Teknologi Herbal dan Hortikultura (TSTH2).',
                'image_path' => null,
            ]
        ];

        foreach ($penelitian as $item) {
            Penelitian::create($item);
        }
    }
}