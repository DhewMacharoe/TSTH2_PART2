<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('department_id')
                ->nullable()
                ->after('role')
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('division_id')
                ->nullable()
                ->after('department_id')
                ->constrained('divisions')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['department_id']);
            $table->dropForeign(['division_id']);

            $table->dropColumn([
                'department_id',
                'division_id',
            ]);

        });
    }
};