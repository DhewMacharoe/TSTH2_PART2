<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\PermintaanBarang;
use App\Models\SatuanBarang;
use App\Models\AktivitasGudang;
use Illuminate\Http\Request;

class DasborController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'web_admin') {
            return redirect()->route('web.management.home');
        }

        // Statistik operasional per role
        $stats = [
            'total_barang'    => 0,
            'total_stok'      => 0,
            'pending_request' => 0,
            'total_satuan'    => 0,
            'urgent_items'    => 0,
        ];

        if ($user->isPicLab()) {
            $stats['total_barang']    = Barang::where('jenis_barang', 'lab')->count();
            $stats['total_stok']      = BarangBatch::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lab'))->sum('stok');
            $stats['pending_request'] = PermintaanBarang::where('jenis_barang', 'lab')
                ->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])
                ->count();
            $stats['total_satuan']    = SatuanBarang::where('jenis_barang', 'lab')->count();
            $stats['urgent_items']    = BarangBatch::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lab'))
                ->where(function($q) {
                    $q->where('stok', '<=', 5)
                      ->orWhere(fn($s) => $s->whereNotNull('tanggal_kadaluarsa')->where('tanggal_kadaluarsa', '<=', now()->addDays(30)));
                })->count();
            $recentPermintaan = PermintaanBarang::with(['user', 'barang'])
                ->where('jenis_barang', 'lab')
                ->latest()
                ->take(5)
                ->get();
            $recentActivities = AktivitasGudang::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lab'))
                ->orWhere('keterangan', 'like', '%lab%')
                ->latest()
                ->take(5)
                ->get();
        } elseif ($user->isPicGudang()) {
            $stats['total_barang']    = Barang::where('jenis_barang', 'lapangan')->count();
            $stats['total_stok']      = BarangBatch::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lapangan'))->sum('stok');
            $stats['pending_request'] = PermintaanBarang::where('jenis_barang', 'lapangan')
                ->whereIn('status', ['Menunggu Approval', 'Menunggu Pengambilan', 'Menunggu Verifikasi Pengembalian', 'Pending (Jadwal Ulang)'])
                ->count();
            $stats['total_satuan']    = SatuanBarang::where('jenis_barang', 'lapangan')->count();
            $stats['urgent_items']    = BarangBatch::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lapangan'))
                ->where('stok', '<=', 10)->count();
            $recentPermintaan = PermintaanBarang::with(['user', 'barang'])
                ->where('jenis_barang', 'lapangan')
                ->latest()
                ->take(5)
                ->get();
            $recentActivities = AktivitasGudang::whereHas('barang', fn($q) => $q->where('jenis_barang', 'lapangan'))
                ->orWhere('keterangan', 'like', '%gudang%')
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats['total_barang']    = Barang::count();
            $stats['total_stok']      = BarangBatch::sum('stok');
            $stats['pending_request'] = PermintaanBarang::where('status', 'Menunggu Approval')->count();
            $stats['total_satuan']    = SatuanBarang::count();
            $stats['urgent_items']    = 0;
            $recentPermintaan = PermintaanBarang::with(['user', 'barang'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            $recentActivities = AktivitasGudang::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dasbor', compact('user', 'stats', 'recentPermintaan', 'recentActivities'));
    }

    public function logbook(Request $request)
    {
        if ($request->query('fallback')) {
            return view('catatan.index');
        }

        $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
        return redirect()->away($urlLogbook . '/dashboard');
    }
}
