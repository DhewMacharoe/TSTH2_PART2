<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\Barang;
use App\Models\SatuanBarang;
use App\Services\AktivitasLogger;
use App\Models\AktivitasGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    // ─── INDEX ───────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jadwal pengambilan terlewat → kembali ke Pending
        PermintaanBarang::where('status', PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN)
            ->whereNotNull('jadwal_pengambilan')
            ->where('jadwal_pengambilan', '<', now())
            ->each(function ($p) {
                $p->update([
                    'status'      => PermintaanBarang::STATUS_PENDING,
                    'pending_at'  => $p->pending_at ?? now(),
                    'catatan_pic' => ($p->catatan_pic ? $p->catatan_pic . "\n" : '') .
                        '[Sistem] Pengambilan melewati jadwal pada ' . $p->jadwal_pengambilan->format('d M Y H:i') . '. Status otomatis diubah menjadi Pending.',
                ]);
            });

        // Pending >= 3 hari tanpa reschedule → dibatalkan
        PermintaanBarang::where('status', PermintaanBarang::STATUS_PENDING)
            ->where(function ($q) {
                $q->where('pending_at', '<=', now()->subDays(3))
                  ->orWhere(function ($sub) {
                      $sub->whereNull('pending_at')
                          ->where('updated_at', '<=', now()->subDays(3));
                  });
            })
            ->each(function ($p) {
                $p->update([
                    'status'        => PermintaanBarang::STATUS_DIBATALKAN,
                    'closed_at'     => now(),
                    'forum_ends_at' => now()->addDays(3),
                    'catatan_pic'   => ($p->catatan_pic ? $p->catatan_pic . "\n" : '') .
                        '[Sistem] Permintaan otomatis dibatalkan karena pending lebih dari 3 hari tanpa reschedule.',
                ]);
            });

        // Menunggu kepala_divisi >= 3 hari → eskalasi ke kepala_departemen
        PermintaanBarang::where('status', PermintaanBarang::STATUS_MENUNGGU_APPROVAL)
            ->where('target_approval', PermintaanBarang::TARGET_KEPALA_DIVISI)
            ->where('created_at', '<=', now()->subDays(3))
            ->each(function ($p) {
                $alasan = 'Kepala Divisi Operasional tidak merespons dalam 3 hari. Permintaan diteruskan ke Kepala Departemen General Affair.';
                $p->update([
                    'target_approval'   => PermintaanBarang::TARGET_KEPALA_DEPARTEMEN,
                    'escalated_at'      => now(),
                    'escalation_reason' => $alasan,
                    'catatan_pic'       => ($p->catatan_pic ? $p->catatan_pic . "\n" : '') . '[Sistem] ' . $alasan,
                ]);
                AktivitasLogger::catat(
                    AktivitasGudang::MODUL_PERMINTAAN,
                    'eskalasi',
                    "Permintaan #{$p->id} oleh {$p->user->name} dieskalasi ke Kepala Departemen karena tidak ditanggapi 3 hari."
                );
            });

        $query = PermintaanBarang::with(['user', 'barang', 'pic', 'chats'])
            ->orderBy('created_at', 'desc');

        if (!in_array($user->role, ['pic_gudang', 'web_admin', 'kepala_divisi', 'kepala_departemen'])) {
            $query->where('user_id', $user->id);
        }

        $permintaans = $query->paginate(15)->withQueryString();
        $satuans     = SatuanBarang::orderBy('nama_satuan')->get();

        $statsQuery = PermintaanBarang::query();
        if (!in_array($user->role, ['pic_gudang', 'web_admin', 'kepala_divisi', 'kepala_departemen'])) {
            $statsQuery->where('user_id', $user->id);
        }
        $statCounts = [
            'Menunggu Approval'    => (clone $statsQuery)->where('status', PermintaanBarang::STATUS_MENUNGGU_APPROVAL)->count(),
            'Menunggu Pengambilan' => (clone $statsQuery)->where('status', PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN)->count(),
            'Barang Diambil'       => (clone $statsQuery)->where('status', PermintaanBarang::STATUS_BARANG_DIAMBIL)->count(),
            'Selesai'              => (clone $statsQuery)->whereIn('status', [PermintaanBarang::STATUS_SELESAI, PermintaanBarang::STATUS_BARANG_DIKEMBALIKAN])->count(),
        ];

        $kategoriList      = ['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya'];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('permintaan.index', compact('permintaans', 'satuans', 'kondisiByKategori', 'statCounts'));
    }

    // ─── CREATE FORM ─────────────────────────────────────────────────────────
    public function create(Request $request)
    {
        if (Auth::user()->isWebAdmin() || Auth::user()->isPicGudang()) {
            return redirect()->route('permintaan.index')->with('error', 'Role Anda tidak dapat membuat permintaan barang.');
        }

        $barangs    = Barang::with('batches')->orderBy('nama_barang')->get();
        $satuans    = SatuanBarang::orderBy('nama_satuan')->get();
        $allKondisi = SatuanBarang::kondisiByKategori(null);

        $selectedBarangId  = null;
        $targetBatchNumber = null;
        $requestedKode     = null;

        if ($request->filled('kode')) {
            $requestedKode = trim($request->kode);
            $parts    = explode('-', $requestedKode);
            $baseKode = $parts[0] ?? $requestedKode;
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $targetBatchNumber = (int) $parts[1];
            }
            $matched = $barangs->firstWhere('kode_barang', $baseKode)
                    ?? $barangs->firstWhere('kode_barang', $requestedKode);
            if ($matched) {
                $selectedBarangId = $matched->id;
            }
        } elseif ($request->filled('barang_id')) {
            $selectedBarangId = (int) $request->barang_id;
        }

        $kategoriList      = ['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya'];
        $satuanByKategori  = [];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat]  = SatuanBarang::forKategori($kat)->orderBy('nama_satuan')->pluck('nama_satuan')->toArray();
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('permintaan.create', compact(
            'barangs', 'satuans', 'allKondisi',
            'satuanByKategori', 'kondisiByKategori',
            'selectedBarangId', 'targetBatchNumber', 'requestedKode'
        ));
    }

    // ─── STORE ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (Auth::user()->isWebAdmin() || Auth::user()->isPicGudang()) {
            return redirect()->route('permintaan.index')->with('error', 'Role Anda tidak dapat membuat permintaan barang.');
        }

        $request->validate([
            'barang_id'      => 'required|exists:barangs,id',
            'jumlah'         => 'required|integer|min:1',
            'satuan_diminta' => 'required|string|max:50',
            'catatan'        => 'nullable|string|max:1000',
        ]);

        $barang = Barang::with('batches')->findOrFail($request->barang_id);
        $totalStok = 0;
        foreach ($barang->batches as $batch) {
            $totalStok += $batch->getAvailableStokBySatuan($request->satuan_diminta);
        }
        if ($request->jumlah > $totalStok) {
            return back()->withInput()->withErrors([
                'jumlah' => "Jumlah permintaan melebihi stok layak pakai ($totalStok " . strtoupper($request->satuan_diminta) . ").",
            ]);
        }

        $permintaan = PermintaanBarang::create([
            'user_id'         => Auth::id(),
            'barang_id'       => $request->barang_id,
            'jumlah'          => $request->jumlah,
            'satuan_diminta'  => $request->satuan_diminta,
            'catatan'         => $request->catatan,
            'status'          => PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
            'target_approval' => PermintaanBarang::TARGET_KEPALA_DIVISI,
        ]);

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_PERMINTAAN,
            'ajukan',
            "Permintaan #{$permintaan->id}: {$request->jumlah} {$request->satuan_diminta} {$barang->nama_barang} diajukan oleh " . Auth::user()->name . '.',
            AktivitasGudang::MUTASI_NETRAL,
            $barang->id,
            $barang->nama_barang,
            $request->jumlah,
            $request->satuan_diminta
        );

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan berhasil diajukan. Menunggu persetujuan Kepala Divisi Operasional.');
    }

    // ─── Forwarding ke controller khusus ─────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        return app(PermintaanStatusController::class)->updateStatus($request, $id);
    }

    public function getChat($id)
    {
        return app(PermintaanChatController::class)->getChat($id);
    }

    public function storeChat(Request $request, $id)
    {
        return app(PermintaanChatController::class)->storeChat($request, $id);
    }
}
