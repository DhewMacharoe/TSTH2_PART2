<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\HomeBackground;
use App\Models\AboutBullet;
use App\Models\Program;
use App\Models\Research;
use App\Models\News;
use App\Models\Gallery;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Static Contents
        $contents = [
            ['section' => 'home', 'key' => 'home_hero_badge', 'value' => 'Pusat Riset Terpadu Nasional', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_hero_title', 'value' => 'Inovasi Berkelanjutan untuk Masa Depan', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_hero_desc', 'value' => 'Taman Sains Teknologi Herbal dan Hortikultura (TSTH2) hadir sebagai episentrum pengembangan sains, teknologi herbal, dan hortikultura endemik di tingkat global dari jantung Sumatera Utara.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_about_title', 'value' => 'Mewujudkan Ketahanan Kesehatan & Pangan', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_about_desc', 'value' => 'TSTH2 merupakan fasilitas riset strategis nasional yang didedikasikan untuk konservasi, penelitian mutakhir, dan hilirisasi tanaman herbal serta hortikultura endemik Indonesia. Kami berkomitmen untuk menjembatani kekayaan alam dengan inovasi teknologi.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_about_bg', 'value' => null, 'type' => 'image', 'image_path' => 'contents/visi_misi_iniigambar.jpg'],
            ['section' => 'home', 'key' => 'home_research_title', 'value' => 'Fokus Riset Utama', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_research_desc', 'value' => 'Mengoptimalkan potensi keanekaragaman hayati nusantara melalui pendekatan multidisiplin yang terintegrasi dan berkelanjutan.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director', 'value' => null, 'type' => 'image', 'image_path' => 'contents/foto_direktur_tsth2.jpg'], 
            ['section' => 'home', 'key' => 'home_director_name', 'value' => 'Manajemen TSTH2', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_title', 'value' => 'Dewan Pengarah & Pimpinan', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_desc', 'value' => 'Memberikan kepemimpinan strategis dalam memajukan ekosistem riset herbal dan hortikultura di Indonesia, didukung oleh fasilitas berstandar internasional.', 'type' => 'text'],
            ['section' => 'home', 'key' => 'home_director_quote', 'value' => '"Inovasi sejati berasal dari pemahaman mendalam terhadap alam, diiringi teknologi untuk masa depan yang lebih baik."', 'type' => 'text'],
            ['section' => 'program', 'key' => 'program_hero_title', 'value' => 'Program Unggulan TSTH2', 'type' => 'text'],
            ['section' => 'program', 'key' => 'program_hero_desc', 'value' => 'Inisiatif strategis untuk memajukan sektor pertanian, hortikultura, dan pengembangan bahan baku obat tradisional di Indonesia.', 'type' => 'text'],
            ['section' => 'program', 'key' => 'program_hero_bg', 'value' => null, 'type' => 'image', 'image_path' => 'contents/banner_program.jpg'],
            ['section' => 'research', 'key' => 'research_hero_title', 'value' => 'Fasilitas & Penelitian', 'type' => 'text'],
            ['section' => 'research', 'key' => 'research_hero_desc', 'value' => 'Infrastruktur riset kelas dunia untuk mengungkap potensi farmakologis plasma nutfah Indonesia.', 'type' => 'text'],
            ['section' => 'research', 'key' => 'research_hero_bg', 'value' => null, 'type' => 'image', 'image_path' => 'contents/banner_riset.jpg'],
            ['section' => 'news', 'key' => 'news_hero_title', 'value' => 'Berita & Artikel', 'type' => 'text'],
            ['section' => 'news', 'key' => 'news_hero_desc', 'value' => 'Kabar terbaru seputar inovasi, riset, dan kegiatan strategis di TSTH2.', 'type' => 'text'],
            ['section' => 'gallery', 'key' => 'gallery_hero_title', 'value' => 'Galeri Dokumentasi', 'type' => 'text'],
            ['section' => 'gallery', 'key' => 'gallery_hero_desc', 'value' => 'Dokumentasi lengkap fasilitas riset, laboratorium, dan kunjungan di TSTH2.', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'navbar_title', 'value' => 'TSTH2', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'navbar_subtitle', 'value' => 'Taman Sains Teknologi<br>Herbal dan Hortikultura', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_desc', 'value' => 'Pusat unggulan inovasi botani, herbal, dan hortikultura di Indonesia, mendorong riset berstandar internasional.', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_address', 'value' => 'Pollung, Humbang Hasundutan, Sumatera Utara', 'type' => 'text'],
            ['section' => 'layout', 'key' => 'footer_email', 'value' => 'info@tsth2.id', 'type' => 'text'],
        ];

        foreach ($contents as $data) {
            Content::updateOrCreate(['key' => $data['key']], $data);
        }

        // About Bullets
        AboutBullet::truncate();
        $bullets = ['Fasilitas Laboratorium Genomik (Genolab)', 'Greenhouse Presisi Terkontrol IoT', 'Kawasan Konservasi & Budidaya Ex-situ', 'Pusat Ekstraksi & Distilasi Herbal Mutakhir'];
        foreach($bullets as $b) { AboutBullet::create(['content' => $b]); }

        // Home Backgrounds
        HomeBackground::truncate();
        HomeBackground::create(['image_path' => 'contents/home_hero_1.jpg']);
        HomeBackground::create(['image_path' => 'contents/home_hero_2.jpg']);

        // Programs (15 programs with realistic titles)
        Program::truncate();
        $programsData = [
            ['Kolaborasi Industri & Hilirisasi', 'Bermitra dengan industri farmasi ternama seperti Kalbe Farma untuk memastikan riset herbal dapat dihilirisasi menjadi produk unggulan yang bermanfaat bagi masyarakat luas.'],
            ['Kemitraan Strategis Internasional', 'Membangun kerjasama dengan institusi riset dan universitas global untuk pertukaran pengetahuan, teknologi, dan peningkatan kapasitas SDM peneliti.'],
            ['Pengembangan Konservasi Herbal (Genolab)', 'Program perbanyakan dan perlindungan varietas tanaman endemik menggunakan teknologi genomik di Genolab untuk menjamin ketersediaan bibit unggul secara berkelanjutan.'],
            ['Inkubasi UMKM Berbasis Herbal', 'Pendampingan bagi pelaku UMKM lokal dalam standarisasi mutu dan sertifikasi BPOM agar produk herbal siap bersaing di pasar nasional.'],
            ['Pelatihan Smart Farming Petani Lokal', 'Mengedukasi petani di kawasan Humbang Hasundutan mengenai pemanfaatan teknologi IoT dalam budidaya pertanian yang presisi dan efisien.'],
            ['Pemetaan Genomik Plasma Nutfah', 'Riset berkelanjutan untuk memetakan DNA tanaman obat langka Sumatera Utara sebagai langkah perlindungan hak kekayaan intelektual (HAKI).'],
            ['Pusat Uji Praklinis Fitofarmaka', 'Menyediakan fasilitas uji in-vivo dan in-vitro berstandar internasional untuk mempercepat pengembangan obat modern asli Indonesia (OMAI).'],
            ['Pengembangan Pupuk Organik Mandiri', 'Mengolah limbah biomassa hasil panen menjadi pupuk organik cair dan padat yang ramah lingkungan untuk mendukung pertanian sirkular.'],
            ['Sekolah Lapang Agroklimatologi', 'Program kolaboratif BMKG dan TSTH2 untuk meningkatkan kesadaran petani terhadap pola tanam yang adaptif terhadap perubahan iklim.'],
            ['Bank Benih (Seed Bank) Nasional', 'Fasilitas penyimpanan jangka panjang untuk koleksi benih tanaman hortikultura strategis sebagai cadangan genetik di masa depan.'],
            ['Riset Ketahanan Pangan Dataran Tinggi', 'Pengembangan varietas bawang merah, kentang, dan kubis yang memiliki produktivitas tinggi dan tahan terhadap hama endemik pegunungan.'],
            ['Sertifikasi Bibit Unggul Bermutu', 'Membantu proses sertifikasi dan pelabelan bibit hasil kultur jaringan sebelum didistribusikan secara masif kepada kelompok tani.'],
            ['Pengembangan Wisata Sains Botani', 'Menjadikan kawasan konservasi TSTH2 sebagai destinasi eduwisata yang menarik bagi pelajar, mahasiswa, dan masyarakat umum.'],
            ['Optimasi Nutrisi Tanaman Hidroponik', 'Riset formulasi nutrisi (AB Mix) khusus yang disesuaikan dengan kebutuhan hara spesifik tanaman sayuran daun premium.'],
            ['Hilirisasi Minyak Atsiri Kemenyan', 'Program ekstraksi dan purifikasi getah kemenyan (Styrax sumatrana) menjadi essential oil bernilai jual tinggi untuk industri kosmetik global.']
        ];
        
        foreach ($programsData as $index => $prog) {
            Program::create([
                'title' => $prog[0],
                'description' => $prog[1]
            ]);
        }

        // Researches (15 items with realistic titles)
        Research::truncate();
        $researchData = [
            'Pengembangan Varietas Unggul Kentang Aeroponik',
            'Ekstraksi Minyak Atsiri Kemenyan (Styrax sumatrana)',
            'Kultur Jaringan Anggrek Endemik Sumatera Utara',
            'Analisis Genomik Tanaman Obat Tradisional Batak (Tawar)',
            'Rekayasa Lingkungan Smart Greenhouse Berbasis IoT',
            'Formulasi Fitofarmaka Berbasis Ekstrak Andaliman',
            'Konservasi Ex-Situ Tumbuhan Langka Dataran Tinggi',
            'Uji Praklinis Bioaktivitas Senyawa Antioksidan Herbal',
            'Optimasi Nutrisi Hidroponik Tanaman Sayur Premium',
            'Isolasi DNA Metagenomik Tanah Hutan Tropis',
            'Pemanfaatan Biomassa Sisa Panen sebagai Pupuk Organik',
            'Pemuliaan Bawang Merah Tahan Penyakit Kapang',
            'Standardisasi Mutu Bahan Baku Obat Alam',
            'Sistem Deteksi Dini Hama dengan Computer Vision',
            'Profiling Metabolit Sekunder Tanaman Aromatik'
        ];

        foreach ($researchData as $index => $title) {
            Research::create([
                'title' => $title,
                'description' => 'Fokus riset TSTH2 pada bidang ' . strtolower($title) . ' untuk mengoptimalkan potensi keanekaragaman hayati dan meningkatkan kualitas pertanian serta kesehatan nasional.',
                'image_path' => 'contents/gambar_riset_' . ($index + 1) . '.jpg'
            ]);
        }

        // News (15 items with realistic titles)
        News::truncate();
        $newsData = [
            'Kunjungan Menko Marves Tinjau Progres Pembangunan TSTH2',
            'Kolaborasi TSTH2 dan Kalbe Farma untuk Hilirisasi Herbal',
            'Kunjungan Studi Banding Delegasi Universitas dari Tiongkok',
            'Peresmian Gedung Laboratorium Genomik (Genolab) TSTH2',
            'TSTH2 Gelar FGD Peta Jalan Riset Herbal Nasional',
            'Pelatihan Smart Farming bagi Petani Milenial Humbang Hasundutan',
            'Kunjungan Kerja Ketua Dewan Energi Nasional ke Kawasan Pollung',
            'Panen Perdana Bibit Bawang Putih Varietas Unggul di TSTH2',
            'Penandatanganan MoU dengan Universitas Sumatera Utara (USU)',
            'Semarak Perayaan HUT RI ke-81 di Lapangan Utama TSTH2',
            'TSTH2 Terima Sertifikasi ISO 17025 untuk Laboratorium Uji',
            'Seminar Internasional Keanekaragaman Hayati dan Bioteknologi',
            'Peluncuran Sistem Irigasi Presisi Berbasis AI di Greenhouse',
            'Kunjungan Tim Ahli Botani LIPI-BRIN ke TSTH2',
            'Pameran Hasil Riset TSTH2 di Inovasi Teknologi Nasional'
        ];

        foreach ($newsData as $index => $title) {
            News::create([
                'category' => 'Kabar Utama',
                'title' => $title,
                'description' => 'Berita terkini mengenai ' . strtolower($title) . ' yang berlangsung dengan lancar, membawa dampak positif bagi perkembangan riset dan kolaborasi strategis di lingkungan TSTH2.',
                'date' => sprintf('%02d', ($index % 28) + 1) . ' Agustus 2026',
                'image_path' => 'contents/gambar_berita_' . ($index + 1) . '.jpg'
            ]);
        }

        // Gallery (15 items with realistic titles)
        Gallery::truncate();
        $galleryData = [
            'Eksterior Gedung Riset Utama TSTH2 Pollung',
            'Fasilitas Smart Greenhouse dari Dalam',
            'Peneliti Sedang Mengoperasikan Alat PCR di Genolab',
            'Kunjungan Delegasi Tiongkok Mei 2026',
            'Diskusi Kolaborasi Bersama Kalbe Farma',
            'Proses Kultur Jaringan di Laboratorium',
            'Hamparan Lahan Konservasi TSTH2',
            'Fasilitas Ekstraksi dan Distilasi Skala Pilot',
            'Area Persemaian Bibit Hortikultura',
            'Ruang Kontrol IoT dan Pengawasan Iklim',
            'Kunjungan Pejabat Negara ke TSTH2',
            'Tanaman Herbal Koleksi Ex-Situ',
            'Suasana Rapat FGD Hilirisasi Riset',
            'Peneliti Mengamati Sampel di Bawah Mikroskop',
            'Kemeriahan HUT RI di Kawasan TSTH2'
        ];

        foreach ($galleryData as $index => $title) {
            Gallery::create([
                'title' => $title,
                'description' => 'Dokumentasi ' . strtolower($title) . ' di area TSTH2.',
                'image_path' => 'contents/gambar_galeri_' . ($index + 1) . '.jpg'
            ]);
        }
    }
}
