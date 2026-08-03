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
        Schema::create('tb_kelas', function (Blueprint $table) {
            $table->increments('id_kelas');
            $table->string('tingkat', 10);
            $table->string('index_kelas', 5);
            $table->integer('id_wali_kelas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_wali_kelas', 'fk_tb_kelas_id_wali_kelas')->references('id_guru')->on('tb_guru')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kelas');
    }
};
