<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedTinyInteger('extended_semesters')->default(0)->after('status');
            $table->text('extension_reason')->nullable()->after('extended_semesters');
            $table->enum('extension_status', ['None', 'Pending', 'Approved', 'Rejected'])->default('None')->after('extension_reason');
            $table->timestamp('extension_requested_at')->nullable()->after('extension_status');
            $table->timestamp('extension_approved_at')->nullable()->after('extension_requested_at');
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