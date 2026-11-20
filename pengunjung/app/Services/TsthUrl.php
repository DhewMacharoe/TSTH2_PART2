<?php

namespace App\Services;

class TsthUrl
{
    /**
     * Cek apakah saat ini berjalan di domain produksi (tsth2.id)
     */
    public static function isProduction(): bool
    {
        $host = request()->getHost();
        return str_contains($host, 'tsth2.id');
    }

    /**
     * URL Portal Pengunjung / Hub Utama (Port 8000 / https://tsth2.id)
     */
    public static function pengunjung(string $path = ''): string
    {
        $default = self::isProduction() ? 'https://tsth2.id' : 'http://localhost:8000';
        $base = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', $default)), '/');
        if (self::isProduction() && (str_contains($base, 'localhost') || str_contains($base, '127.0.0.1'))) {
            $base = 'https://tsth2.id';
        }
        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }

    /**
     * URL Logbook Karyawan & Manajemen Target (Port 8001 / https://logbook.tsth2.id)
     */
    public static function logbook(string $path = ''): string
    {
        $default = self::isProduction() ? 'https://logbook.tsth2.id' : 'http://localhost:8001';
        $base = rtrim(config('app.url_logbook', env('URL_LOGBOOK', $default)), '/');
        if (self::isProduction() && (str_contains($base, 'localhost') || str_contains($base, '127.0.0.1'))) {
            $base = 'https://logbook.tsth2.id';
        }
        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }

    /**
     * URL Sistem Gudang & Inventaris (Port 8002 / https://gudang.tsth2.id)
     */
    public static function gudang(string $path = ''): string
    {
        $default = self::isProduction() ? 'https://gudang.tsth2.id' : 'http://localhost:8002';
        $base = rtrim(config('app.url_gudang', env('URL_GUDANG', $default)), '/');
        if (self::isProduction() && (str_contains($base, 'localhost') || str_contains($base, '127.0.0.1'))) {
            $base = 'https://gudang.tsth2.id';
        }
        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }

    /**
     * URL Katalog Tanaman Flora (Port 8003 / https://katalog.tsth2.id)
     */
    public static function katalog(string $path = ''): string
    {
        $default = self::isProduction() ? 'https://katalog.tsth2.id' : 'http://localhost:8003';
        $base = rtrim(config('app.url_katalog', env('URL_KATALOG', $default)), '/');
        if (self::isProduction() && (str_contains($base, 'localhost') || str_contains($base, '127.0.0.1'))) {
            $base = 'https://katalog.tsth2.id';
        }
        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }

    /**
     * Domain Cookie yang aman (lintas port di dev, lintas subdomain di prod)
     */
    public static function cookieDomain(): ?string
    {
        $host = request()->getHost();
        if ($host === 'localhost' || $host === '127.0.0.1') {
            return null; // Pada localhost, domain null mencakup semua port
        }
        if (str_contains($host, 'tsth2.id')) {
            return '.tsth2.id'; // Mencakup tsth2.id, logbook.tsth2.id, gudang.tsth2.id, katalog.tsth2.id
        }
        return '.' . $host;
    }
}

