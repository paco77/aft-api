<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\MonthlyPlan;
use App\Models\TrainingDay;
use App\Models\WorkoutSession;
use Carbon\Carbon;

$client = User::where('username', 'lanye')->first();
if (!$client) {
    echo "User lanye not found!\n";
    exit;
}

echo "Found client: {$client->name} (ID: {$client->id})\n";

// Get or create a plan for lanye
$plan = MonthlyPlan::where('assigned_client_id', $client->id)->first();
if (!$plan) {
    $plan = MonthlyPlan::create([
        'user_id' => $client->coach_id ?? 1,
        'assigned_client_id' => $client->id,
        'month' => 'Julio',
        'year' => 2026,
        'days_per_week' => 4,
        'split_type' => 'Push Pull Legs',
    ]);
    echo "Created new plan for lanye.\n";
}

$trainingDay = TrainingDay::where('monthly_plan_id', $plan->id)->first();
if (!$trainingDay) {
    $trainingDay = TrainingDay::create([
        'monthly_plan_id' => $plan->id,
        'day_number' => 1,
        'label' => 'Día 1 - Push',
        'notes' => 'Enfocarse en la contracción.',
    ]);
    echo "Created training day.\n";
}

// Create a new session with duration
$durationMinutes = rand(45, 90);
$startTime = Carbon::now()->subMinutes($durationMinutes);

$session = WorkoutSession::create([
    'user_id' => $client->id,
    'training_day_id' => $trainingDay->id,
    'start_time' => $startTime,
    'end_time' => Carbon::now(),
    'duration' => $durationMinutes,
    'comments' => 'Entrenamiento completado automáticamente (prueba)',
]);

// Since there is no duration column on the workout_sessions table, we cannot save it there.
// If duration was added in another migration, we would save it, but we can also just output it.
echo "Created session (ID: {$session->id}) for lanye. Start: {$startTime}, End: " . Carbon::now() . "\n";
echo "Duration generated: {$durationMinutes} minutes.\n";
