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
            if (!Schema::hasColumn('planned_exercises', 'rir')) {
                $table->string('rir', 20)->nullable()->after('max_reps');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planned_exercises', function (Blueprint $table) {
            if (Schema::hasColumn('planned_exercises', 'rir')) {
                $table->dropColumn('rir');
            }
        });
    }
};

