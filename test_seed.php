<?php

use App\Models\User;
use App\Models\NutritionPlan;
use App\Models\NutritionPlanMeal;
use App\Models\MealFood;
use App\Models\MonthlyPlan;
use App\Models\TrainingDay;
use App\Models\PlannedExercise;
use App\Models\WorkoutSession;
use App\Models\ExerciseLog;
use App\Models\SetLog;
use App\Models\ClientProgressLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$coachId = 6; // FRANCISCO EDUARDO

// 1. Create a New Client
$clientName = "Cliente Prueba " . Str::random(4);
$client = User::create([
    'name' => $clientName,
    'username' => 'client' . rand(1000, 9999),
    'email' => strtolower(str_replace(' ', '', $clientName)) . '@example.com',
    'password' => Hash::make('password123'),
    'role' => 'client',
    'coach_id' => $coachId,
]);

echo "Created client: {$client->name} (ID: {$client->id})\n";

// 2. Create Nutrition Plan
$nutritionPlan = NutritionPlan::create([
    'coach_id' => $coachId,
    'client_id' => $client->id,
    'name' => 'Plan Ganancia Muscular',
    'total_calories' => 2500,
    'total_protein' => 180,
    'total_carbs' => 250,
    'total_fat' => 70,
    'objective' => 'Ganancia Muscular',
]);

$meal = NutritionPlanMeal::create([
    'nutrition_plan_id' => $nutritionPlan->id,
    'name' => 'Desayuno Fuerte',
    'time' => '08:00:00',
]);

MealFood::create([
    'nutrition_plan_meal_id' => $meal->id,
    'name' => 'Avena con leche y proteína',
    'serving_size' => '1',
    'serving_unit' => 'taza',
    'calories' => 450,
    'protein' => 40,
    'carbs' => 50,
    'fat' => 10,
]);

echo "Created nutrition plan for client.\n";

// 3. Create Workout Plan
$workoutPlan = MonthlyPlan::create([
    'user_id' => $coachId,
    'assigned_client_id' => $client->id,
    'month' => 'Julio',
    'year' => 2026,
    'days_per_week' => 4,
    'split_type' => 'Push Pull Legs',
]);

$trainingDay = TrainingDay::create([
    'monthly_plan_id' => $workoutPlan->id,
    'day_number' => 1,
    'label' => 'Día 1 - Push',
    'notes' => 'Enfocarse en la contracción.',
]);

$exercise = App\Models\Exercise::where('name', 'like', '%Press%')->first() ?? App\Models\Exercise::first();

$plannedEx = PlannedExercise::create([
    'training_day_id' => $trainingDay->id,
    'exercise_id' => $exercise->id,
    'sets' => 4,
    'min_reps' => 8,
    'max_reps' => 12,
    'instruction' => 'Controlar la excéntrica 3 segundos',
    'order' => 1,
]);

echo "Created workout plan and training day.\n";

// 4. Register Workout Sessions
$session = WorkoutSession::create([
    'user_id' => $client->id,
    'training_day_id' => $trainingDay->id,
    'start_time' => Carbon::now()->subDays(2)->setHour(18)->setMinute(0),
    'end_time' => Carbon::now()->subDays(2)->setHour(19)->setMinute(15),
    'duration' => 75,
    'comments' => 'Me sentí muy fuerte hoy, subí pesos.',
]);

$exLog = ExerciseLog::create([
    'workout_session_id' => $session->id,
    'planned_exercise_id' => $plannedEx->id,
    'exercise_id' => $exercise->id,
    'notes' => 'Llegué al fallo en la última.',
]);

for ($i = 1; $i <= 4; $i++) {
    SetLog::create([
        'exercise_log_id' => $exLog->id,
        'set_number' => $i,
        'weight' => 60 + ($i * 5),
        'weight_lb' => (60 + ($i * 5)) * 2.2,
        'reps' => 10 - (int)($i/2),
    ]);
}

echo "Created workout session and logs.\n";

// 5. Add Evaluations
ClientProgressLog::create([
    'coach_id' => $coachId,
    'client_id' => $client->id,
    'recorded_at' => Carbon::now()->subDays(1),
    'weight' => 78.5,
    'measurements' => [
        'chest' => 102,
        'arms' => 38,
        'waist' => 82,
        'legs' => 60
    ],
    'comments' => 'El cliente ha mejorado su composición corporal notoriamente.',
    'front_photo_path' => null,
    'side_photo_path' => null,
    'back_photo_path' => null,
]);

echo "Created evaluation for client.\n";
echo "DONE!\n";
