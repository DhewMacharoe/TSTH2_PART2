<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HandlesBatchStok;

class BarangBatch extends Model
{
    use HasFactory, HandlesBatchStok;

    protected $guarded = [];

    protected $casts = [
        'tanggal_kedatangan' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'stok_detail'        => 'array',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCleanBatchNoAttribute(): int
    {
        $clean = (int) ltrim((string)($this->batch_no ?? '1'), 'B');
        return max(1, $clean);
    }

    public function getFullKodeAttribute(): string
    {
        $base = $this->barang ? $this->barang->kode_barang : '0000';
        return $base . '-' . $this->clean_batch_no;
    }

    /**
     * Generate batch_no berikutnya untuk sebuah barang (misal: 001, 002).
     */
    public static function nextBatchNo(int $barangId): string
    {
        $lastNo = static::where('barang_id', $barangId)
            ->whereNotNull('batch_no')
            ->orderByRaw("CAST(batch_no AS INTEGER) DESC")
            ->value('batch_no');

        if (!$lastNo) {
            return '001';
        }
        
        $cleanNo = ltrim($lastNo, 'B');
        $num     = (int) $cleanNo;
        
        return str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Helper untuk menghapus batch jika stok habis dan mengurutkan ulang batch lainnya.
     */
    public function checkEmptyAndDelete()
    {
        if ($this->stok <= 0) {
            $barangId = $this->barang_id;
            $this->delete();
            
            $batches = static::where('barang_id', $barangId)->orderBy('created_at', 'asc')->get();
            $counter = 1;
            foreach ($batches as $b) {
                $b->batch_no = str_pad($counter, 3, '0', STR_PAD_LEFT);
                $b->save();
                $counter++;
            }
        }
    }
}
