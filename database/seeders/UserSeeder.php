<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        try {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        } catch (\Throwable $e) {}

        $pw = Hash::make('password');

        $createUser = function(string $email, array $data) {
            $user = User::updateOrCreate(['email' => $email], $data);
            try {
                if (!empty($data['role'])) {
                    Role::firstOrCreate(['name' => $data['role'], 'guard_name' => 'web']);
                    $user->syncRoles([$data['role']]);
                }
            } catch (\Throwable $e) {}
            return $user;
        };

        // ═══════════════════════════════════════════════
        // PORTAL WEB PENGUNJUNG & WEB MANAGEMENT (8000)
        // ═══════════════════════════════════════════════

        $createUser('admin@tsth2.id', [
            'name'     => 'Web Administrator',
            'password' => $pw,
            'role'     => 'web_admin',
        ]);

        $createUser('web@tsth2.id', [
            'name'     => 'Web Administrator',
            'password' => $pw,
            'role'     => 'web_admin',
        ]);

        // ═══════════════════════════════════════════════
        // PORTAL SISTEM GUDANG & WAREHOUSE (8002)
        // ═══════════════════════════════════════════════

        $createUser('pic@tsth2.id', [
            'name'     => 'Budi Santoso',
            'password' => $pw,
            'role'     => 'pic_gudang',
            'divisi'   => 'General Affair',
        ]);

        $createUser('piclab@tsth2.id', [
            'name'     => 'Dr. Hendra Wijaya',
            'password' => $pw,
            'role'     => 'pic_lab',
            'divisi'   => 'Riset dan Inovasi',
        ]);

        $createUser('wh@tsth2.id', [
            'name'     => 'Cahyo Utomo',
            'password' => $pw,
            'role'     => 'warehouse',
            'divisi'   => 'gudang',
        ]);

        $createUser('agro@tsth2.id', [
            'name'     => 'Dedi Irawan',
            'password' => $pw,
            'role'     => 'agronom',
            'divisi'   => 'lapangan',
        ]);

        // ═══════════════════════════════════════════════
        // PORTAL LOGBOOK KARYAWAN (8001)
        // ═══════════════════════════════════════════════

        $createUser('pimpinan@tsth2.id', [
            'name'     => 'Pimpinan TSTH2',
            'password' => $pw,
            'role'     => 'pimpinan',
        ]);

        $createUser('pimpinan@gmail.com', [
            'name'     => 'Pimpinan TSTH2',
            'password' => $pw,
            'role'     => 'pimpinan',
        ]);

        $createUser('dir@tsth2.id', [
            'name'     => 'Eko Prasetyo',
            'password' => $pw,
            'role'     => 'pimpinan',
        ]);

        $createUser('admin@gmail.com', [
            'name'     => 'Admin Logbook',
            'password' => $pw,
            'role'     => 'admin',
        ]);

        $createUser('kadep@gmail.com', [
            'name'     => 'Kepala Departemen General Affair',
            'password' => $pw,
            'role'     => 'kepala_departemen',
        ]);

        $createUser('kadiv@gmail.com', [
            'name'     => 'Kepala Divisi Operasional',
            'password' => $pw,
            'role'     => 'kepala_divisi',
        ]);

        $createUser('ko@gmail.com', [
            'name'     => 'Karyawan Operasional',
            'password' => $pw,
            'role'     => 'employee',
        ]);

        $createUser('karyawan@tsth2.id', [
            'name'     => 'Karyawan Operasional',
            'password' => $pw,
            'role'     => 'employee',
        ]);

        // ── Karyawan & Pimpinan Real dari SQL Dump (Logbook) ─────────────────
        $realLogbookUsers = [
            ['name' => 'Donni Napitupulu',             'email' => 'donni@gmail.com',      'role' => 'kepala_departemen'],
            ['name' => 'Maria Elfiana Ina Kewa Helan', 'email' => 'maria@gmail.com',      'role' => 'kepala_departemen'],
            ['name' => 'Joy Damanik',                  'email' => 'joy@gmail.com',        'role' => 'kepala_divisi'],
            ['name' => 'Ika Yuliasari',                'email' => 'ika@gmail.com',        'role' => 'kepala_divisi'],
            ['name' => 'Alfonsus Sadewa',              'email' => 'alfonsus@gmail.com',   'role' => 'kepala_divisi'],
            ['name' => 'Panca Sibuea',                 'email' => 'panca@gmail.com',      'role' => 'kepala_divisi'],
            ['name' => 'Irvandi',                      'email' => 'irvandi@gmail.com',    'role' => 'employee'],
            ['name' => 'Welkincon Butar - Butar',      'email' => 'welrkincon@gmail.com', 'role' => 'employee'],
            ['name' => 'Dionisius Situmorang',         'email' => 'dionisius@gmail.com',  'role' => 'employee'],
            ['name' => 'Ester Rosdiana Sinaga',        'email' => 'ester@gmail.com',      'role' => 'employee'],
            ['name' => 'Miftah Rizqan Khalid',         'email' => 'miftah@gmail.com',     'role' => 'employee'],
            ['name' => 'Natalia Lumban Gaol',          'email' => 'natalia@gmail.com',    'role' => 'employee'],
            ['name' => 'Abdon Marusaha Lumban Gaol',   'email' => 'abdon@gmail.com',      'role' => 'employee'],
            ['name' => 'Herbinawan Nainggolan',        'email' => 'herbinawan@gmail.com', 'role' => 'employee'],
            ['name' => 'Srifani Nainggolan',           'email' => 'srifani@gmail.com',    'role' => 'employee'],
            ['name' => 'Timoria Novalinda',            'email' => 'timo@gmail.com',       'role' => 'employee'],
        ];

        foreach ($realLogbookUsers as $u) {
            $createUser($u['email'], [
                'name'     => $u['name'],
                'password' => $pw,
                'role'     => $u['role'],
            ]);
        }
    }
}
