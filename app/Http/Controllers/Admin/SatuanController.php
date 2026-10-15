<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\SatuanBarang;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = SatuanBarang::orderBy('kategori')->orderBy('nama_satuan')->with('creator')->get();
        $grouped = $satuans->groupBy(fn($s) => $s->kategori);
        $kategoriList = SatuanBarang::whereNotNull('kategori')->distinct()->pluck('kategori');
        
        // Ensure default categories exist if table is empty
        if ($kategoriList->isEmpty()) {
            $kategoriList = collect(['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya']);
        }
        
        $konversis = \App\Models\SatuanKonversi::all();

        return view('gudang.satuan', compact('satuans', 'grouped', 'kategoriList', 'konversis'));
    }

    /**
     * API: kembalikan satuan berdasarkan kategori (untuk AJAX filter).
     */
    public function byKategori(string $kategori)
    {
        $satuans = SatuanBarang::forKategori($kategori)
            ->orderBy('nama_satuan')
            ->get(['nama_satuan', 'kategori']);

        $kondisi = SatuanBarang::kondisiByKategori($kategori);

        return response()->json([
            'satuans' => $satuans,
            'kondisi' => $kondisi,
        ]);
    }

    /**
     * Simpan satuan baru.
     */
    public function store(Request $permintaan)
    {
        $permintaan->validate([
            'nama_satuan' => [
                'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('satuan_barangs')->where(function ($query) use ($permintaan) {
                    return $query->where('kategori', trim($permintaan->kategori) ?: null);
                })
            ],
            'kategori'    => 'required|string|max:50',
        ], [
            'nama_satuan.unique' => 'Satuan ini sudah terdaftar di kategori tersebut.',
        ]);

        SatuanBarang::create([
            'nama_satuan' => strtolower(trim($permintaan->nama_satuan)),
            'kategori'    => trim($permintaan->kategori),
            'created_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Satuan "' . $permintaan->nama_satuan . '" berhasil ditambahkan.');
    }

    /**
     * Hapus satuan.
     */
    public function destroy($id)
    {
        $satuan = SatuanBarang::findOrFail($id);
        $satuan->delete();
        return back()->with('success', 'Satuan "' . $satuan->nama_satuan . '" berhasil dihapus.');
    }

    public function storeKonversi(Request $permintaan)
    {
        $permintaan->validate([
            'kategori' => 'required|string',
            'dari_satuan' => 'required|string',
            'ke_satuan' => 'required|string|different:dari_satuan',
            'multiplier' => 'required|numeric|min:0.01'
        ]);

        \App\Models\SatuanKonversi::updateOrCreate(
            [
                'kategori' => $permintaan->kategori,
                'dari_satuan' => strtolower($permintaan->dari_satuan),
                'ke_satuan' => strtolower($permintaan->ke_satuan)
            ],
            ['multiplier' => $permintaan->multiplier]
        );

        return back()->with('success', 'Konversi satuan berhasil ditambahkan.');
    }

    public function destroyKonversi($id)
    {
        $konversi = \App\Models\SatuanKonversi::findOrFail($id);
        $konversi->delete();
        return back()->with('success', 'Konversi satuan berhasil dihapus.');
    }
}
