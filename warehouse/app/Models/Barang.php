<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $guarded = [];

    const TIPE_LAPANGAN = 'lapangan';
    const TIPE_LAB      = 'lab';

    protected static function booted()
    {
        static::creating(function ($barang) {
            if (empty($barang->kode_barang)) {
                $barang->kode_barang = self::generateKodeBarang($barang->kategori, $barang->lokasi_blok);
            }
        });
    }

    /**
     * Map nama kategori ke digit angka:
     * Pupuk = 1, Bahan Kimia = 2, Alat Budidaya = 3, Bibit Tanaman = 4, Lainnya = 5
     */
    public static function getKategoriCode(?string $kategori): int
    {
        $map = [
            'pupuk'         => 1,
            'bahan kimia'   => 2,
            'alat budidaya' => 3,
            'bibit tanaman' => 4,
            'bibit'         => 4,
            'lainnya'       => 5,
        ];
        $clean = strtolower(trim($kategori ?? ''));
        foreach ($map as $key => $val) {
            if (str_contains($clean, $key)) return $val;
        }
        return 5;
    }

    /**
     * Parse lokasi blok dan rak menjadi digit angka.
     * Contoh: "Blok 1 Rak 1" -> blok: 1, rak: 1
     *         "Blok A - Rak 2" -> A=1, rak=2
     */
    public static function parseLokasiCode(?string $lokasi): array
    {
        $str = $lokasi ?? '';
        $blok = 1;
        $rak  = 1;

        // Cek Blok: Blok 1, Blok A, dll.
        if (preg_match('/(?:blok|block|b)\s*([0-9]+)/i', $str, $m)) {
            $blok = (int) $m[1];
        } elseif (preg_match('/(?:blok|block|b)\s*([a-z])/i', $str, $m)) {
            $blok = ord(strtoupper($m[1])) - ord('A') + 1;
        }

        // Cek Rak: Rak 1, Rak 01, R1, dll.
        if (preg_match('/(?:rak|rack|r)\s*([0-9]+)/i', $str, $m)) {
            $rak = (int) $m[1];
        } elseif (preg_match('/(?:rak|rack|r)\s*([a-z])/i', $str, $m)) {
            $rak = ord(strtoupper($m[1])) - ord('A') + 1;
        }

        return [
            'blok' => $blok > 0 ? $blok : 1,
            'rak'  => $rak > 0 ? $rak : 1,
        ];
    }

    /**
     * Hitung urutan barang keberapa dalam kategori tersebut
     */
    public static function getItemSequenceInKategori(?string $kategori, ?int $barangId = null): int
    {
        $query = static::where('kategori', $kategori);
        if ($barangId) {
            $pos = $query->where('id', '<=', $barangId)->count();
            return max(1, $pos);
        }
        return $query->count() + 1;
    }

    /**
     * Generate format kode barang sederhana & terstruktur:
     * [Kategori][Blok][Rak][Barang ke-n di kategori]
     * Contoh: Pupuk (1), Blok 1 (1), Rak 1 (1), NPK barang pertama (1) => 1111
     */
    public static function generateKodeBarang($kategori = null, $lokasi = null, $barangId = null): string
    {
        $catCode  = self::getKategoriCode($kategori);
        $lokCodes = self::parseLokasiCode($lokasi);
        $blokCode = $lokCodes['blok'];
        $rakCode  = $lokCodes['rak'];
        $itemSeq  = self::getItemSequenceInKategori($kategori, $barangId);

        $baseCode = sprintf('%d%d%d%d', $catCode, $blokCode, $rakCode, $itemSeq);

        // Pastikan kode unik, jika bentrok tambahkan urutan
        $candidate = $baseCode;
        $offset = 1;
        while (static::where('kode_barang', $candidate)->when($barangId, fn($q) => $q->where('id', '!=', $barangId))->exists()) {
            $candidate = sprintf('%d%d%d%d', $catCode, $blokCode, $rakCode, $itemSeq + $offset);
            $offset++;
        }

        return $candidate;
    }

    /**
     * Mendapatkan kode lengkap barang beserta batch
     * Contoh: 1111-1
     */
    public function getFullKodeBatch(int|string $batchNo = 1): string
    {
        $cleanNo = (int) ltrim((string) $batchNo, 'B');
        return ($this->kode_barang ?? '0000') . '-' . max(1, $cleanNo);
    }

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function aktivitas()
    {
        return $this->hasMany(AktivitasGudang::class, 'barang_id')->orderBy('created_at', 'desc');
    }

    public function batches()
    {
        return $this->hasMany(BarangBatch::class);
    }

    public function permintaan()
    {
        return $this->hasMany(PermintaanBarang::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────
    public function scopeLapangan($query)
    {
        return $query->where('jenis_barang', 'lapangan');
    }

    public function scopeLab($query)
    {
        return $query->where('jenis_barang', 'lab');
    }

    public function getJenisLabelAttribute(): string
    {
        return $this->jenis_barang === 'lab' ? 'Barang Lab' : 'Barang Lapangan';
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