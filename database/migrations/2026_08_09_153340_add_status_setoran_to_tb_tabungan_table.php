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
        Schema::table('tb_tabungan', function (Blueprint $table) {
            $table->enum('status_setoran', ['belum', 'sudah'])->default('belum')->after('keterangan');
            $table->unsignedBigInteger('id_setoran')->nullable()->after('status_setoran');

            $table->foreign('id_setoran')->references('id_setoran')->on('tb_setoran_bendahara')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_tabungan', function (Blueprint $table) {
            $table->dropForeign(['id_setoran']);
            $table->dropColumn(['status_setoran', 'id_setoran']);
        });
    }
};
