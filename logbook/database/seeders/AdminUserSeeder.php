<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Membuat akun admin pertama.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@logbook.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}