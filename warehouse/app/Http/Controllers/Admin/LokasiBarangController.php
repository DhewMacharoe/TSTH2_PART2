<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiBarang;
use App\Models\Barang;
use App\Models\AktivitasGudang;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LokasiBarangController extends Controller
{
    /**
     * Tambah lokasi penyimpanan baru ke warehouse (Gudang GA atau Lab RdI)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isPicGudang()) {
            $request->merge(['jenis_barang' => 'lapangan']);
        } elseif ($user->isPicLab()) {
            $request->merge(['jenis_barang' => 'lab']);
        }

        $validated = $request->validate([
            'nama_lokasi'  => [
                'required', 'string', 'max:100',
                Rule::unique('lokasi_barangs')->where(function ($q) use ($request) {
                    return $q->where('jenis_barang', $request->jenis_barang);
                })
            ],
            'jenis_barang' => 'required|in:lapangan,lab',
            'deskripsi'    => 'nullable|string|max:255',
        ], [
            'nama_lokasi.unique'   => 'Lokasi ini sudah terdaftar pada warehouse yang dipilih.',
            'nama_lokasi.required' => 'Nama lokasi penyimpanan wajib diisi.',
        ]);

        if ($user->isPicGudang() && $validated['jenis_barang'] !== 'lapangan') {
            abort(403, 'Akses ditolak: Anda hanya berwenang menambah lokasi Warehouse Gudang (GA).');
        }
        if ($user->isPicLab() && $validated['jenis_barang'] !== 'lab') {
            abort(403, 'Akses ditolak: Anda hanya berwenang menambah lokasi Warehouse Laboratorium (RdI).');
        }

        $lokasi = LokasiBarang::create([
            'nama_lokasi'  => trim($validated['nama_lokasi']),
            'jenis_barang' => $validated['jenis_barang'],
            'deskripsi'    => !empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null,
            'created_by'   => $user->id,
        ]);

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_BARANG,
            'tambah_lokasi',
            "Lokasi penyimpanan baru '{$lokasi->nama_lokasi}' [{$lokasi->jenis_label}] ditambahkan oleh " . $user->name . " (" . $user->role_label . ")."
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Lokasi '{$lokasi->nama_lokasi}' berhasil ditambahkan.",
                'lokasi'  => $lokasi,
            ]);
        }

        return back()->with('success', "Lokasi penyimpanan '{$lokasi->nama_lokasi}' berhasil ditambahkan ke {$lokasi->jenis_label}.");
    }

    /**
     * Hapus lokasi penyimpanan dari warehouse
     */
    public function destroy(Request $request, $id)
    {
        $lokasi = LokasiBarang::findOrFail($id);
        $user   = Auth::user();

        if ($user->isPicGudang() && $lokasi->jenis_barang !== 'lapangan') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus lokasi laboratorium.');
        }
        if ($user->isPicLab() && $lokasi->jenis_barang !== 'lab') {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk menghapus lokasi gudang general/lapangan.');
        }

        $namaLokasi  = $lokasi->nama_lokasi;
        $jenisBarang = $lokasi->jenis_barang;
        $jenisLabel  = $lokasi->jenis_label;

        $usedCount = Barang::where('lokasi_blok', $namaLokasi)
            ->where('jenis_barang', $jenisBarang)
            ->count();

        if ($usedCount > 0) {
            if ($request->input('pindahkan_ke_umum') == '1' || $request->has('force')) {
                $fallback = $jenisBarang === 'lab' ? 'Ruang Lab Utama' : 'Gudang Utama';
                Barang::where('lokasi_blok', $namaLokasi)
                    ->where('jenis_barang', $jenisBarang)
                    ->update(['lokasi_blok' => $fallback]);
            } else {
                return back()->with('error', "Lokasi '{$namaLokasi}' tidak dapat dihapus karena masih digunakan oleh {$usedCount} data barang aktif. Pindahkan barang terlebih dahulu atau konfirmasi pemindahan.");
            }
        }

        $lokasi->delete();

        AktivitasLogger::catat(
            AktivitasGudang::MODUL_BARANG,
            'hapus_lokasi',
            "Lokasi penyimpanan '{$namaLokasi}' [{$jenisLabel}] dihapus oleh " . $user->name . " (" . $user->role_label . ")."
        );

        $pesan = "Lokasi '{$namaLokasi}' ({$jenisLabel}) berhasil dihapus.";
        if ($usedCount > 0) {
            $fallback = $jenisBarang === 'lab' ? 'Ruang Lab Utama' : 'Gudang Utama';
            $pesan .= " Sebanyak {$usedCount} barang terkait telah dialihkan ke '{$fallback}'.";
        }

        return back()->with('success', $pesan);
    }
}

