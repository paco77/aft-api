@extends('layouts.admin')

@section('header', 'Editar Plan de Alimentación')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 pb-12">
        
        <!-- Hero Header -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                        Modo Edición · Plan Nutricional
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                        <span class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </span>
                        Editar Plan: {{ $nutritionPlan->name ?: 'Plan de Alimentación' }}
                    </h2>
                    <p class="text-slate-300 text-sm max-w-2xl leading-relaxed">
                        Ajusta las calorías meta, modula los macronutrientes asignados o actualiza los tiempos de comida del atleta.
                    </p>
                </div>

                <div class="flex items-center gap-3 self-start md:self-auto shrink-0">
                    <a href="{{ route('admin.nutrition-plans.index') }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-slate-300 bg-white/5 hover:bg-white/10 hover:text-white border border-white/10 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a Planes
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.nutrition-plans.update', $nutritionPlan->id) }}" method="POST" id="nutrition-form" class="space-y-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">Revisa los siguientes campos:</h4>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- STEP 1: Client & Plan Basic Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm border border-indigo-100">01</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Información del Cliente y Plan</h3>
                        <p class="text-xs text-slate-500">Datos generales del usuario y nombre asignado</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Cliente <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="client_id" id="client_id" required
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 bg-slate-50/60 transition-colors">
                                <option value="">Selecciona un cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" data-weight="{{ $client->weight ?? '' }}"
                                        data-height="{{ $client->height ?? '' }}" data-age="{{ $client->age ?? '' }}"
                                        data-gender="{{ $client->gender ?? 'male' }}" data-phone="{{ $client->phone ?? '' }}" data-name="{{ $client->name }}" {{ (old('client_id', $nutritionPlan->client_id)) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('client_id')<p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre del Plan</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $nutritionPlan->name) }}"
                            placeholder="Ej: Plan de Definición - Junio"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 transition-colors">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Indicaciones y Recomendaciones Generales</label>
                        <textarea name="description" id="description" rows="2"
                            placeholder="Ej: Mantener hidratación continua, respetar los tiempos entre comidas..."
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 transition-colors">{{ old('description', $nutritionPlan->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Physiological Data & Calculation Engine -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 text-blue-600 font-bold text-sm border border-blue-100">02</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Datos Fisiológicos y Metodología</h3>
                            <p class="text-xs text-slate-500">Parámetros biométricos y fórmula metabólica basal</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Género</label>
                        <select id="calc_gender" name="gender"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                            <option value="male" {{ (old('gender', $nutritionPlan->gender ?? 'male') === 'male') ? 'selected' : '' }}>Hombre</option>
                            <option value="female" {{ (old('gender', $nutritionPlan->gender ?? 'male') === 'female') ? 'selected' : '' }}>Mujer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Peso (kg)</label>
                        <div class="relative">
                            <input type="number" id="calc_weight" name="weight" step="0.1" value="{{ old('weight', $nutritionPlan->weight) }}"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 pr-8">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium pointer-events-none">kg</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Altura (cm)</label>
                        <div class="relative">
                            <input type="number" id="calc_height" name="height" value="{{ old('height', $nutritionPlan->height) }}"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 pr-8">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium pointer-events-none">cm</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Edad (años)</label>
                        <div class="relative">
                            <input type="number" id="calc_age" name="age" value="{{ old('age', $nutritionPlan->age) }}"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 pr-8">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium pointer-events-none">años</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Nivel de Actividad</label>
                        <select id="calc_activity" name="activity_level"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                            <option value="1.2">1.2 - Sedentario (poco ejercicio)</option>
                            <option value="1.375">1.375 - Ligero (1-3 días/sem)</option>
                            <option value="1.55">1.55 - Moderado (3-5 días/sem)</option>
                            <option value="1.725">1.725 - Intenso (6-7 días/sem)</option>
                            <option value="1.9">1.9 - Muy Intenso (atleta doble sesión)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Fórmula Metabólica</label>
                        <select id="calc_formula" name="formula"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                            <option value="mifflin">Mifflin-St Jeor (Estándar)</option>
                            <option value="harris">Harris-Benedict</option>
                            <option value="tinsley">Tinsley (Atletas & Fuerza)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Objetivo</label>
                        <select id="calc_objective" name="objective"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="perdida_grasa">Pérdida de Grasa</option>
                            <option value="volumen">Aumento de Masa (Volumen)</option>
                            <option value="definicion">Definición Extrema</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">Ajuste Calórico</label>
                        <div class="relative">
                            <input type="number" id="calc_adjustment" name="caloric_adjustment" value="{{ old('caloric_adjustment', $nutritionPlan->caloric_adjustment ?? '0') }}"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 pr-10">
                            <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-medium pointer-events-none">kcal</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end">
                    <button type="button" id="btn_calculate"
                        class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/25 active:scale-95 transition-all flex items-center gap-2.5 text-sm">
                        <svg class="w-5 h-5 text-indigo-200 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Recalcular Requerimientos
                    </button>
                </div>
            </div>

            <!-- STEP 3: Results & Macro Distribution Dashboard -->
            <div class="bg-gradient-to-b from-slate-50 to-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200/80 flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 font-bold text-sm border border-emerald-100">03</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Resultados Energéticos y Distribución de Macronutrientes</h3>
                            <p class="text-xs text-slate-500">Valores de la prescripción nutricional del plan</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- TDEE & Target Hero Card -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                                </svg>
                                Gasto Calórico Total (TDEE)
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-medium">Mantenimiento</span>
                        </div>
                        <div class="mt-2 mb-4">
                            <div class="flex items-baseline gap-2">
                                <span id="display_tdee" class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">0</span>
                                <span class="text-sm font-semibold text-slate-400">kcal/día</span>
                            </div>
                        </div>

                        <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-2"></div>

                        <div class="mt-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                                    Calorías Objetivo Diarias
                                </span>
                                <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Meta Plan</span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span id="display_target" class="text-4xl sm:text-5xl font-black text-indigo-600 tracking-tight">{{ old('target_calories', $nutritionPlan->target_calories ?? 0) }}</span>
                                <span class="text-base font-bold text-indigo-400">kcal</span>
                            </div>
                            <input type="hidden" name="target_calories" id="input_target_calories" value="{{ old('target_calories', $nutritionPlan->target_calories) }}">
                        </div>
                    </div>

                    <!-- Macro Settings Card -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Ajuste de Macros por Peso (g / kg)
                                </h4>
                                <span class="text-xs text-slate-400 font-medium">Modula la prescripción</span>
                            </div>

                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50/50 border border-blue-100/70">
                                    <div>
                                        <p class="text-xs font-bold text-blue-900">Proteínas</p>
                                        <p class="text-[11px] text-blue-600">Recomendado: 1.8 - 2.5 g/kg</p>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <input type="number" id="calc_protein_kg" value="2.2" step="0.1"
                                            class="w-20 text-center font-bold text-blue-900 rounded-lg border-blue-200 shadow-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm py-1.5 bg-white">
                                        <span class="text-xs font-bold text-blue-700">g/kg</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 border border-emerald-100/70">
                                    <div>
                                        <p class="text-xs font-bold text-emerald-900">Grasas (Lípidos)</p>
                                        <p class="text-[11px] text-emerald-600">Recomendado: 0.6 - 1.2 g/kg</p>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <input type="number" id="calc_lipids_kg" value="0.8" step="0.1"
                                            class="w-20 text-center font-bold text-emerald-900 rounded-lg border-emerald-200 shadow-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm py-1.5 bg-white">
                                        <span class="text-xs font-bold text-emerald-700">g/kg</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/50 border border-amber-100/70">
                                    <div>
                                        <p class="text-xs font-bold text-amber-900">Carbohidratos (Restante)</p>
                                        <p class="text-[11px] text-amber-600">Se calcula automáticamente</p>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <input type="number" id="display_carbs_kg" value="0.0" disabled
                                            class="w-20 text-center font-bold text-amber-900 rounded-lg border-amber-200/80 bg-white/70 shadow-xs text-sm py-1.5 cursor-not-allowed">
                                        <span class="text-xs font-bold text-amber-700">g/kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Calculated Macro Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Proteínas -->
                    <div class="bg-gradient-to-br from-blue-500/10 via-white to-blue-50/40 border border-blue-200/80 rounded-2xl p-5 shadow-xs relative overflow-hidden group hover:border-blue-300 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                Proteínas
                            </span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-blue-100/80 text-blue-700">4 kcal/g</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="display_protein_g" class="text-3xl font-black text-slate-900 tracking-tight">{{ old('total_protein', $nutritionPlan->total_protein ?? 0) }}</span>
                            <span class="text-sm font-bold text-blue-600">gramos</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 font-medium">Aporte: <span id="display_protein_kcal" class="font-bold text-blue-700">0</span> kcal</p>
                        <input type="hidden" name="total_protein" id="input_protein" value="{{ old('total_protein', $nutritionPlan->total_protein) }}">
                    </div>

                    <!-- Carbohidratos -->
                    <div class="bg-gradient-to-br from-amber-500/10 via-white to-amber-50/40 border border-amber-200/80 rounded-2xl p-5 shadow-xs relative overflow-hidden group hover:border-amber-300 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                Carbohidratos
                            </span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-amber-100/80 text-amber-700">4 kcal/g</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="display_carbs_g" class="text-3xl font-black text-slate-900 tracking-tight">{{ old('total_carbs', $nutritionPlan->total_carbs ?? 0) }}</span>
                            <span class="text-sm font-bold text-amber-600">gramos</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 font-medium">Aporte: <span id="display_carbs_kcal" class="font-bold text-amber-700">0</span> kcal</p>
                        <input type="hidden" name="total_carbs" id="input_carbs" value="{{ old('total_carbs', $nutritionPlan->total_carbs) }}">
                    </div>

                    <!-- Grasas -->
                    <div class="bg-gradient-to-br from-emerald-500/10 via-white to-emerald-50/40 border border-emerald-200/80 rounded-2xl p-5 shadow-xs relative overflow-hidden group hover:border-emerald-300 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Grasas (Lípidos)
                            </span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-100/80 text-emerald-700">9 kcal/g</span>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span id="display_lipids_g" class="text-3xl font-black text-slate-900 tracking-tight">{{ old('total_fat', $nutritionPlan->total_fat ?? 0) }}</span>
                            <span class="text-sm font-bold text-emerald-600">gramos</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 font-medium">Aporte: <span id="display_lipids_kcal" class="font-bold text-emerald-700">0</span> kcal</p>
                        <input type="hidden" name="total_fat" id="input_fat" value="{{ old('total_fat', $nutritionPlan->total_fat) }}">
                    </div>
                </div>
            </div>

            <!-- STEP 4: Meal Planner (Tiempos de Comida) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-purple-50 text-purple-600 font-bold text-sm border border-purple-100">04</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Planificador de Tiempos de Comida</h3>
                            <p class="text-xs text-slate-500">Modifica o añade platos, alimentos y gramajes</p>
                        </div>
                    </div>

                    <button type="button" id="btn_add_meal"
                        class="inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold px-4 py-2.5 rounded-xl border border-indigo-200/60 shadow-xs transition-all active:scale-95 text-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Añadir Tiempo de Comida
                    </button>
                </div>

                <div id="meals_container" class="space-y-5">
                    <!-- Meal templates injected via JS -->
                </div>

                <input type="hidden" name="meals_data" id="input_meals_data">
            </div>

            <!-- Form Actions Bar -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-200 flex-wrap gap-4">
                <a href="{{ route('admin.nutrition-plans.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancelar
                </a>

                <button type="submit" id="btn_submit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/25 transition-all text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Plan de Alimentación
                </button>
            </div>
        </form>
    </div>

    <!-- Modal para añadir alimento manual & FatSecret -->
    <div id="modal_add_food" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden z-[60] items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Añadir Alimento</h4>
                        <p class="text-xs text-slate-500">Busca en la nube o escribe el alimento manualmente</p>
                    </div>
                </div>
                <button type="button" id="btn_close_modal" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                <input type="hidden" id="modal_meal_index">
                <input type="hidden" id="modal_fatsecret_id">

                <!-- BÚSQUEDA FATSECRET -->
                <div class="bg-gradient-to-br from-indigo-50/70 via-blue-50/50 to-slate-50 p-4 sm:p-5 rounded-2xl border border-indigo-100/80">
                    <label class="block text-xs font-bold text-indigo-900 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscador Nutricional Online (FatSecret API)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="fs_search_input" placeholder="Ej. Pechuga de pollo, Arroz integral, Avena, Manzana..." 
                            class="flex-1 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                        <button type="button" id="btn_fs_search" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 font-semibold text-sm flex items-center gap-1.5 shadow-sm active:scale-95 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Buscar
                        </button>
                    </div>
                    <div id="fs_loading" class="hidden mt-2 text-xs font-medium text-indigo-600 flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Consultando base de alimentos...
                    </div>
                    <div id="fs_results" class="mt-3 max-h-48 overflow-y-auto hidden bg-white rounded-xl shadow-xs border border-indigo-100 divide-y divide-slate-100">
                        <!-- Results injected here -->
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="h-px bg-slate-200 flex-1"></div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Detalles del Alimento</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre del alimento <span class="text-rose-500">*</span></label>
                    <input type="text" id="modal_food_name" placeholder="Ej. Pechuga de pollo cocida"
                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-slate-700">Cantidad <span class="text-rose-500">*</span></label>
                            <span class="text-[10px] text-slate-400">1, 1/2, 100</span>
                        </div>
                        <input type="text" id="modal_food_qty" value="100" placeholder="100"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3">
                        <div id="fraction_quick_buttons" class="flex flex-wrap gap-1 mt-2">
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="1/4">1/4</button>
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="1/3">1/3</button>
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="1/2">1/2</button>
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="3/4">3/4</button>
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="1">1</button>
                            <button type="button" class="btn-fraction text-[11px] bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 rounded-lg px-2 py-0.5 font-semibold text-slate-700 transition-colors" data-val="1 1/2">1 1/2</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Unidad de Medida</label>
                        <select id="modal_food_unit"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white">
                            <option value="g">Gramos (g)</option>
                            <option value="ml">Mililitros (ml)</option>
                            <option value="taza">Taza (tz)</option>
                            <option value="porcion">Porción</option>
                            <option value="pz">Pieza (pz)</option>
                            <option value="cda">Cucharada (cda)</option>
                            <option value="cdita">Cucharadita (cdita)</option>
                            <option value="rebanada">Rebanada</option>
                            <option value="scoop">Scoop / Medida</option>
                            <option value="vaso">Vaso</option>
                            <option value="lata">Lata</option>
                        </select>
                    </div>
                </div>

                <!-- Nutrition Breakdown for the quantity entered -->
                <div class="border border-slate-100 bg-slate-50/60 rounded-2xl p-4 hidden">
                    <p class="text-xs font-bold text-slate-700 mb-2.5 uppercase tracking-wider">Aporte Nutricional Estimado</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-white p-2.5 rounded-xl border border-slate-200/70 text-center">
                            <label class="block text-[11px] text-slate-500 font-semibold mb-0.5">Calorías</label>
                            <input type="number" id="modal_food_cals" value="0"
                                class="w-full text-center text-sm font-bold rounded-lg border-slate-200 shadow-xs focus:border-indigo-500 py-1">
                        </div>
                        <div class="bg-blue-50/50 p-2.5 rounded-xl border border-blue-100 text-center">
                            <label class="block text-[11px] text-blue-600 font-semibold mb-0.5">Proteína (g)</label>
                            <input type="number" id="modal_food_prot" step="0.1" value="0"
                                class="w-full text-center text-sm font-bold text-blue-700 rounded-lg border-blue-200 shadow-xs focus:border-indigo-500 py-1">
                        </div>
                        <div class="bg-amber-50/50 p-2.5 rounded-xl border border-amber-100 text-center">
                            <label class="block text-[11px] text-amber-600 font-semibold mb-0.5">Carbs (g)</label>
                            <input type="number" id="modal_food_carbs" step="0.1" value="0"
                                class="w-full text-center text-sm font-bold text-amber-700 rounded-lg border-amber-200 shadow-xs focus:border-indigo-500 py-1">
                        </div>
                        <div class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-100 text-center">
                            <label class="block text-[11px] text-emerald-600 font-semibold mb-0.5">Grasas (g)</label>
                            <input type="number" id="modal_food_fat" step="0.1" value="0"
                                class="w-full text-center text-sm font-bold text-emerald-700 rounded-lg border-emerald-200 shadow-xs focus:border-indigo-500 py-1">
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('btn_close_modal').click()"
                        class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="button" id="btn_save_food"
                        class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-500/20 active:scale-95 text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Añadir a la Comida
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            let targetCals = {{ (float) ($nutritionPlan->target_calories ?? 0) }};
            let w = {{ (float) ($nutritionPlan->weight ?? 0) }};
            let meals = [];

            function formatServingSize(qty) {
                if (qty === null || qty === undefined || qty === '') return '0';
                const str = String(qty).trim();
                if (str.includes('/')) return str;
                const num = parseFloat(str);
                return isNaN(num) ? str : String(num);
            }

            function updateMealsData() {
                inputMealsData.value = JSON.stringify(meals);
                window.meals = meals;
            }

            function renderMeals() {
                mealsContainer.innerHTML = '';

                if (meals.length === 0) {
                    mealsContainer.innerHTML = `
                        <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">No hay tiempos de comida agregados</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Haz clic en "Añadir Tiempo de Comida" para registrar alimentos.</p>
                        </div>`;
                    updateMealsData();
                    return;
                }

                meals.forEach((meal, mealIndex) => {
                    let mealTotalCals = 0;
                    if (meal.foods) {
                        meal.foods.forEach(f => mealTotalCals += parseFloat(f.calories || 0));
                    }

                    let foodsHtml = '';
                    if (meal.foods && meal.foods.length > 0) {
                        foodsHtml = `<ul class="divide-y divide-slate-100 border-t border-slate-100 mt-3">`;
                        meal.foods.forEach((food, foodIndex) => {
                            foodsHtml += `
                                <li class="py-2.5 px-3 rounded-xl hover:bg-slate-50 flex justify-between items-center group transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-800">${food.name}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md">
                                                    ${formatServingSize(food.serving_size)} ${food.serving_unit}
                                                </span>
                                                ${food.calories ? `<span class="text-xs text-slate-400">· ${parseFloat(food.calories).toFixed(0)} kcal</span>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" class="btn-remove-food text-slate-300 hover:text-rose-500 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" data-meal-idx="${mealIndex}" data-food-idx="${foodIndex}" title="Eliminar alimento">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>`;
                        });
                        foodsHtml += `</ul>`;
                    } else {
                        foodsHtml = `<div class="py-4 text-center text-xs text-slate-400 italic">Sin alimentos registrados en esta comida aún</div>`;
                    }

                    mealsContainer.innerHTML += `
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:border-indigo-200 transition-all">
                            <div class="flex justify-between items-center pb-3">
                                <div class="flex-1 mr-4 flex items-center gap-2.5">
                                    <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </span>
                                    <input type="text" class="meal-name-input font-bold text-slate-900 border-b-2 border-dashed border-slate-200 hover:border-indigo-400 focus:border-indigo-600 bg-transparent py-1 px-1 focus:ring-0 w-full placeholder-slate-400 focus:outline-none transition-colors text-base" value="${meal.name ? meal.name : ''}" data-meal-idx="${mealIndex}" placeholder="Ej. Desayuno, Almuerzo, Merienda, Cena...">
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-remove-meal text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors" data-meal-idx="${mealIndex}" title="Eliminar comida completa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>

                            ${foodsHtml}

                            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-center">
                                <button type="button" class="btn-open-modal text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50/70 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-all flex items-center gap-1.5 active:scale-95" data-meal-idx="${mealIndex}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Añadir Alimento a esta comida
                                </button>
                            </div>
                        </div>`;
                });

                updateMealsData();
                attachMealEventListeners();
            }

            function attachMealEventListeners() {
                document.querySelectorAll('.btn-remove-meal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const idx = parseInt(this.getAttribute('data-meal-idx'));
                        meals.splice(idx, 1);
                        renderMeals();
                    });
                });

                document.querySelectorAll('.btn-remove-food').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const mIdx = parseInt(this.getAttribute('data-meal-idx'));
                        const fIdx = parseInt(this.getAttribute('data-food-idx'));
                        meals[mIdx].foods.splice(fIdx, 1);
                        renderMeals();
                    });
                });

                document.querySelectorAll('.meal-name-input').forEach(input => {
                    input.addEventListener('input', function () {
                        const mIdx = parseInt(this.getAttribute('data-meal-idx'));
                        meals[mIdx].name = this.value;
                        updateMealsData();
                    });
                    input.addEventListener('change', function () {
                        const mIdx = parseInt(this.getAttribute('data-meal-idx'));
                        meals[mIdx].name = this.value;
                        updateMealsData();
                    });
                });

                document.querySelectorAll('.btn-open-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const idx = parseInt(this.getAttribute('data-meal-idx'));
                        modalMealIndex.value = idx;

                        modalFoodName.value = '';
                        modalFoodUnit.value = 'g';
                        modalFoodQty.value = '100';
                        modalFoodCals.value = '0';
                        modalFoodProt.value = '0';
                        modalFoodCarbs.value = '0';
                        modalFoodFat.value = '0';

                        modalAddFood.classList.remove('hidden');
                        modalAddFood.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    });
                });
            }

            document.querySelectorAll('.btn-fraction').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    modalFoodQty.value = this.getAttribute('data-val');
                    modalFoodQty.focus();
                });
            });

            modalFoodUnit.addEventListener('change', function () {
                const val = this.value;
                if (['g', 'ml'].includes(val)) {
                    if (modalFoodQty.value === '1' || modalFoodQty.value.includes('/')) {
                        modalFoodQty.value = '100';
                    }
                } else {
                    if (modalFoodQty.value === '100') {
                        modalFoodQty.value = '1';
                    }
                }
            });

            btnAddMeal.addEventListener('click', function () {
                meals.push({ name: '', foods: [] });
                renderMeals();
            });

            btnCloseModal.addEventListener('click', function () {
                modalAddFood.classList.add('hidden');
                modalAddFood.classList.remove('flex');
                document.body.style.overflow = '';
            });

            btnSaveFood.addEventListener('click', function () {
                const idx = parseInt(modalMealIndex.value);
                if (isNaN(idx)) return;

                const name = modalFoodName.value.trim();
                if (!name) { alert('Ingresa un nombre para el alimento.'); return; }

                const rawQty = modalFoodQty.value.trim();
                const newFood = {
                    fatsecret_food_id: modalFatsecretId.value || null,
                    name: name,
                    serving_size: rawQty || '1',
                    serving_unit: modalFoodUnit.value,
                    calories: parseFloat(modalFoodCals.value) || 0,
                    protein: parseFloat(modalFoodProt.value) || 0,
                    carbs: parseFloat(modalFoodCarbs.value) || 0,
                    fat: parseFloat(modalFoodFat.value) || 0
                };

                if (!meals[idx].foods) meals[idx].foods = [];
                meals[idx].foods.push(newFood);

                modalAddFood.classList.add('hidden');
                modalAddFood.classList.remove('flex');
                document.body.style.overflow = '';

                renderMeals();
            });

            // --- FATSECRET SEARCH LOGIC ---
            const btnFsSearch = document.getElementById('btn_fs_search');
            const fsSearchInput = document.getElementById('fs_search_input');
            const fsLoading = document.getElementById('fs_loading');
            const fsResults = document.getElementById('fs_results');
            const modalFatsecretId = document.getElementById('modal_fatsecret_id');
            const macroContainer = modalFoodCals.closest('.hidden');

            btnFsSearch.addEventListener('click', async function() {
                const query = fsSearchInput.value.trim();
                if(!query) return;

                fsLoading.classList.remove('hidden');
                fsResults.classList.add('hidden');
                fsResults.innerHTML = '';

                try {
                    const response = await fetch(`/admin/fatsecret/search?query=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    fsLoading.classList.add('hidden');
                    
                    if(data.error) {
                        alert("Error: " + data.error);
                        return;
                    }

                    if(data.length === 0) {
                        fsResults.innerHTML = '<div class="p-4 text-sm text-slate-500">No se encontraron resultados.</div>';
                        fsResults.classList.remove('hidden');
                        return;
                    }

                    data.sort((a, b) => {
                        const typeA = a.food_type || '';
                        const typeB = b.food_type || '';
                        if (typeA === 'Generic' && typeB !== 'Generic') return -1;
                        if (typeA !== 'Generic' && typeB === 'Generic') return 1;
                        return 0;
                    });

                    let html = '';
                    data.forEach(item => {
                        const desc = item.food_description || '';
                        
                        let displayDesc = desc
                            .replace(/Calories:/gi, 'Calorías:')
                            .replace(/Fat:/gi, 'Grasa:')
                            .replace(/Carbs:/gi, 'Carbh:')
                            .replace(/Protein:/gi, 'Prot:')
                            .replace(/Per /gi, 'por ');

                        html += `
                            <div class="p-3 hover:bg-indigo-50/70 cursor-pointer transition fs-result-item border-b border-slate-100 last:border-0" 
                                 data-id="${item.food_id}" 
                                 data-name="${item.food_name}" 
                                 data-desc="${desc}">
                                <div class="font-bold text-sm text-indigo-700 flex items-center justify-between">
                                    <span>${item.food_name}</span>
                                    ${item.brand_name ? `<span class="text-xs font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded">${item.brand_name}</span>` : ''}
                                </div>
                                <div class="text-xs text-slate-600 mt-1">${displayDesc}</div>
                            </div>
                        `;
                    });

                    fsResults.innerHTML = html;
                    fsResults.classList.remove('hidden');

                    document.querySelectorAll('.fs-result-item').forEach(el => {
                        el.addEventListener('click', function() {
                            const name = this.getAttribute('data-name');
                            const desc = this.getAttribute('data-desc');
                            const fsId = this.getAttribute('data-id');

                            let cals = 0, fat = 0, carbs = 0, prot = 0;
                            let servingQty = "100";
                            let servingUnit = "g";

                            const extractNumber = (str) => parseFloat(str.replace(',', '.'));

                            const calsMatch = desc.match(/(?:Calories|Calorías):\s*([\d,\.]+)kcal/i);
                            const fatMatch = desc.match(/(?:Fat|Grasa):\s*([\d,\.]+)g/i);
                            const carbsMatch = desc.match(/(?:Carbs|Carbh|Carbohidratos):\s*([\d,\.]+)g/i);
                            const protMatch = desc.match(/(?:Protein|Prot|Proteína|Proteínas):\s*([\d,\.]+)g/i);
                            
                            const servingMatch = desc.match(/(?:Per|Por)\s+([\d,\.\/]+)\s*([a-zA-Z]+)/i);
                            if(servingMatch) {
                                servingQty = servingMatch[1].replace(',', '.');
                                const rawUnit = servingMatch[2].toLowerCase();
                                if(rawUnit === 'oz' || rawUnit === 'onza') {
                                    servingUnit = 'g';
                                    try { servingQty = (eval(servingQty) * 28.35).toFixed(0); } catch(e){}
                                } else if(rawUnit === 'cup' || rawUnit === 'taza') {
                                    servingUnit = 'taza';
                                } else if(rawUnit === 'tbsp' || rawUnit === 'cda') {
                                    servingUnit = 'cda';
                                } else if(rawUnit === 'tsp' || rawUnit === 'cdita') {
                                    servingUnit = 'cdita';
                                } else if(rawUnit === 'slice' || rawUnit === 'rebanada' || rawUnit === 'rebanadas') {
                                    servingUnit = 'rebanada';
                                } else if(rawUnit === 'serving' || rawUnit === 'porción' || rawUnit === 'porcion') {
                                    servingUnit = 'porcion';
                                }
                            }

                            if(calsMatch) cals = extractNumber(calsMatch[1]);
                            if(fatMatch) fat = extractNumber(fatMatch[1]);
                            if(carbsMatch) carbs = extractNumber(carbsMatch[1]);
                            if(protMatch) prot = extractNumber(protMatch[1]);

                            modalFoodName.value = name;
                            modalFatsecretId.value = fsId;
                            modalFoodQty.value = servingQty;
                            
                            const unitExists = Array.from(modalFoodUnit.options).some(opt => opt.value === servingUnit);
                            modalFoodUnit.value = unitExists ? servingUnit : 'porcion';

                            let baseQtyParsed = 100;
                            if(String(servingQty).includes('/')) {
                                try {
                                    const parts = String(servingQty).split(' ');
                                    if(parts.length === 2) {
                                        const frac = parts[1].split('/');
                                        baseQtyParsed = parseFloat(parts[0]) + (parseFloat(frac[0]) / parseFloat(frac[1]));
                                    } else {
                                        const frac = parts[0].split('/');
                                        baseQtyParsed = (parseFloat(frac[0]) / parseFloat(frac[1]));
                                    }
                                } catch(e) {}
                            } else {
                                baseQtyParsed = parseFloat(servingQty) || 1;
                            }

                            modalFoodQty.dataset.baseQty = baseQtyParsed;
                            modalFoodQty.dataset.baseCals = cals;
                            modalFoodQty.dataset.baseProt = prot;
                            modalFoodQty.dataset.baseCarbs = carbs;
                            modalFoodQty.dataset.baseFat = fat;

                            modalFoodCals.value = cals;
                            modalFoodProt.value = prot;
                            modalFoodCarbs.value = carbs;
                            modalFoodFat.value = fat;

                            if(macroContainer) {
                                macroContainer.classList.remove('hidden');
                            }

                            fsResults.classList.add('hidden');
                            fsSearchInput.value = '';
                        });
                    });

                } catch(e) {
                    fsLoading.classList.add('hidden');
                    alert("Error conectando con la API");
                }
            });

            fsSearchInput.addEventListener('keypress', function(e) {
                if(e.key === 'Enter') {
                    e.preventDefault();
                    btnFsSearch.click();
                }
            });

            function recalculateMacros() {
                if(!modalFoodQty.dataset.baseQty) return;
                const baseQty = parseFloat(modalFoodQty.dataset.baseQty);
                if(isNaN(baseQty) || baseQty === 0) return;

                let currentQtyRaw = modalFoodQty.value.trim();
                let currentQty = 1;
                
                if (currentQtyRaw.includes('/')) {
                    try {
                        const parts = currentQtyRaw.split(' ');
                        if(parts.length === 2) {
                            const frac = parts[1].split('/');
                            currentQty = parseFloat(parts[0]) + (parseFloat(frac[0]) / parseFloat(frac[1]));
                        } else {
                            const frac = parts[0].split('/');
                            currentQty = (parseFloat(frac[0]) / parseFloat(frac[1]));
                        }
                    } catch(e) {}
                } else {
                    currentQty = parseFloat(currentQtyRaw) || 0;
                }

                if(currentQty > 0) {
                    const ratio = currentQty / baseQty;
                    modalFoodCals.value = (parseFloat(modalFoodQty.dataset.baseCals) * ratio).toFixed(1);
                    modalFoodProt.value = (parseFloat(modalFoodQty.dataset.baseProt) * ratio).toFixed(1);
                    modalFoodCarbs.value = (parseFloat(modalFoodQty.dataset.baseCarbs) * ratio).toFixed(1);
                    modalFoodFat.value = (parseFloat(modalFoodQty.dataset.baseFat) * ratio).toFixed(1);
                } else {
                    modalFoodCals.value = 0;
                    modalFoodProt.value = 0;
                    modalFoodCarbs.value = 0;
                    modalFoodFat.value = 0;
                }
            }

            modalFoodQty.addEventListener('input', recalculateMacros);
            
            document.querySelectorAll('.btn-fraction').forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                newBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    modalFoodQty.value = this.getAttribute('data-val');
                    modalFoodQty.dispatchEvent(new Event('input'));
                    modalFoodQty.focus();
                });
            });

            const origAttach = attachMealEventListeners;
            attachMealEventListeners = function() {
                origAttach();
                document.querySelectorAll('.btn-open-modal').forEach(btn => {
                    btn.addEventListener('click', function () {
                        delete modalFoodQty.dataset.baseQty;
                    });
                });
            };

            clientSelect.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    calcWeight.value = selected.getAttribute('data-weight') || '';
                    calcHeight.value = selected.getAttribute('data-height') || '';
                    calcAge.value = selected.getAttribute('data-age') || '';
                    const g = selected.getAttribute('data-gender');
                    calcGender.value = (g === 'male' || g === 'Hombre') ? 'male' : 'female';
                }
            });

            calcObjective.addEventListener('change', function () {
                if (this.value === 'mantenimiento') calcAdjustment.value = 0;
                if (this.value === 'volumen') calcAdjustment.value = 300;
                if (this.value === 'perdida_grasa') calcAdjustment.value = -300;
                if (this.value === 'definicion') calcAdjustment.value = -500;
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

                displayProteinG.innerText = pGrams.toFixed(1);
                displayProteinKcal.innerText = pCals.toFixed(0);
                inputProtein.value = pGrams.toFixed(1);

                displayLipidsG.innerText = lGrams.toFixed(1);
                displayLipidsKcal.innerText = lCals.toFixed(0);
                inputFat.value = lGrams.toFixed(1);

                displayCarbsG.innerText = cGrams.toFixed(1);
                displayCarbsKcal.innerText = cCals.toFixed(0);
                inputCarbs.value = cGrams.toFixed(1);

                displayCarbsKg.value = (cGrams / w).toFixed(1);
            }

            btnCalculate.addEventListener('click', calculateRequirements);
            calcProteinKg.addEventListener('input', calculateMacros);
            calcLipidsKg.addEventListener('input', calculateMacros);

            // PREFILL DATA
            calcGender.value = "{{ old('gender', $nutritionPlan->gender ?? 'male') }}";
            calcWeight.value = "{{ old('weight', $nutritionPlan->weight) }}";
            calcHeight.value = "{{ old('height', $nutritionPlan->height) }}";
            calcAge.value = "{{ old('age', $nutritionPlan->age) }}";
            calcActivity.value = "{{ old('activity_level', $nutritionPlan->activity_level ?? '1.2') }}";
            calcFormula.value = "{{ old('formula', $nutritionPlan->formula ?? 'mifflin') }}";
            calcObjective.value = "{{ old('objective', $nutritionPlan->objective ?? 'mantenimiento') }}";
            calcAdjustment.value = "{{ old('caloric_adjustment', $nutritionPlan->caloric_adjustment ?? '0') }}";

            @if($nutritionPlan->weight && $nutritionPlan->total_protein && $nutritionPlan->target_calories)
                calcProteinKg.value = ({{ $nutritionPlan->total_protein }} / {{ $nutritionPlan->weight }}).toFixed(1);
                calcLipidsKg.value = ({{ $nutritionPlan->total_fat }} / {{ $nutritionPlan->weight }}).toFixed(1);
            @endif

            @php
                $oldMeals = old('meals_data') ? json_decode(old('meals_data'), true) : null;
                $defaultMeals = $nutritionPlan->meals->map(function($meal) {
                    return [
                        'name' => $meal->name,
                        'time' => $meal->time,
                        'foods' => $meal->foods->map(function($food) {
                            return [
                                'fatsecret_food_id' => $food->fatsecret_food_id,
                                'name' => $food->name,
                                'serving_size' => $food->serving_size,
                                'serving_unit' => $food->serving_unit,
                                'calories' => $food->calories,
                                'protein' => $food->protein,
                                'carbs' => $food->carbs,
                                'fat' => $food->fat
                            ];
                        })->toArray()
                    ];
                })->toArray();
                $mealsData = $oldMeals ?: $defaultMeals;
            @endphp
            meals = @json($mealsData);

            if (calcWeight.value && calcHeight.value && calcAge.value) {
                calculateRequirements();
            }
            renderMeals();

            const formElement = document.getElementById('nutrition-form');
            if (formElement) {
                formElement.addEventListener('submit', function (e) {
                    if (!this.dataset.confirmed) {
                        e.preventDefault();
                        
                        Swal.fire({
                            title: '¿Avisar por WhatsApp?',
                            text: '¿Deseas avisar a este cliente por WhatsApp sobre la actualización de su plan de alimentación?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, avisar y guardar',
                            cancelButtonText: 'No, solo guardar',
                            confirmButtonColor: '#25D366'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const select = document.getElementById('client_id');
                                if (select.selectedIndex > 0) {
                                    const option = select.options[select.selectedIndex];
                                    const phone = option.getAttribute('data-phone');
                                    const name = option.getAttribute('data-name');
                                    
                                    if (phone) {
                                        const cleanPhone = phone.replace(/[^0-9]/g, '');
                                        const planName = document.getElementById('name').value;
                                        const planText = planName ? `: ${planName}` : '';
                                        const message = encodeURIComponent(`Hola ${name}, he actualizado tu plan de alimentación${planText}. Puedes revisarlo en tu app.`);
                                        window.open(`https://wa.me/${cleanPhone}?text=${message}`, '_blank');
                                    }
                                }
                            }
                            
                            this.dataset.confirmed = true;
                            
                            const btn = document.getElementById('btn_submit');
                            if (btn) {
                                btn.disabled = true;
                                btn.classList.add('opacity-50', 'cursor-not-allowed');
                                btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
                            }

                            const mealsDataInput = document.getElementById('input_meals_data');
                            if (mealsDataInput) {
                                mealsDataInput.value = JSON.stringify(window.meals);
                            }

                            this.submit();
                        });
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush