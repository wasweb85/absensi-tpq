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
        Schema::create('tb_setoran_bendahara', function (Blueprint $table) {
            $table->id('id_setoran');
            $table->integer('id_guru');
            $table->unsignedInteger('id_bendahara');
            $table->dateTime('tanggal');
            $table->integer('nominal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('tb_guru')->onDelete('cascade');
            $table->foreign('id_bendahara')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_setoran_bendahara');
    }
};
