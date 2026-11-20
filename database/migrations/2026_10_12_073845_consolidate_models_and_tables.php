<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Merampingkan tabel database:
     * 1. Gabung latar_beranda & poin_tentang ke tabel konten (section: home_background & about_bullet)
     * 2. Hapus tabel baca_obrolan (status read tidak terpakai/disatukan ke alur chat)
     * 3. Gabung galeri_photos ke galeri_albums (tambahkan kolom JSON `photos` di galeri_albums)
     */
    public function up(): void
    {
        // 1. Migrasikan data latar_beranda ke tabel konten jika tabel latar_beranda ada
        if (Schema::hasTable('latar_beranda') && Schema::hasTable('konten')) {
            $backgrounds = DB::table('latar_beranda')->get();
            foreach ($backgrounds as $bg) {
                DB::table('konten')->insert([
                    'section'    => 'home_background',
                    'key'        => 'home_bg_' . $bg->id,
                    'value'      => '',
                    'type'       => 'image',
                    'image_path' => $bg->image_path,
                    'created_at' => $bg->created_at ?? now(),
                    'updated_at' => $bg->updated_at ?? now(),
                ]);
            }
            Schema::dropIfExists('latar_beranda');
        }

        // 2. Migrasikan data poin_tentang ke tabel konten jika tabel poin_tentang ada
        if (Schema::hasTable('poin_tentang') && Schema::hasTable('konten')) {
            $bullets = DB::table('poin_tentang')->get();
            foreach ($bullets as $b) {
                DB::table('konten')->insert([
                    'section'    => 'about_bullet',
                    'key'        => 'about_bullet_' . $b->id,
                    'value'      => $b->content,
                    'type'       => 'text',
                    'image_path' => null,
                    'created_at' => $b->created_at ?? now(),
                    'updated_at' => $b->updated_at ?? now(),
                ]);
            }
            Schema::dropIfExists('poin_tentang');
        }

        // 3. Drop baca_obrolan
        Schema::dropIfExists('baca_obrolan');

        // 4. Tambah kolom `photos` (JSON) ke galeri_albums & migrasikan data galeri_photos
        if (Schema::hasTable('galeri_albums')) {
            if (!Schema::hasColumn('galeri_albums', 'photos')) {
                Schema::table('galeri_albums', function (Blueprint $table) {
                    $table->json('photos')->nullable()->after('event_date');
                });
            }

            // Migrasi foto-foto yang ada ke kolom photos JSON
            if (Schema::hasTable('galeri_photos')) {
                $albums = DB::table('galeri_albums')->get();
                foreach ($albums as $album) {
                    $photos = DB::table('galeri_photos')
                        ->where('album_id', $album->id)
                        ->orderBy('sort_order')
                        ->pluck('image_path')
                        ->toArray();

                    DB::table('galeri_albums')
                        ->where('id', $album->id)
                        ->update(['photos' => json_encode($photos)]);
                }
                Schema::dropIfExists('galeri_photos');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create galeri_photos
        if (!Schema::hasTable('galeri_photos')) {
            Schema::create('galeri_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('album_id')->constrained('galeri_albums')->onDelete('cascade');
                $table->string('image_path');
                $table->boolean('is_cover')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Re-create baca_obrolan
        if (!Schema::hasTable('baca_obrolan')) {
            Schema::create('baca_obrolan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permintaan_barang_id')->constrained('permintaan_barangs')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Re-create latar_beranda
        if (!Schema::hasTable('latar_beranda')) {
            Schema::create('latar_beranda', function (Blueprint $table) {
                $table->id();
                $table->string('image_path');
                $table->timestamps();
            });
        }

        // Re-create poin_tentang
        if (!Schema::hasTable('poin_tentang')) {
            Schema::create('poin_tentang', function (Blueprint $table) {
                $table->id();
                $table->string('content');
                $table->timestamps();
            });
        }
    }
};
