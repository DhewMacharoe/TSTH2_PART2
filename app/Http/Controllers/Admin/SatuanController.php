<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\SatuanBarang;
use App\Models\AktivitasGudang;
use App\Services\AktivitasLogger;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'pic_gudang') {
            $selectedJenis = 'lapangan';
        } elseif ($user->role === 'pic_lab') {
            $selectedJenis = 'lab';
        } else {
            $selectedJenis = $request->query('jenis', 'all'); // 'all', 'lapangan', 'lab'
        }

        $allSatuans   = SatuanBarang::orderBy('kategori')->orderBy('nama_satuan')->with('creator')->get();
        $allKonversis = \App\Models\SatuanKonversi::all();

        $query = SatuanBarang::orderBy('kategori')->orderBy('nama_satuan')->with('creator');
        $konversiQuery = \App\Models\SatuanKonversi::query();

        if ($selectedJenis === 'lapangan') {
            $query->where('jenis_barang', 'lapangan');
            $konversiQuery->where('jenis_barang', 'lapangan');
        } elseif ($selectedJenis === 'lab') {
            $query->where('jenis_barang', 'lab');
            $konversiQuery->where('jenis_barang', 'lab');
        }

        $satuans   = $query->get();
        $grouped   = $satuans->groupBy(fn($s) => $s->kategori);
        $konversis = $konversiQuery->get();

        // Statistik
        $totalSatuan           = $allSatuans->count();
        $totalSatuanLapangan   = $allSatuans->where('jenis_barang', 'lapangan')->count();
        $totalSatuanLab        = $allSatuans->where('jenis_barang', 'lab')->count();
        $totalKategoriLapangan = $allSatuans->where('jenis_barang', 'lapangan')->pluck('kategori')->unique()->count();
        $totalKategoriLab      = $allSatuans->where('jenis_barang', 'lab')->pluck('kategori')->unique()->count();
        $totalKonversi         = $allKonversis->count();
        $totalKonversiLapangan = $allKonversis->where('jenis_barang', 'lapangan')->count();
        $totalKonversiLab      = $allKonversis->where('jenis_barang', 'lab')->count();

        // Kategori dari tabel kategori_barangs
        $kategoriLapanganModels = \App\Models\KategoriBarang::lapangan()->orderBy('nama_kategori')->get();
        $kategoriLabModels      = \App\Models\KategoriBarang::lab()->orderBy('nama_kategori')->get();

        $totalKategoriLapangan = $kategoriLapanganModels->count();
        $totalKategoriLab      = $kategoriLabModels->count();

        // Kategori list per jenis untuk dropdown/datalist form
        $kategoriLapangan = $kategoriLapanganModels->pluck('nama_kategori');
        $kategoriLab      = $kategoriLabModels->pluck('nama_kategori');

        if ($user->role === 'pic_gudang') {
            $kategoriList = $kategoriLapangan;
        } elseif ($user->role === 'pic_lab') {
            $kategoriList = $kategoriLab;
        } else {
            $kategoriList = ($selectedJenis === 'lapangan')
                ? $kategoriLapangan
                : (($selectedJenis === 'lab') ? $kategoriLab : \App\Models\KategoriBarang::orderBy('nama_kategori')->pluck('nama_kategori')->unique());
        }

        // Fallback jika database masih kosong
        if ($kategoriLapangan->isEmpty()) {
            $kategoriLapangan = collect(['Pupuk', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya']);
        }
        if ($kategoriLab->isEmpty()) {
            $kategoriLab = collect(['Bahan Kimia', 'Reagen & Larutan', 'Alat Laboratorium', 'Lainnya']);
        }
        if ($kategoriList->isEmpty()) {
            $kategoriList = collect(['Pupuk', 'Bahan Kimia', 'Alat Budidaya', 'Bibit Tanaman', 'Lainnya']);
        }

        return view('gudang.satuan', compact(
            'satuans', 'grouped', 'kategoriList', 'kategoriLapangan', 'kategoriLab',
            'kategoriLapanganModels', 'kategoriLabModels',
            'konversis', 'selectedJenis',
            'totalSatuan', 'totalSatuanLapangan', 'totalSatuanLab',
            'totalKategoriLapangan', 'totalKategoriLab',
            'totalKonversi', 'totalKonversiLapangan', 'totalKonversiLab'
        ));
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
            'nama_satuan'  => [
                'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('satuan_barangs')->where(function ($query) use ($permintaan) {
                    return $query->where('kategori', trim($permintaan->kategori) ?: null)
                                 ->where('jenis_barang', $permintaan->jenis_barang ?? 'lapangan');
                })
            ],
            'kategori'     => 'required|string|max:50',
            'jenis_barang' => 'required|in:lapangan,lab',
        ], [
            'nama_satuan.unique' => 'Satuan ini sudah terdaftar di kategori dan tipe peruntukan tersebut.',
        ]);

        $satuan = SatuanBarang::create([
            'nama_satuan'  => strtolower(trim($permintaan->nama_satuan)),
            'kategori'     => trim($permintaan->kategori),
            'jenis_barang' => $permintaan->jenis_barang,
            'created_by'   => Auth::id(),
        ]);

        // Pastikan kategori juga tercatat di tabel master kategori_barangs
        \App\Models\KategoriBarang::firstOrCreate(
            [
                'nama_kategori' => trim($permintaan->kategori),
                'jenis_barang'  => $permintaan->jenis_barang,
            ],
            [
                'created_by'    => Auth::id(),
            ]
        );

        $jenisLabel = $satuan->jenis_label;
        AktivitasLogger::catat(
            AktivitasGudang::MODUL_SATUAN,
            'tambah',
            "Satuan baru '{$satuan->nama_satuan}' [{$jenisLabel}] (Kategori: {$satuan->kategori}) ditambahkan oleh " . Auth::user()->name . "."
        );

        return back()->with('success', 'Satuan "' . $permintaan->nama_satuan . '" (' . $jenisLabel . ') berhasil ditambahkan.');
    }

    /**
     * Hapus satuan.
     */
    public function destroy($id)
    {
        $satuan     = SatuanBarang::findOrFail($id);
        $namaSatuan = $satuan->nama_satuan;
        $kategori   = $satuan->kategori;
        $jenisLabel = $satuan->jenis_label;

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_SATUAN,
            'hapus',
            "Satuan '{$namaSatuan}' [{$jenisLabel}] (Kategori: {$kategori}) dihapus oleh " . Auth::user()->name . "."
        );

        $satuan->delete();
        return back()->with('success', 'Satuan "' . $namaSatuan . '" (' . $jenisLabel . ') berhasil dihapus.');
    }

    public function storeKonversi(Request $permintaan)
    {
        $permintaan->validate([
            'kategori'     => 'required|string',
            'jenis_barang' => 'nullable|in:lapangan,lab',
            'dari_satuan'  => 'required|string',
            'ke_satuan'    => 'required|string|different:dari_satuan',
            'multiplier'   => 'required|numeric|min:0.01'
        ]);

        // Tentukan jenis_barang dari input atau dari satuan_barangs kategori tersebut
        $jenis = $permintaan->jenis_barang;
        if (!$jenis) {
            $matchingSatuan = SatuanBarang::where('kategori', $permintaan->kategori)->first();
            $jenis = $matchingSatuan?->jenis_barang ?? 'lapangan';
        }

        \App\Models\SatuanKonversi::updateOrCreate(
            [
                'kategori'    => $permintaan->kategori,
                'dari_satuan' => strtolower($permintaan->dari_satuan),
                'ke_satuan'   => strtolower($permintaan->ke_satuan)
            ],
            [
                'jenis_barang' => $jenis,
                'multiplier'   => $permintaan->multiplier
            ]
        );

        $jenisLabel = $jenis === 'lab' ? 'Barang Lab' : 'Barang Lapangan';
        AktivitasLogger::catat(
            AktivitasGudang::MODUL_KONVERSI,
            'tambah',
            "Konversi satuan {$permintaan->dari_satuan} -> {$permintaan->ke_satuan} (x{$permintaan->multiplier}) [{$jenisLabel} - Kategori: {$permintaan->kategori}] ditambahkan oleh " . Auth::user()->name . "."
        );

        return back()->with('success', 'Konversi satuan berhasil ditambahkan.');
    }

    public function destroyKonversi($id)
    {
        $konversi = \App\Models\SatuanKonversi::findOrFail($id);
        $jenisLabel = $konversi->jenis_label ?? ($konversi->jenis_barang === 'lab' ? 'Barang Lab' : 'Barang Lapangan');
        $ket = "Konversi {$konversi->dari_satuan} -> {$konversi->ke_satuan} [{$jenisLabel} - Kategori: {$konversi->kategori}] dihapus oleh " . Auth::user()->name . ".";
        $konversi->delete();

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_KONVERSI,
            'hapus',
            $ket
        );

        return back()->with('success', 'Konversi satuan berhasil dihapus.');
    }
}
