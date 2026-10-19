@extends('layouts.admin')

@section('header', 'Nueva Rutina de Gimnasio')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.gym-routines.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Volver a Rutinas</span>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Crear Rutina para Gimnasio</h1>
            <p class="text-sm text-slate-500">Configura una sesión de entrenamiento autónoma disponible para escanear por código QR.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            <h4 class="font-semibold text-sm mb-1">Por favor corrige los siguientes errores:</h4>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.gym-routines.store') }}" method="POST" id="routineForm" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                <span>Información General de la Sesión</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @if(auth()->user()->role === 'admin')
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Gimnasio Asignado</label>
                    <select name="user_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($gymUsers as $g)
                            <option value="{{ $g->id }}" {{ old('user_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->name }} (@ {{ $g->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="{{ auth()->user()->role === 'admin' ? 'sm:col-span-2' : 'sm:col-span-2 lg:col-span-2' }}">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nombre de la Sesión / Rutina *</label>
                    <input type="text" name="label" value="{{ old('label') }}" required placeholder="ej. Sesión de Espalda, Bíceps y Hombros"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tiempo Estimado (Minutos)</label>
                    <div class="relative">
                        <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 60) }}" min="10" max="180"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="absolute right-3 top-2 text-xs text-slate-400">min</span>
                    </div>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Enfoque / Descripción de la Rutina</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="ej. Enfoque en tracciones para amplitud dorsal y trabajo de deltoides posterior y flexores de codo."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Comentario del Coach AF (Cues técnicos en la app)</label>
                    <textarea name="comment" rows="2" placeholder="ej. Mantener retracción escapular estricta en todo el recorrido. Fase excéntrica controlada de 2s, evitar arquear zona lumbar en el punto de máximo estiramiento."
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('comment') }}</textarea>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Grupos Musculares Principales</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Espalda', 'Pecho', 'Pierna', 'Cuádriceps', 'Isquios', 'Glúteos', 'Hombros', 'Bíceps', 'Tríceps', 'Abdomen', 'Fullbody'] as $muscle)
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-slate-200 bg-slate-50 text-xs font-medium text-slate-700 cursor-pointer hover:bg-slate-100 has-[:checked]:bg-indigo-600 has-[:checked]:text-white has-[:checked]:border-indigo-600 transition">
                                <input type="checkbox" name="muscle_groups[]" value="{{ $muscle }}" class="hidden" {{ in_array($muscle, old('muscle_groups', [])) ? 'checked' : '' }}>
                                <span>{{ $muscle }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Exercises Builder Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <span>Secuencia de Ejercicios</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Agrega y ordena los ejercicios que integran esta sesión.</p>
                </div>
                <button type="button" id="addExerciseBtn" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium text-xs rounded-lg transition border border-blue-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Agregar Ejercicio</span>
                </button>
            </div>

            <!-- Exercises Container -->
            <div id="exercisesContainer" class="space-y-3">
                <!-- Dynamically populated or filled from old() -->
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.gym-routines.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition">
                Guardar Rutina
            </button>
        </div>
    </form>
</div>

<!-- Exercise Template (Hidden) -->
<template id="exerciseTemplate">
    <div class="exercise-item border border-slate-200 rounded-lg p-4 bg-slate-50/50 hover:bg-white transition-all space-y-3 relative group">
        <div class="flex items-center justify-between gap-2 border-b border-slate-200 pb-2">
            <div class="flex items-center gap-2">
                <span class="exercise-num w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center">1</span>
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Configuración de Ejercicio</span>
            </div>
            <button type="button" class="remove-exercise text-slate-400 hover:text-red-500 p-1 transition" title="Eliminar este ejercicio">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- Exercise Select -->
            <div class="sm:col-span-5">
                <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Ejercicio del Catálogo *</label>
                <select name="exercises[INDEX][exercise_id]" required class="exercise-select w-full rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="" disabled selected>Selecciona un ejercicio...</option>
                    @foreach($exercises as $ex)
                        <option value="{{ $ex->id }}" data-muscle="{{ $ex->muscleGroup->name ?? 'General' }}">
                            {{ $ex->name }} ({{ $ex->muscleGroup->name ?? 'General' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sets -->
            <div class="sm:col-span-2">
                <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Series *</label>
                <input type="number" name="exercises[INDEX][sets]" value="3" min="1" max="15" required
                       class="w-full rounded-md border border-slate-300 px-2.5 py-1.5 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <!-- Min Reps -->
            <div class="sm:col-span-2">
                <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Reps Mín *</label>
                <input type="number" name="exercises[INDEX][min_reps]" value="10" min="1" max="100" required
                       class="w-full rounded-md border border-slate-300 px-2.5 py-1.5 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <!-- Max Reps -->
            <div class="sm:col-span-2">
                <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Reps Máx *</label>
                <input type="number" name="exercises[INDEX][max_reps]" value="15" min="1" max="100" required
                       class="w-full rounded-md border border-slate-300 px-2.5 py-1.5 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <!-- Instruction -->
            <div class="sm:col-span-12">
                <label class="block text-2xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Indicación Específica / Enfoque</label>
                <input type="text" name="exercises[INDEX][instruction]" placeholder="ej. Rango completo, pausa de 1s en contracción máxima"
                       class="w-full rounded-md border border-slate-300 px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('exercisesContainer');
    const template = document.getElementById('exerciseTemplate');
    const addBtn = document.getElementById('addExerciseBtn');

    let exerciseIndex = 0;

    function addExercise(data = null) {
        const clone = template.content.cloneNode(true);
        const item = clone.querySelector('.exercise-item');

        item.innerHTML = item.innerHTML.replaceAll('INDEX', exerciseIndex);

        // Update numbers
        const numBadge = item.querySelector('.exercise-num');
        numBadge.textContent = container.children.length + 1;

        // Set values if data provided
        if (data) {
            const select = item.querySelector('.exercise-select');
            if (select && data.exercise_id) select.value = data.exercise_id;

            const setsInput = item.querySelector(`[name="exercises[${exerciseIndex}][sets]"]`);
            if (setsInput && data.sets) setsInput.value = data.sets;

            const minRepsInput = item.querySelector(`[name="exercises[${exerciseIndex}][min_reps]"]`);
            if (minRepsInput && data.min_reps) minRepsInput.value = data.min_reps;

            const maxRepsInput = item.querySelector(`[name="exercises[${exerciseIndex}][max_reps]"]`);
            if (maxRepsInput && data.max_reps) maxRepsInput.value = data.max_reps;

            const instInput = item.querySelector(`[name="exercises[${exerciseIndex}][instruction]"]`);
            if (instInput && data.instruction) instInput.value = data.instruction;
        }

        // Remove listener
        item.querySelector('.remove-exercise').addEventListener('click', function () {
            if (container.children.length > 1) {
                item.remove();
                renumberExercises();
            } else {
                alert('La rutina debe tener al menos un ejercicio.');
            }
        });

        container.appendChild(item);
        exerciseIndex++;
    }

    function renumberExercises() {
        Array.from(container.children).forEach((el, idx) => {
            const num = el.querySelector('.exercise-num');
            if (num) num.textContent = idx + 1;
        });
    }

    addBtn.addEventListener('click', () => addExercise());

    // Initialize with 3 empty exercises or old values
    const oldExercises = @json(old('exercises', []));
    if (oldExercises && oldExercises.length > 0) {
        oldExercises.forEach(ex => addExercise(ex));
    } else {
        // default 3 exercises
        addExercise();
        addExercise();
        addExercise();
    }
});
</script>
@endsection
