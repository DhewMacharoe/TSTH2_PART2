<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Merampingkan tabel yang duplikat, tidak dipakai, atau sisa eksperimen:
     * 1. obrolan_divisi & baca_obrolan_divisi (fitur obrolan memakai obrolan_permintaan & baca_obrolan)
     * 2. barang_konversis (fitur konversi memakai satuan_konversis)
     * 3. galeri lama (fitur galeri saat ini memakai galeri_albums & galeri_photos)
     */
    public function up(): void
    {
        // 1. Drop tabel obrolan divisi yang tidak terpakai
        Schema::dropIfExists('baca_obrolan_divisi');
        Schema::dropIfExists('obrolan_divisi');

        // 2. Drop tabel barang_konversis kosong/redundant
        Schema::dropIfExists('barang_konversis');

        // 3. Drop tabel galeri lama (legacy flat galeri)
        Schema::dropIfExists('galeri');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create galeri
        if (!Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('image_path');
                $table->timestamps();
            });
        }

        // Re-create barang_konversis
        if (!Schema::hasTable('barang_konversis')) {
            Schema::create('barang_konversis', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        // Re-create obrolan_divisi
        if (!Schema::hasTable('obrolan_divisi')) {
            Schema::create('obrolan_divisi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->text('pesan');
                $table->timestamps();
            });
        }

        // Re-create baca_obrolan_divisi
        if (!Schema::hasTable('baca_obrolan_divisi')) {
            Schema::create('baca_obrolan_divisi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }
};
