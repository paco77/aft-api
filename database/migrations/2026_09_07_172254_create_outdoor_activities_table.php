<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outdoor_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->default('Running');
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->decimal('avg_pace_min_km', 8, 2)->nullable();
            $table->json('coordinates')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outdoor_activities');
    }
};
