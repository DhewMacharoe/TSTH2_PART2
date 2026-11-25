<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\SatuanKonversi;

class StockFormatter
{
    /**
     * Cache konversi lokal per request untuk performa tinggi
     */
    protected static ?array $konversiCache = null;

    /**
     * Format nama satuan agar rapi dan konsisten (misal: "kg" -> "Kg", "botol (1 liter)" -> "Botol (1 Liter)").
     */
    public static function formatSatuanName(?string $satuan): string
    {
        $clean = trim($satuan ?? '');
        if ($clean === '') return 'Unit';

        // Normalisasi spasi di dalam tanda kurung: "botol(1 liter)" -> "Botol (1 Liter)"
        $clean = preg_replace('/\s*\(\s*/', ' (', $clean);
        $clean = preg_replace('/\s*\)\s*/', ')', $clean);

        $lower = strtolower($clean);

        // Satuan singkatan umum
        $specialMap = [
            'kg'  => 'Kg',
            'g'   => 'Gram',
            'mg'  => 'mg',
            'ml'  => 'ml',
            'l'   => 'Liter',
            'dl'  => 'dl',
            'cl'  => 'cl',
            'ul'  => 'µl',
            'cc'  => 'cc',
            'pcs' => 'Pcs',
        ];

        if (isset($specialMap[$lower])) {
            return $specialMap[$lower];
        }

        // Title case untuk kata/frasa
        return ucwords($lower);
    }

    /**
     * Hitung bobot/peringkat relatif dari sebuah satuan untuk menentukan satuan tertinggi.
     * Semakin tinggi angkanya, semakin tinggi satuannya dalam hierarki konversi/kemasan.
     */
    public static function parseUnitRank(string $satuan, ?string $kategori = null): float
    {
        $clean = strtolower(trim($satuan));

        $ranks = [
            // Massa
            'ton'       => 1000000000,
            'kwintal'   => 100000000,
            'kuintal'   => 100000000,
            'sack'      => 50000000,
            'sak'       => 50000000,
            'karung'    => 50000000,
            'kg'        => 1000000,
            'kilo'      => 1000000,
            'kilogram'  => 1000000,
            'ons'       => 100000,
            'hg'        => 100000,
            'gram'      => 1000,
            'g'         => 1000,
            'mg'        => 1,
            'miligram'  => 1,

            // Volume
            'drum'      => 200000000,
            'jerigen'   => 20000000,
            'jerikin'   => 20000000,
            'galon'     => 4000000,
            'liter'     => 1000000,
            'l'         => 1000000,
            'dl'        => 100000,
            'cl'        => 10000,
            'ml'        => 1000,
            'mililiter' => 1000,
            'cc'        => 1000,
            'ul'        => 1,

            // Kemasan / Wadah / Hitungan
            'palet'     => 500000000,
            'dus'       => 10000000,
            'karton'    => 10000000,
            'box'       => 10000000,
            'kotak'     => 10000000,
            'pak'       => 500000,
            'pack'      => 500000,
            'bungkus'   => 500000,
            'rol'       => 200000,
            'roll'      => 200000,
            'botol'     => 100000,
            'vial'      => 50000,
            'ampul'     => 20000,
            'tube'      => 20000,
            'set'       => 10000,
            'unit'      => 5000,
            'buah'      => 5000,
            'batang'    => 2000,
            'polybag'   => 1500,
            'biji'      => 1000,
            'bibit'     => 1000,
            'butir'     => 1000,
            'lembar'    => 1000,
            'pcs'       => 1000,
            'strip'     => 1000,
            'tablet'    => 500,
            'kapsul'    => 500,
        ];

        // 1. Cocok langsung dengan tabel peringkat
        if (isset($ranks[$clean])) {
            return (float) $ranks[$clean];
        }

        // 2. Deteksi kemasan berspesifikasi kapasitas: misal "botol (1 liter)", "botol (1/2 liter)", "botol (500 ml)", "sack (50 kg)"
        if (preg_match('/^([a-z]+)\s*\((.+?)\)$/i', $clean, $matches)) {
            $baseUnit = strtolower(trim($matches[1]));
            $spec     = strtolower(trim($matches[2]));
            $baseRank = $ranks[$baseUnit] ?? 100000;

            if (preg_match('/(\d+(?:\.\d+)?|\d+\/\d+)\s*([a-z]+)/i', $spec, $specMatches)) {
                $rawVal  = $specMatches[1];
                $subUnit = strtolower(trim($specMatches[2]));

                if (str_contains($rawVal, '/')) {
                    $parts = explode('/', $rawVal);
                    $val   = (float)$parts[0] / max(1, (float)$parts[1]);
                } else {
                    $val = (float)$rawVal;
                }

                $subRank = $ranks[$subUnit] ?? 1;
                return (float) ($baseRank + ($val * $subRank));
            }

            return (float) $baseRank;
        }

        // 3. Deteksi awalan kata
        foreach ($ranks as $word => $score) {
            if (str_starts_with($clean, $word)) {
                return (float) $score;
            }
        }

        return 100.0;
    }

