<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel album galeri
        Schema::create('galeri_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date')->nullable();
            $table->timestamps();
        });

        // Tabel foto individual (relasi ke album)
        Schema::create('galeri_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('galeri_albums')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_cover')->default(false); // foto pertama sebagai cover
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_photos');
        Schema::dropIfExists('galeri_albums');
    }
};
