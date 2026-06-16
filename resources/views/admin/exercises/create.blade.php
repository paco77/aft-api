@extends('layouts.admin')

@section('header', 'Crear Ejercicio')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.exercises.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Name -->
                <div class="sm:col-span-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="name" class="w-fit pl-0.5 text-sm">Nombre del Ejercicio</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('name') border border-red-500 @enderror"
                            placeholder="Ej. Press de Banca">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Muscle Group -->
                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="muscle_group" class="w-fit pl-0.5 text-sm">Grupo Muscular</label>
                        <select name="muscle_group" id="muscle_group" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('muscle_group') border border-red-500 @enderror">
                             <option value="">Seleccionar...</option>
                             <option value="Pecho" {{ old('muscle_group') == 'Pecho' ? 'selected' : '' }}>Pecho</option>
                             <option value="Espalda" {{ old('muscle_group') == 'Espalda' ? 'selected' : '' }}>Espalda</option>
                             <option value="Pierna" {{ old('muscle_group') == 'Pierna' ? 'selected' : '' }}>Pierna</option>
                             <option value="Hombro" {{ old('muscle_group') == 'Hombro' ? 'selected' : '' }}>Hombro</option>
                             <option value="Brazos" {{ old('muscle_group') == 'Brazos' ? 'selected' : '' }}>Brazos</option>
                             <option value="Bícep" {{ old('muscle_group') == 'Bícep' ? 'selected' : '' }}>Bícep</option>
                             <option value="Trícep" {{ old('muscle_group') == 'Trícep' ? 'selected' : '' }}>Trícep</option>
                             <option value="Core" {{ old('muscle_group') == 'Core' ? 'selected' : '' }}>Core</option>
                             <option value="Descanso" {{ old('muscle_group') == 'Descanso' ? 'selected' : '' }}>Descanso</option>
                        </select>
                        @error('muscle_group') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Video URL -->
                <div class="sm:col-span-6">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="video_url" class="w-fit pl-0.5 text-sm">URL del Video (opcional)</label>
                        <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            placeholder="https://youtube.com/...">
                        @error('video_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="description" class="w-fit pl-0.5 text-sm">Descripción (opcional)</label>
                        <textarea name="description" id="description" rows="3" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            placeholder="Detalles del ejercicio...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.exercises.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm mr-2 text-center">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                    Guardar Ejercicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
