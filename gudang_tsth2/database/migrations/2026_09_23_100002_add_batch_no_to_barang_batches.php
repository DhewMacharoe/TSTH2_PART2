<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_batches', function (Blueprint $table) {
            // Nomor batch (misal: B001, B002) — untuk grouping item dalam satu kedatangan
            $table->string('batch_no', 20)->nullable()->after('id')->index();
        });

        // Generate batch_no untuk data yang sudah ada
        // Setiap baris lama mendapat batch_no unik per barang (B001, B002, dst)
        $barangIds = DB::table('barang_batches')->distinct()->pluck('barang_id');
        foreach ($barangIds as $barangId) {
            $batches = DB::table('barang_batches')
                ->where('barang_id', $barangId)
                ->orderBy('tanggal_kedatangan', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // Group by tanggal_kedatangan — anggap satu hari = satu batch
            $grouped = [];
            foreach ($batches as $b) {
                $grouped[$b->tanggal_kedatangan][] = $b->id;
            }

            $batchCounter = 1;
            foreach ($grouped as $tanggal => $ids) {
                $batchNo = 'B' . str_pad($batchCounter, 3, '0', STR_PAD_LEFT);
                DB::table('barang_batches')->whereIn('id', $ids)->update(['batch_no' => $batchNo]);
                $batchCounter++;
            }
        }
    }

    public function down(): void
    {
        Schema::table('barang_batches', function (Blueprint $table) {
            $table->dropColumn('batch_no');
        });
    }
};
