<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Web Admin
        User::updateOrCreate(['email' => 'web@tsth2.id'], [
            'name' => 'Andi Setiawan',
            'password' => Hash::make('password'),
            'role' => 'web_admin',
        ]);

        // PIC Warehouse Gudang — Departemen General Affair (GA)
        User::updateOrCreate(['email' => 'gudang@tsth2.id'], [
            'name' => 'PIC Gudang Logistik',
            'password' => Hash::make('password'),
            'role' => 'pic_gudang',
            'divisi' => 'General Affair',
        ]);

        User::updateOrCreate(['email' => 'pic@tsth2.id'], [
            'name' => 'PIC Gudang Logistik',
            'password' => Hash::make('password'),
            'role' => 'pic_gudang',
            'divisi' => 'General Affair',
        ]);

        // PIC Warehouse Laboratorium — Departemen Riset dan Inovasi (RdI)
        User::updateOrCreate(['email' => 'piclab@tsth2.id'], [
            'name' => 'Dr. Hendra Wijaya',
            'password' => Hash::make('password'),
            'role' => 'pic_lab',
            'divisi' => 'Riset dan Inovasi',
        ]);

        // Staf Warehouse
        User::updateOrCreate(['email' => 'wh@tsth2.id'], [
            'name' => 'Staf Warehouse',
            'password' => Hash::make('password'),
            'role' => 'warehouse',
            'divisi' => 'gudang',
        ]);

        // Karyawan / Pemohon Barang
        User::updateOrCreate(['email' => 'karyawan@tsth2.id'], [
            'name' => 'Karyawan TSTH2',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'divisi' => 'lapangan',
        ]);

        User::updateOrCreate(['email' => 'agro@tsth2.id'], [
            'name' => 'Agronom Lapangan',
            'password' => Hash::make('password'),
            'role' => 'agronom',
            'divisi' => 'lapangan',
        ]);

        // Pimpinan (Direktur/Manajer)
        User::updateOrCreate(['email' => 'pimpinan@tsth2.id'], [
            'name' => 'Direktur Eksekutif TSTH2',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        User::updateOrCreate(['email' => 'dir@tsth2.id'], [
            'name' => 'Direktur Eksekutif TSTH2',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }
}
