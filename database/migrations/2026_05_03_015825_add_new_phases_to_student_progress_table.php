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
        $table->enum('scholarship_status', ['Not Applicable', 'Pending', 'Approved', 'Rejected'])
              ->default('Not Applicable')->after('viva_status');
        $table->enum('tuition_fee_status', ['Pending', 'Paid', 'Partial', 'Waived'])
              ->default('Pending')->after('scholarship_status');

        // P06 — Academic Progress
        $table->enum('progress_report_status', ['Pending', 'Submitted', 'Approved', 'Rejected'])
              ->default('Pending')->after('tuition_fee_status');
        $table->date('last_progress_report_date')->nullable()->after('progress_report_status');

        // P07 — Degree Verification
        $table->enum('degree_verification_status', ['Pending', 'In Progress', 'Verified', 'Awarded'])
              ->default('Pending')->after('last_progress_report_date');
        $table->date('graduation_date')->nullable()->after('degree_verification_status');
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
