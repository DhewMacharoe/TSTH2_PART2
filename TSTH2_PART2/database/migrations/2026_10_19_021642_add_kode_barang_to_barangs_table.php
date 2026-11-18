<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('barangs', 'kode_barang')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->string('kode_barang', 50)->nullable()->unique()->after('id');
            });
        }

        // Generate unique codes for any existing items
        $prefixMap = [
            'Pupuk'         => 'PUP',
            'Bahan Kimia'   => 'KIM',
            'Alat Budidaya' => 'ALT',
            'Bibit Tanaman' => 'BBT',
        ];

        $barangs = DB::table('barangs')->orderBy('id', 'asc')->get();
        $counts = [];

        foreach ($barangs as $b) {
            $tag = $prefixMap[$b->kategori] ?? 'GEN';
            $counts[$tag] = ($counts[$tag] ?? 0) + 1;
            $code = sprintf('BRG-%s-%03d', $tag, $counts[$tag]);

            DB::table('barangs')->where('id', $b->id)->update([
                'kode_barang' => $code,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('barangs', 'kode_barang')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('kode_barang');
            });
        }
    }
};
