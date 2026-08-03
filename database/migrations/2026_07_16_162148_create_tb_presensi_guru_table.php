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
        Schema::create('tb_presensi_guru', function (Blueprint $table) {
            $table->integer('id_presensi', true);
            $table->integer('id_guru')->nullable();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->integer('id_kehadiran');
            $table->string('keterangan', 255);

            $table->foreign('id_guru', 'tb_presensi_guru_id_guru_foreign')->references('id_guru')->on('tb_guru')->onUpdate('set null')->onDelete('cascade');
            $table->foreign('id_kehadiran', 'tb_presensi_guru_id_kehadiran_foreign')->references('id_kehadiran')->on('tb_kehadiran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_presensi_guru');
    }
};
