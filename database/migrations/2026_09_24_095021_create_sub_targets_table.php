<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_targets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('target_id')
                ->constrained('targets')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->unsignedTinyInteger('percentage');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_targets');
    }
};