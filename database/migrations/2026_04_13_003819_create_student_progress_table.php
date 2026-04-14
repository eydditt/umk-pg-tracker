<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/..._create_student_progress_table.php
public function up(): void
{
    Schema::create('student_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->unique()->constrained()->cascadeOnDelete();
        $table->enum('eng_test_status', ['Pending', 'Passed'])->default('Pending');
        $table->enum('research_method', ['Pending', 'Passed'])->default('Pending');
        $table->enum('pd_status', ['Pending', 'Passed', 'Minor Correction', 'Major Correction'])
               ->default('Pending');
        $table->enum('pre_viva_status', ['Pending', 'Passed', 'Failed'])->default('Pending');
        $table->enum('viva_status', ['Pending', 'Passed', 'Failed'])->default('Pending');
        $table->json('gdrive_links')->nullable();
        $table->timestamps();
    });
}

// Jalankan semua migration:
// ./vendor/bin/sail artisan migrate

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
