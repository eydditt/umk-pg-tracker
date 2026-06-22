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
    Schema::table('student_progress', function (Blueprint $table) {
        // P05 — Scholarship & Fees
        $table->string('scholarship_status')
              ->default('Not Applicable');
        $table->string('tuition_fee_status')
              ->default('Pending');

        // P06 — Academic Progress
        $table->string('progress_report_status')
              ->default('Pending');
        $table->date('last_progress_report_date')->nullable();

        // P07 — Degree Verification
        $table->string('degree_verification_status')
              ->default('Pending');
        $table->date('graduation_date')->nullable();
    });
}

public function down(): void
{
    Schema::table('student_progress', function (Blueprint $table) {
        $table->dropColumn([
            'scholarship_status',
            'tuition_fee_status',
            'progress_report_status',
            'last_progress_report_date',
            'degree_verification_status',
            'graduation_date',
        ]);
    });
}
};
