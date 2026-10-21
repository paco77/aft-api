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
            $table->string('video_url', 500)->nullable()->after('instruction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planned_exercises', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
