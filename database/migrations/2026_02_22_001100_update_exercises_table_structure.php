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
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('muscle_group');
            $table->foreignId('muscle_group_id')->after('name')->nullable()->constrained()->onDelete('set null');
            $table->json('primary_muscles')->nullable();
            $table->json('secondary_muscles')->nullable();
            $table->json('benefits')->nullable();
            $table->string('level')->default('Principiante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign(['muscle_group_id']);
            $table->dropColumn(['muscle_group_id', 'primary_muscles', 'secondary_muscles', 'benefits', 'level']);
            $table->string('muscle_group')->after('name');
        });
    }
};
