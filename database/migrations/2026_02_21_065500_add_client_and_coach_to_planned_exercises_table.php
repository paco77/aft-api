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
        Schema::table('planned_exercises', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('exercise_id')->constrained('users')->onDelete('set null');
            $table->foreignId('coach_id')->nullable()->after('client_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planned_exercises', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['coach_id']);
            $table->dropColumn(['client_id', 'coach_id']);
        });
    }
};
