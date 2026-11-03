<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Acceso restringido únicamente al administrador.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $categories = FoodCategory::withCount('foods')->orderBy('name')->get();

        $query = Food::with('category')->orderBy('name')->orderBy('id');
        if ($request->filled('category_id')) {
            $query->where('food_category_id', $request->query('category_id'));
        }

        $foods = $query->get();

        return view('admin.foods.index', compact('foods', 'categories'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $categories = FoodCategory::orderBy('name')->get();
        $subcategories = Food::whereNotNull('subcategory')
            ->where('subcategory', '!=', '')
            ->distinct()
            ->orderBy('subcategory')
            ->pluck('subcategory');

        return view('admin.foods.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'food_category_id' => 'nullable|exists:food_categories,id',
            'new_category_name' => 'nullable|string|max:120',
            'subcategory' => 'nullable|string|max:120',
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:120',
            'portion_type' => 'required|string|max:100',
            'portion_detail' => 'nullable|string|max:255',
            'base_quantity' => 'required|numeric|min:0.01',
            'base_unit' => 'required|string|max:30',
            'gram_weight' => 'nullable|numeric|min:0',
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
        ], [
            'name.required' => 'El nombre del alimento es obligatorio.',
            'portion_type.required' => 'El tipo de porción es obligatorio.',
            'base_quantity.required' => 'La cantidad base es obligatoria.',
            'base_unit.required' => 'La unidad de medida base es obligatoria.',
            'calories.required' => 'Las calorías (kcal) son obligatorias.',
            'protein.required' => 'Los gramos de proteína son obligatorios.',
            'carbs.required' => 'Los gramos de carbohidratos son obligatorios.',
            'fat.required' => 'Los gramos de grasa son obligatorios.',
        ]);

        $categoryId = $validated['food_category_id'] ?? null;

        if (!empty($validated['new_category_name'])) {
            $newCatName = trim($validated['new_category_name']);
            $slug = Str::slug($newCatName);

            $existingCategory = FoodCategory::where('slug', $slug)
                ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($newCatName)])
                ->first();

            if ($existingCategory) {
                $categoryId = $existingCategory->id;
            } else {
                $createdCat = FoodCategory::create([
                    'name' => $newCatName,
                    'slug' => $slug,
                ]);
                $categoryId = $createdCat->id;
            }
        }

        if (!$categoryId) {
            return back()
                ->withErrors(['food_category_id' => 'Debes seleccionar una categoría existente o escribir una nueva categoría.'])
                ->withInput();
        }

        Food::create([
            'food_category_id' => $categoryId,
            'subcategory' => !empty($validated['subcategory']) ? trim($validated['subcategory']) : null,
            'name' => trim($validated['name']),
            'state' => !empty($validated['state']) ? trim($validated['state']) : 'Listo para consumo',
            'portion_type' => trim($validated['portion_type']),
            'portion_detail' => !empty($validated['portion_detail']) ? trim($validated['portion_detail']) : trim($validated['portion_type']),
            'base_quantity' => round((float) $validated['base_quantity'], 2),
            'base_unit' => trim($validated['base_unit']),
            'gram_weight' => isset($validated['gram_weight']) && $validated['gram_weight'] !== '' ? round((float) $validated['gram_weight'], 2) : null,
            'calories' => round((float) $validated['calories'], 2),
            'protein' => round((float) $validated['protein'], 2),
            'carbs' => round((float) $validated['carbs'], 2),
            'fat' => round((float) $validated['fat'], 2),
        ]);

        if ($request->input('save_action') === 'create_another') {
            return redirect()
                ->route('admin.foods.create')
                ->with('success', 'Alimento "' . trim($validated['name']) . '" registrado correctamente. Puedes agregar otro.');
        }

        return redirect()
            ->route('admin.foods.index')
            ->with('success', 'Alimento "' . trim($validated['name']) . '" dado de alta correctamente.');
    }

    public function edit(Food $food)
    {
        $this->authorizeAdmin();

        $categories = FoodCategory::orderBy('name')->get();
        $subcategories = Food::whereNotNull('subcategory')
            ->where('subcategory', '!=', '')
            ->distinct()
            ->orderBy('subcategory')
            ->pluck('subcategory');

        return view('admin.foods.edit', compact('food', 'categories', 'subcategories'));
    }

    public function update(Request $request, Food $food)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'food_category_id' => 'nullable|exists:food_categories,id',
            'new_category_name' => 'nullable|string|max:120',
            'subcategory' => 'nullable|string|max:120',
            'name' => 'required|string|max:255',
            'state' => 'nullable|string|max:120',
            'portion_type' => 'required|string|max:100',
            'portion_detail' => 'nullable|string|max:255',
            'base_quantity' => 'required|numeric|min:0.01',
            'base_unit' => 'required|string|max:30',
            'gram_weight' => 'nullable|numeric|min:0',
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
        ]);

        $categoryId = $validated['food_category_id'] ?? null;

        if (!empty($validated['new_category_name'])) {
            $newCatName = trim($validated['new_category_name']);
            $slug = Str::slug($newCatName);

            $existingCategory = FoodCategory::where('slug', $slug)
                ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($newCatName)])
                ->first();

            if ($existingCategory) {
                $categoryId = $existingCategory->id;
            } else {
                $createdCat = FoodCategory::create([
                    'name' => $newCatName,
                    'slug' => $slug,
                ]);
                $categoryId = $createdCat->id;
            }
        }

        if (!$categoryId) {
            return back()
                ->withErrors(['food_category_id' => 'Debes seleccionar una categoría.'])
                ->withInput();
        }

        $food->update([
            'food_category_id' => $categoryId,
            'subcategory' => !empty($validated['subcategory']) ? trim($validated['subcategory']) : null,
            'name' => trim($validated['name']),
            'state' => !empty($validated['state']) ? trim($validated['state']) : 'Listo para consumo',
            'portion_type' => trim($validated['portion_type']),
            'portion_detail' => !empty($validated['portion_detail']) ? trim($validated['portion_detail']) : trim($validated['portion_type']),
            'base_quantity' => round((float) $validated['base_quantity'], 2),
            'base_unit' => trim($validated['base_unit']),
            'gram_weight' => isset($validated['gram_weight']) && $validated['gram_weight'] !== '' ? round((float) $validated['gram_weight'], 2) : null,
            'calories' => round((float) $validated['calories'], 2),
            'protein' => round((float) $validated['protein'], 2),
            'carbs' => round((float) $validated['carbs'], 2),
            'fat' => round((float) $validated['fat'], 2),
        ]);

        return redirect()
            ->route('admin.foods.index')
            ->with('success', 'Alimento "' . $food->name . '" actualizado correctamente.');
    }

    public function destroy(Food $food)
    {
        $this->authorizeAdmin();

        $name = $food->name;
        $food->delete();

        return redirect()
            ->route('admin.foods.index')
            ->with('success', 'Alimento "' . $name . '" eliminado correctamente.');
    }
}

