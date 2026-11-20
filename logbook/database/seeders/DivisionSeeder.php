<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Manajemen',
            'Administrasi',
            'Keuangan',
            'SDM',
            'Riset',
            'Laboratorium',
            'Pertanian',
            'Produksi',
            'Operasional',
            'Sarana & Prasarana',
            'Humas & Kerja Sama',
            'Lingkungan',
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate([
                'name' => $division,
            ]);
        }
    }
}