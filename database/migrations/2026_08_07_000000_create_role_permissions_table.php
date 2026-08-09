<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role', 50)->default('guru');
            $table->string('feature_key', 50);
            $table->boolean('is_allowed')->default(true);
            $table->timestamps();

            $table->unique(['role', 'feature_key']);
        });

        // Seed default features for 'guru' role
        $defaultFeatures = [
            'dashboard' => true,
            'scan_qr' => true,
            'monitoring' => true,
            'data_santri' => false,
            'data_guru' => false,
            'generate_qr' => false,
            'laporan' => false,
            'general_settings' => false,
            'backup' => false,
        ];

        $now = now();
        foreach ($defaultFeatures as $key => $isAllowed) {
            DB::table('role_permissions')->insert([
                'role' => 'guru',
                'feature_key' => $key,
                'is_allowed' => $isAllowed,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
