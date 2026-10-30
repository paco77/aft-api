<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use App\Models\FoodCategory;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NutritionPlanController extends Controller
{
    /**
     * Ensure food catalog is seeded and return unique categories + grouped foods catalog.
     */
    protected function getFoodCatalogData(): array
    {
        try {
            if (!Schema::hasTable('food_categories') || !Schema::hasTable('foods')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }

            if (Schema::hasTable('food_categories') && Schema::hasTable('foods')) {
                if (FoodCategory::count() === 0 || Food::count() === 0) {
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

        // Fallback directly from dataset if tables are not yet migrated
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

                $slug = \Illuminate\Support\Str::slug($catName);
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
        $query = mb_strtolower(trim((string) $request->query('query', '')));

        $filtered = array_filter($catalogFoods, function ($item) use ($categoryId, $query) {
            if ($categoryId !== null && $categoryId !== '' && (string) $item['food_category_id'] !== (string) $categoryId) {
                return false;
            }
            if ($query !== '') {
                $haystack = mb_strtolower($item['name'] . ' ' . $item['state'] . ' ' . $item['subcategory'] . ' ' . $item['category_name']);
                return str_contains($haystack, $query);
            }
            return true;
        });

        return response()->json([
            'categories' => $foodCategories,
            'foods' => array_values($filtered),
        ]);
    }
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

        $clientQuery = \App\Models\User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $clientQuery->where(function($q) {
                $q->where('coach_id', auth()->id())
                  ->orWhereNull('coach_id');
            });
        }
        $clients = $clientQuery->orderBy('name')->get();

        return view('admin.nutrition-plans.index', compact('plans', 'clients'));
    }

    public function show(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id() && $nutritionPlan->client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este plan nutricional.');
        }

        $nutritionPlan->load(['client', 'coach', 'meals.foods']);
        
        $clientQuery = \App\Models\User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $clientQuery->where(function($q) {
                $q->where('coach_id', auth()->id())
                  ->orWhereNull('coach_id');
            });
        }
        $clients = $clientQuery->orderBy('name')->get();
        
        return view('admin.nutrition-plans.show', compact('nutritionPlan', 'clients'));
    }

    public function create(Request $request)
    {
        $query = \App\Models\User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) {
                $q->where('coach_id', auth()->id())
                  ->orWhereNull('coach_id');
            });
        }
        $clients = $query->orderBy('name')->get();
        
        $selectedClientId = $request->query('client_id');
        [$foodCategories, $catalogFoods] = $this->getFoodCatalogData();

        return view('admin.nutrition-plans.create', compact('clients', 'selectedClientId', 'foodCategories', 'catalogFoods'));
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
            'gender' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'activity_level' => 'nullable|string',
            'formula' => 'nullable|string',
            'caloric_adjustment' => 'nullable|numeric',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
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
            'gender' => $data['gender'] ?? null,
            'weight' => $data['weight'] ?? null,
            'height' => $data['height'] ?? null,
            'age' => $data['age'] ?? null,
            'activity_level' => $data['activity_level'] ?? null,
            'formula' => $data['formula'] ?? null,
            'caloric_adjustment' => $data['caloric_adjustment'] ?? null,
        ]);

        if ($request->filled('meals_data')) {
            $meals = json_decode($request->input('meals_data'), true);
            if (is_array($meals)) {
                foreach ($meals as $index => $mealData) {
                    $mealName = isset($mealData['name']) && trim($mealData['name']) !== ''
                        ? trim($mealData['name'])
                        : 'Comida ' . ($index + 1);

                    $meal = $plan->meals()->create([
                        'name' => $mealName,
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

        $redirect = redirect()->route('admin.users.nutrition-plans', $plan->client_id)->with('success', 'Plan de alimentación creado correctamente.');

        if ($request->boolean('notify_whatsapp')) {
            $client = \App\Models\User::find($data['client_id']);
            if ($client && !empty($client->phone)) {
                $message = urlencode("Hola {$client->name}, he creado un nuevo plan de alimentación para ti" . ($plan->name ? ": {$plan->name}" : "") . ". Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
            }
        }

        return $redirect;
    }

    public function edit(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id() && $nutritionPlan->client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este plan nutricional.');
        }

        $nutritionPlan->load(['client', 'coach', 'meals.foods']);
        
        $query = \App\Models\User::where('role', 'client');
        if (auth()->user()->role === 'coach') {
            $query->where(function($q) use ($nutritionPlan) {
                $q->where('coach_id', auth()->id())
                  ->orWhereNull('coach_id')
                  ->orWhere('id', $nutritionPlan->client_id);
            });
        }
        $clients = $query->orderBy('name')->get();
        [$foodCategories, $catalogFoods] = $this->getFoodCatalogData();

        return view('admin.nutrition-plans.edit', compact('nutritionPlan', 'clients', 'foodCategories', 'catalogFoods'));
    }

    public function update(Request $request, NutritionPlan $nutritionPlan)
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
            'gender' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'age' => 'nullable|integer',
            'activity_level' => 'nullable|string',
            'formula' => 'nullable|string',
            'caloric_adjustment' => 'nullable|numeric',
            'save_as_new' => 'nullable|boolean',
        ]);

        if (auth()->user()->role === 'coach') {
            if ($nutritionPlan->coach_id !== auth()->id()) {
                abort(403, 'No tienes permiso para modificar este plan.');
            }
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
            }
        }

        if ($request->boolean('save_as_new')) {
            $targetPlan = NutritionPlan::create([
                'coach_id' => auth()->id(),
                'client_id' => $data['client_id'],
                'name' => $data['name'] ?: ($nutritionPlan->name ? $nutritionPlan->name . ' (Copia)' : 'Plan de Alimentación'),
                'description' => $data['description'],
                'objective' => $data['objective'],
                'target_calories' => $data['target_calories'],
                'total_calories' => $data['target_calories'], 
                'total_protein' => $data['total_protein'],
                'total_carbs' => $data['total_carbs'],
                'total_fat' => $data['total_fat'],
                'gender' => $data['gender'] ?? null,
                'weight' => $data['weight'] ?? null,
                'height' => $data['height'] ?? null,
                'age' => $data['age'] ?? null,
                'activity_level' => $data['activity_level'] ?? null,
                'formula' => $data['formula'] ?? null,
                'caloric_adjustment' => $data['caloric_adjustment'] ?? null,
            ]);
            $successMsg = 'Plan guardado como nuevo plan exitosamente para ' . ($targetPlan->client->name ?? 'el cliente') . '.';
        } else {
            $nutritionPlan->update([
                'client_id' => $data['client_id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'objective' => $data['objective'],
                'target_calories' => $data['target_calories'],
                'total_calories' => $data['target_calories'], 
                'total_protein' => $data['total_protein'],
                'total_carbs' => $data['total_carbs'],
                'total_fat' => $data['total_fat'],
                'gender' => $data['gender'] ?? null,
                'weight' => $data['weight'] ?? null,
                'height' => $data['height'] ?? null,
                'age' => $data['age'] ?? null,
                'activity_level' => $data['activity_level'] ?? null,
                'formula' => $data['formula'] ?? null,
                'caloric_adjustment' => $data['caloric_adjustment'] ?? null,
            ]);
            $nutritionPlan->meals()->delete();
            $targetPlan = $nutritionPlan;
            $successMsg = 'Plan de alimentación actualizado correctamente.';
        }

        if ($request->filled('meals_data')) {
            $meals = json_decode($request->input('meals_data'), true);
            if (is_array($meals)) {
                foreach ($meals as $index => $mealData) {
                    $mealName = isset($mealData['name']) && trim($mealData['name']) !== ''
                        ? trim($mealData['name'])
                        : 'Comida ' . ($index + 1);

                    $meal = $targetPlan->meals()->create([
                        'name' => $mealName,
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

        $redirect = redirect()->route('admin.users.nutrition-plans', $targetPlan->client_id)->with('success', $successMsg);

        if ($request->boolean('notify_whatsapp')) {
            $client = \App\Models\User::find($data['client_id']);
            if ($client && !empty($client->phone)) {
                $planText = $targetPlan->name ? ": {$targetPlan->name}" : "";
                $actionText = $request->boolean('save_as_new') ? "un nuevo plan de alimentación" : "tu plan de alimentación";
                $message = urlencode("Hola {$client->name}, he preparado {$actionText}{$planText}. Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
            }
        }

        return $redirect;
    }

    public function duplicate(Request $request, NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id() && $nutritionPlan->client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para duplicar este plan.');
        }

        $data = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string|max:255',
            'action_type' => 'required|string|in:direct,edit',
        ]);

        if (auth()->user()->role === 'coach') {
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || ($client->coach_id !== auth()->id() && !is_null($client->coach_id))) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
            }
        }

        $nutritionPlan->load('meals.foods');

        $newPlan = \DB::transaction(function () use ($nutritionPlan, $data) {
            $clonedPlan = NutritionPlan::create([
                'coach_id' => auth()->id(),
                'client_id' => $data['client_id'],
                'name' => $data['name'] ?: ($nutritionPlan->name ? $nutritionPlan->name . ' (Copia)' : 'Plan de Alimentación'),
                'description' => $nutritionPlan->description,
                'objective' => $nutritionPlan->objective,
                'target_calories' => $nutritionPlan->target_calories,
                'total_calories' => $nutritionPlan->total_calories ?? $nutritionPlan->target_calories,
                'total_protein' => $nutritionPlan->total_protein,
                'total_carbs' => $nutritionPlan->total_carbs,
                'total_fat' => $nutritionPlan->total_fat,
                'gender' => $nutritionPlan->gender,
                'weight' => $nutritionPlan->weight,
                'height' => $nutritionPlan->height,
                'age' => $nutritionPlan->age,
                'activity_level' => $nutritionPlan->activity_level,
                'formula' => $nutritionPlan->formula,
                'caloric_adjustment' => $nutritionPlan->caloric_adjustment,
            ]);

            foreach ($nutritionPlan->meals as $meal) {
                $newMeal = $clonedPlan->meals()->create([
                    'name' => $meal->name,
                    'time' => $meal->time,
                ]);

                foreach ($meal->foods as $food) {
                    $newMeal->foods()->create([
                        'fatsecret_food_id' => $food->fatsecret_food_id,
                        'name' => $food->name,
                        'serving_size' => $food->serving_size,
                        'serving_unit' => $food->serving_unit,
                        'calories' => $food->calories,
                        'protein' => $food->protein,
                        'carbs' => $food->carbs,
                        'fat' => $food->fat,
                    ]);
                }
            }

            return $clonedPlan;
        });

        $whatsappUrl = null;
        if ($request->boolean('notify_whatsapp')) {
            $client = \App\Models\User::find($data['client_id']);
            if ($client && !empty($client->phone)) {
                $message = urlencode("Hola {$client->name}, te he preparado un nuevo plan de alimentación" . ($newPlan->name ? ": {$newPlan->name}" : "") . ". Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $whatsappUrl = "https://wa.me/{$phone}?text={$message}";
            }
        }

        if ($data['action_type'] === 'edit') {
            $redirect = redirect()->route('admin.nutrition-plans.edit', $newPlan)
                ->with('success', 'Plan duplicado exitosamente. Ahora puedes modificar y personalizar las comidas para ' . ($newPlan->client->name ?? 'el cliente') . '.');
        } else {
            $redirect = redirect()->route('admin.users.nutrition-plans', $newPlan->client_id)
                ->with('success', 'Plan duplicado exitosamente para ' . ($newPlan->client->name ?? 'el cliente') . '.');
        }

        if ($whatsappUrl) {
            $redirect->with('whatsapp_url', $whatsappUrl);
        }

        return $redirect;
    }

    public function destroy(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este plan.');
        }
        $nutritionPlan->delete();
        return redirect()->route('admin.nutrition-plans.index')->with('success', 'Plan nutricional eliminado correctamente.');
    }

    public function exportPdf(NutritionPlan $nutritionPlan)
    {
        if (auth()->user()->role === 'coach' && $nutritionPlan->coach_id !== auth()->id() && $nutritionPlan->client_id !== auth()->id()) {
            abort(403, 'No tienes permiso para exportar este plan nutricional.');
        }

        $nutritionPlan->load(['client', 'coach', 'meals.foods']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.nutrition-plans.pdf', compact('nutritionPlan'));
        return $pdf->download('Plan_Nutricional_' . str_replace(' ', '_', $nutritionPlan->client->name ?? 'Cliente') . '.pdf');
    }
}
