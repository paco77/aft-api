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
            $table->string('gender')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->integer('age')->nullable();
            $table->decimal('activity_level', 4, 2)->nullable();
            $table->string('formula')->nullable();
            $table->string('objective')->nullable();
            $table->decimal('caloric_adjustment', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'weight',
                'height',
                'age',
                'activity_level',
                'formula',
                'objective',
                'caloric_adjustment'
            ]);
        });
    }
};
