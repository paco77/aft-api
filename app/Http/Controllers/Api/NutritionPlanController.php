<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\NutritionPlan;

class NutritionPlanController extends Controller
{
    /**
     * Normalize accents for accent-insensitive search.
     */
    protected function normalizeSearchText(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }

    /**
     * Return unique categories + grouped foods catalog from `foods` table (with JSON fallback).
     */
    protected function getFoodCatalogData(): array
    {
        try {
            if (Schema::hasTable('food_categories') && Schema::hasTable('foods') && Schema::hasColumn('foods', 'food_category_id')) {
                if (FoodCategory::count() === 0 || Food::count() < 600) {
                    (new \Database\Seeders\FoodSeeder())->run();
                }

                $foodCategories = FoodCategory::orderBy('name')->get(['id', 'name', 'slug']);
                $rawFoods = Food::with('category:id,name')
                    ->orderBy('name')
                    ->orderBy('id')
                    ->get();

                $grouped = [];
                foreach ($rawFoods as $f) {
                    $key = $f->food_category_id . '|' . mb_strtolower($f->name) . '|' . mb_strtolower((string) $f->state);
                    if (!isset($grouped[$key])) {
                        $grouped[$key] = [
                            'key' => $key,
                            'food_category_id' => $f->food_category_id,
                            'category_name' => $f->category?->name ?? '',
                            'subcategory' => $f->subcategory ?? '',
                            'name' => $f->name,
                            'state' => $f->state ?? '',
                            'display_name' => $f->state && !in_array(mb_strtolower($f->state), ['listo para consumo', 'fresco / crudo'])
                                ? "{$f->name} ({$f->state})"
                                : $f->name,
                            'portions' => [],
                        ];
                    }

                    $grouped[$key]['portions'][] = [
                        'id' => $f->id,
                        'portion_type' => $f->portion_type,
                        'portion_detail' => $f->portion_detail,
                        'base_quantity' => (float) $f->base_quantity,
                        'base_unit' => $f->base_unit,
                        'gram_weight' => $f->gram_weight ? (float) $f->gram_weight : null,
                        'calories' => (float) $f->calories,
                        'protein' => (float) $f->protein,
                        'carbs' => (float) $f->carbs,
                        'fat' => (float) $f->fat,
                    ];
                }

                return [$foodCategories, array_values($grouped)];
            }
        } catch (\Throwable $e) {
            // Fallback to dataset if DB migration hasn't run yet
        }

        $jsonPath = database_path('seeders/data/foods.json');
        if (file_exists($jsonPath)) {
            $rows = json_decode(file_get_contents($jsonPath), true) ?: [];
            $categoriesMap = [];
            $grouped = [];
            $catIdCounter = 1;
            $portionIdCounter = 1;

            foreach ($rows as $r) {
                $catName = trim($r['category'] ?? '');
                $name = trim($r['name'] ?? '');
                if ($catName === '' || $name === '') continue;

                $slug = Str::slug($catName);
                if (!isset($categoriesMap[$slug])) {
                    $categoriesMap[$slug] = (object) [
                        'id' => $catIdCounter++,
                        'name' => $catName,
                        'slug' => $slug,
                    ];
                }
                $catObj = $categoriesMap[$slug];
                $state = trim($r['state'] ?? '');
                $key = $catObj->id . '|' . mb_strtolower($name) . '|' . mb_strtolower($state);

                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'key' => $key,
                        'food_category_id' => $catObj->id,
                        'category_name' => $catObj->name,
                        'subcategory' => trim($r['subcategory'] ?? ''),
                        'name' => $name,
                        'state' => $state,
                        'display_name' => $state && !in_array(mb_strtolower($state), ['listo para consumo', 'fresco / crudo'])
                            ? "{$name} ({$state})"
                            : $name,
                        'portions' => [],
                    ];
                }

                $pt = mb_strtolower(trim($r['portion_type'] ?? '100 g'));
                $detail = mb_strtolower(trim($r['portion_detail'] ?? ''));
                $baseQty = 1;
                $baseUnit = 'porcion';
                if (str_contains($pt, '100')) {
                    $baseQty = 100;
                    $baseUnit = (preg_match('/\bml\b/', $detail) && !preg_match('/\bg\b/', $detail)) ? 'ml' : 'g';
                } elseif (str_contains($pt, 'taza')) {
                    $baseQty = 1;
                    $baseUnit = 'taza';
                } elseif (str_contains($pt, 'cda')) {
                    $baseQty = 1;
                    $baseUnit = 'cda';
                } elseif (str_contains($pt, 'pieza') || str_contains($pt, 'pz')) {
                    $baseQty = 1;
                    $baseUnit = 'pz';
                }

                $grouped[$key]['portions'][] = [
                    'id' => $portionIdCounter++,
                    'portion_type' => $r['portion_type'] ?? '100 g',
                    'portion_detail' => $r['portion_detail'] ?? '',
                    'base_quantity' => (float) $baseQty,
                    'base_unit' => $baseUnit,
                    'gram_weight' => null,
                    'calories' => (float) ($r['calories'] ?? 0),
                    'protein' => (float) ($r['protein'] ?? 0),
                    'carbs' => (float) ($r['carbs'] ?? 0),
                    'fat' => (float) ($r['fat'] ?? 0),
                ];
            }

