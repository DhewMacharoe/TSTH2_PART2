<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('satuan_konversis', function (Blueprint $table) {
            $table->id();
            $table->string('dari_satuan');
            $table->string('ke_satuan');
            $table->decimal('multiplier', 10, 2);
            $table->timestamps();
            
            $table->unique(['dari_satuan', 'ke_satuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satuan_konversis');
    }
};
