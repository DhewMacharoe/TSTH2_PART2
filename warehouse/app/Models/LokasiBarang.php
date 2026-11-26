<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiBarang extends Model
{
    protected $table = 'lokasi_barangs';

    protected $fillable = [
        'nama_lokasi',
        'jenis_barang',
        'deskripsi',
        'created_by',
    ];

    const TIPE_LAPANGAN = 'lapangan';
    const TIPE_LAB      = 'lab';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeLapangan($query)
    {
        return $query->where('jenis_barang', self::TIPE_LAPANGAN);
    }

    public function scopeLab($query)
    {
        return $query->where('jenis_barang', self::TIPE_LAB);
    }

    public function getJenisLabelAttribute(): string
    {
        return $this->jenis_barang === self::TIPE_LAB
            ? 'Warehouse Lab (RdI)'
            : 'Warehouse Gudang (GA)';
    }

    /**
     * Hitung berapa banyak barang fisik yang memakai lokasi ini
     */
    public function getBarangsCountAttribute(): int
    {
        return Barang::where('lokasi_blok', $this->nama_lokasi)
            ->where('jenis_barang', $this->jenis_barang)
            ->count();
    }
}

