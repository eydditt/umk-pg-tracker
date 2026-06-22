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
        $table->string('identity_type');
        $table->string('identity_no')->unique();
        $table->string('program_applied');
        $table->text('prev_edu');
        $table->string('eng_test')->nullable();
        $table->string('status')
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
