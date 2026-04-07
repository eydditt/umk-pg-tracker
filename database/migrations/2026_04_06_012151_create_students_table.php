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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

              //ubah sini klu nk tmbah attributes students ;)
            $table->string('matric_no')->unique(); // No Matrik (Mesti unik, tak boleh ada dua org no sama)
            $table->string('email')->unique();
            $table->string('name'); // Nama penuh pelajar
            $table->string('program'); // Tahap pengajian (Contoh: Master / PhD)
            $table->string('faculty'); // Fakulti (Contoh: FSK, FHPK)
            $table->string('status')->default('Active'); // Status terkini: Active, Graduated, dll
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
