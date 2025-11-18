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
        //mengubah tipe ata ke json
        //change ubah
        Schema::table('schedules', function (Blueprint $table) {
            $table->json('hours')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //ketiak kita ingin membatakan, kembalikan ke tipe data awal
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('hours')->change();
        });
    }
};