            $sortedCategories = array_values($categoriesMap);
            usort($sortedCategories, fn($a, $b) => strcmp($a->name, $b->name));

            return [$sortedCategories, array_values($grouped)];
        }

        return [[], []];
    }

    public function searchFoods(Request $request)
    {
        [$foodCategories, $catalogFoods] = $this->getFoodCatalogData();

        $categoryId = $request->query('category_id');
        $rawQuery = trim((string) $request->query('query', ''));
        $normQuery = $rawQuery !== '' ? $this->normalizeSearchText($rawQuery) : '';

        $filtered = array_filter($catalogFoods, function ($item) use ($categoryId, $normQuery) {
            if ($categoryId !== null && $categoryId !== '' && (string) $item['food_category_id'] !== (string) $categoryId) {
                return false;
            }
            if ($normQuery !== '') {
                $haystack = $this->normalizeSearchText(
                    $item['name'] . ' ' . $item['state'] . ' ' . $item['subcategory'] . ' ' . $item['category_name']
                );
                return str_contains($haystack, $normQuery);
            }
            return true;
        });

        return response()->json([
            'categories' => $foodCategories,
            'foods' => array_values($filtered),
        ]);
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $query = NutritionPlan::with(['client', 'meals.foods'])->latest();

        if ($user->role === 'coach') {
            $query->where('coach_id', $user->id);
        } elseif ($user->isIndependent()) {
            // Independiente: ve los planes que él mismo creó o que tiene asignados
            $query->where(function ($q) use ($user) {
                $q->where('coach_id', $user->id)->orWhere('client_id', $user->id);
            });
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
            'total_calories' => 'nullable|numeric',
            'total_protein' => 'nullable|numeric',
            'total_carbs' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'meals' => 'required|array',
            'meals.*.name' => 'required|string',
            'meals.*.time' => 'nullable|date_format:H:i',
            'meals.*.foods' => 'present|array',
            'meals.*.foods.*.fatsecret_food_id' => 'nullable|string',
            'meals.*.foods.*.name' => 'required|string',
            'meals.*.foods.*.serving_size' => 'required|string',
            'meals.*.foods.*.serving_unit' => 'required|string',
            'meals.*.foods.*.calories' => 'required|numeric',
            'meals.*.foods.*.protein' => 'required|numeric',
            'meals.*.foods.*.carbs' => 'required|numeric',
            'meals.*.foods.*.fat' => 'required|numeric',
        ]);

        // Independiente: el plan siempre es para sí mismo
        if ($request->user()->isIndependent()) {
            $validated['client_id'] = $request->user()->id;
        }

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
            'total_calories' => $validated['total_calories'] ?? null,
            'total_protein' => $validated['total_protein'] ?? null,
            'total_carbs' => $validated['total_carbs'] ?? null,
            'total_fat' => $validated['total_fat'] ?? null,
        ]);

        foreach ($validated['meals'] as $mealData) {
            $meal = $plan->meals()->create([
                'name' => $mealData['name'],
                'time' => $mealData['time'] ?? null,
            ]);

            foreach ($mealData['foods'] as $foodData) {
                $foodData['fatsecret_food_id'] = $foodData['fatsecret_food_id'] ?? ('manual_' . uniqid());
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
            'total_calories' => 'nullable|numeric',
            'total_protein' => 'nullable|numeric',
            'total_carbs' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'meals' => 'required|array',
            'meals.*.name' => 'required|string',
            'meals.*.time' => 'nullable|date_format:H:i',
            'meals.*.foods' => 'present|array',
            'meals.*.foods.*.fatsecret_food_id' => 'nullable|string',
            'meals.*.foods.*.name' => 'required|string',
            'meals.*.foods.*.serving_size' => 'required|string',
            'meals.*.foods.*.serving_unit' => 'required|string',
            'meals.*.foods.*.calories' => 'required|numeric',
            'meals.*.foods.*.protein' => 'required|numeric',
            'meals.*.foods.*.carbs' => 'required|numeric',
            'meals.*.foods.*.fat' => 'required|numeric',
        ]);

        // Independiente: el plan siempre es para sí mismo
        if ($request->user()->isIndependent()) {
            $validated['client_id'] = $request->user()->id;
        }

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
            'total_calories' => $validated['total_calories'] ?? null,
            'total_protein' => $validated['total_protein'] ?? null,
            'total_carbs' => $validated['total_carbs'] ?? null,
            'total_fat' => $validated['total_fat'] ?? null,
        ]);

        // Delete existing meals to replace them completely
        $plan->meals()->delete();

        foreach ($validated['meals'] as $mealData) {
            $meal = $plan->meals()->create([
                'name' => $mealData['name'],
                'time' => $mealData['time'] ?? null,
            ]);

            foreach ($mealData['foods'] as $foodData) {
                $foodData['fatsecret_food_id'] = $foodData['fatsecret_food_id'] ?? ('manual_' . uniqid());
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
