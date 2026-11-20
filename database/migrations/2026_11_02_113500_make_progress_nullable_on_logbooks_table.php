<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->nullable()->default(null)->change();
        });

        // Setel progress menjadi NULL untuk logbook bertipe mandiri yang sudah ada
        DB::table('logbooks')
            ->where('logbook_type', 'independent')
            ->update(['progress' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('logbooks')
            ->whereNull('progress')
            ->update(['progress' => 0]);

        Schema::table('logbooks', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->default(0)->nullable(false)->change();
        });
    }
};

