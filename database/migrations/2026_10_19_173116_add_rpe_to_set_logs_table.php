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
        Schema::table('set_logs', function (Blueprint $table) {
            $table->decimal('rpe', 3, 1)->nullable()->after('reps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('set_logs', function (Blueprint $table) {
            $table->dropColumn('rpe');
        });
    }
};
