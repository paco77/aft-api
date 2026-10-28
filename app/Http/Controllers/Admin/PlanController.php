<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPlan;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        $query = MonthlyPlan::with([
            'user', 
            'assignedClient', 
            'trainingDays.plannedExercises.exercise.muscleGroup'
        ])->latest();

        if (in_array(auth()->user()->role, ['coach', 'client', 'independiente'])) {
            $query->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('assigned_client_id', auth()->id());
            });
        }
        $plans = $query->get();
        $clients = $this->getClientsForUser();
        return view('admin.plans.index', compact('plans', 'clients'));
    }

    private function getClientsForUser()
    {
        $query = User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) { $q->where('coach_id', auth()->id())->orWhereNull('coach_id'); });
        }
        $clients = $query->orderBy('name')->get();
        if (auth()->user()->role === 'independiente' && !$clients->contains('id', auth()->id())) {
            $clients->prepend(auth()->user());
        }
        return $clients;
    }

    public function create()
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Los clientes no tienen permisos para crear planes.');
        }

        $query = User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) { $q->where('coach_id', auth()->id())->orWhereNull('coach_id'); });
        }
        $clients = $query->get();
        $exercises = Exercise::with('muscleGroup')->where('is_active', true)->get();
        $muscleGroups = \App\Models\MuscleGroup::all();
        return view('admin.plans.create', compact('clients', 'exercises', 'muscleGroups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'assigned_client_id' => 'required|integer|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'days_per_week' => 'required|integer',
            'split_type' => 'required|string',
            'days' => 'required|array',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'nullable|array',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
            'days.*.exercises.*.video_url' => 'string|nullable|max:500',
            'days.*.exercises.*.superset_id' => 'string|nullable',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = User::find($data['assigned_client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
                if (is_null($client->coach_id)) {
                    $client->coach_id = auth()->id();
                    $client->save();
                }
        }

        DB::transaction(function () use ($data) {
            $plan = MonthlyPlan::create([
                'user_id' => auth()->id(),
                'assigned_client_id' => $data['assigned_client_id'],
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
                ]);

                if (isset($dayData['exercises'])) {
                    foreach ($dayData['exercises'] as $exerciseData) {
                        $trainingDay->plannedExercises()->create([
                            'exercise_id' => $exerciseData['exercise_id'],
                            'sets' => $exerciseData['sets'],
                            'min_reps' => $exerciseData['min_reps'],
                            'max_reps' => $exerciseData['max_reps'],
                            'instruction' => $exerciseData['instruction'] ?? null,
                            'video_url' => !empty($exerciseData['video_url']) ? trim($exerciseData['video_url']) : null,
                            'superset_id' => $exerciseData['superset_id'] ?? null,
                        ]);
                    }
                }
            }
        });

        $redirect = redirect()->route('admin.plans.index')->with('success', 'Plan creado correctamente.');

        if ($request->boolean('notify_whatsapp')) {
            $client = User::find($data['assigned_client_id']);
            if ($client && !empty($client->phone)) {
                $message = urlencode("Hola {$client->name}, te he asignado un nuevo plan de entrenamiento para {$data['month']} {$data['year']}. Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
            }
        }

        return $redirect;
    }

    public function show(MonthlyPlan $plan)
    {
        if (in_array(auth()->user()->role, ['coach', 'client', 'independiente']) && $plan->user_id !== auth()->id() && $plan->assigned_client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este plan.');
        }

        $plan->load(['user', 'assignedClient', 'trainingDays.plannedExercises.exercise']);
        $clients = $this->getClientsForUser();
        return view('admin.plans.show', compact('plan', 'clients'));
    }

    public function edit(MonthlyPlan $plan)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Los clientes no tienen permisos para editar planes.');
        }

        if (auth()->user()->role === 'coach' && $plan->user_id !== auth()->id() && $plan->assigned_client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este plan.');
        }

        $plan->load(['trainingDays.plannedExercises.exercise.muscleGroup']);

        $query = User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) { $q->where('coach_id', auth()->id())->orWhereNull('coach_id'); });
        }
        $clients = $query->get();
        $exercises = Exercise::with('muscleGroup')->where('is_active', true)->get();
        $muscleGroups = \App\Models\MuscleGroup::all();

        return view('admin.plans.edit', compact('plan', 'clients', 'exercises', 'muscleGroups'));
    }

    public function update(Request $request, MonthlyPlan $plan)
    {
        $data = $request->validate([
            'assigned_client_id' => 'required|integer|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'days_per_week' => 'required|integer',
            'split_type' => 'required|string',
            'days' => 'required|array',
            'days.*.label' => 'required|string',
            'days.*.day_number' => 'required|integer',
            'days.*.muscle_groups' => 'nullable|array',
            'days.*.exercises' => 'array',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
            'days.*.exercises.*.instruction' => 'string|nullable',
            'days.*.exercises.*.video_url' => 'string|nullable|max:500',
            'days.*.exercises.*.superset_id' => 'string|nullable',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = User::find($data['assigned_client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
                if (is_null($client->coach_id)) {
                    $client->coach_id = auth()->id();
                    $client->save();
                }
        }

        DB::transaction(function () use ($data, $plan) {
            $plan->update([
                'assigned_client_id' => $data['assigned_client_id'],
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $data['days_per_week'],
                'split_type' => $data['split_type'],
            ]);

            foreach ($plan->trainingDays as $day) {
                $day->plannedExercises()->delete();
                $day->delete();
            }

            foreach ($data['days'] as $dayData) {
                $trainingDay = $plan->trainingDays()->create([
                    'label' => $dayData['label'],
                    'day_number' => $dayData['day_number'],
                    'muscle_groups' => $dayData['muscle_groups'] ?? [],
                ]);

                if (isset($dayData['exercises'])) {
                    foreach ($dayData['exercises'] as $exerciseData) {
                        $trainingDay->plannedExercises()->create([
                            'exercise_id' => $exerciseData['exercise_id'],
                            'sets' => $exerciseData['sets'],
                            'min_reps' => $exerciseData['min_reps'],
                            'max_reps' => $exerciseData['max_reps'],
                            'instruction' => $exerciseData['instruction'] ?? null,
                            'video_url' => !empty($exerciseData['video_url']) ? trim($exerciseData['video_url']) : null,
                            'superset_id' => $exerciseData['superset_id'] ?? null,
                        ]);
                    }
                }
            }
        });

        $redirect = redirect()->route('admin.plans.index')->with('success', 'Plan actualizado correctamente.');

        if ($request->boolean('notify_whatsapp')) {
            $client = User::find($data['assigned_client_id']);
            if ($client && !empty($client->phone)) {
                $message = urlencode("Hola {$client->name}, he actualizado tu plan de entrenamiento para {$data['month']} {$data['year']}. Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
            }
        }

        return $redirect;
    }

    public function destroy(MonthlyPlan $plan)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Los clientes no tienen permisos para eliminar planes.');
        }

        if (auth()->user()->role === 'coach' && $plan->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este plan.');
        }
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan eliminado correctamente.');
    }

    public function exportPdf(MonthlyPlan $plan)
    {
        if (in_array(auth()->user()->role, ['coach', 'client', 'independiente']) && $plan->user_id !== auth()->id() && $plan->assigned_client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para exportar este plan.');
        }

        $plan->load(['user', 'assignedClient', 'trainingDays.plannedExercises.exercise.muscleGroup']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.plans.pdf', compact('plan'));
        return $pdf->download('Plan_Entrenamiento_' . str_replace(' ', '_', $plan->assignedClient->name ?? 'Cliente') . '_' . $plan->month . '.pdf');
    }

    public function duplicate(Request $request, MonthlyPlan $plan)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Los clientes no tienen permisos para duplicar planes.');
        }

        if (auth()->user()->role === 'coach' && $plan->user_id !== auth()->id() && $plan->assigned_client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para duplicar este plan.');
        }

        $data = $request->validate([
            'assigned_client_id' => 'required|integer|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'action_type' => 'required|in:save,edit',
            'notify_whatsapp' => 'nullable|boolean',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = User::find($data['assigned_client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
                if (is_null($client->coach_id)) {
                    $client->coach_id = auth()->id();
                    $client->save();
                }
        }

        $newPlan = DB::transaction(function () use ($plan, $data) {
            $clonedPlan = MonthlyPlan::create([
                'user_id' => auth()->id(),
                'assigned_client_id' => $data['assigned_client_id'],
                'month' => $data['month'],
                'year' => $data['year'],
                'days_per_week' => $plan->days_per_week,
                'split_type' => $plan->split_type,
                'comment' => $plan->comment,
            ]);

            $plan->load('trainingDays.plannedExercises');

            foreach ($plan->trainingDays as $day) {
                $newDay = $clonedPlan->trainingDays()->create([
                    'user_id' => auth()->id(),
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
                        'client_id' => $data['assigned_client_id'],
                        'coach_id' => auth()->id(),
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

        $whatsappUrl = null;
        if ($request->boolean('notify_whatsapp')) {
            $client = User::find($data['assigned_client_id']);
            if ($client && !empty($client->phone)) {
                $message = urlencode("Hola {$client->name}, te he preparado un nuevo plan de entrenamiento para {$data['month']} {$data['year']}. Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $whatsappUrl = "https://wa.me/{$phone}?text={$message}";
            }
        }

        if ($data['action_type'] === 'edit') {
            $redirect = redirect()->route('admin.plans.edit', $newPlan)
                ->with('success', 'Plan duplicado exitosamente. Ahora puedes modificar las especificaciones que desees para este plan.');
        } else {
            $redirect = redirect()->route('admin.plans.index')
                ->with('success', 'Plan duplicado exitosamente para ' . ($newPlan->assignedClient->name ?? 'el cliente') . '.');
        }

        if ($whatsappUrl) {
            $redirect->with('whatsapp_url', $whatsappUrl);
        }

        return $redirect;
    }
}
