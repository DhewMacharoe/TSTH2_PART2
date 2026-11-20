<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class LogbookImportSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = database_path('seeders/logbook_dump.sql');

        if (!File::exists($sqlPath)) {
            $this->command->error("File logbook_dump.sql tidak ditemukan di {$sqlPath}");
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Eksekusi dump SQL
        DB::unprepared(File::get($sqlPath));

        // 1. Reset password seluruh user asli dari dump ke 'password'
        DB::table('users')->update([
            'password' => Hash::make('password'),
        ]);

        // 2. Tambahkan akun testing default agar kompatibel dengan akun portal
        $pw  = Hash::make('password');
        $now = now();
        $defaultUsers = [
            ['id' => 101, 'name' => 'Admin Logbook',                    'email' => 'admin@tsth2.id',    'password' => $pw, 'role' => 'admin',             'department_id' => null, 'division_id' => null, 'employee_code' => 'ADM001',    'join_year' => 2023, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 102, 'name' => 'Pimpinan TSTH2',                   'email' => 'pimpinan@tsth2.id', 'password' => $pw, 'role' => 'pimpinan',          'department_id' => null, 'division_id' => null, 'employee_code' => 'PMP001',    'join_year' => 2023, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 103, 'name' => 'Kepala Departemen General Affair',  'email' => 'kadep@gmail.com',   'password' => $pw, 'role' => 'kepala_departemen', 'department_id' => 1,    'division_id' => null, 'employee_code' => 'KADEP01',   'join_year' => 2023, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 104, 'name' => 'Kepala Divisi Operasional',         'email' => 'kadiv@gmail.com',   'password' => $pw, 'role' => 'kepala_divisi',     'department_id' => 1,    'division_id' => 1,    'employee_code' => 'KADIV01',   'join_year' => 2023, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 105, 'name' => 'Karyawan Operasional',              'email' => 'ko@gmail.com',      'password' => $pw, 'role' => 'employee',          'department_id' => 1,    'division_id' => 1,    'employee_code' => 'EMPKO01',   'join_year' => 2024, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 106, 'name' => 'Karyawan TSTH2',                    'email' => 'karyawan@tsth2.id', 'password' => $pw, 'role' => 'employee',          'department_id' => 1,    'division_id' => 1,    'employee_code' => 'EMPTSTH01', 'join_year' => 2024, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($defaultUsers as $u) {
            DB::table('users')->updateOrInsert(['email' => $u['email']], $u);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Auto increment yang aman
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 200;');
        DB::statement('ALTER TABLE logbooks AUTO_INCREMENT = 500;');
        DB::statement('ALTER TABLE tasks AUTO_INCREMENT = 200;');
        DB::statement('ALTER TABLE targets AUTO_INCREMENT = 100;');
        DB::statement('ALTER TABLE sub_targets AUTO_INCREMENT = 100;');

        $userCount = DB::table('users')->count();
        $lbCount   = DB::table('logbooks')->count();

        $this->command->info("✓ LogbookImportSeeder berhasil diimpor!");
        $this->command->info("  - Total user: {$userCount} akun (semua password: password)");
        $this->command->info("  - Total logbook: {$lbCount} aktivitas");
    }
}
