<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\PermintaanBarang;
use App\Models\BarangBatch;
use Illuminate\Support\Facades\DB;

class DasborController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Barang di Gudang (Total Barang & Total Stok)
        $totalBarang = Barang::count();
        $totalStok = BarangBatch::sum('stok');
        $kategoriStats = Barang::select('kategori', DB::raw('count(*) as total'))->groupBy('kategori')->pluck('total', 'kategori');

        // 2. Permintaan Barang (Beragam Status)
        $permintaanQuery = PermintaanBarang::query();
        if (!in_array($user->role, ['pic_gudang', 'web_admin'])) {
            $permintaanQuery->where('user_id', $user->id);
        }
        $permintaans = $permintaanQuery->get();
        
        $permintaanStats = [
            'Menunggu Approval' => $permintaans->where('status', 'Menunggu Approval')->count(),
            'Menunggu Pengambilan' => $permintaans->where('status', 'Menunggu Pengambilan')->count(),
            'Barang Diambil' => $permintaans->where('status', 'Barang Diambil')->count(),
            'Selesai' => $permintaans->whereIn('status', ['Selesai', 'Barang Dikembalikan'])->count(),
        ];

        // 3. Barang Rusak / Expired (Only for PIC Gudang and Web Admin)
        $totalRusak = 0;
        $totalExpired = 0;
        if (in_array($user->role, ['pic_gudang', 'web_admin'])) {
            $batches = BarangBatch::all();
            foreach($batches as $batch) {
                $isExpired = false;
                if ($batch->tanggal_kadaluarsa && \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->endOfDay()->isPast()) {
                    $isExpired = true;
                }
                
                $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);
                
                if (empty($details)) {
                    if (in_array($batch->kondisi, ['Rusak', 'Rusak/Menggumpal'])) {
                        $totalRusak += $batch->stok;
                    } elseif ($isExpired) {
                        $totalExpired += $batch->stok;
                    }
                } else {
                    foreach ($details as $item) {
                        $subStok = $item['stok'] ?? 0;
                        if (in_array($item['kondisi'] ?? '', ['Rusak', 'Rusak/Menggumpal'])) {
                            $totalRusak += $subStok;
                        } elseif ($isExpired) {
                            $totalExpired += $subStok;
                        }
                    }
                }
            }
        }

        // 4. Slide Presentasi (Fitur & Kesimpulan)
        $fiturPengembangan = [
            [
                'judul' => 'Sistem Inventaris & Tracking Stok',
                'deskripsi' => 'Pengelolaan data barang gudang, kategori, batch batching, pemantauan stok detail serta deteksi otomatis barang rusak dan expired.',
                'opsi' => true,
            ],
            [
                'judul' => 'Manajemen Permintaan & Chat Realtime',
                'deskripsi' => 'Alur pengajuan barang oleh pegawai/pimpinan, proses persetujuan (approval), tracking status, dan komunikasi interaktif antar pihak.',
                'opsi' => true,
            ],
            [
                'judul' => 'CMS Web Management & Publikasi',
                'deskripsi' => 'Pengaturan konten landing page, program unggulan, penelitian, berita & artikel, galeri, serta tata letak website TSTH2 secara fleksibel.',
                'opsi' => false,
            ]
        ];

        // Slide 2: 4 Card Detail Fitur
        $slideDuaFitur = [
            [
                'judul' => 'Master Satuan & Konversi',
                'deskripsi' => 'Pengaturan konversi unit otomatis (seperti Kg ke Gram, Liter ke mL) untuk transparansi pencatatan mutasi barang.',
                'opsi' => true,
            ],
            [
                'judul' => 'Pelacakan Batch & Kondisi',
                'deskripsi' => 'Pengelompokan barang berdasarkan nomor batch, tanggal kadaluarsa, serta pemisahan stok fisik bagus dan rusak.',
                'opsi' => true,
            ],
            [
                'judul' => 'Sistem Request & Approval',
                'deskripsi' => 'Pengajuan barang oleh pegawai dengan alur verifikasi PIC Gudang dan validasi ketersediaan stok secara akurat.',
                'opsi' => true,
            ],
            [
                'judul' => 'Fitur Chat & Logbook Activity',
                'deskripsi' => 'Diskusi langsung pada tiap transaksi barang serta pencatatan logbook aktivitas harian pegawai secara terintegrasi.',
                'opsi' => true,
            ],
        ];

        // Slide 3: Kesimpulan 1 Paragraf
        $kesimpulanParagraf = "Pengembangan Sistem Informasi Terpadu TSTH2 ini berhasil mengintegrasikan seluruh alur pengelolaan inventaris gudang, pemrosesan permintaan barang secara akurat, serta kemudahan publikasi konten web dan aksesibilitas berbasis PWA, sehingga secara signifikan meningkatkan efisiensi operasional, transparansi data, dan produktivitas kerja di lingkungan TSTH2.";

        return view('dasbor', compact('totalBarang', 'totalStok', 'kategoriStats', 'permintaanStats', 'totalRusak', 'totalExpired', 'fiturPengembangan', 'slideDuaFitur', 'kesimpulanParagraf'));
    }
}
