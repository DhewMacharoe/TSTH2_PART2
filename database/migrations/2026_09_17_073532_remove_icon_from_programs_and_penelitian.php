<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('programs') && Schema::hasColumn('programs', 'icon')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
        
        if (Schema::hasTable('penelitian') && Schema::hasColumn('penelitian', 'icon')) {
            Schema::table('penelitian', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('programs')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->string('icon')->nullable();
            });
        }
        if (Schema::hasTable('penelitian')) {
            Schema::table('penelitian', function (Blueprint $table) {
                $table->string('icon')->nullable();
            });
        }
    }
};
