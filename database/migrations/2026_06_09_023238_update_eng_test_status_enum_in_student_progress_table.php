<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE student_progress MODIFY COLUMN eng_test_status ENUM('Pending', 'Passed', 'Not Required') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE student_progress MODIFY COLUMN eng_test_status ENUM('Pending', 'Passed') NOT NULL DEFAULT 'Pending'");
    }
};