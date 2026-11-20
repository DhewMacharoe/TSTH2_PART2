<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            if (! Schema::hasColumn('logbooks', 'documentation_images')) {
                $table->json('documentation_images')->nullable()->after('documentation_image');
            }
            if (! Schema::hasColumn('logbooks', 'documentation_links')) {
                $table->json('documentation_links')->nullable()->after('documentation_link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            if (Schema::hasColumn('logbooks', 'documentation_images')) {
                $table->dropColumn('documentation_images');
            }
            if (Schema::hasColumn('logbooks', 'documentation_links')) {
                $table->dropColumn('documentation_links');
            }
        });
    }
};

