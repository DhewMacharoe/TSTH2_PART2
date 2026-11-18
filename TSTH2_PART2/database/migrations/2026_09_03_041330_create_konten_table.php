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
        Schema::create('konten', function (Blueprint $table) {
            $table->id();
            $table->string('section')->index(); // e.g. home, program, penelitian, berita
            $table->string('key')->unique(); // e.g. home_hero_title
            $table->text('value')->nullable(); // For text content
            $table->string('type')->default('text'); // 'text', 'image'
            $table->string('image_path')->nullable(); // If type is image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
