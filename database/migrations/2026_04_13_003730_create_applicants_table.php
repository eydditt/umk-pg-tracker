<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_applicants_table.php
public function up(): void
{
    Schema::create('applicants', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->enum('identity_type', ['IC', 'Passport']);
        $table->string('identity_no')->unique();
        $table->enum('program_applied', ['Master', 'PhD']);
        $table->text('prev_edu');
        $table->string('eng_test')->nullable();
        $table->enum('status', ['Pending', 'Approved', 'Rejected'])
               ->default('Pending');
        $table->json('application_docs_links')->nullable();
        $table->softDeletes(); // Untuk Rejected records
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
