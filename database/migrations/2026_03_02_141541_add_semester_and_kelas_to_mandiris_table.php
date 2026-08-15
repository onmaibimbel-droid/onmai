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
            $table->tinyInteger('semester')->after('nama_mapel'); 
        // isi 1 atau 2

        $table->string('kelas')->after('semester'); 
        // contoh: X IPA 1, XI IPS 2

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
