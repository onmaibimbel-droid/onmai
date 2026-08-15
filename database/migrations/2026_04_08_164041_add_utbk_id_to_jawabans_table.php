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
        Schema::table('jawabans', function (Blueprint $table) {
            $table->unsignedBigInteger('utbk_id')->nullable()->after('soal_id');
            $table->foreign('utbk_id')->references('id')->on('utbks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawabans', function (Blueprint $table) {
            $table->dropForeign(['utbk_id']);
            $table->dropColumn('utbk_id');
        });
    }
};
