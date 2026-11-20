<?php

namespace App\Services;

use App\Models\AktivitasGudang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AktivitasLogger
{
    /**
     * Catat satu baris aktivitas ke tabel aktivitas_gudangs.
     *
     * @param string      $modul       Dari mana aksi berasal (barang, batch, satuan, permintaan, dll)
     * @param string      $aksi        Apa yang dilakukan (tambah, edit, hapus, approve, reject, eskalasi, dll)
     * @param string      $keterangan  Narasi lengkap aksi
     * @param string      $tipe_mutasi masuk | keluar | netral
     * @param int|null    $barang_id   ID barang terdampak (jika ada)
     * @param string|null $nama_barang Nama barang terdampak (jika ada)
     * @param float|null  $jumlah      Jumlah stok yang berubah (jika ada)
     * @param string|null $satuan      Satuan jumlah (jika ada)
     */
    public static function catat(
        string $modul,
        string $aksi,
        string $keterangan,
        string $tipe_mutasi = AktivitasGudang::MUTASI_NETRAL,
        ?int $barang_id = null,
        ?string $nama_barang = null,
        ?float $jumlah = null,
        ?string $satuan = null
    ): void {
        $user = Auth::user();

        AktivitasGudang::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name ?? 'Sistem',
            'user_role'  => $user?->role,
            'modul'      => $modul,
            'aksi'       => $aksi,
            'tipe_mutasi'=> $tipe_mutasi,
            'barang_id'  => $barang_id,
            'nama_barang'=> $nama_barang,
            'jumlah'     => $jumlah,
            'satuan'     => $satuan,
            'keterangan' => $keterangan,
            'ip_address' => Request::ip(),
        ]);
    }
}
