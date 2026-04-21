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
    Schema::table('applicants', function (Blueprint $table) {
        $table->enum('eng_test_taken', ['Taken', 'Not Taken'])->default('Not Taken')->after('eng_test');
    });
}

public function down(): void
{
    Schema::table('applicants', function (Blueprint $table) {
        $table->dropColumn('eng_test_taken');
    });
}
};
