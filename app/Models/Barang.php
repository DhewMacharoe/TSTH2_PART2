<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $guarded = [];

    public function batches()
    {
        return $this->hasMany(BarangBatch::class);
    }

    public function permintaan()
    {
        return $this->hasMany(PermintaanBarang::class);
    }
}
