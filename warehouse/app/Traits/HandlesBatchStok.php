<?php

namespace App\Traits;

use App\Models\SatuanBarang;
use App\Models\SatuanKonversi;
use Carbon\Carbon;

trait HandlesBatchStok
{
    /**
     * Helper untuk mengambil stok berdasarkan satuan dari stok_detail
     */
    public function getStokBySatuan($satuan)
    {
        $details = is_array($this->stok_detail) ? $this->stok_detail : json_decode($this->stok_detail ?? '[]', true);
        if (empty($details)) {
            return ($this->satuan === $satuan) ? $this->stok : 0;
        }

        $total = 0;
        foreach ($details as $item) {
            if (isset($item['satuan']) && strtolower($item['satuan']) === strtolower($satuan)) {
                $total += $item['stok'] ?? 0;
            }
        }
        return $total;
    }

    /**
     * Mengambil stok yang BISA DIREQUEST (tidak expired & tidak rusak)
     */
    public function getAvailableStokBySatuan($satuan)
    {
        if ($this->tanggal_kadaluarsa && Carbon::parse($this->tanggal_kadaluarsa)->endOfDay()->isPast()) {
            return 0;
        }

        $details = is_array($this->stok_detail) ? $this->stok_detail : json_decode($this->stok_detail ?? '[]', true);
        if (empty($details)) {
            if (SatuanBarang::kondisiPriority($this->kondisi) === 99) return 0;
            
            $multiplier = SatuanKonversi::getMultiplier($this->barang->kategori ?? null, $this->satuan, $satuan);
            return $multiplier !== null ? floor($this->stok * $multiplier) : 0;
        }

        $total = 0;
        foreach ($details as $item) {
            $itemSatuan = $item['satuan'] ?? '';
            $multiplier = SatuanKonversi::getMultiplier($this->barang->kategori ?? null, $itemSatuan, $satuan);
            
            if ($multiplier !== null && SatuanBarang::kondisiPriority($item['kondisi'] ?? '') !== 99) {
                $total += ($item['stok'] ?? 0) * $multiplier;
            }
        }
        return floor($total);
    }

    /**
     * Helper untuk mengurangi stok (hanya berdasarkan satuan, prioritas kondisi FIFO)
     */
    public function decrementStokBySatuan($satuan, $jumlah)
    {
        $details = is_array($this->stok_detail) ? $this->stok_detail : json_decode($this->stok_detail ?? '[]', true);
        if (empty($details)) {
            if (strtolower($this->satuan) === strtolower($satuan)) {
                if (SatuanBarang::kondisiPriority($this->kondisi ?? '') === 99) return;
                $this->stok = max(0, $this->stok - $jumlah);
                $this->save();
            }
            return;
        }

        $sisaKurang = (float) $jumlah;
        
        usort($details, function($a, $b) {
            return SatuanBarang::kondisiPriority($a['kondisi'] ?? '') <=> SatuanBarang::kondisiPriority($b['kondisi'] ?? '');
        });

        $newDetails = [];
        foreach ($details as &$item) {
            if ($sisaKurang <= 0) break;
            if (SatuanBarang::kondisiPriority($item['kondisi'] ?? '') === 99) continue;
            
            $itemSatuan = $item['satuan'] ?? '';
            $itemStok   = (float) ($item['stok'] ?? 0);
            if ($itemStok <= 0) continue;
            
            $multiplier = SatuanKonversi::getMultiplier($this->barang->kategori ?? null, $itemSatuan, $satuan);
            if ($multiplier !== null && $multiplier > 0) {
                $availableInRequestedUnit = $itemStok * $multiplier;
                
                if ($availableInRequestedUnit <= $sisaKurang) {
                    $sisaKurang -= $availableInRequestedUnit;
                    $item['stok'] = 0;
                } else {
                    $neededInOriginalUnit = $sisaKurang / $multiplier;
                    
                    if ($multiplier > 1) {
                        // Dipecah dari satuan besar ke satuan kecil (misal: 1 sack [25 kg] -> dikurangi 5 kg)
                        $packagesToBreak = (int) ceil($neededInOriginalUnit);
                        $item['stok'] = max(0, round($item['stok'] - $packagesToBreak, 4));
                        
                        $totalGeneratedInRequestedUnit = $packagesToBreak * $multiplier;
                        $takenInRequestedUnit          = min($totalGeneratedInRequestedUnit, $sisaKurang);
                        $remainderInRequestedUnit      = $totalGeneratedInRequestedUnit - $takenInRequestedUnit;
                        
                        if ($remainderInRequestedUnit > 0) {
                            $newDetails[] = [
                                'satuan'  => strtolower($satuan),
                                'kondisi' => 'Sudah Terbuka',
                                'stok'    => $remainderInRequestedUnit
                            ];
                        }
                    } else {
                        // Satuan sama (multiplier == 1) atau satuan asal lebih kecil
                        $item['stok'] = max(0, round($item['stok'] - $neededInOriginalUnit, 4));
                    }
                    $sisaKurang = 0;
                }
            }
        }
        
        foreach ($newDetails as $newIt) {
            $details[] = $newIt;
        }

        // Hapus item detail yang stoknya <= 0
        $details = array_values(array_filter($details, function($item) {
            return ($item['stok'] ?? 0) > 0;
        }));

        $this->stok_detail = $details;
        $this->stok        = collect($details)->sum('stok');
        $this->save();
        $this->checkEmptyAndDelete();
    }

    /**
     * Helper untuk menambah stok berdasarkan satuan dan kondisi
     */
    public function incrementStokBySatuan($satuan, $kondisi, $jumlah)
    {
        $details = is_array($this->stok_detail) ? $this->stok_detail : json_decode($this->stok_detail ?? '[]', true);
        if (empty($details)) {
            $details = [];
            if ($this->stok > 0) {
                $details[] = [
                    'kondisi' => $this->kondisi ?? '-',
                    'satuan'  => $this->satuan ?? '-',
                    'stok'    => $this->stok
                ];
            }
        }

        $found = false;
        foreach ($details as &$item) {
            if (isset($item['satuan'], $item['kondisi']) && 
                strtolower($item['satuan']) === strtolower($satuan) && 
                strtolower($item['kondisi']) === strtolower($kondisi)) {
                $item['stok'] = ($item['stok'] ?? 0) + $jumlah;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $details[] = [
                'kondisi' => $kondisi,
                'satuan'  => $satuan,
                'stok'    => $jumlah
            ];
        }

        $this->stok_detail = $details;
        $this->stok        = collect($details)->sum('stok');
        $this->save();
    }
}