    /**
     * Membandingkan dua satuan: mengembalikan 1 jika $satuanA lebih tinggi dari $satuanB,
     * -1 jika $satuanA lebih rendah, dan 0 jika setara.
     */
    public static function compareSatuan(string $satuanA, string $satuanB, ?string $kategori = null): int
    {
        $uA = strtolower(trim($satuanA));
        $uB = strtolower(trim($satuanB));
        if ($uA === $uB) return 0;

        // 1. Cek aturan di tabel SatuanKonversi
        try {
            $multiplier = SatuanKonversi::getMultiplier($kategori, $uA, $uB);
            if ($multiplier !== null && $multiplier > 0) {
                if ($multiplier > 1.0) return 1;  // 1 A = >1 B -> A lebih besar
                if ($multiplier < 1.0) return -1; // 1 A = <1 B -> B lebih besar
            }
        } catch (\Throwable $e) {}

        // 2. Evaluasi berdasarkan peringkat hirarki satuan & kapasitas kemasan
        $rankA = self::parseUnitRank($uA, $kategori);
        $rankB = self::parseUnitRank($uB, $kategori);

        if ($rankA > $rankB) return 1;
        if ($rankA < $rankB) return -1;

        return strcmp($uA, $uB);
    }

    /**
     * Format informasi stok berdasarkan kumpulan unit:
     * Jika hanya 1 satuan: e.g. "500 Bibit"
     * Jika > 1 satuan: e.g. "2 Kilo+" dengan rincian "2 Kilo, 7 Gram"
     */
    public static function formatStockInfo(array $unitStocks, ?string $kategori = null, ?string $fallbackSatuan = 'Unit'): array
    {
        $positive = [];
        foreach ($unitStocks as $satuan => $qty) {
            $satuanClean = trim((string)$satuan);
            if ((float)$qty > 0 && $satuanClean !== '') {
                $positive[$satuanClean] = (float)$qty;
            }
        }

        $formatNumber = function($num) {
            return (float)$num == (int)$num
                ? (string)(int)$num
                : rtrim(rtrim(number_format((float)$num, 2, '.', ''), '0'), '.');
        };

        // Kasus stok kosong / 0
        if (empty($positive)) {
            $fallbackFormatted = self::formatSatuanName($fallbackSatuan ?: 'Unit');
            return [
                'formatted'          => "0 {$fallbackFormatted}",
                'formatted_main'     => "0 {$fallbackFormatted}",
                'has_multiple'       => false,
                'has_positive_stock' => false,
                'highest_unit'       => $fallbackFormatted,
                'highest_stock'      => 0,
                'breakdown'          => "0 {$fallbackFormatted}",
                'grouped'            => []
            ];
        }

        // Urutkan satuan dari tertinggi ke terendah
        $units = array_keys($positive);
        usort($units, function($a, $b) use ($kategori) {
            return self::compareSatuan($b, $a, $kategori); // Urut menurun (descending)
        });

        $highestUnit  = $units[0];
        $highestStock = $positive[$highestUnit];
        $hasMultiple  = count($units) > 1;

        $highestStockFormatted = $formatNumber($highestStock);
        $highestUnitFormatted  = self::formatSatuanName($highestUnit);

        // Buat string rincian lengkap (misal: "2 Kilo, 7 Gram")
        $breakdownParts = [];
        foreach ($units as $u) {
            $breakdownParts[] = $formatNumber($positive[$u]) . ' ' . self::formatSatuanName($u);
        }
        $breakdownStr = implode(', ', $breakdownParts);

        $formattedMain = "{$highestStockFormatted} {$highestUnitFormatted}";
        $formatted     = $hasMultiple ? "{$formattedMain}+" : $formattedMain;

        return [
            'formatted'          => $formatted,
            'formatted_main'     => $formattedMain,
            'has_multiple'       => $hasMultiple,
            'has_positive_stock' => true,
            'highest_unit'       => $highestUnitFormatted,
            'highest_stock'      => $highestStock,
            'breakdown'          => $breakdownStr,
            'grouped'            => $positive
        ];
    }

