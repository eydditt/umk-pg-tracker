<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('intake_session')->nullable();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('intake_session')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('intake_session');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('intake_session');
        });
    }
};