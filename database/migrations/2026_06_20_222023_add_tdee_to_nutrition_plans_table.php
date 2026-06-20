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
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->integer('tdee')->nullable()->after('description');
            $table->integer('target_calories')->nullable()->after('tdee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->dropColumn(['tdee', 'target_calories']);
        });
    }
};
