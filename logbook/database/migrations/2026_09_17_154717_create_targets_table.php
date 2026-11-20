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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | DEPARTMENT
            |--------------------------------------------------------------------------
            */
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TARGET
            |--------------------------------------------------------------------------
            */
            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->unsignedTinyInteger('target_progress')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | PERIOD
            |--------------------------------------------------------------------------
            */
            $table->date('period_start')
                ->nullable();

            $table->date('period_end')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'active',
                'pending',
                'completed',
                'inactive',
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
