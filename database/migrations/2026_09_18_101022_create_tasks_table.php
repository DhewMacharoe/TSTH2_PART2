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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Target departemen yang menjadi acuan pekerjaan
            $table->foreignId('target_id')
                ->constrained('targets')
                ->cascadeOnDelete();

            // Kepala Divisi yang membuat / memberikan pekerjaan
            $table->foreignId('assigned_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Karyawan yang menerima pekerjaan
            $table->foreignId('assigned_to')
                ->constrained('users')
                ->cascadeOnDelete();

            // Struktur organisasi
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            $table->foreignId('division_id')
                ->constrained('divisions')
                ->cascadeOnDelete();

            // Informasi pekerjaan
            $table->string('title');

            $table->text('description')
                ->nullable();

            // Bobot pekerjaan terhadap target
            $table->unsignedTinyInteger('weight')
                ->default(0);

            // Target progres pekerjaan
            $table->unsignedTinyInteger('target_progress')
                ->default(100);

            // Progres yang dilaporkan karyawan
            $table->unsignedTinyInteger('reported_progress')
                ->default(0);

            // Progres yang sudah diverifikasi Kepala Divisi
            $table->unsignedTinyInteger('verified_progress')
                ->default(0);

            $table->enum('status', [
                'draft',
                'assigned',
                'in_progress',
                'revision',
                'completed',
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};