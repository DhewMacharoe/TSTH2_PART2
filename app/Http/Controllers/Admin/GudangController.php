<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\SatuanBarang;
use App\Services\WarehouseExportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GudangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('batches')->get(); // used for stats
        $barangsPaginated = Barang::with('batches')->orderBy('nama_barang')->paginate(20)->withQueryString();

        $totalBarang   = $barangs->sum(fn($b) => $b->batches->sum('stok'));
        $totalKategori = $barangs->pluck('kategori')->unique()->count();

        $barangsByCategory = $barangs->groupBy('kategori')->map(fn($items) => $items->sum(fn($b) => $b->batches->sum('stok')));
        $barangsByBlock    = $barangs->groupBy('lokasi_blok')->map(fn($items) => $items->sum(fn($b) => $b->batches->sum('stok')));

        $kategoriList     = SatuanBarang::whereNotNull('kategori')->distinct()->pluck('kategori');
        $satuanByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat] = SatuanBarang::forKategori($kat)->orderBy('nama_satuan')->pluck('nama_satuan')->toArray();
        }

        return view('gudang.gudang', compact('barangs', 'barangsPaginated', 'totalBarang', 'totalKategori', 'barangsByCategory', 'barangsByBlock', 'satuanByKategori'));
    }

    public function create()
    {
        $existingBarangs = Barang::with('batches')->orderBy('nama_barang')->get();
        $kategoriList   = SatuanBarang::whereNotNull('kategori')->distinct()->pluck('kategori');
        if ($kategoriList->isEmpty()) {
            $kategoriList = collect(['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya']);
        }

        $satuanByKategori = [];
        $kondisiByKategori = [];
        foreach ($kategoriList as $kat) {
            $satuanByKategori[$kat]  = SatuanBarang::forKategori($kat)->orderBy('nama_satuan')->pluck('nama_satuan')->toArray();
            $kondisiByKategori[$kat] = SatuanBarang::kondisiByKategori($kat);
        }

        return view('gudang.gudang_create', compact('existingBarangs', 'satuanByKategori', 'kondisiByKategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang'        => 'nullable|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => [
                'required', 'string', 'max:255',
                Rule::unique('barangs')->where(fn($query) => empty($request->merk) ? $query->whereNull('merk') : $query->where('merk', $request->merk))
            ],
            'merk'               => 'nullable|string|max:255',
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

        if (empty($barangData['kode_barang'])) {
            $barangData['kode_barang'] = Barang::generateKodeBarang($barangData['kategori'], $barangData['lokasi_blok']);
        }

        $barang = Barang::create($barangData);

        if ($request->tanggal_kedatangan && !empty($request->stok_jumlah)) {
            $stokDetail  = [];
            $totalStok   = 0;
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
                'tanggal_kedatangan' => $request->tanggal_kedatangan,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'stok'               => $totalStok,
                'stok_detail'        => $stokDetail
            ]);
        }

        return redirect()->route('gudang')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('gudang.gudang_edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_barang' => 'nullable|string|max:50|unique:barangs,kode_barang,' . $id,
            'nama_barang' => [
                'required', 'string', 'max:255',
                Rule::unique('barangs')->where(fn($query) => empty($request->merk) ? $query->whereNull('merk') : $query->where('merk', $request->merk))->ignore($id)
            ],
            'merk'        => 'nullable|string|max:255',
            'kategori'    => 'required|string|max:100',
            'lokasi_blok' => 'required|string|max:100',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $barang->update($validated);

        return redirect()->route('gudang')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }
        $barang->delete();

        return redirect()->route('gudang')->with('success', 'Data barang berhasil dihapus!');
    }

    public function destroyGambar($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
            $barang->update(['gambar' => null]);
        }

        return back()->with('success', 'Foto barang berhasil dihapus!');
    }

    public function show($id)
    {
        $barang = Barang::with(['batches' => function($query) {
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

        return view('gudang.gudang_show', compact('barang', 'batchGroups', 'nextBatchNo', 'satuans', 'kondisiList'));
    }

    public function export(WarehouseExportService $exportService)
    {
        return $exportService->exportCsv();
    }
}