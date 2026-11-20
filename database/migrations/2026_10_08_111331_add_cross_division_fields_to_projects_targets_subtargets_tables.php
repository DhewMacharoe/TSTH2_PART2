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
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('status');
        });

        Schema::table('targets', function (Blueprint $table) {
            $table->decimal('weight', 5, 2)->default(0)->after('target_progress')->comment('Bobot target per divisi');
        });

        Schema::table('sub_targets', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('percentage');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('progress');
        });

        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('sub_targets', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
