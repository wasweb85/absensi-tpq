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
        if (!Schema::hasTable('guru_kelas')) {
            Schema::create('guru_kelas', function (Blueprint $table) {
                $table->id();
                $table->integer('id_guru');
                $table->unsignedInteger('id_kelas');
                $table->timestamps();

                $table->foreign('id_guru')->references('id_guru')->on('tb_guru')->onDelete('cascade');
                $table->foreign('id_kelas')->references('id_kelas')->on('tb_kelas')->onDelete('cascade');
                $table->unique(['id_guru', 'id_kelas']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_kelas');
    }
};
