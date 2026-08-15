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
            $table->unsignedBigInteger('judulutbk_id')->nullable()->after('ujian_id');
            $table->foreign('judulutbk_id')->references('id')->on('judulutbks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawabans', function (Blueprint $table) {
            $table->dropForeign(['judulutbk_id']);
            $table->dropColumn('judulutbk_id');
        });
    }
};
