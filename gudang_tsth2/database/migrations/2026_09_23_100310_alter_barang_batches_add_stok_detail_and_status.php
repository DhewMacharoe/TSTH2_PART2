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
        Schema::table('barang_batches', function (Blueprint $table) {
            $table->json('stok_detail')->nullable()->after('satuan');
            $table->string('status_keterbukaan')->default('Utuh')->after('stok_detail');
            $table->integer('stok')->nullable()->change();
            $table->string('satuan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_batches', function (Blueprint $table) {
            $table->dropColumn(['stok_detail', 'status_keterbukaan']);
        });
    }
};
