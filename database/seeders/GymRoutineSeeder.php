<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TrainingDay;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Support\Facades\Hash;

class GymRoutineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Gym User
        $gymUser = User::updateOrCreate(
            ['username' => 'gimnasio'],
            [
                'name' => 'AF Performance Center',
                'email' => 'gimnasio@aftraining.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_GYM,
                'is_active' => true,
            ]
        );

        // Clear previous gym-only routines for fresh seed
        $existingRoutines = TrainingDay::where('user_id', $gymUser->id)->whereNull('monthly_plan_id')->get();
        foreach ($existingRoutines as $r) {
            $r->delete();
        }

        // Helper to find or create an exercise
        $getExercise = function (string $name, string $muscleGroup = 'Espalda') {
            $ex = Exercise::where('name', 'like', "%{$name}%")->first();
            if (!$ex) {
                $mg = MuscleGroup::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($muscleGroup)],
                    ['name' => $muscleGroup, 'color' => '#3b82f6']
                );
                $ex = Exercise::create([
                    'name' => $name,
                    'slug' => \Illuminate\Support\Str::slug($name) . '-' . rand(100, 999),
                    'muscle_group_id' => $mg->id,
                    'equipment' => 'Máquina / Barra',
                    'primary_muscles' => [$muscleGroup],
                    'is_active' => true,
                ]);
            }
            return $ex;
        };

        // 2. Routine 1: Sesión de Espalda, Bíceps y Hombros (Image 1 & 2)
        $r1 = TrainingDay::create([
            'user_id' => $gymUser->id,
            'monthly_plan_id' => null,
            'label' => 'Sesión de Espalda, Bíceps y Hombros',
            'description' => 'Enfoque en tracciones para amplitud dorsal y trabajo de deltoides posterior y flexores de codo.',
            'comment' => 'Mantener retracción escapular estricta en todo el recorrido. Fase excéntrica controlada de 2s, evitar arquear zona lumbar en el punto de máximo estiramiento.',
            'estimated_minutes' => 65,
            'day_number' => 1,
            'muscle_groups' => ['Espalda', 'Bíceps', 'Hombros'],
        ]);

        $r1Exercises = [
            ['Jersey con cuerdas o barras', 3, 10, 15, 'Dorsal Ancho & Redondo Mayor', 'Espalda'],
            ['Remo en Polea Baja', 3, 6, 12, 'Dorsal y Trapecio medio con agarre estrecho', 'Espalda'],
            ['Jalón al Pecho', 3, 5, 10, 'Tracción vertical con parada de 1s en clavícula', 'Espalda'],
            ['Remo en Máquina T', 2, 8, 12, 'Apoyo en pecho para aislar espalda media', 'Espalda'],
            ['Curl de Bíceps con Barra', 2, 10, 15, 'Codos pegados al torso, contracción máxima', 'Bíceps'],
            ['Vuelos deltoides posterior', 3, 10, 12, 'Deltoides posterior en polea o mancuerna', 'Hombros'],
        ];

        foreach ($r1Exercises as $item) {
            $exModel = $getExercise($item[0], $item[5]);
            $r1->plannedExercises()->create([
                'exercise_id' => $exModel->id,
                'sets' => $item[1],
                'min_reps' => $item[2],
                'max_reps' => $item[3],
                'instruction' => $item[4],
            ]);
        }

        // 3. Routine 2: Sesión de Hombros, Pecho y Tríceps
        $r2 = TrainingDay::create([
            'user_id' => $gymUser->id,
            'monthly_plan_id' => null,
            'label' => 'Sesión de Hombros, Pecho y Tríceps',
            'description' => '5 Ejercicios disponibles • Prensa Militar, Prensa Banca Inclinado, Fondos',
            'comment' => 'Codos en 45 grados en los empujes para proteger manguito rotador. Pausa de 1s en el punto más bajo.',
            'estimated_minutes' => 60,
            'day_number' => 2,
            'muscle_groups' => ['Hombros', 'Pecho', 'Tríceps'],
        ]);

        $r2Exercises = [
            ['Press Militar con Barra', 3, 6, 10, 'Empuje vertical estricto', 'Hombros'],
            ['Press Banca Inclinado', 3, 8, 12, 'Énfasis en haz clavicular', 'Pecho'],
            ['Fondos en Paralelas', 3, 8, 12, 'Inclinación leve hacia adelante', 'Pecho'],
            ['Elevaciones Laterales', 3, 12, 15, 'Deltoides lateral constante tensión', 'Hombros'],
            ['Extensión de Tríceps en Polea', 3, 10, 15, 'Tríceps braquial completo', 'Tríceps'],
        ];

        foreach ($r2Exercises as $item) {
            $exModel = $getExercise($item[0], $item[5]);
            $r2->plannedExercises()->create([
                'exercise_id' => $exModel->id,
                'sets' => $item[1],
                'min_reps' => $item[2],
                'max_reps' => $item[3],
                'instruction' => $item[4],
            ]);
        }

        // 4. Routine 3: Sesión de Bíceps, Tríceps y Hombros
        $r3 = TrainingDay::create([
            'user_id' => $gymUser->id,
            'monthly_plan_id' => null,
            'label' => 'Sesión de Bíceps, Tríceps y Hombros',
            'description' => '5 Ejercicios disponibles • Super-series de brazos e hipertrofia lateral',
            'comment' => 'Trabajo de densidad con descansos cortos de 60s entre super-series.',
            'estimated_minutes' => 55,
            'day_number' => 3,
            'muscle_groups' => ['Bíceps', 'Tríceps', 'Hombros'],
        ]);

        $r3Exercises = [
            ['Curl con Mancuernas Inclinado', 3, 10, 12, 'Máximo estiramiento de bíceps', 'Bíceps'],
            ['Press Francés con Barra Z', 3, 8, 12, 'Extensión limpia detrás de la cabeza', 'Tríceps'],
            ['Curl Martillo en Polea', 3, 10, 12, 'Braquial anterior y antebrazo', 'Bíceps'],
            ['Fondos entre Bancos', 3, 10, 15, 'Aislamiento de tríceps', 'Tríceps'],
            ['Elevaciones Laterales en Polea', 3, 12, 15, 'Tensión mecánica continua', 'Hombros'],
        ];

        foreach ($r3Exercises as $item) {
            $exModel = $getExercise($item[0], $item[5]);
            $r3->plannedExercises()->create([
                'exercise_id' => $exModel->id,
                'sets' => $item[1],
                'min_reps' => $item[2],
                'max_reps' => $item[3],
                'instruction' => $item[4],
            ]);
        }

        // 5. Routine 4: Sesión de Cuádriceps, Isquios y Glúteos
        $r4 = TrainingDay::create([
            'user_id' => $gymUser->id,
            'monthly_plan_id' => null,
            'label' => 'Sesión de Cuádriceps, Isquios y Glúteos',
            'description' => '5 Ejercicios disponibles • Sentadilla Hack, Peso Muerto Rumano, Prensa',
            'comment' => 'Profundidad máxima sin perder alineación pélvica. Conexión mente-músculo en cada repetición.',
            'estimated_minutes' => 70,
            'day_number' => 4,
            'muscle_groups' => ['Cuádriceps', 'Isquios', 'Glúteos'],
        ]);

        $r4Exercises = [
            ['Sentadilla Hack', 3, 6, 10, 'Bajar controlado en 3 segundos', 'Cuádriceps'],
            ['Prensa de Piernas 45°', 3, 10, 12, 'Pies al centro para cuádriceps', 'Cuádriceps'],
            ['Peso Muerto Rumano', 3, 8, 12, 'Enfoque en estiramiento de isquios', 'Isquios'],
            ['Curl Femoral Tumbado', 3, 10, 12, 'Pausa de 1s en flexión', 'Isquios'],
            ['Hip Thrust con Barra', 3, 8, 12, 'Bloqueo glúteo arriba 2s', 'Glúteos'],
        ];

        foreach ($r4Exercises as $item) {
            $exModel = $getExercise($item[0], $item[5]);
            $r4->plannedExercises()->create([
                'exercise_id' => $exModel->id,
                'sets' => $item[1],
                'min_reps' => $item[2],
                'max_reps' => $item[3],
                'instruction' => $item[4],
            ]);
        }

        // 6. Routine 5: Sesión de Pecho y Espalda
        $r5 = TrainingDay::create([
            'user_id' => $gymUser->id,
            'monthly_plan_id' => null,
            'label' => 'Sesión de Pecho y Espalda',
            'description' => '5 Ejercicios disponibles • Antagonistas torso pesados, musculares',
            'comment' => 'Trabajo alternado entre empujes y tracciones para máximo bombeo vascular.',
            'estimated_minutes' => 60,
            'day_number' => 5,
            'muscle_groups' => ['Pecho', 'Espalda'],
        ]);

        $r5Exercises = [
            ['Press de Banca Plano', 3, 6, 10, 'Fuerza base de torso', 'Pecho'],
            ['Remo con Barra', 3, 6, 10, 'Torso paralelo al suelo', 'Espalda'],
            ['Aperturas en Poleas', 3, 10, 15, 'Apertura controlada al pecho', 'Pecho'],
            ['Jalón al Pecho Neutro', 3, 8, 12, 'Tracción agarre neutro', 'Espalda'],
            ['Pullover con Mancuerna', 3, 10, 15, 'Expansión de caja torácica', 'Pecho'],
        ];

        foreach ($r5Exercises as $item) {
            $exModel = $getExercise($item[0], $item[5]);
            $r5->plannedExercises()->create([
                'exercise_id' => $exModel->id,
                'sets' => $item[1],
                'min_reps' => $item[2],
                'max_reps' => $item[3],
                'instruction' => $item[4],
            ]);
        }
    }
}
