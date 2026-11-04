<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();
        $slug = Str::slug('Pantorrilla');

        $pantorrillaId = DB::table('muscle_groups')->where('slug', $slug)->value('id');

        if (!$pantorrillaId) {
            $pantorrillaId = DB::table('muscle_groups')->insertGetId([
                'name' => 'Pantorrilla',
                'slug' => $slug,
                'color' => '#10B981',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        if ($pantorrillaId) {
            DB::table('exercises')
                ->where(function ($query) {
                    $query->where('name', 'like', '%Gemelo%')
                          ->orWhere('name', 'like', '%Pantorrilla%')
                          ->orWhere('name', 'like', '%Talón%')
                          ->orWhere('name', 'like', '%Talon%')
                          ->orWhere('name', 'like', '%Calf%');
                })
                ->update(['muscle_group_id' => $pantorrillaId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $pantorrillaGroup = DB::table('muscle_groups')->where('slug', Str::slug('Pantorrilla'))->first();
        $isquiosId = DB::table('muscle_groups')->where('slug', Str::slug('Isquios'))->value('id');

        if ($pantorrillaGroup) {
            if ($isquiosId) {
                DB::table('exercises')->where('muscle_group_id', $pantorrillaGroup->id)->update(['muscle_group_id' => $isquiosId]);
            }
            DB::table('muscle_groups')->where('id', $pantorrillaGroup->id)->delete();
        }
    }
};
