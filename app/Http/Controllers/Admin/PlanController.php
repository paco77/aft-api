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
        $query = MonthlyPlan::with(['user', 'assignedClient'])->latest();
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('assigned_client_id', auth()->id());
            });
        }
        $plans = $query->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $query = User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where('coach_id', auth()->id());
        }
        $clients = $query->get();
        $exercises = Exercise::all();
        return view('admin.plans.create', compact('clients', 'exercises'));
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
            'days.*.exercises' => 'array',
            'days.*.exercises.*.exercise_id' => 'required',
            'days.*.exercises.*.sets' => 'required|integer',
            'days.*.exercises.*.min_reps' => 'required|integer',
            'days.*.exercises.*.max_reps' => 'required|integer',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = User::find($data['assigned_client_id']);
            if (!$client || $client->coach_id !== auth()->id()) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
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
                    'muscle_groups' => [],
                ]);

                if (isset($dayData['exercises'])) {
                    foreach ($dayData['exercises'] as $exerciseData) {
                        $trainingDay->plannedExercises()->create([
                            'exercise_id' => $exerciseData['exercise_id'],
                            'sets' => $exerciseData['sets'],
                            'min_reps' => $exerciseData['min_reps'],
                            'max_reps' => $exerciseData['max_reps'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.plans.index')->with('success', 'Plan creado correctamente.');
    }

    public function show(MonthlyPlan $plan)
    {
        if (auth()->user()->role === 'coach' && $plan->user_id !== auth()->id() && $plan->assigned_client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este plan.');
        }

        $plan->load(['user', 'assignedClient', 'trainingDays.plannedExercises.exercise']);
        return view('admin.plans.show', compact('plan'));
    }

    public function destroy(MonthlyPlan $plan)
    {
        if (auth()->user()->role === 'coach' && $plan->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este plan.');
        }
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan eliminado correctamente.');
    }
}
