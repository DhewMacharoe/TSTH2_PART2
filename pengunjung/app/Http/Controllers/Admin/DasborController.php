<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\PermintaanBarang;
use App\Models\SatuanBarang;
use App\Models\AktivitasGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DasborController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'web_admin') {
            return redirect()->route('web.management.home');
        }

        // Warehouse adalah low priority untuk selain PIC.
        // Role non-PIC diarahkan langsung ke Dashboard Logbook utama masing-masing.
        if (!$user->isPicLab() && !$user->isPicGudang()) {
            $urlLogbook = rtrim(config('app.url_logbook', env('URL_LOGBOOK', 'http://localhost:8001')), '/');
            if ($user->role === 'pimpinan') {
                return redirect()->away($urlLogbook . '/pimpinan/dashboard');
            } elseif ($user->role === 'kepala_departemen') {
                return redirect()->away($urlLogbook . '/kepala-departemen/dashboard');
            } elseif ($user->role === 'kepala_divisi') {
                return redirect()->away($urlLogbook . '/kepala-divisi/dashboard');
            } else {
                return redirect()->away($urlLogbook . '/dashboard');
            }
        }

        // Statistik operasional gudang per role PIC (Lab / Gudang)
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

        // Statistik Logbook & Aktivitas Karyawan (dari database bersama tsth2_redesign)
        $logbookStats = [
            'total_logbook'          => 0,
            'logbook_bulan_ini'      => 0,
            'pending_review'         => 0,
            'total_task'             => 0,
            'task_selesai'           => 0,
            'task_berjalan'          => 0,
            'avg_planning'           => 0,
            'avg_aktual'             => 0,
            'lebih_cepat'            => 0,
            'sesuai_rencana'         => 0,
            'tertinggal'             => 0,
            'total_karyawan'         => 0,
        ];
        $recentLogbooks = collect();

        try {
            $isLeader = in_array($user->role, ['pimpinan', 'kepala_departemen', 'kepala_divisi', 'web_admin']);

            if (Schema::hasTable('logbooks')) {
                if ($isLeader) {
                    $logbookStats['total_logbook']     = DB::table('logbooks')->count();
                    $logbookStats['logbook_bulan_ini'] = DB::table('logbooks')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
                    $logbookStats['pending_review']    = DB::table('logbooks')
                        ->whereIn('status', ['pending', 'submitted', 'Menunggu Review'])
                        ->count();

                    $recentLogbooks = DB::table('logbooks')
                        ->leftJoin('users', 'logbooks.user_id', '=', 'users.id')
                        ->select('logbooks.*', 'users.name as user_name')
                        ->latest('logbooks.created_at')
                        ->take(5)
                        ->get();
                } else {
                    $logbookStats['total_logbook']     = DB::table('logbooks')->where('user_id', $user->id)->count();
                    $logbookStats['logbook_bulan_ini'] = DB::table('logbooks')
                        ->where('user_id', $user->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
                    $logbookStats['pending_review']    = DB::table('logbooks')
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'submitted', 'Menunggu Review'])
                        ->count();

                    $recentLogbooks = DB::table('logbooks')
                        ->where('user_id', $user->id)
                        ->latest('created_at')
                        ->take(5)
                        ->get();
                }
            }

            if (Schema::hasTable('tasks')) {
                $taskQuery = DB::table('tasks');
                if (!$isLeader) {
                    $taskQuery->where('assigned_to', $user->id);
                }

                $tasks = $taskQuery->get();
                $logbookStats['total_task']    = $tasks->count();
                $logbookStats['task_selesai']  = $tasks->where('status', 'completed')->count();
                $logbookStats['task_berjalan'] = $tasks->whereIn('status', ['in_progress', 'pending'])->count();

                if ($tasks->count() > 0) {
                    $logbookStats['avg_planning'] = round($tasks->avg('target_progress') ?? 0);
                    $logbookStats['avg_aktual']   = round($tasks->avg('reported_progress') ?? 0);

                    foreach ($tasks as $t) {
                        $target = (float) ($t->target_progress ?? 0);
                        $actual = (float) ($t->reported_progress ?? 0);
                        if ($actual > $target) {
                            $logbookStats['lebih_cepat']++;
                        } elseif ($target > 0 && $actual == $target) {
                            $logbookStats['sesuai_rencana']++;
                        } elseif ($actual < $target) {
                            $logbookStats['tertinggal']++;
                        }
                    }
                }
            }

            if (Schema::hasTable('users')) {
                $logbookStats['total_karyawan'] = DB::table('users')
                    ->whereIn('role', ['employee', 'karyawan', 'agronom', 'kepala_divisi', 'kepala_departemen'])
                    ->count();
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel logbook belum siap atau error query
        }

        return view('dasbor', compact('user', 'stats', 'recentPermintaan', 'recentActivities', 'logbookStats', 'recentLogbooks'));
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
