<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\LokasiBarang;
use App\Models\SatuanBarang;
use App\Models\AktivitasGudang;
use App\Services\AktivitasLogger;
use App\Services\WarehouseExportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Scope otomatis per role PIC warehouse
        if ($user->role === 'pic_gudang') {
            $selectedJenis = 'lapangan';
        } elseif ($user->role === 'pic_lab') {
            $selectedJenis = 'lab';
        } else {
            $selectedJenis = $request->query('jenis', 'all'); // 'all', 'lapangan', 'lab'
        }

        $allBarangs = Barang::with(['batches', 'creator', 'updater'])->get();
        $query = Barang::with(['batches', 'creator', 'updater'])->orderBy('nama_barang');

        if ($selectedJenis === 'lapangan') {
            $query->where('jenis_barang', 'lapangan');
        } elseif ($selectedJenis === 'lab') {
            $query->where('jenis_barang', 'lab');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama_barang', 'like', "%{$s}%")
                  ->orWhere('kode_barang', 'like', "%{$s}%")
                  ->orWhere('merk', 'like', "%{$s}%")
                  ->orWhere('lokasi_blok', 'like', "%{$s}%");
            });
        }

        $barangsPaginated = $query->paginate(20)->withQueryString();

        // Statistik keseluruhan & per jenis
        $totalBarang          = $allBarangs->sum(fn($b) => $b->batches->sum('stok'));
        $totalBarangLapangan  = $allBarangs->where('jenis_barang', 'lapangan')->sum(fn($b) => $b->batches->sum('stok'));
        $totalBarangLab       = $allBarangs->where('jenis_barang', 'lab')->sum(fn($b) => $b->batches->sum('stok'));
        $countItemsLapangan   = $allBarangs->where('jenis_barang', 'lapangan')->count();
        $countItemsLab        = $allBarangs->where('jenis_barang', 'lab')->count();

        $scopedBarangs = ($selectedJenis === 'all')
            ? $allBarangs
            : $allBarangs->where('jenis_barang', $selectedJenis);

        $totalKategori = ($selectedJenis === 'all')
            ? \App\Models\KategoriBarang::count()
            : \App\Models\KategoriBarang::where('jenis_barang', $selectedJenis)->count();

        $totalSatuanLapangan = SatuanBarang::where('jenis_barang', 'lapangan')->count();
        $totalSatuanLab      = SatuanBarang::where('jenis_barang', 'lab')->count();

        $barangsByCategory = $scopedBarangs->groupBy('kategori')->map(fn($items) => $items->sum(fn($b) => $b->batches->sum('stok')));
        $barangsByBlock    = $scopedBarangs->groupBy('lokasi_blok')->map(fn($items) => $items->sum(fn($b) => $b->batches->sum('stok')));

        $kategoriListQuery = ($selectedJenis === 'all')
            ? \App\Models\KategoriBarang::orderBy('nama_kategori')
            : \App\Models\KategoriBarang::where('jenis_barang', $selectedJenis)->orderBy('nama_kategori');
        $kategoriModels = $kategoriListQuery->get();
        $kategoriList   = $kategoriModels->pluck('nama_kategori')->unique();

        $lokasiListQuery = ($selectedJenis === 'all')
            ? LokasiBarang::orderBy('nama_lokasi')
            : LokasiBarang::where('jenis_barang', $selectedJenis)->orderBy('nama_lokasi');
        $lokasiModels = $lokasiListQuery->get();

        $satuanByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat] = SatuanBarang::forKategori($kat)->orderBy('nama_satuan')->pluck('nama_satuan')->toArray();
        }

        return view('gudang.gudang', compact(
            'allBarangs', 'barangsPaginated', 'totalBarang', 'totalKategori',
            'totalBarangLapangan', 'totalBarangLab', 'countItemsLapangan', 'countItemsLab',
            'totalSatuanLapangan', 'totalSatuanLab',
            'barangsByCategory', 'barangsByBlock', 'kategoriModels', 'lokasiModels', 'satuanByKategori', 'selectedJenis'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $queryBarangs = Barang::with('batches')->orderBy('nama_barang');

        if ($user->role === 'pic_gudang') {
            $queryBarangs->where('jenis_barang', 'lapangan');
            $kategoriList = \App\Models\KategoriBarang::lapangan()->orderBy('nama_kategori')->pluck('nama_kategori');
            $lokasiList   = LokasiBarang::lapangan()->orderBy('nama_lokasi')->pluck('nama_lokasi');
        } elseif ($user->role === 'pic_lab') {
            $queryBarangs->where('jenis_barang', 'lab');
            $kategoriList = \App\Models\KategoriBarang::lab()->orderBy('nama_kategori')->pluck('nama_kategori');
            $lokasiList   = LokasiBarang::lab()->orderBy('nama_lokasi')->pluck('nama_lokasi');
        } else {
            $kategoriList = \App\Models\KategoriBarang::orderBy('nama_kategori')->pluck('nama_kategori')->unique();
            $lokasiList   = LokasiBarang::orderBy('nama_lokasi')->pluck('nama_lokasi')->unique();
        }

        if ($kategoriList->isEmpty()) {
            $kategoriList = collect(['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Reagen & Larutan', 'Lainnya']);
        }

        $existingBarangs = $queryBarangs->get();

        $satuanByKategori  = [];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat]  = SatuanBarang::forKategori($kat)->orderBy('nama_satuan')->pluck('nama_satuan')->toArray();
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('gudang.gudang_create', compact('existingBarangs', 'satuanByKategori', 'kondisiByKategori', 'lokasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => [
                'required', 'string', 'max:255',
                Rule::unique('barangs')->where(fn($query) => empty($request->merk) ? $query->whereNull('merk') : $query->where('merk', $request->merk))
            ],
            'merk'               => 'nullable|string|max:255',
            'jenis_barang'       => 'required|in:lapangan,lab',
            'kategori'           => 'required|string|max:100',
            'lokasi_blok'        => 'required|string|max:100',
            'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_kedatangan' => 'nullable|date',
            'tanggal_kadaluarsa' => 'nullable|date|after_or_equal:tanggal_kedatangan',
            'stok_jumlah'        => 'required|array',
            'stok_jumlah.*'      => 'required|integer|min:1',
            'stok_satuan'        => 'required|array',
            'stok_satuan.*'      => 'required|string',
            'stok_kondisi'       => 'required|array',
            'stok_kondisi.*'     => 'required|string',
        ], [
            'nama_barang.unique' => 'Barang dengan nama ini sudah terdaftar di sistem. Silakan cari barang tersebut dan gunakan fitur "Restock" untuk menambah stok.'
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        $barangData = $validated;
        unset($barangData['tanggal_kedatangan'], $barangData['tanggal_kadaluarsa'], $barangData['stok_jumlah'], $barangData['stok_satuan'], $barangData['stok_kondisi']);

        $barangData['kode_barang'] = Barang::generateKodeBarang($barangData['kategori'], $barangData['lokasi_blok']);

        // Kunci jenis_barang sesuai wewenang PIC
        $user = Auth::user();
        if ($user->role === 'pic_gudang') {
            $barangData['jenis_barang'] = 'lapangan';
        } elseif ($user->role === 'pic_lab') {
            $barangData['jenis_barang'] = 'lab';
        }

        $barangData['created_by'] = Auth::id();
        $barangData['updated_by'] = Auth::id();

        $barang = Barang::create($barangData);

        $totalStok = 0;
        if ($request->tanggal_kedatangan && !empty($request->stok_jumlah)) {
            $stokDetail  = [];
            $stokJumlah  = $request->input('stok_jumlah', []);
            $stokSatuan  = $request->input('stok_satuan', []);
            $stokKondisi = $request->input('stok_kondisi', []);

            for ($i = 0; $i < count($stokJumlah); $i++) {
                $stokDetail[] = [
                    'kondisi' => $stokKondisi[$i] ?? '-',
                    'satuan'  => $stokSatuan[$i] ?? '-',
                    'stok'    => (int) $stokJumlah[$i]
                ];
                $totalStok += (int) $stokJumlah[$i];
            }

            BarangBatch::create([
                'barang_id'          => $barang->id,
                'batch_no'           => BarangBatch::nextBatchNo($barang->id),
                'created_by'         => Auth::id(),
                'tanggal_kedatangan' => $request->tanggal_kedatangan,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'stok'               => $totalStok,
                'stok_detail'        => $stokDetail
            ]);
        }

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_BARANG,
            'tambah',
            "Barang baru '{$barang->nama_barang}' [{$barang->jenis_label}] (Kode: {$barang->kode_barang}) ditambahkan oleh " . Auth::user()->name . " (" . Auth::user()->role_label . ").",
            AktivitasGudang::MUTASI_MASUK,
            $barang->id,
            $barang->nama_barang,
            $totalStok,
            $request->input('stok_satuan.0')
        );

        return redirect()->route('gudang')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'pic_gudang' && $barang->jenis_barang === 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengelola barang laboratorium.');
        }
        if ($user->role === 'pic_lab' && $barang->jenis_barang !== 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengelola barang gudang general/lapangan.');
        }

        $jenis = $barang->jenis_barang ?? 'lapangan';
        $kategoriList = \App\Models\KategoriBarang::where('jenis_barang', $jenis)->orderBy('nama_kategori')->pluck('nama_kategori');
        if ($kategoriList->isEmpty()) {
            $kategoriList = $jenis === 'lab'
                ? collect(['Bahan Kimia', 'Reagen & Larutan', 'Alat Gelas & Plastik Lab', 'Instrumen Lab', 'Bahan Habis Pakai Lab', 'Lainnya'])
                : collect(['Pupuk', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya']);
        }
        if (!$kategoriList->contains($barang->kategori) && $barang->kategori) {
            $kategoriList->push($barang->kategori);
        }

        $lokasiList = LokasiBarang::where('jenis_barang', $jenis)->orderBy('nama_lokasi')->pluck('nama_lokasi');
        if (!$lokasiList->contains($barang->lokasi_blok) && $barang->lokasi_blok) {
            $lokasiList->push($barang->lokasi_blok);
        }

        return view('gudang.gudang_edit', compact('barang', 'kategoriList', 'lokasiList'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'pic_gudang' && $barang->jenis_barang === 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengelola barang laboratorium.');
        }
        if ($user->role === 'pic_lab' && $barang->jenis_barang !== 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengelola barang gudang general/lapangan.');
        }

        $validated = $request->validate([
            'kode_barang'  => 'nullable|string|max:50|unique:barangs,kode_barang,' . $id,
            'nama_barang'  => [
                'required', 'string', 'max:255',
                Rule::unique('barangs')->where(fn($query) => empty($request->merk) ? $query->whereNull('merk') : $query->where('merk', $request->merk))->ignore($id)
            ],
            'merk'         => 'nullable|string|max:255',
            'jenis_barang' => 'required|in:lapangan,lab',
            'kategori'     => 'required|string|max:100',
            'lokasi_blok'  => 'required|string|max:100',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.unique' => 'Barang dengan nama ini sudah terdaftar di sistem.'
        ]);

        $barang = Barang::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        $validated['updated_by'] = Auth::id();
        $barang->update($validated);

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_BARANG,
            'edit',
            "Data barang '{$barang->nama_barang}' [{$barang->jenis_label}] diperbarui oleh " . Auth::user()->name . " (" . Auth::user()->role_label . ").",
            AktivitasGudang::MUTASI_NETRAL,
            $barang->id,
            $barang->nama_barang
        );

        return redirect()->route('gudang')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'pic_gudang' && $barang->jenis_barang === 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus barang laboratorium.');
        }
        if ($user->role === 'pic_lab' && $barang->jenis_barang !== 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus barang gudang general/lapangan.');
        }

        $namaBarang = $barang->nama_barang;
        $kodeBarang = $barang->kode_barang;
        $jenisLabel = $barang->jenis_label;

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_BARANG,
            'hapus',
            "Barang '{$namaBarang}' [{$jenisLabel}] (Kode: {$kodeBarang}) dihapus dari gudang oleh " . Auth::user()->name . " (" . Auth::user()->role_label . ").",
            AktivitasGudang::MUTASI_KELUAR,
            $barang->id,
            $namaBarang
        );

        $barang->delete();

        return redirect()->route('gudang')->with('success', 'Data barang berhasil dihapus!');
    }

    public function destroyGambar($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
            $barang->update(['gambar' => null]);

            AktivitasLogger::catat(
                AktivitasGudang::MODUL_BARANG,
                'edit',
                "Foto barang '{$barang->nama_barang}' dihapus oleh " . Auth::user()->name . '.',
                AktivitasGudang::MUTASI_NETRAL,
                $barang->id,
                $barang->nama_barang
            );
        }

        return back()->with('success', 'Foto barang berhasil dihapus!');
    }

    public function show($id)
    {
        $barang = Barang::with(['creator', 'updater', 'batches' => function($query) {
            $query->orderBy('batch_no', 'asc')
                  ->orderByRaw("CASE kondisi
                      WHEN 'Sudah Terbuka' THEN 1 WHEN 'Sisa Sedikit' THEN 1
                      WHEN 'Hampir Habis' THEN 1 WHEN 'Bekas Pakai - Perlu Perbaikan' THEN 1
                      WHEN 'Bekas Pakai - Bagus' THEN 2 WHEN 'Sudah Dipakai' THEN 2
                      ELSE 3 END ASC")
                  ->orderBy('tanggal_kadaluarsa', 'asc');
        }])->findOrFail($id);

        $batchGroups = $barang->batches->groupBy('batch_no');
        $nextBatchNo = BarangBatch::nextBatchNo($barang->id);
        $satuans     = SatuanBarang::forKategori($barang->kategori)->orderBy('nama_satuan')->get();
        $kondisiList = SatuanBarang::kondisiByKategori($barang->kategori);

        $riwayatAktivitas = AktivitasGudang::where('barang_id', $barang->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('gudang.gudang_show', compact('barang', 'batchGroups', 'nextBatchNo', 'satuans', 'kondisiList', 'riwayatAktivitas'));
    }

    public function export(WarehouseExportService $exportService)
    {
        return $exportService->exportCsv();
    }
}
