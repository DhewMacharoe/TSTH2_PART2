<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanBarang extends Model
{
    protected $table = 'satuan_barangs';

    protected $fillable = ['nama_satuan', 'kategori', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Filter satuan berdasarkan kategori barang.
     * Jika kategori=null, kembalikan semua.
     * Satuan dengan kategori=null berlaku untuk semua kategori.
     */
    public function scopeForKategori($query, ?string $kategori)
    {
        if (!$kategori) {
            return $query->whereNull('kategori'); // Return only global if no category selected
        }
        return $query->where('kategori', $kategori);
    }

    /**
     * Mapping kondisi per kategori barang.
     * Digunakan oleh JS dan controller.
     */
    public static function kondisiByKategori(?string $kategori): array
    {
        $map = [
            'Pupuk' => [
                ['value' => 'Tersegel/Baru',       'label' => 'Tersegel / Baru',         'priority' => 3],
                ['value' => 'Sudah Terbuka',        'label' => 'Sudah Terbuka',           'priority' => 1],
                ['value' => 'Sisa Sedikit',         'label' => 'Sisa Sedikit',            'priority' => 1],
                ['value' => 'Rusak/Menggumpal',     'label' => 'Rusak / Menggumpal',      'priority' => 99],
            ],
            'Bahan Kimia' => [
                ['value' => 'Tersegel/Baru',        'label' => 'Tersegel / Baru',         'priority' => 3],
                ['value' => 'Sudah Terbuka',        'label' => 'Sudah Terbuka',           'priority' => 1],
                ['value' => 'Hampir Habis',         'label' => 'Hampir Habis',            'priority' => 1],
                ['value' => 'Kedaluwarsa',          'label' => 'Kedaluwarsa',             'priority' => 99],
            ],
            'Alat Budidaya' => [
                ['value' => 'Baru',                 'label' => 'Baru',                   'priority' => 3],
                ['value' => 'Bekas Pakai - Bagus',  'label' => 'Bekas Pakai — Bagus',    'priority' => 2],
                ['value' => 'Bekas Pakai - Perlu Perbaikan', 'label' => 'Bekas Pakai — Perlu Perbaikan', 'priority' => 1],
                ['value' => 'Rusak',                'label' => 'Rusak',                  'priority' => 99],
            ],
            'Bibit Tanaman' => [
                ['value' => 'Segar/Baru',           'label' => 'Segar / Baru',           'priority' => 3],
                ['value' => 'Sudah Terbuka',        'label' => 'Sudah Terbuka',          'priority' => 1],
                ['value' => 'Rusak',                'label' => 'Rusak',                  'priority' => 99],
            ],
            'Lainnya' => [
                ['value' => 'Baru',                 'label' => 'Baru',                   'priority' => 3],
                ['value' => 'Bagus',                'label' => 'Bagus',                  'priority' => 2],
                ['value' => 'Sudah Dipakai',        'label' => 'Sudah Dipakai',          'priority' => 1],
                ['value' => 'Rusak',                'label' => 'Rusak',                  'priority' => 99],
            ],
        ];

        if ($kategori && isset($map[$kategori])) {
            return $map[$kategori];
        }

        // Default: semua nilai kondisi
        return [
            ['value' => 'Baru',                       'label' => 'Baru',                       'priority' => 3],
            ['value' => 'Tersegel/Baru',               'label' => 'Tersegel / Baru',             'priority' => 3],
            ['value' => 'Bagus',                       'label' => 'Bagus',                       'priority' => 2],
            ['value' => 'Bekas Pakai - Bagus',         'label' => 'Bekas Pakai — Bagus',         'priority' => 2],
            ['value' => 'Sudah Terbuka',               'label' => 'Sudah Terbuka',               'priority' => 1],
            ['value' => 'Sisa Sedikit',                'label' => 'Sisa Sedikit',                'priority' => 1],
            ['value' => 'Hampir Habis',                'label' => 'Hampir Habis',                'priority' => 1],
            ['value' => 'Bekas Pakai - Perlu Perbaikan','label'=> 'Bekas Pakai — Perlu Perbaikan','priority'=> 1],
            ['value' => 'Rusak',                       'label' => 'Rusak',                       'priority' => 99],
            ['value' => 'Kedaluwarsa',                 'label' => 'Kedaluwarsa',                 'priority' => 99],
        ];
    }

    /**
     * Priority FIFO untuk kondisi (semakin kecil = diambil duluan).
     * Priority 99 = tidak boleh diambil (rusak/kedaluwarsa).
     */
    public static function kondisiPriority(string $kondisi): int
    {
        $priorities = [
            // Priority 1: harus diambil duluan
            'Sudah Terbuka'               => 1,
            'Sisa Sedikit'                => 1,
            'Hampir Habis'                => 1,
            'Bekas Pakai - Perlu Perbaikan' => 1,
            'Sudah Terbuka - Separuh'     => 1,
            'Hampir Kadaluarsa'           => 1,
            'Hampir Kedaluwarsa'          => 1,
            // Priority 2: boleh diambil, tapi tersegel lebih baik disimpan
            'Bekas Pakai - Bagus'         => 2,
            'Sudah Dipakai'               => 2,
            // Priority 3: tersegel/baru — diambil paling akhir
            'Tersegel/Baru'               => 3,
            'Segar/Baru'                  => 3,
            'Baru'                        => 3,
            'Bagus'                       => 3,
            'Belum Terbuka'               => 3,
            // Priority 99: jangan diambil
            'Rusak'                       => 99,
            'Rusak/Menggumpal'            => 99,
            'Kedaluwarsa'                 => 99,
            'Kadaluarsa'                  => 99,
        ];
        return $priorities[$kondisi] ?? 2;
    }
}
