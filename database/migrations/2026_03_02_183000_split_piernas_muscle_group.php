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
        // 1. Create the new muscle groups if they don't exist
        $newGroups = [
            ['name' => 'Cuádriceps', 'slug' => Str::slug('Cuádriceps'), 'color' => '#70A1FF'],
            ['name' => 'Isquios', 'slug' => Str::slug('Isquios'), 'color' => '#3742FA'],
            ['name' => 'Glúteos', 'slug' => Str::slug('Glúteos'), 'color' => '#FF6348'],
        ];

        foreach ($newGroups as $group) {
            DB::table('muscle_groups')->updateOrInsert(
            ['slug' => $group['slug']],
            ['name' => $group['name'], 'color' => $group['color'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Get the IDs of the new groups
        $cuadricepsId = DB::table('muscle_groups')->where('slug', Str::slug('Cuádriceps'))->value('id');
        $isquiosId = DB::table('muscle_groups')->where('slug', Str::slug('Isquios'))->value('id');
        $gluteosId = DB::table('muscle_groups')->where('slug', Str::slug('Glúteos'))->value('id');

        // 3. Find the old Piernas group ID
        $piernasGroup = DB::table('muscle_groups')->where('slug', 'piernas')->orWhere('name', 'Piernas')->first();

        if ($piernasGroup && $cuadricepsId && $isquiosId && $gluteosId) {
            $piernasId = $piernasGroup->id;

            // Map exercises based on their name matching the seeder logic
            $cuadricepsExercises = ['Sentadilla con Barra', 'Prensa de Piernas', 'Zancadas con Mancuernas', 'Extensión de Cuádriceps', 'Sentadilla Búlgara', 'Sentadilla Goblet', 'Aductores en Máquina', 'Step-Up con Mancuernas'];
            $isquiosExercises = ['Peso Muerto Rumano', 'Curl Femoral Acostado', 'Elevación de Gemelos de Pie'];
            $gluteosExercises = ['Hip Thrust'];

            foreach ($cuadricepsExercises as $name) {
                DB::table('exercises')->where('name', $name)->update(['muscle_group_id' => $cuadricepsId]);
            }
            foreach ($isquiosExercises as $name) {
                DB::table('exercises')->where('name', $name)->update(['muscle_group_id' => $isquiosId]);
            }
            foreach ($gluteosExercises as $name) {
                DB::table('exercises')->where('name', $name)->update(['muscle_group_id' => $gluteosId]);
            }

            // Any remaining exercises in "Piernas", move to Cuádriceps by default
            DB::table('exercises')->where('muscle_group_id', $piernasId)->update(['muscle_group_id' => $cuadricepsId]);

            // 4. Update existing plans (since muscle groups are stored in JSON `muscle_groups` array in training_days)
            $trainingDays = DB::table('training_days')->get();
            foreach ($trainingDays as $day) {
                $groups = json_decode($day->muscle_groups, true);
                $volumes = json_decode($day->target_volumes, true);

                $updated = false;
                if (is_array($groups)) {
                    $key = array_search('Piernas', $groups);
                    if ($key !== false) {
                        unset($groups[$key]);
                        // Add the new ones
                        $groups[] = 'Cuádriceps';
                        $groups[] = 'Isquios';
                        $groups[] = 'Glúteos';
                        // Re-index array
                        $groups = array_values(array_unique($groups));
                        $updated = true;
                    }
                }

                if (is_array($volumes) && isset($volumes['Piernas'])) {
                    $val = $volumes['Piernas'];
                    unset($volumes['Piernas']);
                    // Split volume evenly or apply to all
                    $volumes['Cuádriceps'] = $val;
                    $volumes['Isquios'] = $val;
                    $volumes['Glúteos'] = $val;
                    $updated = true;
                }

                if ($updated) {
                    DB::table('training_days')->where('id', $day->id)->update([
                        'muscle_groups' => json_encode($groups),
                        'target_volumes' => json_encode($volumes)
                    ]);
                }
            }

            // 5. Finally, delete the old Piernas group
            DB::table('muscle_groups')->where('id', $piernasId)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // Not necessary for this fix
    }
};
