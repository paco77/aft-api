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
            if (!$client || $client->coach_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $query->where('assigned_client_id', $clientId);
        }
        else {
            if ($user->role === 'coach') {
                // Coach ve TODOS sus planes (personales + de clientes)
                $query->where('user_id', $user->id);
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
            'days' => 'required|array',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'array',
            'days.*.target_volumes' => 'array|nullable',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.name' => 'string|nullable',
            'days.*.exercises.*.muscle_group' => 'string|nullable',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
        ]);

        return DB::transaction(function () use ($request, $data) {
            $assignedClientId = $data['assigned_client_id'] ?? $request->user()->id;

            // Si se asigna a otro usuario, verificar que sea un cliente de este coach
            if ($assignedClientId != $request->user()->id) {
                $client = User::find($assignedClientId);
                if (!$client || $client->coach_id !== $request->user()->id) {
                    abort(403, 'No tienes permiso para asignar planes a este usuario.');
                }
            }

            $plan = MonthlyPlan::create([
                'user_id' => $request->user()->id,
                'assigned_client_id' => $data['assigned_client_id'] ?? $request->user()->id,
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $data['days_per_week'],
                'split_type' => $data['split_type'],
            ]);

            foreach ($data['days'] as $dayData) {
                $trainingDay = $plan->trainingDays()->create([
                    'label' => $dayData['label'],
                    'day_number' => $dayData['day_number'],
                    'muscle_groups' => $dayData['muscle_groups'] ?? [],
                    'target_volumes' => null, // Se calculará automáticamente
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
            'days' => 'array',
            'days.*.id' => 'integer|nullable',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'array',
            'days.*.target_volumes' => 'array|nullable',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.id' => 'integer|nullable',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.name' => 'string|nullable',
            'days.*.exercises.*.muscle_group' => 'string|nullable',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
        ]);

        return DB::transaction(function () use ($request, $data, $plan) {
            // Verificar permisos
            if ($plan->user_id !== $request->user()->id) {
                abort(403, 'Unauthorized');
            }

            $plan->update([
                'assigned_client_id' => $data['assigned_client_id'] ?? $request->user()->id,
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $data['days_per_week'],
                'split_type' => $data['split_type'],
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
                                    ]);
                                }
                                else {
                                    $trainingDay = $plan->trainingDays()->create([
                                        'label' => $dayData['label'],
                                        'day_number' => $dayData['day_number'],
                                        'muscle_groups' => $dayData['muscle_groups'] ?? [],
                                        'target_volumes' => $dayData['target_volumes'] ?? null,
                                    ]);
                                }
                            }
                            else {
                                $trainingDay = $plan->trainingDays()->create([
                                    'label' => $dayData['label'],
                                    'day_number' => $dayData['day_number'],
                                    'muscle_groups' => $dayData['muscle_groups'] ?? [],
                                    'target_volumes' => $dayData['target_volumes'] ?? null,
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
                                            ]);
                                        }
                                        else {
                                            $trainingDay->plannedExercises()->create([
                                                'exercise_id' => $exerciseId,
                                                'sets' => $sets,
                                                'min_reps' => $exerciseData['min_reps'],
                                                'max_reps' => $exerciseData['max_reps'],
                                                'instruction' => $exerciseData['instruction'] ?? null,
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
}
