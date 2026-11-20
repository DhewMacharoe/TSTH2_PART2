<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeCodeService
{
    /*
    |--------------------------------------------------------------------------
    | PEMETAAN KODE DEPARTEMEN
    |--------------------------------------------------------------------------
    | Format DDD: 2 angka + 1 huruf  (contoh: 01G, 02R)
    | Kunci = nama departemen (lowercase, trimmed)
    */
    private const DEPARTMENT_CODES = [
        'general affair'          => '01G',
        'riset dan inovasi'       => '02R',
        'riset dan inovasi tsth2' => '02R',
        'hilirisasi riset'        => '02R',
    ];

    /*
    |--------------------------------------------------------------------------
    | PEMETAAN KODE DIVISI
    |--------------------------------------------------------------------------
    | Format BB: 2 huruf  (contoh: AD, OP, GE, HI, GS)
    | Kunci = nama divisi (lowercase, trimmed)
    */
    private const DIVISION_CODES = [
        'administrasi' => 'AD',
        'operasional'  => 'OP',
        'genomic'      => 'GE',
        'hilirisasi'   => 'HI',
        'gh/sh'        => 'GS',
        'gh / sh'      => 'GS',
        'gh-sh'        => 'GS',
        'gh_sh'        => 'GS',
        'gsh'          => 'GS',
    ];

    /*
    |--------------------------------------------------------------------------
    | KODE JABATAN KHUSUS
    |--------------------------------------------------------------------------
    | KD = Kepala Departemen (digunakan sebagai BB karena tidak memiliki divisi)
    */
    private const KEPALA_DEPARTEMEN_CODE = 'KD';

    /**
     * Generate kode unik untuk Kepala Departemen: DDDKDYYNNN
     *
     * @param  string  $departmentName  Nama departemen
     * @param  int     $joinYear        Tahun masuk (4 digit)
     * @return string                   Mis. "01GKD26001"
     *
     * @throws \RuntimeException jika departemen tidak dikenali
     */
    public function generateForKepalaDepartemen(
        string $departmentName,
        int $joinYear
    ): string {
        $ddd = $this->departmentCode($departmentName);
        $bb  = self::KEPALA_DEPARTEMEN_CODE;
        $yy  = substr((string) $joinYear, -2);
        $nnn = $this->nextSequence($ddd, $bb, $yy);

        return $ddd . $bb . $yy . $nnn;
    }

    /**
     * Generate kode unik untuk Kepala Divisi & Employee: DDDBBYYNNN
     *
     * Nomor urut (NNN) bersifat SHARED dalam satu kombinasi DDD+BB+YY,
     * artinya Kepala Divisi dan Employee pada divisi & tahun yang sama
     * berbagi satu urutan agar tidak ada duplikasi.
     *
     * @param  string  $departmentName  Nama departemen
     * @param  string  $divisionName    Nama divisi
     * @param  int     $joinYear        Tahun masuk (4 digit)
     * @return string                   Mis. "01GAD26001"
     *
     * @throws \RuntimeException jika departemen / divisi tidak dikenali
     */
    public function generateForDivisionMember(
        string $departmentName,
        string $divisionName,
        int $joinYear
    ): string {
        $ddd = $this->departmentCode($departmentName);
        $bb  = $this->divisionCode($divisionName);
        $yy  = substr((string) $joinYear, -2);
        $nnn = $this->nextSequence($ddd, $bb, $yy);

        return $ddd . $bb . $yy . $nnn;
    }

    /*
    |--------------------------------------------------------------------------
    | INTERNAL HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Kembalikan kode DDD berdasarkan nama departemen.
     *
     * @throws \RuntimeException
     */
    private function departmentCode(string $name): string
    {
        $key = strtolower(trim($name));

        if (isset(self::DEPARTMENT_CODES[$key])) {
            return self::DEPARTMENT_CODES[$key];
        }

        // Fallback: partial match
        foreach (self::DEPARTMENT_CODES as $pattern => $code) {
            if (str_contains($key, $pattern) || str_contains($pattern, $key)) {
                return $code;
            }
        }

        throw new \RuntimeException(
            "Kode departemen tidak ditemukan untuk: \"{$name}\". " .
            "Daftarkan nama departemen ini di EmployeeCodeService::DEPARTMENT_CODES."
        );
    }

    /**
     * Kembalikan kode BB berdasarkan nama divisi.
     *
     * @throws \RuntimeException
     */
    private function divisionCode(string $name): string
    {
        $key = strtolower(trim($name));

        if (isset(self::DIVISION_CODES[$key])) {
            return self::DIVISION_CODES[$key];
        }

        // Fallback: partial match
        foreach (self::DIVISION_CODES as $pattern => $code) {
            if (str_contains($key, $pattern) || str_contains($pattern, $key)) {
                return $code;
            }
        }

        throw new \RuntimeException(
            "Kode divisi tidak ditemukan untuk: \"{$name}\". " .
            "Daftarkan nama divisi ini di EmployeeCodeService::DIVISION_CODES."
        );
    }

    /**
     * Tentukan nomor urut (NNN) berikutnya untuk kombinasi DDD+BB+YY.
     *
     * Menggunakan SELECT ... FOR UPDATE di dalam transaksi database agar
     * dua proses yang berjalan bersamaan tidak menghasilkan nomor yang sama.
     * Pemanggil (UserController) HARUS sudah membuka transaksi terlebih dahulu.
     *
     * Format prefix tetap 7 karakter:
     *   DDD(3) + BB(2) + YY(2) = 7 karakter
     * 3 karakter terakhir dari employee_code adalah NNN.
     *
     * @return string  3-digit zero-padded, mis. "001", "002", ...
     */
    public function nextSequence(string $ddd, string $bb, string $yy): string
    {
        $prefix = $ddd . $bb . $yy;  // mis. "01GAD26" atau "01GKD26"

        /*
         * Lock baris yang cocok agar tidak ada race condition.
         * Jika belum ada data untuk prefix ini, query mengembalikan null.
         */
        $last = User::where('employee_code', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByRaw('LENGTH(employee_code) DESC, employee_code DESC')
            ->value('employee_code');

        if ($last === null) {
            return '001';
        }

        $lastSequence = (int) substr($last, strlen($prefix));

        return str_pad((string) ($lastSequence + 1), 3, '0', STR_PAD_LEFT);
    }
}
