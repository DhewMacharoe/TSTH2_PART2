<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->string('documentation_image')->nullable()->after('documentation');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', fn (Blueprint $table) => $table->dropColumn('documentation_image'));
    }
};