<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use App\Models\Barang;
use App\Models\SatuanBarang;
use App\Models\SatuanKonversi;
use App\Models\AktivitasGudang;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KategoriBarangController extends Controller
{
    /**
     * Tambah kategori baru ke warehouse (Gudang GA atau Lab RdI)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Tentukan jenis_barang berdasarkan hak akses role
        if ($user->isPicGudang()) {
            $request->merge(['jenis_barang' => 'lapangan']);
        } elseif ($user->isPicLab()) {
            $request->merge(['jenis_barang' => 'lab']);
        }

        $validated = $request->validate([
            'nama_kategori' => [
                'required', 'string', 'max:100',
                Rule::unique('kategori_barangs')->where(function ($q) use ($request) {
                    return $q->where('jenis_barang', $request->jenis_barang);
                })
            ],
            'jenis_barang'  => 'required|in:lapangan,lab',
            'deskripsi'     => 'nullable|string|max:255',
        ], [
            'nama_kategori.unique' => 'Kategori ini sudah terdaftar pada warehouse yang dipilih.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        // Proteksi otorisasi jika admin memilih secara eksplisit
        if ($user->isPicGudang() && $validated['jenis_barang'] !== 'lapangan') {
            abort(403, 'Akses ditolak: Anda hanya berwenang menambah kategori Warehouse Gudang (GA).');
        }
        if ($user->isPicLab() && $validated['jenis_barang'] !== 'lab') {
            abort(403, 'Akses ditolak: Anda hanya berwenang menambah kategori Warehouse Laboratorium (RdI).');
        }

        $kategori = KategoriBarang::create([
            'nama_kategori' => trim($validated['nama_kategori']),
            'jenis_barang'  => $validated['jenis_barang'],
            'deskripsi'     => $validated['deskripsi'] ? trim($validated['deskripsi']) : null,
            'created_by'    => $user->id,
        ]);

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_SATUAN,
            'tambah_kategori',
            "Kategori baru '{$kategori->nama_kategori}' [{$kategori->jenis_label}] ditambahkan oleh " . $user->name . " (" . $user->role_label . ")."
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => "Kategori '{$kategori->nama_kategori}' berhasil ditambahkan.",
                'kategori' => $kategori
            ]);
        }

        return back()->with('success', "Kategori baru '{$kategori->nama_kategori}' berhasil ditambahkan ke {$kategori->jenis_label}.");
    }

    /**
     * Hapus kategori dari warehouse
     */
    public function destroy(Request $request, $id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $user = Auth::user();

        // Lindungi kategori default 'Lainnya'
        if (strtolower(trim($kategori->nama_kategori)) === 'lainnya') {
            return back()->with('error', "Kategori 'Lainnya' adalah kategori default sistem dan tidak boleh dihapus.");
        }

        // Otorisasi role PIC
        if ($user->isPicGudang() && $kategori->jenis_barang !== 'lapangan') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus kategori laboratorium.');
        }
        if ($user->isPicLab() && $kategori->jenis_barang !== 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus kategori gudang general/lapangan.');
        }

        $namaKategori = $kategori->nama_kategori;
        $jenisBarang  = $kategori->jenis_barang;
        $jenisLabel   = $kategori->jenis_label;

        // Cek apakah kategori sedang digunakan oleh barang aktif di warehouse
        $usedCount = Barang::where('kategori', $namaKategori)
            ->where('jenis_barang', $jenisBarang)
            ->count();

        if ($usedCount > 0) {
            // Jika user menyetujui pemindahan ke 'Lainnya'
            if ($request->input('pindahkan_ke_lainnya') == '1' || $request->has('force')) {
                Barang::where('kategori', $namaKategori)
                    ->where('jenis_barang', $jenisBarang)
                    ->update(['kategori' => 'Lainnya']);
            } else {
                return back()->with('error', "Kategori '{$namaKategori}' tidak dapat dihapus karena masih digunakan oleh {$usedCount} data barang aktif di warehouse. Pindahkan kategori barang terlebih dahulu atau konfirmasi pemindahan ke 'Lainnya'.");
            }
        }

        // Bersihkan satuan dan konversi terkait kategori ini
        SatuanBarang::where('kategori', $namaKategori)
            ->where('jenis_barang', $jenisBarang)
            ->delete();

        SatuanKonversi::where('kategori', $namaKategori)
            ->where('jenis_barang', $jenisBarang)
            ->delete();

        $kategori->delete();

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_SATUAN,
            'hapus_kategori',
            "Kategori '{$namaKategori}' [{$jenisLabel}] berserta satuannya dihapus oleh " . $user->name . " (" . $user->role_label . ")."
        );

        $pesan = "Kategori '{$namaKategori}' ({$jenisLabel}) berhasil dihapus dari warehouse.";
        if ($usedCount > 0) {
            $pesan .= " Sebanyak {$usedCount} barang terkait telah dialihkan ke kategori 'Lainnya'.";
        }

        return back()->with('success', $pesan);
    }
}

