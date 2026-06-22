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
        $table->string('eng_test_status')->default('Pending');
        $table->string('research_method')->default('Pending');
        $table->string('pd_status')
               ->default('Pending');
        $table->string('pre_viva_status')->default('Pending');
        $table->string('viva_status')->default('Pending');
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
