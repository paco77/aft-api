@extends('layouts.admin')

@section('header', 'Crear Nuevo Plan de Alimentación')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            Calculadora Nutricional
        </h2>

        <form action="{{ route('admin.nutrition-plans.store') }}" method="POST" id="nutrition-form">
            @csrf

            <!-- Client & Plan Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                    <select name="client_id" id="client_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                        <option value="">Selecciona un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" data-weight="{{ $client->weight ?? '' }}" data-height="{{ $client->height ?? '' }}" data-age="{{ $client->age ?? '' }}" data-gender="{{ $client->gender ?? 'male' }}" {{ (old('client_id') ?? $selectedClientId) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Plan</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ej: Plan de Definición - Junio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción / Notas Adicionales</label>
                    <textarea name="description" id="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">1. Datos Fisiológicos</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                        <select id="calc_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="male">Hombre</option>
                            <option value="female">Mujer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                        <input type="number" id="calc_weight" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
                        <input type="number" id="calc_height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Edad (años)</label>
                        <input type="number" id="calc_age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">2. Parámetros de Cálculo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Actividad</label>
                        <select id="calc_activity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="1.2">(1.2) Sedentario</option>
                            <option value="1.375">(1.375) Ligero</option>
                            <option value="1.55">(1.55) Moderado</option>
                            <option value="1.725">(1.725) Fuerte</option>
                            <option value="1.9">(1.9) Muy Fuerte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fórmula</label>
                        <select id="calc_formula" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="mifflin">Mifflin-St Jeor</option>
                            <option value="harris">Harris-Benedict</option>
                            <option value="tinsley">Tinsley</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                        <select id="calc_objective" name="objective" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="volumen">Volumen (+)</option>
                            <option value="perdida_grasa">Definición (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste Calórico</label>
                        <input type="number" id="calc_adjustment" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn_calculate" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Calcular Requerimientos
                    </button>
                </div>
            </div>

            <!-- Resumen y Macros (Readonly/Auto-calculated) -->
            <div class="border-t border-gray-100 pt-8 mb-6 bg-gray-50 -mx-8 px-8 pb-8 rounded-b-xl border-b border-l border-r">
                <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">3. Resultados y Distribución</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- TDEE & Target -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
                        <p class="text-gray-500 font-semibold mb-2">Gasto Calórico Total (TDEE)</p>
                        <p class="text-3xl font-bold text-gray-900 mb-4"><span id="display_tdee">0</span> <span class="text-sm text-gray-500">kcal</span></p>
                        
                        <div class="h-px w-full bg-gray-100 my-4"></div>
                        
                        <p class="text-indigo-600 font-bold mb-2">Calorías Objetivo</p>
                        <p class="text-4xl font-extrabold text-indigo-700"><span id="display_target">0</span> <span class="text-lg text-indigo-500">kcal</span></p>
                        <!-- Hidden inputs to send in form -->
                        <input type="hidden" name="target_calories" id="input_target_calories">
                    </div>

                    <!-- Macro Settings -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 text-center">Ajuste de Macros (g/kg)</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Proteínas (g/kg)</label>
                                <input type="number" id="calc_protein_kg" value="2.2" step="0.1" class="w-24 text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Lípidos (g/kg)</label>
                                <input type="number" id="calc_lipids_kg" value="0.8" step="0.1" class="w-24 text-center rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-500">Carbohidratos (g/kg)</label>
                                <input type="number" id="display_carbs_kg" value="0.0" disabled class="w-24 text-center rounded-md border-gray-200 bg-gray-50 text-gray-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calculated Macros Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
                        <p class="text-blue-600 font-bold mb-1">Proteínas</p>
                        <p class="text-2xl font-bold text-blue-900"><span id="display_protein_g">0</span>g</p>
                        <p class="text-xs text-blue-500 mt-1"><span id="display_protein_kcal">0</span> kcal</p>
                        <input type="hidden" name="total_protein" id="input_protein">
                    </div>
                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 text-center">
                        <p class="text-orange-600 font-bold mb-1">Carbohidratos</p>
                        <p class="text-2xl font-bold text-orange-900"><span id="display_carbs_g">0</span>g</p>
                        <p class="text-xs text-orange-500 mt-1"><span id="display_carbs_kcal">0</span> kcal</p>
                        <input type="hidden" name="total_carbs" id="input_carbs">
                    </div>
                    <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
                        <p class="text-green-600 font-bold mb-1">Grasas (Lípidos)</p>
                        <p class="text-2xl font-bold text-green-900"><span id="display_lipids_g">0</span>g</p>
                        <p class="text-xs text-green-500 mt-1"><span id="display_lipids_kcal">0</span> kcal</p>
                        <input type="hidden" name="total_fat" id="input_fat">
                    </div>
                </div>

                <!-- 4. Planificador de Comidas -->
                <div class="border-t border-gray-100 pt-8 mt-8 mb-6 relative">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">4. Planificador de Comidas</h3>
                        <button type="button" id="btn_add_meal" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Añadir Comida
                        </button>
                    </div>
                    
                    <div id="meals_container" class="space-y-6">
                        <!-- Meal Templates will be injected here via JS -->
                    </div>
                    
                    <!-- Hidden input for JSON data -->
                    <input type="hidden" name="meals_data" id="input_meals_data">
                </div>

                <div class="mt-8 flex justify-end gap-4 border-t border-gray-200 pt-6">
                    <a href="{{ url()->previous() }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" id="btn_submit" disabled class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Guardar Plan de Alimentación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para añadir alimento manual -->
<div id="modal_add_food" class="fixed inset-0 bg-black/50 hidden z-[60] items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden m-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h4 class="font-bold text-gray-900">Añadir Alimento Manual</h4>
            <button type="button" id="btn_close_modal" class="text-gray-400 hover:text-gray-600 p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="modal_meal_index">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del alimento *</label>
                <input type="text" id="modal_food_name" placeholder="Ej. Pechuga de pollo cocida" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad *</label>
                    <input type="number" id="modal_food_qty" step="0.1" value="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidad</label>
                    <select id="modal_food_unit" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="g">Gramos (g)</option>
                        <option value="pz">Pieza (pz)</option>
                        <option value="porcion">Porción</option>
                        <option value="ml">Mililitros (ml)</option>
                    </select>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-4 mt-2 hidden">
                <p class="text-sm font-semibold text-gray-600 mb-3">Valores Nutricionales (Para la cantidad ingresada)</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Calorías</label>
                        <input type="number" id="modal_food_cals" value="0" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-blue-500 mb-1">Proteína (g)</label>
                        <input type="number" id="modal_food_prot" step="0.1" value="0" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-orange-500 mb-1">Carbs (g)</label>
                        <input type="number" id="modal_food_carbs" step="0.1" value="0" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-green-500 mb-1">Grasa (g)</label>
                        <input type="number" id="modal_food_fat" step="0.1" value="0" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="button" id="btn_save_food" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-sm">
                    Añadir Alimento
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientSelect = document.getElementById('client_id');
        const calcGender = document.getElementById('calc_gender');
        const calcWeight = document.getElementById('calc_weight');
        const calcHeight = document.getElementById('calc_height');
        const calcAge = document.getElementById('calc_age');
        const calcActivity = document.getElementById('calc_activity');
        const calcFormula = document.getElementById('calc_formula');
        const calcObjective = document.getElementById('calc_objective');
        const calcAdjustment = document.getElementById('calc_adjustment');
        
        const calcProteinKg = document.getElementById('calc_protein_kg');
        const calcLipidsKg = document.getElementById('calc_lipids_kg');
        const displayCarbsKg = document.getElementById('display_carbs_kg');

        const displayTdee = document.getElementById('display_tdee');
        const displayTarget = document.getElementById('display_target');
        const displayProteinG = document.getElementById('display_protein_g');
        const displayProteinKcal = document.getElementById('display_protein_kcal');
        const displayCarbsG = document.getElementById('display_carbs_g');
        const displayCarbsKcal = document.getElementById('display_carbs_kcal');
        const displayLipidsG = document.getElementById('display_lipids_g');
        const displayLipidsKcal = document.getElementById('display_lipids_kcal');

        const inputTargetCalories = document.getElementById('input_target_calories');
        const inputProtein = document.getElementById('input_protein');
        const inputCarbs = document.getElementById('input_carbs');
        const inputFat = document.getElementById('input_fat');
        const btnSubmit = document.getElementById('btn_submit');
        const btnCalculate = document.getElementById('btn_calculate');

        // Meal Builder UI Elements
        const btnAddMeal = document.getElementById('btn_add_meal');
        const mealsContainer = document.getElementById('meals_container');
        const inputMealsData = document.getElementById('input_meals_data');
        const form = document.getElementById('nutrition-form');

        // Modal Elements
        const modalAddFood = document.getElementById('modal_add_food');
        const btnCloseModal = document.getElementById('btn_close_modal');
        const modalMealIndex = document.getElementById('modal_meal_index');
        const modalFoodName = document.getElementById('modal_food_name');
        const modalFoodQty = document.getElementById('modal_food_qty');
        const modalFoodUnit = document.getElementById('modal_food_unit');
        const modalFoodCals = document.getElementById('modal_food_cals');
        const modalFoodProt = document.getElementById('modal_food_prot');
        const modalFoodCarbs = document.getElementById('modal_food_carbs');
        const modalFoodFat = document.getElementById('modal_food_fat');
        const btnSaveFood = document.getElementById('btn_save_food');

        let targetCals = 0;
        let w = 0;
        let meals = []; // State array for meals

        function updateMealsData() {
            inputMealsData.value = JSON.stringify(meals);
        }

        function renderMeals() {
            mealsContainer.innerHTML = '';
            
            if (meals.length === 0) {
                mealsContainer.innerHTML = `
                    <div class="text-center p-8 border-2 border-dashed border-gray-200 rounded-xl">
                        <p class="text-gray-500">No has agregado tiempos de comida aún.</p>
                        <p class="text-sm text-gray-400 mt-1">Haz clic en "Añadir Comida" para empezar.</p>
                    </div>`;
                updateMealsData();
                return;
            }

            meals.forEach((meal, mealIndex) => {
                let mealTotalCals = 0;
                meal.foods.forEach(f => mealTotalCals += parseFloat(f.calories || 0));

                let foodsHtml = '';
                if (meal.foods.length > 0) {
                    foodsHtml = `<ul class="divide-y divide-gray-100 border-t border-gray-100 mt-3">`;
                    meal.foods.forEach((food, foodIndex) => {
                        foodsHtml += `
                            <li class="py-3 flex justify-between items-center group">
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">${food.name}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">${food.serving_size} ${food.serving_unit} • ${food.calories} kcal</p>
                                    <div class="flex gap-2 mt-1">
                                        <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded font-medium">P: ${food.protein}g</span>
                                        <span class="text-[10px] bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded font-medium">C: ${food.carbs}g</span>
                                        <span class="text-[10px] bg-green-50 text-green-600 px-1.5 py-0.5 rounded font-medium">G: ${food.fat}g</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-food text-gray-300 hover:text-red-500 transition-colors p-1" data-meal-idx="${mealIndex}" data-food-idx="${foodIndex}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </li>`;
                    });
                    foodsHtml += `</ul>`;
                } else {
                    foodsHtml = `<p class="text-sm text-gray-400 mt-3 italic">Sin alimentos registrados</p>`;
                }

                mealsContainer.innerHTML += `
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:border-blue-100 transition-colors">
                        <div class="flex justify-between items-center">
                            <div class="flex-1 mr-4 flex items-center gap-2">
                                <input type="text" class="meal-name-input font-bold text-gray-900 border-none bg-transparent p-0 focus:ring-0 w-full" value="${meal.name}" data-meal-idx="${mealIndex}" placeholder="Ej. Desayuno">
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-semibold bg-gray-100 px-2 py-1 rounded text-gray-600">${mealTotalCals.toFixed(0)} kcal</span>
                                <button type="button" class="btn-remove-meal text-red-500 hover:text-red-700" data-meal-idx="${mealIndex}" title="Eliminar comida">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        ${foodsHtml}
                        <div class="mt-4 pt-4 border-t border-gray-50 flex justify-center">
                            <button type="button" class="btn-open-modal text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1" data-meal-idx="${mealIndex}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Añadir Alimento
                            </button>
                        </div>
                    </div>`;
            });

            updateMealsData();
            attachMealEventListeners();
        }

        function attachMealEventListeners() {
            document.querySelectorAll('.btn-remove-meal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-meal-idx'));
                    meals.splice(idx, 1);
                    renderMeals();
                });
            });

            document.querySelectorAll('.btn-remove-food').forEach(btn => {
                btn.addEventListener('click', function() {
                    const mIdx = parseInt(this.getAttribute('data-meal-idx'));
                    const fIdx = parseInt(this.getAttribute('data-food-idx'));
                    meals[mIdx].foods.splice(fIdx, 1);
                    renderMeals();
                });
            });

            document.querySelectorAll('.meal-name-input').forEach(input => {
                input.addEventListener('change', function() {
                    const mIdx = parseInt(this.getAttribute('data-meal-idx'));
                    meals[mIdx].name = this.value;
                    updateMealsData();
                });
            });

            document.querySelectorAll('.btn-open-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-meal-idx'));
                    modalMealIndex.value = idx;
                    
                    // Clear modal fields
                    modalFoodName.value = '';
                    modalFoodQty.value = '100';
                    modalFoodUnit.value = 'g';
                    modalFoodCals.value = '0';
                    modalFoodProt.value = '0';
                    modalFoodCarbs.value = '0';
                    modalFoodFat.value = '0';

                    modalAddFood.classList.remove('hidden');
                    modalAddFood.classList.add('flex');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                });
            });
        }

        btnAddMeal.addEventListener('click', function() {
            meals.push({ name: 'Nueva Comida', foods: [] });
            renderMeals();
        });

        btnCloseModal.addEventListener('click', function() {
            modalAddFood.classList.add('hidden');
            modalAddFood.classList.remove('flex');
            document.body.style.overflow = '';
        });

        btnSaveFood.addEventListener('click', function() {
            const idx = parseInt(modalMealIndex.value);
            if(isNaN(idx)) return;

            const name = modalFoodName.value.trim();
            if(!name) { alert('Ingresa un nombre para el alimento.'); return; }

            const newFood = {
                name: name,
                serving_size: parseFloat(modalFoodQty.value) || 0,
                serving_unit: modalFoodUnit.value,
                calories: parseFloat(modalFoodCals.value) || 0,
                protein: parseFloat(modalFoodProt.value) || 0,
                carbs: parseFloat(modalFoodCarbs.value) || 0,
                fat: parseFloat(modalFoodFat.value) || 0
            };

            meals[idx].foods.push(newFood);
            
            modalAddFood.classList.add('hidden');
            modalAddFood.classList.remove('flex');
            document.body.style.overflow = '';
            
            renderMeals();
        });

        // Initialize empty meals
        renderMeals();

        // Auto-fill client physiological data when selected
        clientSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                calcWeight.value = selected.getAttribute('data-weight') || '';
                calcHeight.value = selected.getAttribute('data-height') || '';
                calcAge.value = selected.getAttribute('data-age') || '';
                // gender logic
                const g = selected.getAttribute('data-gender');
                calcGender.value = (g === 'male' || g === 'Hombre') ? 'male' : 'female';
            }
        });

        // Trigger initial data load if client was pre-selected
        if (clientSelect.value) {
            clientSelect.dispatchEvent(new Event('change'));
        }

        calcObjective.addEventListener('change', function() {
            if (this.value === 'mantenimiento') calcAdjustment.value = 0;
            if (this.value === 'volumen') calcAdjustment.value = 300;
            if (this.value === 'perdida_grasa') calcAdjustment.value = -300;
        });

        function calculateRequirements() {
            w = parseFloat(calcWeight.value);
            const h = parseFloat(calcHeight.value);
            const a = parseInt(calcAge.value);
            const activity = parseFloat(calcActivity.value);
            const adjustment = parseFloat(calcAdjustment.value) || 0;
            const formula = calcFormula.value;
            const gender = calcGender.value;

            if (!w || !h || !a) {
                alert('Por favor ingresa peso, altura y edad.');
                return;
            }

            let rmr = 0;

            if (formula === 'mifflin') {
                if (gender === 'male') {
                    rmr = (10 * w) + (6.25 * h) - (5 * a) + 5;
                } else {
                    rmr = (10 * w) + (6.25 * h) - (5 * a) - 161;
                }
            } else if (formula === 'harris') {
                if (gender === 'male') {
                    rmr = 66.5 + (13.75 * w) + (5.003 * h) - (6.75 * a);
                } else {
                    rmr = 655.1 + (9.563 * w) + (1.850 * h) - (4.676 * a);
                }
            } else if (formula === 'tinsley') {
                rmr = (24.8 * w) + 10;
            }

            const tdee = Math.round(rmr * activity);
            targetCals = tdee + adjustment;

            displayTdee.innerText = tdee;
            displayTarget.innerText = targetCals;
            inputTargetCalories.value = targetCals;

            calculateMacros();
            btnSubmit.disabled = false;
        }

        function calculateMacros() {
            if (targetCals <= 0 || !w) return;

            const pPerKg = parseFloat(calcProteinKg.value) || 0;
            const lPerKg = parseFloat(calcLipidsKg.value) || 0;

            const pGrams = pPerKg * w;
            const pCals = pGrams * 4;

            const lGrams = lPerKg * w;
            const lCals = lGrams * 9;

            const remainingCals = targetCals - pCals - lCals;
            const cCals = Math.max(0, remainingCals);
            const cGrams = cCals / 4;

            // Update displays
            displayProteinG.innerText = pGrams.toFixed(1);
            displayProteinKcal.innerText = pCals.toFixed(0);
            inputProtein.value = pGrams.toFixed(1);

            displayLipidsG.innerText = lGrams.toFixed(1);
            displayLipidsKcal.innerText = lCals.toFixed(0);
            inputFat.value = lGrams.toFixed(1);

            displayCarbsG.innerText = cGrams.toFixed(1);
            displayCarbsKcal.innerText = cCals.toFixed(0);
            inputCarbs.value = cGrams.toFixed(1);

            // Carbs per kg display
            displayCarbsKg.value = (cGrams / w).toFixed(1);
        }

        btnCalculate.addEventListener('click', calculateRequirements);
        calcProteinKg.addEventListener('input', calculateMacros);
        calcLipidsKg.addEventListener('input', calculateMacros);
    });
</script>
@endpush
