<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrasi ini merombak total kolom status dan menambahkan kolom baru
     * pada tabel permintaan_barangs untuk mendukung alur status baru.
     */
    public function up(): void
    {
        // 1. Update nilai status lama ke nilai baru (data migration)
        $statusMap = [
            'Waiting'                        => 'Menunggu Approval',
            'Pending'                        => 'Menunggu Approval',
            'On Progress'                    => 'Menunggu Pengambilan',
            'Diambil'                        => 'Barang Diambil',
            'Menunggu Approval Pengembalian' => 'Menunggu Verifikasi Pengembalian',
            'Dikembalikan'                   => 'Barang Dikembalikan',
            'Dibatalkan'                     => 'Dibatalkan',
            'Ditolak'                        => 'Ditolak',
            'Ditunda'                        => 'Menunggu Approval', // reset ke awal
        ];

        foreach ($statusMap as $old => $new) {
            DB::table('permintaan_barangs')
                ->where('status', $old)
                ->update(['status' => $new]);
        }

        // 2. Tambah kolom-kolom baru
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            // Catatan wajib dari PIC saat approve
            $table->text('catatan_pic')->nullable()->after('catatan');
            // Kapan permintaan ditutup (status Selesai)
            $table->timestamp('closed_at')->nullable()->after('tanggal_pengembalian');
            // Kapan forum diskusi berakhir (closed_at + 3 hari)
            $table->timestamp('forum_ends_at')->nullable()->after('closed_at');
            // Apakah barang bersifat habis pakai (tidak perlu dikembalikan)
            $table->boolean('is_habis_pakai')->default(false)->after('forum_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['catatan_pic', 'closed_at', 'forum_ends_at', 'is_habis_pakai']);
        });

        // Revert status (simplified)
        DB::table('permintaan_barangs')
            ->where('status', 'Menunggu Approval')
            ->update(['status' => 'Waiting']);
        DB::table('permintaan_barangs')
            ->where('status', 'Menunggu Pengambilan')
            ->update(['status' => 'On Progress']);
        DB::table('permintaan_barangs')
            ->where('status', 'Barang Diambil')
            ->update(['status' => 'Diambil']);
        DB::table('permintaan_barangs')
            ->where('status', 'Barang Dikembalikan')
            ->update(['status' => 'Dikembalikan']);
    }
};
