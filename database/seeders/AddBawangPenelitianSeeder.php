<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penelitian;

class AddBawangPenelitianSeeder extends Seeder
{
    public function run()
    {
        $existing = Penelitian::where('title', 'like', '%Bawang%')->first();
        if (!$existing) {
            Penelitian::create([
                'title' => 'Riset Adaptasi & Produktivitas Benih Bawang Putih Dataran Tinggi Pollung',
                'description' => "Program riset budidaya presisi dan evaluasi adaptasi varietas bawang putih unggulan (Sangga Sembalun dan Lumbu Hijau) di dataran tinggi Pollung, Kabupaten Humbang Hasundutan (1.400 mdpl). Bekerja sama dengan CV Roberkat dan Kementerian Pertanian RI, riset ini mengintegrasikan monitoring mikroklimat cerdas, pemuliaan benih dasar G0/G1, dan analisis bioaktif alisin untuk mewujudkan kemandirian benih hortikultura nasional.\n\nTujuan:\nMengembangkan protokol budidaya presisi, meningkatkan produktivitas hingga >12 ton/ha, meminimalkan susut pascapanen, serta memproduksi benih bawang putih bersertifikasi unggul yang adaptif terhadap iklim sejuk dan tanah andosol dataran tinggi Sumatera Utara.\n\nPeneliti Utama: Tim Riset Hortikultura TSTH2 & Balithi Kementan RI",
                'image_path' => 'konten/cQzW0750TvfSUm5ivUyzHebbD8EAU9cKFnaKxlEx.jpg',
            ]);
        }
    }
}
