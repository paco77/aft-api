<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthlyPlan;
use App\Models\TrainingDay;
use App\Models\PlannedExercise;
use App\Http\Resources\MonthlyPlanResource;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $query = MonthlyPlan::query();
        $user = $request->user();
        if ($request->has('client_id')) {
            $clientId = $request->input('client_id');
            $client = User::find($clientId);
            if (!$client || ($client->coach_id !== $user->id && !is_null($client->coach_id))) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $query->where('assigned_client_id', $clientId);
        }
        else {
            if ($user->role === 'coach') {
                // Coach ve TODOS sus planes (personales + de clientes)
                $query->where('user_id', $user->id);
            }
            elseif ($user->isIndependent()) {
                // Independiente ve los planes creados por él o asignados a él
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhere('assigned_client_id', $user->id);
                });
            }
            else {
                // Cliente solo ve sus planes asignados
                $query->where('assigned_client_id', $user->id);
            }
        }
        $plans = $query->with(['trainingDays.plannedExercises.exercise'])
            ->latest()
            ->get();
        return MonthlyPlanResource::collection($plans)->sortByDesc('id');
    }

    public function activePlan(Request $request)
    {
        $plan = MonthlyPlan::where('assigned_client_id', $request->user()->id)
            ->latest()
            ->with(['trainingDays.plannedExercises.exercise'])
            ->first();

        if (!$plan) {
            return response()->json(['message' => 'No hay planes activos.'], 404);
        }

        return new MonthlyPlanResource($plan);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'assigned_client_id' => 'integer|exists:users,id|nullable',
            'month' => 'required|string',
            'year' => 'required|integer',
            'days_per_week' => 'required|integer',
            'split_type' => 'required|string',
            'comment' => 'nullable|string',
            'days' => 'required|array',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'array',
            'days.*.target_volumes' => 'array|nullable',
            'days.*.comment' => 'nullable|string',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.name' => 'string|nullable',
            'days.*.exercises.*.muscle_group' => 'string|nullable',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
            'days.*.exercises.*.video_url' => 'string|nullable',
            'days.*.exercises.*.superset_id' => 'string|nullable',
        ]);

        return DB::transaction(function () use ($request, $data) {
            $assignedClientId = $data['assigned_client_id'] ?? $request->user()->id;

            if ($request->user()->isIndependent()) {
                // El usuario independiente siempre crea sus planes para sí mismo
                $assignedClientId = $request->user()->id;
            } elseif ($assignedClientId != $request->user()->id) {
                // Si se asigna a otro usuario, verificar que sea un cliente de este coach
                $client = User::find($assignedClientId);
                if (!$client || ($client->coach_id !== $request->user()->id && !is_null($client->coach_id))) {
                    abort(403, 'No tienes permiso para asignar planes a este usuario.');
                }
                    if (is_null($client->coach_id)) {
                        $client->coach_id = $request->user()->id;
                        $client->save();
                    }
            }

            $plan = MonthlyPlan::create([
                'user_id' => $request->user()->id,
                'assigned_client_id' => $data['assigned_client_id'] ?? $request->user()->id,
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $data['days_per_week'],
                'split_type' => $data['split_type'],
                'comment' => $data['comment'] ?? null,
            ]);

            foreach ($data['days'] as $dayData) {
                $trainingDay = $plan->trainingDays()->create([
                    'label' => $dayData['label'],
                    'day_number' => $dayData['day_number'],
                    'muscle_groups' => $dayData['muscle_groups'] ?? [],
                    'target_volumes' => null, // Se calculará automáticamente
                    'comment' => $dayData['comment'] ?? null,
                ]);

                if (isset($dayData['exercises'])) {
                    $calculatedVolumes = [];
                    
                    foreach ($dayData['exercises'] as $exerciseData) {
                        $exerciseId = $exerciseData['exercise_id'];
                        $muscleGroup = $exerciseData['muscle_group'] ?? ($dayData['muscle_groups'][0] ?? 'Core');

                        // Si el ID no es numérico, lo tratamos como un slug/código
                        if (!is_numeric($exerciseId)) {
                            $exercise = Exercise::firstOrCreate(
                            ['slug' => $exerciseId],
                            [
                                'name' => $exerciseData['name'] ?? $exerciseId,
                                'muscle_group' => $muscleGroup,
                                'is_custom' => strpos($exerciseId, 'custom-') === 0
                            ]
                            );
                            $exerciseId = $exercise->id;
                        } else {
                            $exerciseModel = Exercise::find($exerciseId);
                            if ($exerciseModel) {
                                $muscleGroup = $exerciseModel->muscle_group;
                            }
                        }

                        $sets = $exerciseData['sets'];
                        if (!isset($calculatedVolumes[$muscleGroup])) {
                            $calculatedVolumes[$muscleGroup] = 0;
                        }
                        $calculatedVolumes[$muscleGroup] += $sets;

                        $trainingDay->plannedExercises()->create([
                            'exercise_id' => $exerciseId,
                            'sets' => $sets,
                            'min_reps' => $exerciseData['min_reps'],
                            'max_reps' => $exerciseData['max_reps'],
                            'instruction' => $exerciseData['instruction'] ?? null,
                            'video_url' => $exerciseData['video_url'] ?? null,
                            'superset_id' => $exerciseData['superset_id'] ?? null,
                        ]);
                    }
                    
                    // Actualizar el TrainingDay con el volumen calculado
                    $trainingDay->update(['target_volumes' => $calculatedVolumes]);
                }
            }

            return new MonthlyPlanResource($plan->load('trainingDays.plannedExercises.exercise'));
        });
    }

    public function update(Request $request, MonthlyPlan $plan)
    {
        $data = $request->validate([
            'assigned_client_id' => 'integer|exists:users,id|nullable',
            'month' => 'required|string',
            'year' => 'required|integer',
            'days_per_week' => 'required|integer',
            'split_type' => 'required|string',
            'comment' => 'nullable|string',
            'days' => 'array',
            'days.*.id' => 'integer|nullable',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'array',
            'days.*.target_volumes' => 'array|nullable',
            'days.*.comment' => 'nullable|string',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.id' => 'integer|nullable',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.name' => 'string|nullable',
            'days.*.exercises.*.muscle_group' => 'string|nullable',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
            'days.*.exercises.*.video_url' => 'string|nullable',
            'days.*.exercises.*.superset_id' => 'string|nullable',
        ]);

        return DB::transaction(function () use ($request, $data, $plan) {
            // Verificar permisos
            if ($plan->user_id !== $request->user()->id) {
                abort(403, 'Unauthorized');
            }

            if ($request->user()->isIndependent()) {
                $assignedClientId = $request->user()->id;
            } else {
                $assignedClientId = $data['assigned_client_id'] ?? $request->user()->id;
            }
            $plan->update([
                'assigned_client_id' => $assignedClientId,
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $data['days_per_week'],
                'split_type' => $data['split_type'],
                'comment' => $data['comment'] ?? null,
            ]);

            if (isset($data['days'])) {
                // Delete days that are no longer present
                $keepDayIds = collect($data['days'])->pluck('id')->filter()->toArray();
                $plan->trainingDays()->whereNotIn('id', $keepDayIds)->get()->each(function ($day) {
                            $day->plannedExercises()->delete();
                            $day->delete();
                        }
                        );

                        foreach ($data['days'] as $dayData) {
                            if (isset($dayData['id'])) {
                                $trainingDay = $plan->trainingDays()->find($dayData['id']);
                                if ($trainingDay) {
                                    $trainingDay->update([
                                        'label' => $dayData['label'],
                                        'day_number' => $dayData['day_number'],
                                        'muscle_groups' => $dayData['muscle_groups'] ?? [],
                                        'target_volumes' => $dayData['target_volumes'] ?? null,
                                        'comment' => $dayData['comment'] ?? null,
                                    ]);
                                }
                                else {
                                    $trainingDay = $plan->trainingDays()->create([
                                        'label' => $dayData['label'],
                                        'day_number' => $dayData['day_number'],
                                        'muscle_groups' => $dayData['muscle_groups'] ?? [],
                                        'target_volumes' => $dayData['target_volumes'] ?? null,
                                        'comment' => $dayData['comment'] ?? null,
                                    ]);
                                }
                            }
                            else {
                                $trainingDay = $plan->trainingDays()->create([
                                    'label' => $dayData['label'],
                                    'day_number' => $dayData['day_number'],
                                    'muscle_groups' => $dayData['muscle_groups'] ?? [],
                                    'target_volumes' => $dayData['target_volumes'] ?? null,
                                    'comment' => $dayData['comment'] ?? null,
                                ]);
                            }

                            if (isset($dayData['exercises'])) {
                                // Delete exercises that are no longer present
                                $keepExIds = collect($dayData['exercises'])->pluck('id')->filter()->toArray();
                                $trainingDay->plannedExercises()->whereNotIn('id', $keepExIds)->delete();
                                
                                $calculatedVolumes = [];

                                foreach ($dayData['exercises'] as $exerciseData) {
                                    $exerciseId = $exerciseData['exercise_id'];
                                    $muscleGroup = $exerciseData['muscle_group'] ?? ($dayData['muscle_groups'][0] ?? 'Core');

                                    if (!is_numeric($exerciseId)) {
                                        $exercise = Exercise::firstOrCreate(
                                        ['slug' => $exerciseId],
                                        [
                                            'name' => $exerciseData['name'] ?? $exerciseId,
                                            'muscle_group' => $muscleGroup,
                                            'is_custom' => strpos($exerciseId, 'custom-') === 0
                                        ]
                                        );
                                        $exerciseId = $exercise->id;
                                    } else {
                                        $exerciseModel = Exercise::find($exerciseId);
                                        if ($exerciseModel) {
                                            $muscleGroup = $exerciseModel->muscle_group;
                                        }
                                    }

                                    $sets = $exerciseData['sets'];
                                    if (!isset($calculatedVolumes[$muscleGroup])) {
                                        $calculatedVolumes[$muscleGroup] = 0;
                                    }
                                    $calculatedVolumes[$muscleGroup] += $sets;

                                    if (isset($exerciseData['id'])) {
                                        $plannedEx = $trainingDay->plannedExercises()->find($exerciseData['id']);
                                        if ($plannedEx) {
                                            $plannedEx->update([
                                                'exercise_id' => $exerciseId,
                                                'sets' => $sets,
                                                'min_reps' => $exerciseData['min_reps'],
                                                'max_reps' => $exerciseData['max_reps'],
                                                'instruction' => $exerciseData['instruction'] ?? null,
                                                'video_url' => $exerciseData['video_url'] ?? null,
                                                'superset_id' => $exerciseData['superset_id'] ?? null,
                                            ]);
                                        }
                                        else {
                                            $trainingDay->plannedExercises()->create([
                                                'exercise_id' => $exerciseId,
                                                'sets' => $sets,
                                                'min_reps' => $exerciseData['min_reps'],
                                                'max_reps' => $exerciseData['max_reps'],
                                                'instruction' => $exerciseData['instruction'] ?? null,
                                                'video_url' => $exerciseData['video_url'] ?? null,
                                                'superset_id' => $exerciseData['superset_id'] ?? null,
                                            ]);
                                        }
                                    }
                                    else {
                                        $trainingDay->plannedExercises()->create([
                                            'exercise_id' => $exerciseId,
                                            'sets' => $sets,
                                            'min_reps' => $exerciseData['min_reps'],
                                            'max_reps' => $exerciseData['max_reps'],
                                            'instruction' => $exerciseData['instruction'] ?? null,
                                            'video_url' => $exerciseData['video_url'] ?? null,
                                            'superset_id' => $exerciseData['superset_id'] ?? null,
                                        ]);
                                    }
                                }
                                
                                // Actualizar el TrainingDay con el volumen calculado
                                $trainingDay->update(['target_volumes' => $calculatedVolumes]);
                            }
                        }
                    }

                    return new MonthlyPlanResource($plan->load('trainingDays.plannedExercises.exercise'));
                });
    }

    public function show(Request $request, MonthlyPlan $plan)
    {
        // El creador o el cliente asignado pueden ver el plan
        if ($plan->user_id !== $request->user()->id && $plan->assigned_client_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $plan->load('trainingDays.plannedExercises.exercise');
        return new MonthlyPlanResource($plan);
    }

    public function destroy(Request $request, MonthlyPlan $plan)
    {
        // Solo el creador puede eliminar el plan
        if ($plan->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $plan->delete();
        return response()->json(['message' => 'Plan eliminado correctamente.']);
    }

    public function duplicate(Request $request, MonthlyPlan $plan)
    {
        $user = $request->user();
        if ($user->role === 'client') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->role === 'coach' && $plan->user_id !== $user->id && $plan->assigned_client_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'assigned_client_id' => 'nullable|integer|exists:users,id',
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
        ]);

        $targetClientId = $data['assigned_client_id'] ?? $plan->assigned_client_id ?? $user->id;

        if ($user->isIndependent()) {
            $targetClientId = $user->id;
        } elseif ($targetClientId != $user->id) {
            $client = \App\Models\User::find($targetClientId);
            if (!$client || ($client->coach_id !== $user->id && !is_null($client->coach_id))) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $newPlan = \Illuminate\Support\Facades\DB::transaction(function () use ($plan, $data, $user, $targetClientId) {
            $clonedPlan = MonthlyPlan::create([
                'user_id' => $user->id,
                'assigned_client_id' => $targetClientId,
                'month' => $data['month'] ?? $plan->month,
                'year' => $data['year'] ?? $plan->year,
                'days_per_week' => $plan->days_per_week,
                'split_type' => $plan->split_type,
                'comment' => $plan->comment,
            ]);

            $plan->load('trainingDays.plannedExercises');

            foreach ($plan->trainingDays as $day) {
                $newDay = $clonedPlan->trainingDays()->create([
                    'user_id' => $user->id,
                    'label' => $day->label,
                    'description' => $day->description,
                    'day_number' => $day->day_number,
                    'estimated_minutes' => $day->estimated_minutes,
                    'muscle_groups' => $day->muscle_groups ?? [],
                    'target_volumes' => $day->target_volumes ?? [],
                    'comment' => $day->comment,
                ]);

                foreach ($day->plannedExercises as $exercise) {
                    $newDay->plannedExercises()->create([
                        'exercise_id' => $exercise->exercise_id,
                        'client_id' => $targetClientId,
                        'coach_id' => $user->id,
                        'sets' => $exercise->sets,
                        'min_reps' => $exercise->min_reps,
                        'max_reps' => $exercise->max_reps,
                        'instruction' => $exercise->instruction,
                        'video_url' => $exercise->video_url,
                        'superset_id' => $exercise->superset_id,
                    ]);
                }
            }

            return $clonedPlan;
        });

        return new MonthlyPlanResource($newPlan->load('trainingDays.plannedExercises.exercise'));
    }

}
