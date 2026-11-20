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
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->foreignId('pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_gudang_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_barangs', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['staff_gudang_id']);
            $table->dropColumn(['pic_id', 'staff_gudang_id']);
        });
    }
};
