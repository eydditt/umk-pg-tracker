<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('intake_month', ['September', 'February'])->default('September')->after('intake_session');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->enum('intake_month', ['September', 'February'])->default('September')->after('intake_session');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('intake_month');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('intake_month');
        });
    }
};