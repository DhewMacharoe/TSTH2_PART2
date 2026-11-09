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
}
