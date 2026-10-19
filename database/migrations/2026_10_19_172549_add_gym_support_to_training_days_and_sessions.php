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
        Schema::table('training_days', function (Blueprint $table) {
            $table->unsignedBigInteger('monthly_plan_id')->nullable()->change();
            if (!Schema::hasColumn('training_days', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('training_days', 'estimated_minutes')) {
                $table->integer('estimated_minutes')->default(60)->after('day_number');
            }
            if (!Schema::hasColumn('training_days', 'description')) {
                $table->text('description')->nullable()->after('label');
            }
        });

        Schema::table('workout_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            if (!Schema::hasColumn('workout_sessions', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_days', function (Blueprint $table) {
            if (Schema::hasColumn('training_days', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('training_days', 'estimated_minutes')) {
                $table->dropColumn('estimated_minutes');
            }
            if (Schema::hasColumn('training_days', 'description')) {
                $table->dropColumn('description');
            }
        });

        Schema::table('workout_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('workout_sessions', 'guest_name')) {
                $table->dropColumn('guest_name');
            }
        });
    }
};
