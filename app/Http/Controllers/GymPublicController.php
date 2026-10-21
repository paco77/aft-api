<?php

namespace App\Http\Controllers;

use App\Models\TrainingDay;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Models\ExerciseLog;
use App\Models\SetLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GymPublicController extends Controller
{
    /**
     * Resolve gym user by id or username.
     */
    protected function resolveGym(string $identifier): User
    {
        $gym = User::where(function($query) use ($identifier) {
            $query->where('id', $identifier)
                  ->orWhere('username', $identifier);
        })
        ->whereIn('role', ['gimnasio', 'admin'])
        ->firstOrFail();

        return $gym;
    }

    /**
     * Display the Gym's workout routines (Image 1: "Programas de Rutinas").
     */
    public function showRoutines(string $identifier, Request $request)
    {
        $gym = $this->resolveGym($identifier);

        $routines = TrainingDay::with(['plannedExercises.exercise.muscleGroup'])
            ->where('user_id', $gym->id)
            ->whereNull('monthly_plan_id')
            ->orderBy('day_number')
            ->get();

        // Selected routine ID from query or first routine
        $selectedId = $request->query('selected');
        $selectedRoutine = $routines->firstWhere('id', $selectedId) ?? $routines->first();

        return view('gym.routines', compact('gym', 'routines', 'selectedRoutine'));
    }

    /**
     * Display the live active workout session (Image 2: "Active Workout Session").
     */
    public function liveWorkout(string $identifier, TrainingDay $trainingDay)
    {
        $gym = $this->resolveGym($identifier);

        // Verify routine belongs to this gym
        if ($trainingDay->user_id !== $gym->id) {
            abort(404, 'La rutina no pertenece a este gimnasio.');
        }

        $trainingDay->load(['plannedExercises.exercise.muscleGroup', 'plannedExercises.exerciseLogs.setLogs']);

        // Sibling routines for day sequence indicator
        $allRoutines = TrainingDay::where('user_id', $gym->id)
            ->whereNull('monthly_plan_id')
            ->orderBy('day_number')
            ->get();

        $currentRoutineIndex = $allRoutines->search(function ($item) use ($trainingDay) {
            return $item->id === $trainingDay->id;
        });
        $currentDayNumber = ($currentRoutineIndex !== false) ? ($currentRoutineIndex + 1) : 1;
        $totalDays = $allRoutines->count() ?: 1;

        $exercisesPayload = $trainingDay->plannedExercises->map(function ($p) {
            $initialSets = [];
            for ($i = 0; $i < max(1, $p->sets); $i++) {
                $initialSets[] = [
                    'set_number' => $i + 1,
                    'weight' => '',
                    'reps' => '',
                    'rpe' => '',
                    'checked' => false,
                ];
            }

            return [
                'id' => $p->id,
                'exercise_id' => $p->exercise_id,
                'name' => $p->exercise->name ?? 'Ejercicio',
                'muscle_group' => $p->exercise->muscleGroup->name ?? 'General',
                'primary_muscle' => is_array($p->exercise->primary_muscles) ? implode(', ', $p->exercise->primary_muscles) : ($p->exercise->primary_muscles ?? 'Músculo Principal'),
                'instruction' => $p->instruction,
                'video_url' => $p->video_url ?: ($p->exercise->video_url ?? null),
                'target_sets' => $p->sets,
                'min_reps' => $p->min_reps,
                'max_reps' => $p->max_reps,
                'previous_sets' => [
                    ['load' => 40.0, 'reps' => 12],
                    ['load' => 45.0, 'reps' => 10],
                    ['load' => 45.0, 'reps' => 10],
                ],
                'sets' => $initialSets,
            ];
        });

        return view('gym.workout', compact('gym', 'trainingDay', 'allRoutines', 'currentDayNumber', 'totalDays', 'exercisesPayload'));
    }

    /**
     * Save workout session and set logs submitted from live workout.
     */
    public function saveWorkout(Request $request)
    {
        $validated = $request->validate([
            'training_day_id' => 'required|exists:training_days,id',
            'guest_name' => 'nullable|string|max:100',
            'start_time' => 'nullable|string',
            'duration' => 'nullable|string|max:20',
            'comments' => 'nullable|string|max:1000',
            'exercises' => 'required|array',
            'exercises.*.planned_exercise_id' => 'required|exists:planned_exercises,id',
            'exercises.*.sets' => 'required|array',
            'exercises.*.sets.*.set_number' => 'required|integer',
            'exercises.*.sets.*.weight' => 'nullable|numeric',
            'exercises.*.sets.*.weight_lb' => 'nullable|numeric',
            'exercises.*.sets.*.reps' => 'nullable|integer',
            'exercises.*.sets.*.rpe' => 'nullable|numeric',
        ]);

        $userId = auth()->check() ? auth()->id() : null;
        $guestName = $validated['guest_name'] ?? ($userId ? null : 'Atleta Gimnasio');

        // Parse duration to integer minutes
        $durationMinutes = 45;
        if (!empty($validated['duration'])) {
            if (is_numeric($validated['duration'])) {
                $durationMinutes = (int) $validated['duration'];
            } elseif (preg_match('/^(\d+):(\d+)(?::(\d+))?$/', $validated['duration'], $m)) {
                $hours = (int) $m[1];
                $minutes = (int) $m[2];
                $durationMinutes = max(1, ($hours * 60) + $minutes);
            }
        }

        $session = DB::transaction(function () use ($validated, $userId, $guestName, $durationMinutes) {
            $workoutSession = WorkoutSession::create([
                'user_id' => $userId,
                'guest_name' => $guestName,
                'training_day_id' => $validated['training_day_id'],
                'start_time' => !empty($validated['start_time']) ? Carbon::parse($validated['start_time']) : now()->subMinutes($durationMinutes),
                'end_time' => now(),
                'duration' => $durationMinutes,
                'comments' => $validated['comments'] ?? null,
            ]);

            foreach ($validated['exercises'] as $exData) {
                $exerciseLog = ExerciseLog::create([
                    'workout_session_id' => $workoutSession->id,
                    'planned_exercise_id' => $exData['planned_exercise_id'],
                ]);

                foreach ($exData['sets'] as $setData) {
                    $rawWeight = (isset($setData['weight']) && $setData['weight'] !== null && $setData['weight'] !== '') ? (float) $setData['weight'] : 0.0;
                    $rawWeightLb = (isset($setData['weight_lb']) && $setData['weight_lb'] !== null && $setData['weight_lb'] !== '') ? (float) $setData['weight_lb'] : 0.0;
                    $rawReps = (isset($setData['reps']) && $setData['reps'] !== null && $setData['reps'] !== '') ? (int) $setData['reps'] : 0;
                    $rawRpe = (isset($setData['rpe']) && $setData['rpe'] !== null && $setData['rpe'] !== '') ? (float) $setData['rpe'] : 0.0;

                    SetLog::create([
                        'exercise_log_id' => $exerciseLog->id,
                        'set_number' => $setData['set_number'],
                        'weight' => $rawWeight,
                        'weight_lb' => $rawWeightLb,
                        'reps' => $rawReps,
                        'rpe' => $rawRpe,
                    ]);
                }
            }

            return $workoutSession;
        });

        return response()->json([
            'success' => true,
            'message' => '¡Entrenamiento registrado exitosamente!',
            'session_id' => $session->id,
        ]);
    }
}
