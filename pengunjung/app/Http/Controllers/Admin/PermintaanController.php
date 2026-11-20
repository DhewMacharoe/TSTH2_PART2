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

        // 1. Jadwal pengambilan terlewat → kembali ke Pending
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

        // 2. Pending >= 3 hari tanpa reschedule → dibatalkan
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

        // 3. Query utama & filter role
        $selectedJenis = $request->query('jenis', 'all'); // 'all', 'lapangan', 'lab'

        $query = PermintaanBarang::with(['user', 'barang', 'pic', 'chats', 'approvedBy', 'rejectedBy'])
            ->orderBy('created_at', 'desc');

        // Isolasi data warehouse per PIC
        if ($user->role === 'pic_gudang') {
            $selectedJenis = 'lapangan';
            $query->where('jenis_barang', 'lapangan');
        } elseif ($user->role === 'pic_lab') {
            $selectedJenis = 'lab';
            $query->where('jenis_barang', 'lab');
        } else {
            if ($selectedJenis === 'lapangan') {
                $query->where('jenis_barang', 'lapangan');
            } elseif ($selectedJenis === 'lab') {
                $query->where('jenis_barang', 'lab');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', "%{$s}%")->orWhere('kode_barang', 'like', "%{$s}%"))
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$s}%"))
                  ->orWhere('catatan', 'like', "%{$s}%");
            });
        }

        // Filter berdasarkan hak akses pemohon
        if (!in_array($user->role, ['pic_gudang', 'pic_lab', 'web_admin', 'pimpinan'])) {
            $query->where('user_id', $user->id);
        }

        $permintaans = $query->paginate(15)->withQueryString();
        $satuans     = SatuanBarang::orderBy('nama_satuan')->get();

        // 4. Statistik ringkas
        $statsBase = PermintaanBarang::query();
        if ($user->role === 'pic_gudang') {
            $statsBase->where('jenis_barang', 'lapangan');
        } elseif ($user->role === 'pic_lab') {
            $statsBase->where('jenis_barang', 'lab');
        } elseif (!in_array($user->role, ['web_admin', 'pimpinan'])) {
            $statsBase->where('user_id', $user->id);
        }

        $statCounts = [
            'Menunggu Approval'     => (clone $statsBase)->where('status', PermintaanBarang::STATUS_MENUNGGU_APPROVAL)->count(),
            'Menunggu Pengambilan'  => (clone $statsBase)->where('status', PermintaanBarang::STATUS_MENUNGGU_PENGAMBILAN)->count(),
            'Barang Diambil'        => (clone $statsBase)->where('status', PermintaanBarang::STATUS_BARANG_DIAMBIL)->count(),
            'Selesai'               => (clone $statsBase)->whereIn('status', [PermintaanBarang::STATUS_SELESAI, PermintaanBarang::STATUS_BARANG_DIKEMBALIKAN])->count(),
            'total_lapangan'        => (clone $statsBase)->where('jenis_barang', 'lapangan')->count(),
            'total_lab'             => (clone $statsBase)->where('jenis_barang', 'lab')->count(),
        ];

        $kategoriList      = ['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya'];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('permintaan.index', compact('permintaans', 'satuans', 'kondisiByKategori', 'statCounts', 'selectedJenis'));
    }

    // ─── CREATE FORM ─────────────────────────────────────────────────────────
    public function create(Request $request)
    {
        if (!Auth::user()->isRequester()) {
            return redirect()->route('permintaan.index')->with('error', 'Akun manajerial operasional (PIC / Web Admin) tidak dapat membuat permintaan barang.');
        }

        $selectedJenis = $request->query('jenis', 'all'); // 'all', 'lapangan', 'lab'

        $barangsQuery = Barang::with('batches')->orderBy('nama_barang');
        if ($selectedJenis === 'lapangan') {
            $barangsQuery->where('jenis_barang', 'lapangan');
        } elseif ($selectedJenis === 'lab') {
            $barangsQuery->where('jenis_barang', 'lab');
        }
        $barangs = $barangsQuery->get();

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
            'selectedBarangId', 'targetBatchNumber', 'requestedKode', 'selectedJenis'
        ));
    }

    // ─── STORE ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (!Auth::user()->isRequester()) {
            return redirect()->route('permintaan.index')->with('error', 'Akun manajerial operasional (PIC / Web Admin) tidak dapat membuat permintaan barang.');
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

        $targetApproval = ($barang->jenis_barang === 'lab') ? 'pic_lab' : 'pic_gudang';
        $targetLabel    = ($barang->jenis_barang === 'lab') ? 'PIC Laboratorium (Riset & Inovasi)' : 'PIC Gudang (General Affair)';

        $permintaan = PermintaanBarang::create([
            'user_id'         => Auth::id(),
            'barang_id'       => $request->barang_id,
            'jenis_barang'    => $barang->jenis_barang ?? 'lapangan',
            'jumlah'          => $request->jumlah,
            'satuan_diminta'  => $request->satuan_diminta,
            'catatan'         => $request->catatan,
            'status'          => PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
            'target_approval' => $targetApproval,
        ]);

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_PERMINTAAN,
            'ajukan',
            "Permintaan #{$permintaan->id}: {$request->jumlah} {$request->satuan_diminta} {$barang->nama_barang} diajukan oleh " . Auth::user()->name . ". Menunggu persetujuan {$targetLabel}.",
            AktivitasGudang::MUTASI_NETRAL,
            $barang->id,
            $barang->nama_barang,
            $request->jumlah,
            $request->satuan_diminta
        );

        return redirect()->route('permintaan.index')
            ->with('success', "Permintaan berhasil diajukan. Menunggu persetujuan langsung dari {$targetLabel}.");
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
