<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix inkonsistensi kolom `pesan` vs `message` di obrolan_permintaan.
     * Tambah kolom `channel` jika belum ada.
     */
    public function up(): void
    {
        Schema::table('obrolan_permintaan', function (Blueprint $table) {
            // Rename pesan -> message jika kolom pesan ada
            if (Schema::hasColumn('obrolan_permintaan', 'pesan') && !Schema::hasColumn('obrolan_permintaan', 'message')) {
                $table->renameColumn('pesan', 'message');
            }

            // Tambah channel jika belum ada
            if (!Schema::hasColumn('obrolan_permintaan', 'channel')) {
                $table->string('channel')->default('permintaan')->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('obrolan_permintaan', function (Blueprint $table) {
            if (Schema::hasColumn('obrolan_permintaan', 'message') && !Schema::hasColumn('obrolan_permintaan', 'pesan')) {
                $table->renameColumn('message', 'pesan');
            }
        });
    }
};
