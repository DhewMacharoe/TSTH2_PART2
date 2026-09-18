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
        User::create([
            'name' => 'Andi Setiawan',
            'email' => 'webadmin@tsth2.id',
            'password' => Hash::make('password'),
            'role' => 'web_admin',
        ]);

        // PIC Warehouse (Admin Gudang)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pic@tsth2.id',
            'password' => Hash::make('password'),
            'role' => 'pic_warehouse',
            'divisi' => 'gudang',
        ]);

        // Staf Warehouse
        User::create([
            'name' => 'Cahyo Utomo',
            'email' => 'staffgudang@tsth2.id',
            'password' => Hash::make('password'),
            'role' => 'warehouse',
            'divisi' => 'gudang',
        ]);

        // Staf Lapangan
        User::create([
            'name' => 'Dedi Irawan',
            'email' => 'stafflapangan@tsth2.id',
            'password' => Hash::make('password'),
            'role' => 'agronom',
            'divisi' => 'lapangan',
        ]);

        // Pimpinan (Direktur/Manajer)
        User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'pimpinan@tsth2.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }
}
