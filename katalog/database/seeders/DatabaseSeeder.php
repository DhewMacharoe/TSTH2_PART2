<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'katalog@tsth2.id'],
            [
                'name' => 'Admin Katalog Flora',
                'password' => 'password',
            ],
        );

        User::updateOrCreate(
            ['email' => 'admin@tsth2.id'],
            [
                'name' => 'Admin Katalog Flora',
                'password' => 'password',
            ],
        );

        User::updateOrCreate(
            ['email' => 'elisabeth.pasaribu@tsth2.test'],
            [
                'name' => 'Elisabeth Pasaribu',
                'password' => 'semangat',
            ],
        );

        User::updateOrCreate(
            ['email' => 'budi.herbal@tsth2.test'],
            [
                'name' => 'Budi Herbal',
                'password' => 'herbal123',
            ],
        );

        $this->call([
            CategorySeeder::class,
            PlantSeeder::class,
        ]);
    }
}