    /**
     * Format stok untuk model Barang
     */
    public static function formatBarang(Barang $barang): array
    {
        $grouped = [];
        $batches = $barang->relationLoaded('batches') ? $barang->batches : $barang->batches()->get();
        $fallbackSatuan = $barang->satuan_dasar ?: null;

        foreach ($batches as $batch) {
            $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);

            if (!empty($details)) {
                foreach ($details as $item) {
                    $stok   = (float) ($item['stok'] ?? 0);
                    $satuan = trim($item['satuan'] ?? '');
                    if ($stok > 0 && $satuan !== '') {
                        $key = strtolower($satuan);
                        $grouped[$key] = ($grouped[$key] ?? 0) + $stok;
                    }
                    if (!$fallbackSatuan && $satuan !== '') {
                        $fallbackSatuan = $satuan;
                    }
                }
            } else {
                $stok   = (float) ($batch->stok ?? 0);
                $satuan = trim($batch->satuan ?? '');
                if ($stok > 0 && $satuan !== '') {
                    $key = strtolower($satuan);
                    $grouped[$key] = ($grouped[$key] ?? 0) + $stok;
                }
                if (!$fallbackSatuan && $satuan !== '') {
                    $fallbackSatuan = $satuan;
                }
            }
        }

        return self::formatStockInfo($grouped, $barang->kategori, $fallbackSatuan ?: 'Unit');
    }

    /**
     * Format stok untuk model BarangBatch
     */
    public static function formatBatch(BarangBatch $batch): array
    {
        $grouped = [];
        $details = is_array($batch->stok_detail) ? $batch->stok_detail : json_decode($batch->stok_detail ?? '[]', true);
        $fallbackSatuan = $batch->satuan ?: ($batch->barang?->satuan_dasar ?: 'Unit');

        if (!empty($details)) {
            foreach ($details as $item) {
                $stok   = (float) ($item['stok'] ?? 0);
                $satuan = trim($item['satuan'] ?? '');
                if ($stok > 0 && $satuan !== '') {
                    $key = strtolower($satuan);
                    $grouped[$key] = ($grouped[$key] ?? 0) + $stok;
                }
                if (!$fallbackSatuan && $satuan !== '') {
                    $fallbackSatuan = $satuan;
                }
            }
        } else {
            $stok = (float) ($batch->stok ?? 0);
            if ($stok > 0 && $batch->satuan) {
                $key = strtolower(trim($batch->satuan));
                $grouped[$key] = $stok;
            }
        }

        $kategori = $batch->barang?->kategori ?? null;
        return self::formatStockInfo($grouped, $kategori, $fallbackSatuan);
    }
}

