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
        Schema::create('tb_tabungan', function (Blueprint $table) {
            $table->increments('id_tabungan');
            $table->integer('id_siswa');
            $table->unsignedBigInteger('id_user')->nullable(); // user who recorded it
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['setor', 'tarik']);
            $table->integer('nominal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_siswa')->references('id_siswa')->on('tb_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tabungan');
    }
};
