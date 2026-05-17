<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE students MODIFY COLUMN payment_method ENUM('Scholarship', 'Self-funded', 'Other', 'Not-stated') NOT NULL DEFAULT 'Not-stated'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE students MODIFY COLUMN payment_method ENUM('Scholarship', 'Self-funded', 'Other') NOT NULL");
    }
};