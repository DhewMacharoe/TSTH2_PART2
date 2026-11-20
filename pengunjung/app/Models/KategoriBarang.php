<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barangs';

    protected $fillable = [
        'nama_kategori',
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
     * Hitung berapa banyak barang fisik yang memakai kategori ini
     */
    public function getBarangsCountAttribute(): int
    {
        return Barang::where('kategori', $this->nama_kategori)
            ->where('jenis_barang', $this->jenis_barang)
            ->count();
    }

    /**
     * Hitung berapa banyak satuan yang terdaftar di kategori ini
     */
    public function getSatuansCountAttribute(): int
    {
        return SatuanBarang::where('kategori', $this->nama_kategori)
            ->where('jenis_barang', $this->jenis_barang)
            ->count();
    }
}

