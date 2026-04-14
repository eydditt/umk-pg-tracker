<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_students_table.php
public function up(): void
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
        $table->string('matric_no')->unique();
        $table->enum('program_type', ['Master', 'PhD']);
        $table->enum('gender', ['Male', 'Female']);
        $table->enum('payment_method', ['Scholarship', 'Self-funded', 'Other']);
        $table->foreignId('main_sv_id')->nullable()->constrained('lecturers')->nullOnDelete();
        $table->foreignId('co_sv_id')->nullable()->constrained('lecturers')->nullOnDelete();
        $table->enum('status', ['Active', 'Completed', 'Terminated', 'Deferred'])
               ->default('Active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
