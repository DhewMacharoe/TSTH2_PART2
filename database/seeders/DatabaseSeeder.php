<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\BarangBatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BarangSeeder::class,
            ContentSeeder::class,
            CategorySeeder::class,
            PlantSeeder::class,
            TsthidDatabaseSeeder::class,
            BeritaSeeder::class,
            GaleriSeeder::class,
            PenelitianSeeder::class,
        ]);

    }
}
