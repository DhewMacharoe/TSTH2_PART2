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
        Schema::table('satuan_konversis', function (Blueprint $table) {
            $table->string('kategori')->after('id')->nullable();
            
            // Drop old unique constraint (depends on database driver how we drop it, but we can just drop the index name)
            $table->dropUnique(['dari_satuan', 'ke_satuan']);
            $table->unique(['kategori', 'dari_satuan', 'ke_satuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satuan_konversis', function (Blueprint $table) {
            $table->dropUnique(['kategori', 'dari_satuan', 'ke_satuan']);
            $table->dropColumn('kategori');
            $table->unique(['dari_satuan', 'ke_satuan']);
        });
    }
};
