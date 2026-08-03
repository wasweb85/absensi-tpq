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
        Schema::table('tb_guru', function (Blueprint $table) {
            $table->boolean('can_crud_siswa')->default(false)->after('rfid_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_guru', function (Blueprint $table) {
            $table->dropColumn('can_crud_siswa');
        });
    }
};
