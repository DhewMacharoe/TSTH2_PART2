<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('programs', 'icon')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
        
        if (Schema::hasColumn('research', 'icon')) {
            Schema::table('research', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
        Schema::table('research', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });
    }
};
