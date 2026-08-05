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
        Schema::create('tb_siswa', function (Blueprint $table) {
            $table->integer('id_siswa', true);
            $table->string('nis', 16);
            $table->string('nama_siswa', 255);
            $table->unsignedInteger('id_kelas');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_hp', 32)->nullable();
            $table->string('unique_code', 64)->unique();
            $table->string('rfid_code', 100)->nullable()->index('idx_tb_siswa_rfid_code');

            $table->foreign('id_kelas', 'tb_siswa_id_kelas_foreign')->references('id_kelas')->on('tb_kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa');
    }
};
