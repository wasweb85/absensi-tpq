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
        Schema::table('tb_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_siswa', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('tb_siswa', 'nama_ayah')) {
                $table->string('nama_ayah', 255)->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('tb_siswa', 'nama_ibu')) {
                $table->string('nama_ibu', 255)->nullable()->after('nama_ayah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_siswa', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('tb_siswa', 'tanggal_lahir')) $columns[] = 'tanggal_lahir';
            if (Schema::hasColumn('tb_siswa', 'nama_ayah')) $columns[] = 'nama_ayah';
            if (Schema::hasColumn('tb_siswa', 'nama_ibu')) $columns[] = 'nama_ibu';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
