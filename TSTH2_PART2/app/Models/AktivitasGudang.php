<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasGudang extends Model
{
    protected $guarded = [];

    const MUTASI_MASUK   = 'masuk';
    const MUTASI_KELUAR  = 'keluar';
    const MUTASI_NETRAL  = 'netral';

    const MODUL_BARANG     = 'barang';
    const MODUL_BATCH      = 'batch';
    const MODUL_SATUAN     = 'satuan';
    const MODUL_KONVERSI   = 'konversi';
    const MODUL_PERMINTAAN = 'permintaan';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function getAksiLabelAttribute(): string
    {
        return match ($this->aksi) {
            'tambah'            => 'Tambah Data',
            'edit'              => 'Ubah Data',
            'hapus'             => 'Hapus Data',
            'hapus_batch'       => 'Hapus Batch',
            'restock'           => 'Restock / Batch Baru',
            'ajukan'            => 'Ajukan Permintaan',
            'approve'           => 'Disetujui (Approval)',
            'tolak', 'reject'   => 'Ditolak',
            'eskalasi'          => 'Eskalasi Otomatis',
            'pengambilan'       => 'Serah Terima / Pengambilan',
            'reschedule'        => 'Jadwal Ulang',
            'batalkan'          => 'Dibatalkan',
            'lapor_kembali'     => 'Lapor Pengembalian',
            'verifikasi_kembali'=> 'Verifikasi Pengembalian',
            'selesai'           => 'Permintaan Selesai',
            default             => ucfirst(str_replace('_', ' ', $this->aksi ?? '-')),
        };
    }

    public function getPelakuLabelAttribute(): string
    {
        $role = $this->user_role ? match ($this->user_role) {
            'kepala_divisi'     => 'Kepala Divisi Operasional',
            'kepala_departemen' => 'Kepala Departemen General Affair',
            'pic_gudang'        => 'PIC Gudang',
            'web_admin'         => 'Web Admin',
            'pimpinan'          => 'Pimpinan',
            'agronom'           => 'Agronom',
            default             => ucfirst($this->user_role),
        } : null;

        return $role ? "{$this->user_name} ({$role})" : $this->user_name;
    }
}
