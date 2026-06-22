<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('country')->nullable()->after('identity_no');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('country')->nullable()->after('nationality_type');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('country');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
};