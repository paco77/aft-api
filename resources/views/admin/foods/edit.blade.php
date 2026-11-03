@extends('layouts.admin')

@section('header', 'Editar Alimento')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Back Link -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Alimento: {{ $food->name }}</h2>
            <p class="text-sm text-gray-500">Actualiza la información nutricional o la unidad de medida de este registro.</p>
        </div>
        <a href="{{ route('admin.foods.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-300 rounded-lg px-3 py-2 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Volver al Catálogo</span>
        </a>
    </div>

    <div class="bg-white shadow-xs border border-gray-200 overflow-hidden rounded-xl">
        <form action="{{ route('admin.foods.update', $food) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Clasificación y Nombre -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600 mb-4 pb-2 border-b border-gray-100">
                    1. Clasificación e Identificación del Alimento
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                    <div class="sm:col-span-3">
                        <label for="food_category_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Categoría <span class="text-red-500">*</span>
                        </label>
                        <select name="food_category_id" id="food_category_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                            <option value="">Seleccionar categoría...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('food_category_id', $food->food_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('food_category_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="new_category_name" class="block text-sm font-semibold text-gray-700 mb-1">
                            O Crear Nueva Categoría <span class="text-xs font-normal text-gray-400">(opcional)</span>
                        </label>
                        <input type="text" name="new_category_name" id="new_category_name" value="{{ old('new_category_name') }}"
                            placeholder="Ej. Bebidas Deportivas"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                            Nombre del Alimento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $food->name) }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="subcategory" class="block text-sm font-semibold text-gray-700 mb-1">
                            Subcategoría <span class="text-xs font-normal text-gray-400">(opcional)</span>
                        </label>
                        <input type="text" name="subcategory" id="subcategory" value="{{ old('subcategory', $food->subcategory) }}" list="subcategories-list"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                        <datalist id="subcategories-list">
                            @foreach($subcategories as $sub)
                                <option value="{{ $sub }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="state" class="block text-sm font-semibold text-gray-700 mb-1">
                            Estado / Preparación
                        </label>
                        <input type="text" name="state" id="state" value="{{ old('state', $food->state) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- 2. Unidad de Medida y Porción Base -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600 mb-4 pb-2 border-b border-gray-100">
                    2. Porción y Unidad de Medida Base
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="portion_type" class="block text-sm font-semibold text-gray-700 mb-1">
                            Etiqueta de Porción <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="portion_type" id="portion_type" value="{{ old('portion_type', $food->portion_type) }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="base_quantity" class="block text-sm font-semibold text-gray-700 mb-1">
                            Cantidad Base <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0.01" name="base_quantity" id="base_quantity" value="{{ old('base_quantity', $food->base_quantity) }}" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="base_unit" class="block text-sm font-semibold text-gray-700 mb-1">
                            Unidad de Medida <span class="text-red-500">*</span>
                        </label>
                        <select name="base_unit" id="base_unit"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                            @foreach(['g' => 'Gramos (g)', 'ml' => 'Mililitros (ml)', 'pz' => 'Pieza (pz)', 'taza' => 'Taza (taza)', 'cda' => 'Cucharada (cda)', 'porcion' => 'Porción (porcion)'] as $unitKey => $unitLabel)
                                <option value="{{ $unitKey }}" {{ old('base_unit', $food->base_unit) === $unitKey ? 'selected' : '' }}>
                                    {{ $unitLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="gram_weight" class="block text-sm font-semibold text-gray-700 mb-1">
                            Peso en g/ml <span class="text-xs font-normal text-gray-400">(equiv.)</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="gram_weight" id="gram_weight" value="{{ old('gram_weight', $food->gram_weight) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="sm:col-span-4">
                        <label for="portion_detail" class="block text-sm font-semibold text-gray-700 mb-1">
                            Detalle de Porción
                        </label>
                        <input type="text" name="portion_detail" id="portion_detail" value="{{ old('portion_detail', $food->portion_detail) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- 3. Macronutrientes -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 pb-2 border-b border-gray-100 gap-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-600">
                        3. Macronutrientes
                    </h3>
                    <button type="button" onclick="autoCalculateKcal()"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                        <span>Autocalcular Kcal (4P + 4C + 9G)</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-orange-50/60 border border-orange-200 rounded-xl p-3">
                        <label for="calories" class="block text-xs font-bold uppercase text-orange-700 mb-1">
                            Calorías (kcal) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="calories" id="calories" value="{{ old('calories', $food->calories) }}" required
                            class="w-full rounded-lg border border-orange-300 bg-white px-3 py-2 text-sm font-bold text-gray-900 focus:border-orange-500 focus:outline-none">
                    </div>

                    <div class="bg-blue-50/60 border border-blue-200 rounded-xl p-3">
                        <label for="protein" class="block text-xs font-bold uppercase text-blue-700 mb-1">
                            Proteína (g) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="protein" id="protein" value="{{ old('protein', $food->protein) }}" required
                            class="w-full rounded-lg border border-blue-300 bg-white px-3 py-2 text-sm font-bold text-gray-900 focus:border-blue-500 focus:outline-none">
                    </div>

                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-3">
                        <label for="carbs" class="block text-xs font-bold uppercase text-emerald-700 mb-1">
                            Carbohidratos (g) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="carbs" id="carbs" value="{{ old('carbs', $food->carbs) }}" required
                            class="w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 text-sm font-bold text-gray-900 focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-3">
                        <label for="fat" class="block text-xs font-bold uppercase text-amber-700 mb-1">
                            Grasas (g) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="fat" id="fat" value="{{ old('fat', $food->fat) }}" required
                            class="w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm font-bold text-gray-900 focus:border-amber-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.foods.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-lg text-sm transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg text-sm shadow-xs transition">
                    Actualizar Alimento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function autoCalculateKcal() {
        const p = parseFloat(document.getElementById('protein').value) || 0;
        const c = parseFloat(document.getElementById('carbs').value) || 0;
        const f = parseFloat(document.getElementById('fat').value) || 0;
        document.getElementById('calories').value = Math.round(((p * 4) + (c * 4) + (f * 9)) * 10) / 10;
    }
</script>
@endpush

