<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `tb_kelas` MODIFY COLUMN `tingkat` VARCHAR(50) NOT NULL, MODIFY COLUMN `index_kelas` VARCHAR(50) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `tb_kelas` MODIFY COLUMN `tingkat` VARCHAR(10) NOT NULL, MODIFY COLUMN `index_kelas` VARCHAR(5) NULL");
    }
};
