@extends('layouts.admin')

@section('header', 'Detalles del Plan: ' . $plan->name)

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Información General</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalles y estructura del entrenamiento.</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
            Volver a la lista
        </a>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-200">
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Mes / Año</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $plan->month }} / {{ $plan->year }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Cliente Asignado</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $plan->assignedClient->name ?? 'No asignado' }}</dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Coach</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $plan->user->name ?? 'N/A' }}</dd>
            </div>
        </dl>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Días de Entrenamiento</h3>
    <div class="space-y-6">
        @forelse($plan->trainingDays as $day)
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-3 bg-slate-800 text-white flex justify-between items-center">
                <h4 class="font-medium">Día {{ $day->day_number }}: {{ $day->label }}</h4>
                <span class="text-xs uppercase px-2 py-1 bg-slate-700 rounded">
                    {{ is_array($day->muscle_groups) ? implode(', ', $day->muscle_groups) : $day->muscle_groups }}
                </span>
            </div>
            <div class="p-0">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ejercicio</th>
                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase">Series</th>
                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase">Reps</th>
                            <th class="px-6 py-2 text-center text-xs font-medium text-gray-500 uppercase">RPE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($day->plannedExercises as $planned)
                        <tr>
                            <td class="px-6 py-3 text-gray-900 font-medium">
                                {{ $planned->exercise->name ?? 'Ejercicio Eliminado' }}
                                @if($planned->notes)
                                    <p class="text-xs font-normal text-gray-500 italic mt-1">{{ $planned->notes }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-center">{{ $planned->sets }}</td>
                            <td class="px-6 py-3 text-gray-500 text-center">{{ $planned->reps }}</td>
                            <td class="px-6 py-3 text-gray-500 text-center">{{ $planned->rpe ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No hay ejercicios planeados para este día.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center italic py-4 bg-white shadow sm:rounded-lg">Este plan aún no tiene días de entrenamiento configurados.</p>
        @endforelse
    </div>
</div>
@endsection
