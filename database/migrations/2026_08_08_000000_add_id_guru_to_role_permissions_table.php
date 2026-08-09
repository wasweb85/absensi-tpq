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
        Schema::table('role_permissions', function (Blueprint $table) {
            // Drop old unique constraint first
            $table->dropUnique(['role', 'feature_key']);
            
            $table->unsignedBigInteger('id_guru')->nullable()->after('role');
            
            // Add new unique index
            $table->unique(['role', 'id_guru', 'feature_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropUnique(['role', 'id_guru', 'feature_key']);
            $table->dropColumn('id_guru');
            $table->unique(['role', 'feature_key']);
        });
    }
};
