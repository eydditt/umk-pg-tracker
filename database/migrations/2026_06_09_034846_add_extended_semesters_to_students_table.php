<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedTinyInteger('extended_semesters')->default(0);
            $table->text('extension_reason')->nullable();
            $table->string('extension_status')->default('None');
            $table->timestamp('extension_requested_at')->nullable();
            $table->timestamp('extension_approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'extended_semesters',
                'extension_reason',
                'extension_status',
                'extension_requested_at',
                'extension_approved_at',
            ]);
        });
    }
};