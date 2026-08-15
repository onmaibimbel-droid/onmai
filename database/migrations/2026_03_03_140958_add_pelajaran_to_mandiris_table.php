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
        Schema::table('mandiris', function (Blueprint $table) {
                $table->string('pelajaran')->after('kelas'); 
                // contoh: Matematika, Fisika, Kimia, Biologi, Bahasa Indonesia, Bahasa Inggris, dll
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandiris', function (Blueprint $table) {
            //
        });
    }
};
