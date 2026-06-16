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
        Schema::create('meal_foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_plan_meal_id')->constrained()->cascadeOnDelete();
            $table->string('fatsecret_food_id')->nullable();
            $table->string('name');
            $table->decimal('serving_size', 8, 2)->default(0);
            $table->string('serving_unit');
            $table->decimal('calories', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('carbs', 8, 2)->default(0);
            $table->decimal('fat', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_foods');
    }
};
