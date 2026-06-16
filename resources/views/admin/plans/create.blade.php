@extends('layouts.admin')

@section('header', 'Crear Plan de Entrenamiento')

@section('content')
<div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.plans.store') }}" method="POST" class="p-6" id="planForm">
            @csrf
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 mb-8 border-b pb-6">
                <div class="sm:col-span-3">
                    <label for="assigned_client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select name="assigned_client_id" id="assigned_client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Selecciona un cliente</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-1">
                    <label for="month" class="block text-sm font-medium text-gray-700">Mes</label>
                    <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-1">
                    <label for="year" class="block text-sm font-medium text-gray-700">Año</label>
                    <input type="number" name="year" id="year" value="{{ date('Y') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="sm:col-span-2">
                    <label for="split_type" class="block text-sm font-medium text-gray-700">Tipo de Rutina (Split)</label>
                    <input type="text" name="split_type" id="split_type" placeholder="Ej. Push/Pull/Legs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="sm:col-span-1">
                    <label for="days_per_week" class="block text-sm font-medium text-gray-700">Días por semana</label>
                    <input type="number" name="days_per_week" id="days_per_week" min="1" max="7" value="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
            </div>

            <div id="days-container">
                <!-- Días dinámicos -->
            </div>

            <div class="mt-4 mb-8">
                <button type="button" onclick="addDay()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm text-center border border-gray-400">
                    + Añadir Día de Entrenamiento
                </button>
            </div>

            <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.plans.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded text-sm mr-2 text-center">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                    Guardar Plan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template para Ejercicio (oculto) -->
<template id="exercise-template">
    <div class="exercise-row flex items-center gap-4 mb-3 bg-gray-50 p-3 rounded border border-gray-200">
        <div class="flex-1">
            <select class="exercise-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option value="">Selecciona un ejercicio...</option>
                @foreach($exercises as $ex)
                    <option value="{{ $ex->id }}">{{ $ex->name }} ({{ $ex->muscleGroup->name ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>
        <div class="w-20">
            <label class="block text-xs text-gray-500">Series</label>
            <input type="number" class="exercise-sets mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="3" min="1" required>
        </div>
        <div class="w-20">
            <label class="block text-xs text-gray-500">Min Reps</label>
            <input type="number" class="exercise-min mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="8" min="1" required>
        </div>
        <div class="w-20">
            <label class="block text-xs text-gray-500">Max Reps</label>
            <input type="number" class="exercise-max mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="12" min="1" required>
        </div>
        <div>
            <button type="button" class="text-red-600 hover:text-red-800 mt-5 remove-exercise">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    let dayIndex = 0;

    function addDay() {
        const container = document.getElementById('days-container');
        
        const dayHtml = `
            <div class="day-block bg-white border border-gray-300 shadow-sm rounded-lg p-5 mb-6" data-day-index="${dayIndex}">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h4 class="text-lg font-medium text-gray-800">Día ${dayIndex + 1}</h4>
                    <button type="button" class="text-red-600 hover:text-red-800 text-sm font-medium remove-day">Eliminar Día</button>
                </div>
                
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Etiqueta del Día (Ej. Pecho y Tríceps)</label>
                        <input type="text" name="days[${dayIndex}][label]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número de Día</label>
                        <input type="number" name="days[${dayIndex}][day_number]" value="${dayIndex + 1}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>

                <div class="exercises-container">
                    <!-- Ejercicios aquí -->
                </div>

                <button type="button" class="mt-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium py-1.5 px-3 rounded text-sm border border-indigo-200 add-exercise">
                    + Añadir Ejercicio
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', dayHtml);
        
        // Bind events
        const newBlock = container.lastElementChild;
        
        newBlock.querySelector('.remove-day').addEventListener('click', function() {
            newBlock.remove();
        });
        
        newBlock.querySelector('.add-exercise').addEventListener('click', function() {
            addExercise(newBlock);
        });

        // Add first exercise by default
        addExercise(newBlock);

        dayIndex++;
    }

    function addExercise(dayBlock) {
        const dIndex = dayBlock.getAttribute('data-day-index');
        const exercisesContainer = dayBlock.querySelector('.exercises-container');
        const exIndex = exercisesContainer.children.length;
        
        const template = document.getElementById('exercise-template').content.cloneNode(true);
        const newRow = template.querySelector('.exercise-row');
        
        newRow.querySelector('.exercise-select').name = `days[${dIndex}][exercises][${exIndex}][exercise_id]`;
        newRow.querySelector('.exercise-sets').name = `days[${dIndex}][exercises][${exIndex}][sets]`;
        newRow.querySelector('.exercise-min').name = `days[${dIndex}][exercises][${exIndex}][min_reps]`;
        newRow.querySelector('.exercise-max').name = `days[${dIndex}][exercises][${exIndex}][max_reps]`;
        
        newRow.querySelector('.remove-exercise').addEventListener('click', function() {
            newRow.remove();
        });
        
        exercisesContainer.appendChild(newRow);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Añadir un día inicial al cargar
        addDay();
    });
</script>
@endpush
