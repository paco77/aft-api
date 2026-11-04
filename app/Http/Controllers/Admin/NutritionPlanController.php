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
            if (!Schema::hasTable('food_categories') || !Schema::hasTable('foods') || !Schema::hasColumn('foods', 'food_category_id')) {
                if (Schema::hasTable('migrations')) {
                    \Illuminate\Support\Facades\DB::table('migrations')
                        ->where('migration', 'like', '%create_food_categories_and_foods_tables%')
                        ->delete();
                }
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--path' => 'database/migrations/2026_10_29_233000_create_food_categories_and_foods_tables.php',
                    '--force' => true,
                ]);
            }

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

    public function syncCatalog()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('foods');
        Schema::dropIfExists('food_categories');
        Schema::enableForeignKeyConstraints();

        if (Schema::hasTable('migrations')) {
            \Illuminate\Support\Facades\DB::table('migrations')
                ->where('migration', 'like', '%create_food_categories_and_foods_tables%')
                ->delete();
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_10_29_233000_create_food_categories_and_foods_tables.php',
            '--force' => true,
        ]);

        (new \Database\Seeders\FoodSeeder())->run();

        return response()->json([
            'status' => 'ok',
            'message' => 'Catálogo de alimentos migrado y cargado correctamente.',
            'categories_count' => FoodCategory::count(),
            'foods_count' => Food::count(),
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

    protected function canCoachAccessPlan(NutritionPlan $nutritionPlan): bool
    {
        if (auth()->user()->role !== 'coach') {
            return true;
        }
        $coachId = (int) auth()->id();
        if ((int) $nutritionPlan->coach_id === $coachId || (int) $nutritionPlan->client_id === $coachId) {
            return true;
        }
        if ($nutritionPlan->client_id) {
            $client = \App\Models\User::find($nutritionPlan->client_id);
            if ($client && ((int) $client->coach_id === $coachId || is_null($client->coach_id))) {
                return true;
            }
        }
        return false;
    }

    protected function normalizeServingSize($rawSize): string
    {
        $str = trim((string) ($rawSize ?? '1'));
        if ($str === '') {
            return '1';
        }
        return $str;
    }

    protected function saveMealsToPlan(NutritionPlan $plan, array $meals): void
    {
        foreach ($meals as $index => $mealData) {
            $mealName = isset($mealData['name']) && trim((string) $mealData['name']) !== ''
                ? trim((string) $mealData['name'])
                : 'Comida ' . ($index + 1);

            $meal = $plan->meals()->create([
                'name' => $mealName,
                'time' => !empty($mealData['time']) ? $mealData['time'] : null,
            ]);

            if (isset($mealData['foods']) && is_array($mealData['foods'])) {
                foreach ($mealData['foods'] as $foodData) {
                    if (empty($foodData['name'])) {
                        continue;
                    }
                    $servingSize = $this->normalizeServingSize($foodData['serving_size'] ?? '1');
                    $foodPayload = [
                        'fatsecret_food_id' => !empty($foodData['fatsecret_food_id']) ? (string) $foodData['fatsecret_food_id'] : 'manual_' . uniqid(),
                        'name' => trim((string) $foodData['name']),
                        'serving_size' => $servingSize,
                        'serving_unit' => !empty($foodData['serving_unit']) ? trim((string) $foodData['serving_unit']) : 'g',
                        'calories' => round((float) ($foodData['calories'] ?? 0), 2),
                        'protein' => round((float) ($foodData['protein'] ?? 0), 2),
                        'carbs' => round((float) ($foodData['carbs'] ?? 0), 2),
                        'fat' => round((float) ($foodData['fat'] ?? 0), 2),
                    ];

                    try {
                        $meal->foods()->create($foodPayload);
                    } catch (\Throwable $e) {
                        // Fallback if serving_size column on server is numeric and received a fraction like "1/2"
                        if (str_contains($servingSize, '/')) {
                            $parts = preg_split('/\s+/', $servingSize);
                            $numericVal = 1.0;
                            if (count($parts) === 2 && str_contains($parts[1], '/')) {
                                [$num, $den] = explode('/', $parts[1]) + [1, 1];
                                $numericVal = (float) $parts[0] + ((float) $den > 0 ? ((float) $num / (float) $den) : 0);
                            } else {
                                [$num, $den] = explode('/', $parts[0]) + [1, 1];
                                $numericVal = (float) $den > 0 ? ((float) $num / (float) $den) : 1.0;
                            }
                            $foodPayload['serving_size'] = (string) round($numericVal, 2);
                        } else {
                            $foodPayload['serving_size'] = (string) ((float) $servingSize ?: 1);
                        }
                        $meal->foods()->create($foodPayload);
                    }
                }
            }
        }
    }

    public function show(NutritionPlan $nutritionPlan)
    {
        if (!$this->canCoachAccessPlan($nutritionPlan)) {
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
            'objective' => 'nullable|string',
            'target_calories' => 'nullable|numeric',
            'total_protein' => 'nullable|numeric',
            'total_carbs' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
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
            if (!$client || (!is_null($client->coach_id) && (int) $client->coach_id !== (int) auth()->id())) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
            }
        }

        $meals = $request->filled('meals_data') ? (json_decode($request->input('meals_data'), true) ?: []) : [];
        $mealsCalories = 0;
        $mealsProtein = 0;
        $mealsCarbs = 0;
        $mealsFat = 0;
        if (is_array($meals)) {
            foreach ($meals as $m) {
                foreach (($m['foods'] ?? []) as $f) {
                    $mealsCalories += (float) ($f['calories'] ?? 0);
                    $mealsProtein += (float) ($f['protein'] ?? 0);
                    $mealsCarbs += (float) ($f['carbs'] ?? 0);
                    $mealsFat += (float) ($f['fat'] ?? 0);
                }
            }
        }

        $targetCalories = (int) round((float) ($data['target_calories'] ?? 0) ?: $mealsCalories);
        $totalProtein = round((float) ($data['total_protein'] ?? 0) ?: $mealsProtein, 2);
        $totalCarbs = round((float) ($data['total_carbs'] ?? 0) ?: $mealsCarbs, 2);
        $totalFat = round((float) ($data['total_fat'] ?? 0) ?: $mealsFat, 2);

        $plan = \Illuminate\Support\Facades\DB::transaction(function () use ($data, $meals, $targetCalories, $totalProtein, $totalCarbs, $totalFat) {
            $createdPlan = NutritionPlan::create([
                'coach_id' => auth()->id(),
                'client_id' => $data['client_id'],
                'name' => !empty($data['name']) ? trim($data['name']) : 'Plan de Alimentación',
                'description' => $data['description'] ?? null,
                'objective' => !empty($data['objective']) ? $data['objective'] : 'mantenimiento',
                'target_calories' => $targetCalories,
                'total_calories' => $targetCalories,
                'total_protein' => $totalProtein,
                'total_carbs' => $totalCarbs,
                'total_fat' => $totalFat,
                'gender' => $data['gender'] ?? null,
                'weight' => $data['weight'] ?? null,
                'height' => $data['height'] ?? null,
                'age' => $data['age'] ?? null,
                'activity_level' => $data['activity_level'] ?? null,
                'formula' => $data['formula'] ?? null,
                'caloric_adjustment' => $data['caloric_adjustment'] ?? null,
            ]);

            if (is_array($meals) && count($meals) > 0) {
                $this->saveMealsToPlan($createdPlan, $meals);
            }

            return $createdPlan;
        });

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
        if (!$this->canCoachAccessPlan($nutritionPlan)) {
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
            'objective' => 'nullable|string',
            'target_calories' => 'nullable|numeric',
            'total_protein' => 'nullable|numeric',
            'total_carbs' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
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
            if (!$this->canCoachAccessPlan($nutritionPlan)) {
                abort(403, 'No tienes permiso para modificar este plan.');
            }
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || (!is_null($client->coach_id) && (int) $client->coach_id !== (int) auth()->id())) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
            }
        }

        $meals = $request->filled('meals_data') ? (json_decode($request->input('meals_data'), true) ?: []) : null;
        $mealsCalories = 0;
        $mealsProtein = 0;
        $mealsCarbs = 0;
        $mealsFat = 0;
        if (is_array($meals)) {
            foreach ($meals as $m) {
                foreach (($m['foods'] ?? []) as $f) {
                    $mealsCalories += (float) ($f['calories'] ?? 0);
                    $mealsProtein += (float) ($f['protein'] ?? 0);
                    $mealsCarbs += (float) ($f['carbs'] ?? 0);
                    $mealsFat += (float) ($f['fat'] ?? 0);
                }
            }
        }

        $targetCalories = (int) round(
            (float) ($data['target_calories'] ?? 0)
            ?: ($nutritionPlan->target_calories ?: ($nutritionPlan->total_calories ?: $mealsCalories))
        );
        $totalProtein = round(
            (float) ($data['total_protein'] ?? 0)
            ?: ($nutritionPlan->total_protein ?: $mealsProtein),
            2
        );
        $totalCarbs = round(
            (float) ($data['total_carbs'] ?? 0)
            ?: ($nutritionPlan->total_carbs ?: $mealsCarbs),
            2
        );
        $totalFat = round(
            (float) ($data['total_fat'] ?? 0)
            ?: ($nutritionPlan->total_fat ?: $mealsFat),
            2
        );

        $saveAsNew = $request->boolean('save_as_new');

        $targetPlan = \Illuminate\Support\Facades\DB::transaction(function () use (
            $nutritionPlan,
            $data,
            $meals,
            $saveAsNew,
            $targetCalories,
            $totalProtein,
            $totalCarbs,
            $totalFat
        ) {
            if ($saveAsNew) {
                $plan = NutritionPlan::create([
                    'coach_id' => auth()->id(),
                    'client_id' => $data['client_id'],
                    'name' => !empty($data['name']) ? trim($data['name']) : ($nutritionPlan->name ? $nutritionPlan->name . ' (Copia)' : 'Plan de Alimentación'),
                    'description' => $data['description'] ?? $nutritionPlan->description,
                    'objective' => !empty($data['objective']) ? $data['objective'] : ($nutritionPlan->objective ?: 'mantenimiento'),
                    'target_calories' => $targetCalories,
                    'total_calories' => $targetCalories,
                    'total_protein' => $totalProtein,
                    'total_carbs' => $totalCarbs,
                    'total_fat' => $totalFat,
                    'gender' => $data['gender'] ?? $nutritionPlan->gender,
                    'weight' => $data['weight'] ?? $nutritionPlan->weight,
                    'height' => $data['height'] ?? $nutritionPlan->height,
                    'age' => $data['age'] ?? $nutritionPlan->age,
                    'activity_level' => $data['activity_level'] ?? $nutritionPlan->activity_level,
                    'formula' => $data['formula'] ?? $nutritionPlan->formula,
                    'caloric_adjustment' => $data['caloric_adjustment'] ?? $nutritionPlan->caloric_adjustment,
                ]);
            } else {
                $nutritionPlan->update([
                    'client_id' => $data['client_id'],
                    'name' => !empty($data['name']) ? trim($data['name']) : ($nutritionPlan->name ?: 'Plan de Alimentación'),
                    'description' => $data['description'] ?? null,
                    'objective' => !empty($data['objective']) ? $data['objective'] : ($nutritionPlan->objective ?: 'mantenimiento'),
                    'target_calories' => $targetCalories,
                    'total_calories' => $targetCalories,
                    'total_protein' => $totalProtein,
                    'total_carbs' => $totalCarbs,
                    'total_fat' => $totalFat,
                    'gender' => $data['gender'] ?? null,
                    'weight' => $data['weight'] ?? null,
                    'height' => $data['height'] ?? null,
                    'age' => $data['age'] ?? null,
                    'activity_level' => $data['activity_level'] ?? null,
                    'formula' => $data['formula'] ?? null,
                    'caloric_adjustment' => $data['caloric_adjustment'] ?? null,
                ]);
                $plan = $nutritionPlan;
            }

            if (is_array($meals)) {
                if (!$saveAsNew) {
                    $plan->meals()->delete();
                }
                $this->saveMealsToPlan($plan, $meals);
            }

            return $plan;
        });

        $successMsg = $saveAsNew
            ? 'Plan guardado como nuevo plan exitosamente para ' . ($targetPlan->client->name ?? 'el cliente') . '.'
            : 'Plan de alimentación actualizado correctamente.';

        $redirect = redirect()->route('admin.users.nutrition-plans', $targetPlan->client_id)->with('success', $successMsg);

        if ($request->boolean('notify_whatsapp')) {
            $client = \App\Models\User::find($data['client_id']);
            if ($client && !empty($client->phone)) {
                $planText = $targetPlan->name ? ": {$targetPlan->name}" : "";
                $actionText = $saveAsNew ? "un nuevo plan de alimentación" : "tu plan de alimentación";
                $message = urlencode("Hola {$client->name}, he preparado {$actionText}{$planText}. Puedes revisarlo en tu app.");
                $phone = preg_replace('/[^0-9]/', '', $client->phone);
                $redirect->with('whatsapp_url', "https://wa.me/{$phone}?text={$message}");
            }
        }

        return $redirect;
    }

    public function duplicate(Request $request, NutritionPlan $nutritionPlan)
    {
        if (!$this->canCoachAccessPlan($nutritionPlan)) {
            abort(403, 'No tienes permiso para duplicar este plan.');
        }

        $data = $request->validate([
            'client_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string|max:255',
            'action_type' => 'nullable|string|in:direct,edit',
        ]);

        $actionType = $data['action_type'] ?? 'edit';

        if (auth()->user()->role === 'coach') {
            $client = \App\Models\User::find($data['client_id']);
            if (!$client || (!is_null($client->coach_id) && (int) $client->coach_id !== (int) auth()->id())) {
                abort(403, 'No tienes permiso para asignar un plan a este usuario.');
            }
            if (is_null($client->coach_id)) {
                $client->coach_id = auth()->id();
                $client->save();
            }
        }

        $nutritionPlan->load('meals.foods');

        $newPlan = \Illuminate\Support\Facades\DB::transaction(function () use ($nutritionPlan, $data) {
            $targetCals = (int) round((float) ($nutritionPlan->target_calories ?: ($nutritionPlan->total_calories ?: 0)));
            $totalCals = round((float) ($nutritionPlan->total_calories ?: $targetCals), 2);

            $clonedPlan = NutritionPlan::create([
                'coach_id' => auth()->id(),
                'client_id' => $data['client_id'],
                'name' => !empty($data['name']) ? trim($data['name']) : ($nutritionPlan->name ? $nutritionPlan->name . ' (Copia)' : 'Plan de Alimentación (Copia)'),
                'description' => $nutritionPlan->description,
                'objective' => $nutritionPlan->objective ?: 'mantenimiento',
                'target_calories' => $targetCals,
                'total_calories' => $totalCals,
                'total_protein' => round((float) ($nutritionPlan->total_protein ?? 0), 2),
                'total_carbs' => round((float) ($nutritionPlan->total_carbs ?? 0), 2),
                'total_fat' => round((float) ($nutritionPlan->total_fat ?? 0), 2),
                'gender' => $nutritionPlan->gender,
                'weight' => $nutritionPlan->weight,
                'height' => $nutritionPlan->height,
                'age' => $nutritionPlan->age,
                'activity_level' => $nutritionPlan->activity_level,
                'formula' => $nutritionPlan->formula,
                'caloric_adjustment' => $nutritionPlan->caloric_adjustment,
            ]);

            $mealsPayload = [];
            foreach ($nutritionPlan->meals as $meal) {
                $foodsArr = [];
                foreach ($meal->foods as $food) {
                    $foodsArr[] = [
                        'fatsecret_food_id' => $food->fatsecret_food_id,
                        'name' => $food->name,
                        'serving_size' => $food->serving_size,
                        'serving_unit' => $food->serving_unit,
                        'calories' => $food->calories,
                        'protein' => $food->protein,
                        'carbs' => $food->carbs,
                        'fat' => $food->fat,
                    ];
                }
                $mealsPayload[] = [
                    'name' => $meal->name,
                    'time' => $meal->time,
                    'foods' => $foodsArr,
                ];
            }

            if (!empty($mealsPayload)) {
                $this->saveMealsToPlan($clonedPlan, $mealsPayload);
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

        if ($actionType === 'edit') {
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

    public function destroy(Request $request, NutritionPlan $nutritionPlan)
    {
        if (!$this->canCoachAccessPlan($nutritionPlan)) {
            abort(403, 'No tienes permiso para eliminar este plan.');
        }

        $clientId = $nutritionPlan->client_id;

        \Illuminate\Support\Facades\DB::transaction(function () use ($nutritionPlan) {
            foreach ($nutritionPlan->meals as $meal) {
                $meal->foods()->delete();
                $meal->delete();
            }
            $nutritionPlan->delete();
        });

        if ($request->input('redirect_to') === 'user' && $clientId) {
            return redirect()->route('admin.users.nutrition-plans', $clientId)
                ->with('success', 'Plan nutricional eliminado correctamente.');
        }

        return redirect()->route('admin.nutrition-plans.index')
            ->with('success', 'Plan nutricional eliminado correctamente.');
    }

    public function exportPdf(NutritionPlan $nutritionPlan)
    {
        if (!$this->canCoachAccessPlan($nutritionPlan)) {
            abort(403, 'No tienes permiso para exportar este plan nutricional.');
        }

        $nutritionPlan->load(['client', 'coach', 'meals.foods']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.nutrition-plans.pdf', compact('nutritionPlan'));
        return $pdf->download('Plan_Nutricional_' . str_replace(' ', '_', $nutritionPlan->client->name ?? 'Cliente') . '.pdf');
    }
}
