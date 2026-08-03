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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo', 225)->nullable();
            $table->string('school_name', 225)->default('SMK 1 Indonesia');
            $table->string('school_year', 225)->default('2024/2025');
            $table->string('copyright', 225)->default('© 2025 All rights reserved.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
