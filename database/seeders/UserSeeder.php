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

        // PIC Warehouse (Admin Gudang)
        User::updateOrCreate(['email' => 'pic@tsth2.id'], [
            'name' => 'Budi Santoso',
            'password' => Hash::make('password'),
            'role' => 'pic_gudang',
            'divisi' => 'gudang',
        ]);

        // Staf Warehouse
        User::updateOrCreate(['email' => 'wh@tsth2.id'], [
            'name' => 'Cahyo Utomo',
            'password' => Hash::make('password'),
            'role' => 'warehouse',
            'divisi' => 'gudang',
        ]);

        // Staf Lapangan
        User::updateOrCreate(['email' => 'agro@tsth2.id'], [
            'name' => 'Dedi Irawan',
            'password' => Hash::make('password'),
            'role' => 'agronom',
            'divisi' => 'lapangan',
        ]);

        // Pimpinan (Direktur/Manajer)
        User::updateOrCreate(['email' => 'dir@tsth2.id'], [
            'name' => 'Eko Prasetyo',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }
}
