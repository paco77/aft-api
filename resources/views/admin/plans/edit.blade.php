@extends('layouts.admin')

@section('header', 'Editar Plan de Entrenamiento')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 pb-12 relative">
        
        <!-- Hero Header -->
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-40 -bottom-10 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                        Modo Edición · Plan de Entrenamiento
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                        <span class="p-2.5 rounded-2xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </span>
                        Editar Rutina: {{ $plan->client->name ?? 'Cliente' }} ({{ $plan->month }} {{ $plan->year }})
                    </h2>
                    <p class="text-slate-300 text-sm max-w-2xl leading-relaxed">
                        Ajusta la distribución de días, reorganiza el orden de ejercicios y modifica series o rangos de repetición.
                    </p>
                </div>

                <div class="flex items-center gap-3 self-start md:self-auto shrink-0">
                    <a href="{{ route('admin.plans.index') }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-slate-300 bg-white/5 hover:bg-white/10 hover:text-white border border-white/10 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a Planes
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" id="planForm" class="space-y-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">Hubo algunos problemas con los datos:</h4>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Plan Global Configuration Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm border border-indigo-100">01</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Configuración General de la Rutina</h3>
                        <p class="text-xs text-slate-500">Asignación de usuario, periodo del mes y tipo de división</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="assigned_client_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Cliente Asignado <span class="text-rose-500">*</span>
                        </label>
                        <select name="assigned_client_id" id="assigned_client_id" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 bg-slate-50/60 transition-colors">
                            <option value="">Selecciona un cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-phone="{{ $client->phone ?? '' }}" data-name="{{ $client->name }}" {{ (old('assigned_client_id', $plan->assigned_client_id) == $client->id) ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="month" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Mes <span class="text-rose-500">*</span>
                        </label>
                        <select name="month" id="month" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 bg-white transition-colors">
                            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $m)
                                <option value="{{ $m }}" {{ (old('month', $plan->month) == $m) ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="year" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Año <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="year" id="year" value="{{ old('year', $plan->year) }}" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 transition-colors">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="split_type" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tipo de Rutina (Split) <span class="text-rose-500">*</span>
                        </label>
                        <select name="split_type" id="split_type" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 bg-white transition-colors">
                            <option value="">Seleccionar...</option>
                            @foreach(['Empuje', 'Traccion', 'Pierna', 'Full Body', 'Torso / Pierna', 'Personalizado'] as $st)
                                <option value="{{ $st }}" {{ (old('split_type', $plan->split_type) == $st) ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="days_per_week" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Días / sem <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="days_per_week" id="days_per_week" min="1" max="7" value="{{ old('days_per_week', $plan->days_per_week) }}" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Training Days Container -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm border border-indigo-100">02</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Estructura de Días y Ejercicios</h3>
                            <p class="text-xs text-slate-500">Arrastra para reordenar los ejercicios y ajusta sus series y repeticiones</p>
                        </div>
                    </div>
                </div>

                <div id="days-container" class="space-y-6">
                    <!-- Días dinámicos inyectados aquí -->
                </div>

                <!-- Add Day CTA Button -->
                <button type="button" onclick="addDay()"
                    class="w-full py-4 border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-white hover:bg-indigo-50/50 text-indigo-600 font-bold rounded-2xl shadow-xs transition-all flex items-center justify-center gap-2.5 text-sm group">
                    <span class="p-1 rounded-lg bg-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    Añadir Nuevo Día de Entrenamiento
                </button>
            </div>

            <!-- Form Actions Bar -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-200 flex-wrap gap-4">
                <a href="{{ route('admin.plans.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">
                    Cancelar
                </a>
                
                <button type="submit" id="btn_submit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/25 transition-all text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Plan de Entrenamiento
                </button>
            </div>
        </form>
    </div>

    <!-- Modal para Seleccionar / Crear Ejercicios -->
    <div id="exercise-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" aria-hidden="true" onclick="closeExerciseModal()"></div>

            <div class="relative z-10 inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl sm:w-full border border-slate-100">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">Banco de Ejercicios</h3>
                            <p class="text-xs text-slate-500">Selecciona los ejercicios que formarán parte de la sesión</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeExerciseModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Buscador con icono -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="exercise-search" placeholder="Buscar ejercicio por nombre o movimiento..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm bg-slate-50/50">
                    </div>

                    <!-- Filtros por Grupo Muscular en el Modal (Siempre disponibles) -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filtrar por grupo muscular:</span>
                            <span id="exercise-count-badge" class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ count($exercises) }} ejercicios
                            </span>
                        </div>
                        <div id="modal-mg-filters" class="flex flex-wrap gap-1.5 p-2 rounded-2xl bg-slate-100/70 border border-slate-200">
                            <button type="button" class="mg-filter-chip px-3 py-1 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-xs" data-mg="all">
                                Todos
                            </button>
                            @foreach($muscleGroups as $mg)
                                <button type="button" class="mg-filter-chip px-3 py-1 rounded-xl text-xs font-bold transition-all bg-white text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200/80 shadow-2xs" data-mg="{{ $mg->id }}">
                                    {{ $mg->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Lista de Ejercicios Grid -->
                    <div class="max-h-[52vh] overflow-y-auto border border-slate-200/80 rounded-2xl p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 bg-slate-50/30"
                        id="modal-exercises-list">
                        @foreach($exercises as $ex)
                            @php
                                $rawUrl = $ex->video_url;
                                $isHttp = \Illuminate\Support\Str::startsWith($rawUrl, ['http://', 'https://']);
                                $mediaSrc = $rawUrl ? ($isHttp ? $rawUrl : (str_starts_with($rawUrl, '/') ? $rawUrl : '/' . $rawUrl)) : null;
                                $isMp4 = $rawUrl ? \Illuminate\Support\Str::endsWith(strtolower($rawUrl), ['.mp4', '.webm', '.mov']) : false;
                            @endphp
                            <div class="flex flex-col p-2.5 rounded-2xl border border-slate-200 bg-white shadow-xs hover:border-indigo-300 hover:shadow-md transition-all exercise-item relative group cursor-pointer select-none has-[:checked]:border-indigo-500 has-[:checked]:ring-2 has-[:checked]:ring-indigo-500/20 has-[:checked]:bg-indigo-50/20"
                                data-name="{{ strtolower($ex->name) }}" data-mg="{{ $ex->muscle_group_id }}">
                                
                                <div class="w-full h-24 mb-2 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center relative">
                                    @if($mediaSrc)
                                        @if($isMp4)
                                            <video src="{{ $mediaSrc }}" class="w-full h-full object-cover" autoplay loop muted playsinline></video>
                                        @else
                                            <img src="{{ $mediaSrc }}" alt="{{ $ex->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                            <div class="hidden w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute top-1 right-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-900/75 text-white backdrop-blur-xs">
                                        {{ $ex->muscleGroup->name ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="flex items-start w-full text-left gap-2 pt-1">
                                    <input type="checkbox" id="ex-{{ $ex->id }}" value="{{ $ex->id }}" data-name="{{ $ex->name }}" data-video="{{ $ex->video_url }}"
                                        class="modal-exercise-checkbox mt-0.5 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded shrink-0">
                                    <label for="ex-{{ $ex->id }}" class="block text-xs font-bold text-slate-800 cursor-pointer w-full leading-tight line-clamp-2">
                                        {{ $ex->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Acordeón para crear ejercicio rápido -->
                    <div class="border-t border-slate-100 pt-4">
                        <button type="button" onclick="toggleQuickCreate()"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50/60 hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            ¿No encuentras el ejercicio? Crear nuevo al instante
                        </button>

                        <div id="quick-create-form" class="hidden mt-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre</label>
                                    <input type="text" id="qc-name" placeholder="Ej. Press Inclinado con Mancuernas"
                                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs py-1.5 px-2.5 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Grupo Muscular</label>
                                    <select id="qc-muscle-group"
                                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs py-1.5 px-2.5 bg-white">
                                        <option value="">Seleccionar...</option>
                                        @foreach($muscleGroups as $mg)
                                            <option value="{{ $mg->id }}" data-name="{{ $mg->name }}">{{ $mg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Enlace de Video (YouTube, IG, TikTok) <span class="text-slate-400 font-normal">(opcional)</span></label>
                                    <input type="text" id="qc-video-url" placeholder="https://youtube.com/... o reels (opcional)"
                                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs py-1.5 px-2.5 bg-white">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción / Indicaciones</label>
                                    <textarea id="qc-description" rows="2" placeholder="Técnica, agarre, puntos clave..."
                                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-xs py-1.5 px-2.5 bg-white"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="quickCreateExercise()"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs flex items-center shadow-xs transition-colors">
                                    <span id="qc-spinner" class="hidden mr-2">...</span>
                                    Guardar Ejercicio y Seleccionar
                                </button>
                            </div>
                            <p id="qc-error" class="text-xs text-rose-600 mt-2 font-medium hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeExerciseModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-white transition-colors text-sm">
                        Cancelar
                    </button>
                    <button type="button" onclick="addSelectedExercises()"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-500/20 transition-all text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Añadir Seleccionados a la Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template para Ejercicio en la lista -->
    <template id="exercise-template">
        <div class="exercise-row flex items-center gap-3.5 p-3.5 rounded-2xl bg-slate-50/80 hover:bg-white border border-slate-200/90 shadow-xs hover:shadow-sm cursor-move transition-all group">
            <div class="flex-none text-slate-400 hover:text-indigo-600 cursor-move p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
            </div>
            
            <div class="flex-1 min-w-0">
                <input type="hidden" class="exercise-id" value="">
                <p class="text-sm font-bold text-slate-900 exercise-name-display truncate"></p>
            </div>

            <div class="w-20">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Series</label>
                <input type="number"
                    class="exercise-sets mt-1 block w-full text-center font-bold text-slate-900 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 bg-white"
                    value="3" min="1" required>
            </div>

            <div class="w-20">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Min Reps</label>
                <input type="number"
                    class="exercise-min mt-1 block w-full text-center font-bold text-slate-900 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 bg-white"
                    value="8" min="1" required>
            </div>

            <div class="w-20">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Max Reps</label>
                <input type="number"
                    class="exercise-max mt-1 block w-full text-center font-bold text-slate-900 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-1.5 bg-white"
                    value="12" min="1" required>
            </div>

            <div class="w-full sm:w-56">
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 22c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 22c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/>
                    </svg>
                    <span>Video <span class="font-normal text-slate-400 lowercase">(opcional)</span></span>
                </label>
                <input type="text"
                    class="exercise-video block w-full text-xs text-slate-800 rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 py-1.5 px-2.5 bg-slate-50/50"
                    placeholder="https://youtu.be/... (opcional)">
            </div>

            <div>
                <button type="button" class="text-slate-300 hover:text-rose-500 p-2 rounded-xl hover:bg-rose-50 remove-exercise transition-colors mt-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let dayIndex = 0;
        let currentDayBlockForModal = null;
        let activeMgFilter = 'all';

        document.getElementById('exercise-search').addEventListener('input', function (e) {
            applyModalFilters();
        });

        function setMuscleGroupFilter(mgId) {
            activeMgFilter = String(mgId);
            document.querySelectorAll('.mg-filter-chip').forEach(chip => {
                if (chip.getAttribute('data-mg') === activeMgFilter) {
                    chip.className = 'mg-filter-chip px-3 py-1 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-xs';
                } else {
                    chip.className = 'mg-filter-chip px-3 py-1 rounded-xl text-xs font-bold transition-all bg-white text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200/80 shadow-2xs';
                }
            });
            applyModalFilters();
        }

        function applyModalFilters() {
            const term = (document.getElementById('exercise-search').value || '').toLowerCase().trim();
            const items = document.querySelectorAll('.exercise-item');
            let visibleCount = 0;

            items.forEach(item => {
                const itemMg = item.getAttribute('data-mg');
                const name = item.getAttribute('data-name');
                
                const matchesMg = (activeMgFilter === 'all') || (itemMg === activeMgFilter);
                const matchesSearch = !term || name.includes(term);

                if (matchesMg && matchesSearch) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const countBadge = document.getElementById('exercise-count-badge');
            if (countBadge) {
                countBadge.textContent = `${visibleCount} ejercicio${visibleCount === 1 ? '' : 's'}`;
            }
        }

        document.querySelectorAll('.mg-filter-chip').forEach(chip => {
            chip.addEventListener('click', function () {
                setMuscleGroupFilter(this.getAttribute('data-mg'));
            });
        });

        function attachExerciseCardListeners() {
            document.querySelectorAll('.exercise-item').forEach(item => {
                if (item.dataset.hasClickListener) return;
                item.dataset.hasClickListener = 'true';
                item.addEventListener('click', function (e) {
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                        const cb = this.querySelector('.modal-exercise-checkbox');
                        if (cb) cb.checked = !cb.checked;
                    }
                });
            });
        }
        attachExerciseCardListeners();

        function addDay() {
            const container = document.getElementById('days-container');

            let muscleGroupsHtml = '';
            @foreach($muscleGroups as $mg)
                muscleGroupsHtml += `
                    <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200/90 hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:text-indigo-900">
                        <input id="mg-${dayIndex}-{{ $mg->id }}" name="days[${dayIndex}][muscle_groups][]" value="{{ $mg->id }}" type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                        <span class="text-xs font-semibold text-slate-800">${'{{ $mg->name }}'}</span>
                    </label>
                `;
            @endforeach

            const dayHtml = `
                <div class="day-block bg-white border border-slate-200/90 shadow-sm rounded-3xl p-6 sm:p-7 transition-all hover:border-slate-300" data-day-index="${dayIndex}">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100 flex-wrap gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="px-3 py-1 rounded-xl bg-indigo-600 text-white font-extrabold text-xs tracking-wide uppercase shadow-xs">
                                Sesión ${dayIndex + 1}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">Bloque de entrenamiento diario</span>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-rose-600 text-xs font-bold remove-day flex items-center gap-1.5 px-3 py-1.5 rounded-xl hover:bg-rose-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Eliminar Sesión
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Etiqueta de la Sesión (Ej. Pecho, Hombro y Tríceps)</label>
                            <input type="text" name="days[${dayIndex}][label]" placeholder="Ej. Empuje Pesado / Torso A"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Orden / Número de Día</label>
                            <input type="number" name="days[${dayIndex}][day_number]" value="${dayIndex + 1}"
                                class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2 px-3 bg-white" required>
                        </div>
                    </div>

                    <div class="mb-6 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2.5">
                            Grupos Musculares Tratados (Filtro Rápido)
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            ${muscleGroupsHtml}
                        </div>
                    </div>

                    <!-- Cabecera de ejercicios si hay -->
                    <div class="mb-3 px-3 flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>Ejercicios Programados</span>
                    </div>

                    <div class="exercises-container space-y-3" id="exercises-container-${dayIndex}">
                        <!-- Ejercicios aquí -->
                    </div>

                    <button type="button" class="mt-4 w-full border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/40 hover:bg-indigo-50 text-indigo-700 font-bold py-3 px-4 rounded-2xl text-xs text-center transition flex items-center justify-center gap-2 open-modal-btn">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Añadir Ejercicios a este Día
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', dayHtml);

            const newBlock = container.lastElementChild;

            const exContainer = newBlock.querySelector('.exercises-container');
            new Sortable(exContainer, {
                animation: 150,
                handle: '.cursor-move',
                onEnd: function () {
                    updateExerciseIndices(newBlock);
                }
            });

            newBlock.querySelector('.remove-day').addEventListener('click', function () {
                newBlock.remove();
            });

            newBlock.querySelector('.open-modal-btn').addEventListener('click', function () {
                openExerciseModal(newBlock);
            });

            dayIndex++;
        }

        function openExerciseModal(dayBlock) {
            currentDayBlockForModal = dayBlock;
            
            // Si el bloque de sesión tiene grupos musculares marcados, pre-seleccionamos el primero para conveniencia
            const checkedBoxes = dayBlock.querySelectorAll('input[type="checkbox"]:checked');
            const selectedMgIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (selectedMgIds.length > 0) {
                setMuscleGroupFilter(selectedMgIds[0]);
            } else {
                setMuscleGroupFilter('all');
            }

            document.querySelectorAll('.modal-exercise-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('exercise-search').value = '';

            applyModalFilters();
            attachExerciseCardListeners();
            document.getElementById('exercise-modal').classList.remove('hidden');
        }

        function closeExerciseModal() {
            document.getElementById('exercise-modal').classList.add('hidden');
            currentDayBlockForModal = null;
        }

        function toggleQuickCreate() {
            const form = document.getElementById('quick-create-form');
            form.classList.toggle('hidden');
        }

        async function quickCreateExercise() {
            const nameInput = document.getElementById('qc-name');
            const mgSelect = document.getElementById('qc-muscle-group');
            const videoInput = document.getElementById('qc-video-url');
            const descInput = document.getElementById('qc-description');
            const errP = document.getElementById('qc-error');

            if (!nameInput.value.trim() || !mgSelect.value) {
                errP.textContent = 'Nombre y Grupo Muscular son obligatorios.';
                errP.classList.remove('hidden');
                return;
            }

            errP.classList.add('hidden');

            try {
                const res = await fetch("{{ route('admin.exercises.api-store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: nameInput.value.trim(),
                        muscle_group_id: mgSelect.value,
                        video_url: videoInput ? videoInput.value.trim() : null,
                        description: descInput.value.trim()
                    })
                });

                const data = await res.json();
                if (data.success) {
                    const list = document.getElementById('modal-exercises-list');
                    const ex = data.exercise;
                    const mgName = mgSelect.options[mgSelect.selectedIndex].getAttribute('data-name');

                    const itemHtml = `
                        <div class="flex flex-col p-2.5 rounded-2xl border border-slate-200 bg-white shadow-xs hover:border-indigo-300 hover:shadow-md transition-all exercise-item relative group cursor-pointer select-none has-[:checked]:border-indigo-500 has-[:checked]:ring-2 has-[:checked]:ring-indigo-500/20 has-[:checked]:bg-indigo-50/20"
                            data-name="${ex.name.toLowerCase()}" data-mg="${ex.muscle_group_id}">
                            <div class="w-full h-24 mb-2 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center relative">
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span class="absolute top-1 right-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-900/75 text-white backdrop-blur-xs">
                                    ${mgName}
                                </span>
                            </div>
                            <div class="flex items-start w-full text-left gap-2 pt-1">
                                <input type="checkbox" id="ex-${ex.id}" value="${ex.id}" data-name="${ex.name}" data-video="${ex.video_url || ''}"
                                    class="modal-exercise-checkbox mt-0.5 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded shrink-0" checked>
                                <label for="ex-${ex.id}" class="block text-xs font-bold text-slate-800 cursor-pointer w-full leading-tight">
                                    ${ex.name}
                                </label>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('afterbegin', itemHtml);

                    nameInput.value = '';
                    mgSelect.value = '';
                    if (videoInput) videoInput.value = '';
                    descInput.value = '';
                    toggleQuickCreate();
                    attachExerciseCardListeners();
                    applyModalFilters();

                } else {
                    errP.textContent = 'Error al crear ejercicio.';
                    errP.classList.remove('hidden');
                }
            } catch (e) {
                errP.textContent = 'Error de conexión.';
                errP.classList.remove('hidden');
            }
        }

        function addSelectedExercises() {
            if (!currentDayBlockForModal) return;

            const checkboxes = document.querySelectorAll('.modal-exercise-checkbox:checked');
            const container = currentDayBlockForModal.querySelector('.exercises-container');

            checkboxes.forEach(cb => {
                const exId = cb.value;
                const exName = cb.getAttribute('data-name');
                const videoUrl = cb.getAttribute('data-video') || '';

                const template = document.getElementById('exercise-template').content.cloneNode(true);
                const newRow = template.querySelector('.exercise-row');

                newRow.querySelector('.exercise-id').value = exId;
                newRow.querySelector('.exercise-name-display').textContent = exName;
                newRow.querySelector('.exercise-video').value = videoUrl;

                newRow.querySelector('.remove-exercise').addEventListener('click', function () {
                    newRow.remove();
                    updateExerciseIndices(currentDayBlockForModal);
                });

                container.appendChild(newRow);
            });

            updateExerciseIndices(currentDayBlockForModal);
            closeExerciseModal();
        }

        function updateExerciseIndices(dayBlock) {
            const dIndex = dayBlock.getAttribute('data-day-index');
            const rows = dayBlock.querySelectorAll('.exercise-row');

            rows.forEach((row, index) => {
                row.querySelector('.exercise-id').name = `days[${dIndex}][exercises][${index}][exercise_id]`;
                row.querySelector('.exercise-sets').name = `days[${dIndex}][exercises][${index}][sets]`;
                row.querySelector('.exercise-min').name = `days[${dIndex}][exercises][${index}][min_reps]`;
                row.querySelector('.exercise-max').name = `days[${dIndex}][exercises][${index}][max_reps]`;
                row.querySelector('.exercise-video').name = `days[${dIndex}][exercises][${index}][video_url]`;
            });
        }

        function addDayWithData(dayData) {
            addDay();
            const currentIndex = dayIndex - 1;
            const currentDayBlock = document.querySelector(`.day-block[data-day-index="${currentIndex}"]`);
            
            currentDayBlock.querySelector(`input[name="days[${currentIndex}][label]"]`).value = dayData.label || '';
            currentDayBlock.querySelector(`input[name="days[${currentIndex}][day_number]"]`).value = dayData.day_number || (currentIndex + 1);

            if (dayData.muscle_groups) {
                dayData.muscle_groups.forEach(mg => {
                    const cb = currentDayBlock.querySelector(`input[type="checkbox"][value="${mg}"]`);
                    if (cb) cb.checked = true;
                });
            }

            if (dayData.planned_exercises) {
                const container = currentDayBlock.querySelector('.exercises-container');
                dayData.planned_exercises.forEach((pe, index) => {
                    const template = document.getElementById('exercise-template').content.cloneNode(true);
                    const newRow = template.querySelector('.exercise-row');

                    newRow.querySelector('.exercise-id').value = pe.exercise_id;
                    if (pe.exercise) {
                        newRow.querySelector('.exercise-name-display').textContent = pe.exercise.name;
                    }
                    newRow.querySelector('.exercise-sets').value = pe.sets || 3;
                    newRow.querySelector('.exercise-min').value = pe.min_reps || 8;
                    newRow.querySelector('.exercise-max').value = pe.max_reps || 12;
                    newRow.querySelector('.exercise-video').value = pe.video_url || (pe.exercise ? pe.exercise.video_url : '') || '';

                    newRow.querySelector('.remove-exercise').addEventListener('click', function () {
                        newRow.remove();
                        updateExerciseIndices(currentDayBlock);
                    });

                    container.appendChild(newRow);
                });
                updateExerciseIndices(currentDayBlock);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const planDays = @json($plan->trainingDays);
            if (planDays && planDays.length > 0) {
                planDays.forEach(day => {
                    addDayWithData(day);
                });
            } else {
                addDay();
            }

            document.getElementById('planForm').addEventListener('submit', function (e) {
                if (!this.dataset.confirmed) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Avisar por WhatsApp?',
                        text: '¿Deseas avisar a este cliente por WhatsApp sobre la actualización de su plan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, avisar y guardar',
                        cancelButtonText: 'No, solo guardar',
                        confirmButtonColor: '#25D366'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const select = document.getElementById('assigned_client_id');
                            if (select.selectedIndex >= 0) {
                                const option = select.options[select.selectedIndex];
                                const phone = option.getAttribute('data-phone');
                                const name = option.getAttribute('data-name');
                                
                                if (phone) {
                                    const cleanPhone = phone.replace(/[^0-9]/g, '');
                                    const month = document.getElementById('month').value;
                                    const year = document.getElementById('year').value;
                                    const message = encodeURIComponent(`Hola ${name}, he actualizado tu plan de entrenamiento para ${month} ${year}. Puedes revisarlo en tu app.`);
                                    window.open(`https://wa.me/${cleanPhone}?text=${message}`, '_blank');
                                }
                            }
                        }
                        
                        this.dataset.confirmed = true;
                        const btn = document.getElementById('btn_submit');
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';

                        this.submit();
                    });
                }
            });
        });
    </script>
@endpush