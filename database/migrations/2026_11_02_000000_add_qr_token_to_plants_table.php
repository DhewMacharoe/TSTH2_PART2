<?php

use App\Models\Plant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->uuid('qr_token')->nullable()->unique()->after('slug');
        });

        Plant::query()
            ->whereNull('qr_token')
            ->eachById(function (Plant $plant): void {
                $plant->forceFill(['qr_token' => (string) Str::uuid()])->saveQuietly();
            });
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};
