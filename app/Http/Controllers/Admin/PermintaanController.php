<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermintaanBarang;
use App\Models\Barang;
use App\Models\SatuanBarang;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    // ─── INDEX ───────────────────────────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        $query = PermintaanBarang::with(['user', 'barang', 'pic', 'chats'])
            ->orderBy('created_at', 'desc');

        if (!in_array($user->role, ['pic_gudang', 'web_admin'])) {
            $query->where('user_id', $user->id);
        }

        $permintaans = $query->get();
        $satuans  = SatuanBarang::orderBy('nama_satuan')->get();

        return view('permintaan.index', compact('permintaans', 'satuans'));
    }

    // ─── CREATE FORM ─────────────────────────────────────────────────────────
    public function create()
    {
        if (Auth::user()->isWebAdmin() || Auth::user()->isPicGudang()) {
            return redirect()->route('permintaan.index')->with('error', 'Role Anda tidak dapat membuat permintaan barang.');
        }

        $barangs    = Barang::with('batches')->orderBy('nama_barang')->get();
        $satuans    = SatuanBarang::orderBy('nama_satuan')->get();
        $allKondisi = SatuanBarang::kondisiByKategori(null);

        // Satuan & kondisi per kategori untuk JS
        $kategoriList = ['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya'];
        $satuanByKategori = [];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat] = SatuanBarang::forKategori($kat)
                ->orderBy('nama_satuan')
                ->pluck('nama_satuan')
                ->toArray();
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('permintaan.create', compact(
            'barangs',
            'satuans',
            'allKondisi',
            'satuanByKategori',
            'kondisiByKategori'
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
        $totalStokForSatuan = 0;
        foreach ($barang->batches as $batch) {
            $totalStokForSatuan += $batch->getAvailableStokBySatuan($request->satuan_diminta);
        }

        if ($request->jumlah > $totalStokForSatuan) {
            return back()->withInput()->withErrors(['jumlah' => "Jumlah permintaan melebihi stok yang tersedia dan layak pakai ($totalStokForSatuan " . strtoupper($request->satuan_diminta) . "). Stok rusak atau kadaluarsa tidak dapat dipermintaan."]);
        }

        PermintaanBarang::create([
            'user_id'        => Auth::id(),
            'barang_id'      => $request->barang_id,
            'jumlah'         => $request->jumlah,
            'satuan_diminta' => $request->satuan_diminta,
            'catatan'        => $request->catatan,
            'status'         => PermintaanBarang::STATUS_MENUNGGU_APPROVAL,
        ]);

        return redirect()->route('permintaan.index')
            ->with('success', 'Permintaan berhasil diajukan. Menunggu persetujuan PIC Gudang.');
    }

    // Forwarding methods to dedicated controllers for backward compatibility
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
