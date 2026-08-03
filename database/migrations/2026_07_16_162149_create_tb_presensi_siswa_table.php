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
        Schema::create('tb_presensi_siswa', function (Blueprint $table) {
            $table->integer('id_presensi', true);
            $table->integer('id_siswa');
            $table->unsignedInteger('id_kelas')->nullable();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->integer('id_kehadiran');
            $table->string('keterangan', 255);

            $table->foreign('id_siswa', 'tb_presensi_siswa_id_siswa_foreign')->references('id_siswa')->on('tb_siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_kelas', 'tb_presensi_siswa_id_kelas_foreign')->references('id_kelas')->on('tb_kelas')->onUpdate('set null')->onDelete('cascade');
            $table->foreign('id_kehadiran', 'tb_presensi_siswa_id_kehadiran_foreign')->references('id_kehadiran')->on('tb_kehadiran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_presensi_siswa');
    }
};
