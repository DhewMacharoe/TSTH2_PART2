<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $guarded = [];

    // Relasi
    public function batches()
    {
        return $this->hasMany(BarangBatch::class);
    }

    public function permintaan()
    {
        return $this->hasMany(PermintaanBarang::class);
    }

    /**
     * Total stok tersedia (bisa disesuaikan dengan logic BarangBatchDetail jika Anda memecahnya)
     */
    public function getAvailableTotalStok()
    {
        return $this->batches()->where(function ($q) {
            $q->whereNull('tanggal_kadaluarsa')
              ->orWhere('tanggal_kadaluarsa', '>', now());
        })->sum('stok');
    }
}