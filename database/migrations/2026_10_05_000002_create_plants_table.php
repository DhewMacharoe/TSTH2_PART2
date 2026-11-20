<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plants')) {
            Schema::create('plants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->string('location_type')->default('greenhouse');
                $table->integer('population')->nullable()->default(0);
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('latin_name')->nullable();
                $table->string('family')->nullable();
                $table->string('ordo')->nullable();
                $table->string('genus')->nullable();
                $table->string('botanist')->nullable();
                $table->text('benefits')->nullable();
                $table->string('origin')->nullable();
                $table->text('origin_distribution')->nullable();
                $table->text('description_short')->nullable();
                $table->longText('description_full')->nullable();
                $table->string('image')->nullable();
                $table->json('image_gallery')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
