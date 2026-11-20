<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'target_id')) {
                $table->dropForeign(['target_id']);
                $table->dropColumn('target_id');
            }

            if (Schema::hasColumn('tasks', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('target_id')
                ->nullable()
                ->constrained('targets')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('weight')
                ->default(0);
        });
    }
};