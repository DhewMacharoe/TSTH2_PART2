<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SpatieRoleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. DAFTAR ROLES DI TSTH2
        $roles = [
            'web_admin',          // Admin Portal Utama Pengunjung
            'pic_gudang',         // Admin Logistik & Inventory Gudang
            'agronom',            // Agronomist & Peneliti
            'pimpinan',           // Direktur / Pimpinan Eksekutif
            'admin_katalog',      // Administrator Data Warehouse Tanaman
            'kepala_departemen',  // Manajemen Logbook Tingkat Dept
            'kepala_divisi',      // Manajemen Logbook Tingkat Divisi
            'leader',             // Tim Leader Lapangan
            'karyawan',           // Karyawan Harian / Employee
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. AKUN DEFAULT LINTAS SUBDOMAIN

        // A. Admin Portal Pengunjung (pengunjung.tsth2.id)
        $webAdmin = User::firstOrCreate(
            ['email' => 'admin@tsth2.id'],
            [
                'name' => 'Super Web Administrator',
                'password' => Hash::make('AdminTSTH2_2026!'),
                'role' => 'web_admin',
            ]
        );
        $webAdmin->syncRoles(['web_admin']);

        // B. PIC Gudang & Inventory (pengunjung.tsth2.id/gudang)
        $picGudang = User::firstOrCreate(
            ['email' => 'gudang@tsth2.id'],
            [
                'name' => 'PIC Gudang Logistik',
                'password' => Hash::make('GudangTSTH2_2026!'),
                'role' => 'pic_gudang',
            ]
        );
        $picGudang->syncRoles(['pic_gudang']);

        // C. Admin Katalog Data Warehouse Tanaman (katalog.tsth2.id)
        $adminKatalog = User::firstOrCreate(
            ['email' => 'katalog@tsth2.id'],
            [
                'name' => 'Admin Katalog Flora',
                'password' => Hash::make('KatalogFlora2026!'),
                'role' => 'admin_katalog',
            ]
        );
        $adminKatalog->syncRoles(['admin_katalog']);

        // D. Karyawan Harian Logbook (logbook.tsth2.id)
        $karyawan = User::firstOrCreate(
            ['email' => 'karyawan@tsth2.id'],
            [
                'name' => 'Karyawan Kebun Herbal',
                'password' => Hash::make('LogbookHarian2026!'),
                'role' => 'karyawan',
            ]
        );
        $karyawan->syncRoles(['karyawan']);

        // E. Pimpinan / Executive (Akses Dashboard Pimpinan)
        $pimpinan = User::firstOrCreate(
            ['email' => 'pimpinan@tsth2.id'],
            [
                'name' => 'Direktur Eksekutif TSTH2',
                'password' => Hash::make('PimpinanTSTH2_2026!'),
                'role' => 'pimpinan',
            ]
        );
        $pimpinan->syncRoles(['pimpinan']);
    }
}
