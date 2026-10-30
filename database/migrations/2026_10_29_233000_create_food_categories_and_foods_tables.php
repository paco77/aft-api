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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('foods');
        Schema::dropIfExists('food_categories');
        Schema::enableForeignKeyConstraints();

        Schema::create('food_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_category_id')->constrained('food_categories')->cascadeOnDelete();
            $table->string('subcategory')->nullable();
            $table->string('name');
            $table->string('state')->nullable();
            $table->string('portion_type');
            $table->string('portion_detail')->nullable();
            $table->decimal('base_quantity', 8, 2)->default(100);
            $table->string('base_unit', 30)->default('g');
            $table->decimal('gram_weight', 8, 2)->nullable();
            $table->decimal('calories', 8, 2)->default(0);
            $table->decimal('protein', 8, 2)->default(0);
            $table->decimal('carbs', 8, 2)->default(0);
            $table->decimal('fat', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['food_category_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
        Schema::dropIfExists('food_categories');
    }
};

