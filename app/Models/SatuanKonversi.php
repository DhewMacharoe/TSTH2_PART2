<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanKonversi extends Model
{
    protected $fillable = ['kategori', 'jenis_barang', 'dari_satuan', 'ke_satuan', 'multiplier'];

    const TIPE_LAPANGAN = 'lapangan';
    const TIPE_LAB      = 'lab';

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
        return $this->jenis_barang === self::TIPE_LAB ? 'Barang Lab' : 'Barang Lapangan';
    }

    /**
     * Get the multiplier to convert $fromSatuan TO $toSatuan for a specific category.
     * Returns 1 if no conversion is needed (same unit).
     * Returns null if no conversion rule exists between them.
     */
    public static function getMultiplier($kategori, $fromSatuan, $toSatuan)
    {
        $fromSatuan = strtolower(trim($fromSatuan));
        $toSatuan = strtolower(trim($toSatuan));
        
        if ($fromSatuan === $toSatuan) {
            return 1;
        }

        // Direct conversion: from -> to
        $rule = self::where('kategori', $kategori)
            ->where('dari_satuan', $fromSatuan)
            ->where('ke_satuan', $toSatuan)
            ->first();
        if ($rule) {
            return (float) $rule->multiplier;
        }

        // Reverse conversion: to -> from
        $ruleRev = self::where('kategori', $kategori)
            ->where('dari_satuan', $toSatuan)
            ->where('ke_satuan', $fromSatuan)
            ->first();
        if ($ruleRev && $ruleRev->multiplier > 0) {
            return 1 / (float) $ruleRev->multiplier;
        }

        // Fallback for old regex method if no rule in DB
        // Format: 'sack (50kg)' or 'sack(50kg)'
        if (preg_match('/\((\d+)\s*([a-zA-Z]+)\)/', $fromSatuan, $matches)) {
            $amount = (float) $matches[1];
            $unit = strtolower(trim($matches[2]));
            if ($unit === $toSatuan) {
                return $amount;
            }
        }
        
        if (preg_match('/\((\d+)\s*([a-zA-Z]+)\)/', $toSatuan, $matches)) {
            $amount = (float) $matches[1];
            $unit = strtolower(trim($matches[2]));
            if ($unit === $fromSatuan && $amount > 0) {
                return 1 / $amount;
            }
        }

        return null; // Cannot convert
    }
}
