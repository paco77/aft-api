<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NutritionPlan;

class NutritionPlanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = NutritionPlan::with(['client', 'meals.foods'])->latest();

        if ($user->role === 'coach') {
            $query->where('coach_id', $user->id);
        } else {
            $query->where('client_id', $user->id);
        }

        $plans = $query->get();
        return response()->json($plans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tdee' => 'nullable|numeric',
            'target_calories' => 'nullable|numeric',
            'gender' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'activity_level' => 'nullable|numeric',
            'formula' => 'nullable|string',
            'objective' => 'nullable|string',
            'caloric_adjustment' => 'nullable|numeric',
            'meals' => 'required|array',
            'meals.*.name' => 'required|string',
            'meals.*.time' => 'nullable|date_format:H:i',
            'meals.*.foods' => 'required|array',
            'meals.*.foods.*.fatsecret_food_id' => 'required|string',
            'meals.*.foods.*.name' => 'required|string',
            'meals.*.foods.*.serving_size' => 'required|numeric',
            'meals.*.foods.*.serving_unit' => 'required|string',
            'meals.*.foods.*.calories' => 'required|numeric',
            'meals.*.foods.*.protein' => 'required|numeric',
            'meals.*.foods.*.carbs' => 'required|numeric',
            'meals.*.foods.*.fat' => 'required|numeric',
        ]);

        $plan = NutritionPlan::create([
            'coach_id' => $request->user()->id,
            'client_id' => $validated['client_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tdee' => $validated['tdee'] ?? null,
            'target_calories' => $validated['target_calories'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'age' => $validated['age'] ?? null,
            'activity_level' => $validated['activity_level'] ?? null,
            'formula' => $validated['formula'] ?? null,
            'objective' => $validated['objective'] ?? null,
            'caloric_adjustment' => $validated['caloric_adjustment'] ?? null,
            'total_calories' => collect($validated['meals'])->sum(function ($meal) {
            return collect($meal['foods'])->sum('calories');
        }),
            'total_protein' => collect($validated['meals'])->sum(function ($meal) {
            return collect($meal['foods'])->sum('protein');
        }),
            'total_carbs' => collect($validated['meals'])->sum(function ($meal) {
            return collect($meal['foods'])->sum('carbs');
        }),
            'total_fat' => collect($validated['meals'])->sum(function ($meal) {
            return collect($meal['foods'])->sum('fat');
        }),
        ]);

        foreach ($validated['meals'] as $mealData) {
            $meal = $plan->meals()->create([
                'name' => $mealData['name'],
                'time' => $mealData['time'] ?? null,
            ]);

            foreach ($mealData['foods'] as $foodData) {
                $meal->foods()->create($foodData);
            }
        }

        return response()->json($plan->load('meals.foods'), 201);
    }

    public function update(Request $request, string $id)
    {
        $plan = NutritionPlan::findOrFail($id);

        if ($plan->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tdee' => 'nullable|numeric',
            'target_calories' => 'nullable|numeric',
            'gender' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'activity_level' => 'nullable|numeric',
            'formula' => 'nullable|string',
            'objective' => 'nullable|string',
            'caloric_adjustment' => 'nullable|numeric',
            'meals' => 'required|array',
            'meals.*.name' => 'required|string',
            'meals.*.time' => 'nullable|date_format:H:i',
            'meals.*.foods' => 'required|array',
            'meals.*.foods.*.fatsecret_food_id' => 'required|string',
            'meals.*.foods.*.name' => 'required|string',
            'meals.*.foods.*.serving_size' => 'required|numeric',
            'meals.*.foods.*.serving_unit' => 'required|string',
            'meals.*.foods.*.calories' => 'required|numeric',
            'meals.*.foods.*.protein' => 'required|numeric',
            'meals.*.foods.*.carbs' => 'required|numeric',
            'meals.*.foods.*.fat' => 'required|numeric',
        ]);

        $plan->update([
            'client_id' => $validated['client_id'] ?? $plan->client_id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tdee' => $validated['tdee'] ?? null,
            'target_calories' => $validated['target_calories'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'age' => $validated['age'] ?? null,
            'activity_level' => $validated['activity_level'] ?? null,
            'formula' => $validated['formula'] ?? null,
            'objective' => $validated['objective'] ?? null,
            'caloric_adjustment' => $validated['caloric_adjustment'] ?? null,
            'total_calories' => collect($validated['meals'])->sum(function ($meal) {
                return collect($meal['foods'])->sum('calories');
            }),
            'total_protein' => collect($validated['meals'])->sum(function ($meal) {
                return collect($meal['foods'])->sum('protein');
            }),
            'total_carbs' => collect($validated['meals'])->sum(function ($meal) {
                return collect($meal['foods'])->sum('carbs');
            }),
            'total_fat' => collect($validated['meals'])->sum(function ($meal) {
                return collect($meal['foods'])->sum('fat');
            }),
        ]);

        // Delete existing meals to replace them completely
        $plan->meals()->delete();

        foreach ($validated['meals'] as $mealData) {
            $meal = $plan->meals()->create([
                'name' => $mealData['name'],
                'time' => $mealData['time'] ?? null,
            ]);

            foreach ($mealData['foods'] as $foodData) {
                $meal->foods()->create($foodData);
            }
        }

        return response()->json($plan->load('meals.foods'), 200);
    }

    public function show(Request $request, string $id)
    {
        $plan = NutritionPlan::with(['client', 'meals.foods'])->findOrFail($id);

        if ($plan->coach_id !== $request->user()->id && $plan->client_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($plan);
    }

    public function destroy(Request $request, string $id)
    {
        $plan = NutritionPlan::findOrFail($id);

        if ($plan->coach_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully.']);
    }
}
