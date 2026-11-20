<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan', 50)->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Seed default satuan
        DB::table('satuan_barangs')->insert([
            ['nama_satuan' => 'pcs',   'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'kg',    'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'gram',  'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'liter', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'ml',    'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'sack',  'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'botol', 'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'dus',   'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'unit',  'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'set',   'created_by' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan_barangs');
    }
};
