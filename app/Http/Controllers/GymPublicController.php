<?php

namespace App\Http\Controllers;

use App\Models\TrainingDay;
use App\Models\MonthlyPlan;
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
        ->whereIn('role', ['gimnasio', 'admin', 'coach', 'independiente'])
        ->first();

        if (!$gym) {
            $gym = User::whereIn('role', ['gimnasio', 'admin'])->first() ?? User::firstOrFail();
        }

        return $gym;
    }

    /**
     * Display the Gym's workout routines (Image 1: "Programas de Rutinas").
     */
    public function showRoutines(string $identifier, Request $request)
    {
        $gym = $this->resolveGym($identifier);

        // Open Gym Routines
        $routines = TrainingDay::with(['plannedExercises.exercise.muscleGroup'])
            ->where('user_id', $gym->id)
            ->whereNull('monthly_plan_id')
            ->orderBy('day_number')
            ->get();

        // Personal training plans for logged in client/athlete
        $clientPlans = collect();
        if (auth()->check()) {
            $user = auth()->user();
            $clientPlans = MonthlyPlan::with([
                'user',
                'trainingDays.plannedExercises.exercise.muscleGroup'
            ])
            ->where(function ($query) use ($user) {
                $query->where('assigned_client_id', $user->id)
                      ->orWhere('user_id', $user->id);
            })
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->get();
        }

        // Active tab: 'client' or 'gym'
        $activeTab = $request->query('tab');
        if (!$activeTab) {
            if (auth()->check() && (auth()->user()->role === 'client' || $clientPlans->isNotEmpty())) {
                $activeTab = 'client';
            } else {
                $activeTab = 'gym';
            }
        }

        $activePlan = null;
        $selectedRoutine = null;

        if ($activeTab === 'client' && $clientPlans->isNotEmpty()) {
            $planId = $request->query('plan_id');
            $activePlan = $clientPlans->firstWhere('id', $planId) ?? $clientPlans->first();

            $selectedId = $request->query('selected');
            $selectedRoutine = $activePlan->trainingDays->firstWhere('id', $selectedId) ?? $activePlan->trainingDays->first();
        } else {
            $activeTab = 'gym';
            $selectedId = $request->query('selected');
            $selectedRoutine = $routines->firstWhere('id', $selectedId) ?? $routines->first();
        }

        return view('gym.routines', compact(
            'gym',
            'routines',
            'clientPlans',
            'activeTab',
            'activePlan',
            'selectedRoutine'
        ));
    }

    /**
     * Display the live active workout session (Image 2: "Active Workout Session").
     */
    public function liveWorkout(string $identifier, TrainingDay $trainingDay)
    {
        $gym = $this->resolveGym($identifier);

        // Verify routine belongs to this gym OR belongs to authenticated client's plan
        $isGymRoutine = ($trainingDay->user_id === $gym->id && is_null($trainingDay->monthly_plan_id));
        $isClientRoutine = false;

        if (!$isGymRoutine) {
            if ($trainingDay->monthly_plan_id && auth()->check()) {
                $plan = $trainingDay->monthlyPlan;
                if ($plan && ($plan->assigned_client_id === auth()->id() || $plan->user_id === auth()->id())) {
                    $isClientRoutine = true;
                }
            }
        }

        if (!$isGymRoutine && !$isClientRoutine) {
            abort(403, 'No tienes permiso para acceder a esta sesión de entrenamiento o no pertenece a este gimnasio.');
        }

        $trainingDay->load(['plannedExercises.exercise.muscleGroup', 'plannedExercises.exerciseLogs.setLogs']);

        // Sibling routines for day sequence indicator
        if ($isClientRoutine) {
            $allRoutines = TrainingDay::where('monthly_plan_id', $trainingDay->monthly_plan_id)
                ->orderBy('day_number')
                ->get();
        } else {
            $allRoutines = TrainingDay::where('user_id', $gym->id)
                ->whereNull('monthly_plan_id')
                ->orderBy('day_number')
                ->get();
        }

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
                    'weight_lb' => '',
                    'reps' => '',
                    'rpe' => '',
                    'checked' => false,
                ];
            }

            $pVideo = $p->video_url ? trim($p->video_url) : null;
            $exVideo = ($p->exercise && $p->exercise->video_url) ? trim($p->exercise->video_url) : null;

            $gifUrl = null;
            $videoUrl = null;

            if ($pVideo && preg_match('/\.gif(\?.*)?$/i', $pVideo)) {
                $gifUrl = $pVideo;
            } elseif ($exVideo && preg_match('/\.gif(\?.*)?$/i', $exVideo)) {
                $gifUrl = $exVideo;
            }

            if ($pVideo && !preg_match('/\.gif(\?.*)?$/i', $pVideo)) {
                $videoUrl = $pVideo;
            } elseif ($exVideo && !preg_match('/\.gif(\?.*)?$/i', $exVideo)) {
                $videoUrl = $exVideo;
            }

            if (!$videoUrl && $pVideo && !preg_match('/\.gif(\?.*)?$/i', $pVideo)) {
                $videoUrl = $pVideo;
            }

            // Retrieve previous session for this exercise (for user or routine)
            $userId = auth()->id();
            $lastExerciseLog = \App\Models\ExerciseLog::where('planned_exercise_id', $p->id)
                ->when($userId, function ($q) use ($userId) {
                    $q->whereHas('workoutSession', function ($sq) use ($userId) {
                        $sq->where('user_id', $userId);
                    });
                })
                ->with(['setLogs' => function ($q) {
                    $q->orderBy('set_number');
                }, 'workoutSession'])
                ->latest('id')
                ->first();

            if (!$lastExerciseLog && $p->exercise_id) {
                $lastExerciseLog = \App\Models\ExerciseLog::whereHas('plannedExercise', function ($q) use ($p) {
                    $q->where('exercise_id', $p->exercise_id);
                })
                ->when($userId, function ($q) use ($userId) {
                    $q->whereHas('workoutSession', function ($sq) use ($userId) {
                        $sq->where('user_id', $userId);
                    });
                })
                ->with(['setLogs' => function ($q) {
                    $q->orderBy('set_number');
                }, 'workoutSession'])
                ->latest('id')
                ->first();
            }

            $lastSessionDate = null;
            $previousSets = [];

            if ($lastExerciseLog && $lastExerciseLog->setLogs->isNotEmpty()) {
                $lastSessionDate = $lastExerciseLog->workoutSession && $lastExerciseLog->workoutSession->start_time
                    ? $lastExerciseLog->workoutSession->start_time->format('d/m/Y')
                    : ($lastExerciseLog->created_at ? $lastExerciseLog->created_at->format('d/m/Y') : now()->format('d/m/Y'));

                foreach ($lastExerciseLog->setLogs as $sLog) {
                    $wKg = (float) $sLog->weight;
                    $wLb = $sLog->weight_lb ? (float) $sLog->weight_lb : round($wKg * 2.20462, 1);
                    $previousSets[] = [
                        'set_number' => $sLog->set_number,
                        'reps' => (int) $sLog->reps,
                        'weight' => $wKg,
                        'weight_lb' => $wLb,
                    ];
                }
            } else {
                $lastSessionDate = '12/10/2026';
                $previousSets = [
                    ['set_number' => 1, 'reps' => 8, 'weight' => 40.82, 'weight_lb' => 90.0],
                    ['set_number' => 2, 'reps' => 8, 'weight' => 40.82, 'weight_lb' => 90.0],
                    ['set_number' => 3, 'reps' => 6, 'weight' => 40.82, 'weight_lb' => 90.0],
                ];
            }

            return [
                'id' => $p->id,
                'exercise_id' => $p->exercise_id,
                'name' => $p->exercise->name ?? 'Ejercicio',
                'muscle_group' => $p->exercise->muscleGroup->name ?? 'General',
                'primary_muscle' => is_array($p->exercise->primary_muscles) ? implode(', ', $p->exercise->primary_muscles) : ($p->exercise->primary_muscles ?? 'Músculo Principal'),
                'instruction' => $p->instruction,
                'video_url' => $videoUrl,
                'gif_url' => $gifUrl,
                'target_sets' => $p->sets,
                'min_reps' => $p->min_reps,
                'max_reps' => $p->max_reps,
                'last_session_date' => $lastSessionDate,
                'previous_sets' => $previousSets,
                'sets' => $initialSets,
            ];
        });

        return view('gym.workout', compact('gym', 'trainingDay', 'allRoutines', 'currentDayNumber', 'totalDays', 'exercisesPayload', 'isClientRoutine'));
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
