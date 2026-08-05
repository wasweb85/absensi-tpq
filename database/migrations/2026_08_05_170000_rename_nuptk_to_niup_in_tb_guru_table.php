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
        if (Schema::hasColumn('tb_guru', 'nuptk')) {
            Schema::table('tb_guru', function (Blueprint $table) {
                $table->renameColumn('nuptk', 'niup');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tb_guru', 'niup')) {
            Schema::table('tb_guru', function (Blueprint $table) {
                $table->renameColumn('niup', 'nuptk');
            });
        }
    }
};
