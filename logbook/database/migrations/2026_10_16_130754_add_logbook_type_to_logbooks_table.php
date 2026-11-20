<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->string('logbook_type')
                ->default('assigned')
                ->after('task_id');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn('logbook_type');
        });
    }
};