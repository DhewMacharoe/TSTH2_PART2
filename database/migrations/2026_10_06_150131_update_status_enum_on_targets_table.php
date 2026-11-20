<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE targets
            MODIFY status ENUM(
                'active',
                'pending',
                'completed',
                'inactive'
            ) NOT NULL DEFAULT 'active'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE targets
            MODIFY status ENUM(
                'active',
                'completed',
                'inactive'
            ) NOT NULL DEFAULT 'active'
        ");
    }
};