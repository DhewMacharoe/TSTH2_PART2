<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->text('documentation')->nullable()->after('obstacle');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', fn (Blueprint $table) => $table->dropColumn('documentation'));
    }
};