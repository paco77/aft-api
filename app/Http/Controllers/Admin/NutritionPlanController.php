<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
    public function index()
    {
        $query = NutritionPlan::with(['client', 'coach'])->latest();
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) {
                $q->where('coach_id', auth()->id())
                  ->orWhere('client_id', auth()->id());
            });
        }
        $plans = $query->get();
        return view('admin.nutrition-plans.index', compact('plans'));
    }

    public function show(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id() && $nutritionPlan->client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este plan nutricional.');
        }

        $nutritionPlan->load(['client', 'coach', 'meals.foods']);
        
        return view('admin.nutrition-plans.show', compact('nutritionPlan'));
    }

    public function create(Request $request)
    {
        $query = \App\Models\User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where('coach_id', auth()->id());
        }
        $clients = $query->get();
        
        $selectedClientId = $request->query('client_id');

        return view('admin.nutrition-plans.create', compact('clients', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'objective' => 'required|string',
            'target_calories' => 'required|integer',
            'total_protein' => 'required|numeric',
            'total_carbs' => 'required|numeric',
            'total_fat' => 'required|numeric',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || $client->coach_id !== auth()->id()) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
        }

        $plan = NutritionPlan::create([
            'coach_id' => auth()->id(),
            'client_id' => $data['client_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'objective' => $data['objective'],
            'target_calories' => $data['target_calories'],
            'total_calories' => $data['target_calories'], 
            'total_protein' => $data['total_protein'],
            'total_carbs' => $data['total_carbs'],
            'total_fat' => $data['total_fat'],
        ]);

        if ($request->filled('meals_data')) {
            $meals = json_decode($request->input('meals_data'), true);
            if (is_array($meals)) {
                foreach ($meals as $mealData) {
                    $meal = $plan->meals()->create([
                        'name' => $mealData['name'],
                        'time' => $mealData['time'] ?? null,
                    ]);

                    if (isset($mealData['foods']) && is_array($mealData['foods'])) {
                        foreach ($mealData['foods'] as $foodData) {
                            $meal->foods()->create([
                                'fatsecret_food_id' => $foodData['fatsecret_food_id'] ?? 'manual_' . uniqid(),
                                'name' => $foodData['name'],
                                'serving_size' => $foodData['serving_size'],
                                'serving_unit' => $foodData['serving_unit'],
                                'calories' => $foodData['calories'],
                                'protein' => $foodData['protein'],
                                'carbs' => $foodData['carbs'],
                                'fat' => $foodData['fat'],
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.users.nutrition-plans', $plan->client_id)->with('success', 'Plan de alimentación creado correctamente.');
    }
    
    public function destroy(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este plan.');
        }
        $nutritionPlan->delete();
        return redirect()->route('admin.nutrition-plans.index')->with('success', 'Plan nutricional eliminado correctamente.');
    }
}
