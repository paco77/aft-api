@extends('layouts.admin')

@section('header', 'Detalle de Entrenamiento')

@section('content')
<div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('admin.users.workouts', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">&larr; Volver a los entrenamientos de {{ $user->name }}</a>
    </div>

    <!-- Resumen de Sesión -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Resumen de Sesión</h3>
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                {{ $session->end_time ? 'Completado' : 'En Progreso' }}
            </span>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Fecha y Hora de Inicio</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y h:i A') }}
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Duración Total</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($session->end_time)
                            {{ \Carbon\Carbon::parse($session->start_time)->diffInMinutes(\Carbon\Carbon::parse($session->end_time)) }} minutos
                        @else
                            N/A
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Día de Entrenamiento</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $session->trainingDay->label ?? 'Día Libre' }} 
                        @if($session->trainingDay && $session->trainingDay->monthlyPlan)
                            <span class="text-gray-500 text-xs">({{ $session->trainingDay->monthlyPlan->name ?? 'Plan' }})</span>
                        @endif
                    </dd>
                </div>
                @if($session->comments)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Comentarios del Cliente</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 italic">
                        "{{ $session->comments }}"
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Ejercicios Realizados -->
    <h3 class="text-xl font-bold text-gray-800 mb-4">Ejercicios Realizados</h3>
    
    @if($session->exerciseLogs->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
            No hay ejercicios registrados en esta sesión.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($session->exerciseLogs as $log)
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                        <h4 class="text-md font-bold text-indigo-900">
                            {{ $log->plannedExercise->exercise->name ?? 'Ejercicio Desconocido' }}
                        </h4>
                        @if($log->plannedExercise)
                            <span class="text-xs text-indigo-700 font-medium">
                                Planificado: {{ $log->plannedExercise->sets }} series de {{ $log->plannedExercise->min_reps }}-{{ $log->plannedExercise->max_reps }} reps
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-center">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Set</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Peso (Lbs/Kg)</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Repeticiones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 text-center">
                                @forelse($log->setLogs as $set)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $set->set_number }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-indigo-600">{{ $set->weight }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $set->reps }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-sm text-gray-500 italic">No hay series registradas para este ejercicio.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
