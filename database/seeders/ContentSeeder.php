<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Konten as Content;
use App\Models\LatarBeranda as HomeBackground;
use App\Models\PoinTentang as AboutBullet;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Static Contents
        $contents = [
            // â”€â”€ Hero Section â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'home', 'key' => 'home_hero_badge',  'value' => 'Taman Sains Teknologi Herbal dan Hortikultura', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_hero_title',  'value' => 'Inovasi Herbal & Hortikultura Indonesia',        'type' => 'text'],
            ['section' => 'home', 'key' => 'home_hero_desc',   'value' => 'TSTH2 (Indonesian Herbal and Horticultural Science and Technology Park) adalah pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk inovasi herbal dan hortikultura di Sumatera Utara, didirikan di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi bekerja sama dengan Institut Teknologi Del.', 'type' => 'text'],

            // â”€â”€ About Section â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'home', 'key' => 'home_about_title', 'value' => 'Apa Itu TSTH2?', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_about_desc',  'value' => 'TSTH2 (Indonesian Herbal and Horticultural Science and Technology Park) merupakan pusat riset nasional yang diprakarsai oleh Pemerintah Indonesia untuk mendorong inovasi herbal dan hortikultura di Sumatera Utara. Didirikan di bawah Kementerian Pendidikan Tinggi, Sains, dan Teknologi, dengan tata kelola berkolaborasi bersama Institut Teknologi Del.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_about_bg',    'value' => null, 'type' => 'image', 'image_path' => 'contents/visi_misi_iniigambar.jpg'],

            // â”€â”€ Penelitian Section â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'home', 'key' => 'home_penelitian_title', 'value' => 'Fasilitas & Penelitian Utama', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_penelitian_desc',  'value' => 'Mengoptimalkan potensi keanekaragaman hayati nusantara melalui pendekatan riset terpadu dan fasilitas yang terus dikembangkan.', 'type' => 'text'],

            // â”€â”€ Director Section â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'home', 'key' => 'home_director',       'value' => null, 'type' => 'image', 'image_path' => 'contents/foto_direktur_tsth2.jpg'],
            ['section' => 'home', 'key' => 'home_director_name',  'value' => 'Sri Fatmawati, Ph.D',                                                                   'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_title', 'value' => 'Direktur Indonesian Herbal and Horticultural Science and Technology Park',               'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_desc',  'value' => 'Sri Fatmawati, Ph.D menjabat sebagai Direktur TSTH2, memberikan kepemimpinan strategis dalam memajukan ekosistem riset herbal dan hortikultura Indonesia. Dengan keahlian luas di bidang ilmu produk alam dan inovasi, beliau memimpin pengembangan program riset terpadu, varietas tanaman unggul, dan produk herbal berkualitas tinggi.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_quote', 'value' => '"Inovasi sejati berasal dari pemahaman mendalam terhadap alam, diiringi teknologi untuk masa depan yang lebih baik."', 'type' => 'text'],

            // â”€â”€ Profil / About Page â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'about', 'key' => 'about_vision_title', 'value' => 'Visi', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_vision_desc',  'value' => 'Menjadi pusat riset herbal dan hortikultura terkemuka di Indonesia yang menghasilkan inovasi dan teknologi berdaya saing global, berkelanjutan, dan berdampak sosial.', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_mission_title','value' => 'Misi', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_mission_desc', 'value' => 'Memajukan inovasi herbal dan hortikultura dengan mengembangkan sumber daya genetik unggul, memproduksi produk herbal dan hortikultura berkualitas tinggi, memperkuat kolaborasi multi-pemangku kepentingan, memberikan manfaat berkelanjutan bagi masyarakat dan lingkungan, serta mendorong inovasi yang berdampak positif bagi masyarakat dan lingkungan.', 'type' => 'text'],

            // â”€â”€ Penelitian / Research Page â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'research', 'key' => 'penelitian_hero_title', 'value' => 'Fasilitas & Penelitian',        'type' => 'text'],
            ['section' => 'research', 'key' => 'penelitian_hero_desc',  'value' => 'Infrastruktur dan fasilitas riset terpadu untuk mengembangkan potensi tanaman herbal dan hortikultura Indonesia.', 'type' => 'text'],
            ['section' => 'research', 'key' => 'research_hero_bg',      'value' => null, 'type' => 'image', 'image_path' => 'contents/banner_riset.jpg'],

            // â”€â”€ News Page â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'news', 'key' => 'berita_hero_title', 'value' => 'Berita & Artikel',                                          'type' => 'text'],
            ['section' => 'news', 'key' => 'berita_hero_desc',  'value' => 'Kabar terbaru seputar inovasi, riset, dan kegiatan strategis di TSTH2.', 'type' => 'text'],

            // â”€â”€ Gallery Page â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'gallery', 'key' => 'galeri_hero_title', 'value' => 'Galeri Dokumentasi',                                                        'type' => 'text'],
            ['section' => 'gallery', 'key' => 'galeri_hero_desc',  'value' => 'Dokumentasi lengkap fasilitas riset, laboratorium, dan kunjungan di TSTH2.', 'type' => 'text'],

            // â”€â”€ Layout / Navbar & Footer â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            ['section' => 'layout', 'key' => 'navbar_title',    'value' => 'TSTH2',                                                                                               'type' => 'text'],
            ['section' => 'layout', 'key' => 'navbar_subtitle', 'value' => 'Taman Sains Teknologi<br>Herbal dan Hortikultura',                                                     'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_desc',     'value' => 'Pusat riset nasional inovasi herbal & hortikultura Indonesia di bawah Kemendiktisaintek & Institut Teknologi Del.', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_address',  'value' => 'Pollung, Humbang Hasundutan, Sumatera Utara',                                                         'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_email',    'value' => 'info@tsth2.id',                                                                                       'type' => 'text'],
        ];

        foreach ($contents as $data) {
            Content::updateOrCreate(['key' => $data['key']], $data);
        }

        // About Bullets â€” poin-poin Misi TSTH2
        AboutBullet::truncate();
        $bullets = [
            'Mengembangkan sumber daya genetik herbal dan hortikultura yang unggul',
            'Memproduksi produk herbal dan hortikultura berkualitas tinggi',
            'Memperkuat kolaborasi multi-pemangku kepentingan nasional & global',
            'Memberikan manfaat berkelanjutan bagi masyarakat dan lingkungan',
            'Mendorong inovasi yang berdampak positif bagi masyarakat dan lingkungan',
        ];
        foreach($bullets as $b) { AboutBullet::create(['content' => $b]); }

        // Home Backgrounds
        HomeBackground::truncate();
        HomeBackground::create(['image_path' => 'contents/home_hero_1.jpg']);
        HomeBackground::create(['image_path' => 'contents/home_hero_2.jpg']);
    }
}
