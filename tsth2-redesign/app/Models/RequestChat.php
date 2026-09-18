<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestChat extends Model
{
    use HasFactory;

    protected $fillable = ['permintaan_barang_id', 'user_id', 'pesan'];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
