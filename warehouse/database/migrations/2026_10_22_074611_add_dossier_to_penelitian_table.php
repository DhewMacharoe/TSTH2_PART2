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
        Schema::table('penelitian', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->string('field')->nullable()->after('title');
            $table->string('status_riset')->nullable()->after('field');
            $table->string('facility')->nullable()->after('status_riset');
            $table->string('location')->nullable()->after('facility');
            $table->string('peneliti')->nullable()->after('location');
            $table->text('tujuan')->nullable()->after('peneliti');
            $table->json('roadmap')->nullable()->after('description');
            $table->json('params')->nullable()->after('roadmap');
            $table->json('benchmarks')->nullable()->after('params');
            $table->json('metrics')->nullable()->after('benchmarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'field',
                'status_riset',
                'facility',
                'location',
                'peneliti',
                'tujuan',
                'roadmap',
                'params',
                'benchmarks',
                'metrics',
            ]);
        });
    }
};
