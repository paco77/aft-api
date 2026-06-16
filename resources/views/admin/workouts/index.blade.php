@extends('layouts.admin')

@section('header', 'Entrenamientos de ' . $user->name)

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Historial de Entrenamientos</h3>
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">&larr; Volver a Usuarios</a>
    </div>
    
    @if($sessions->isEmpty())
        <div class="px-6 py-8 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-lg">Este usuario aún no tiene entrenamientos registrados.</p>
        </div>
    @else
        <div class="border-t border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan / Día</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sessions as $session)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($session->trainingDay)
                                <div class="text-sm text-gray-900">{{ $session->trainingDay->monthlyPlan->name ?? 'Plan Mensual' }}</div>
                                <div class="text-xs text-gray-500">{{ $session->trainingDay->label }} (Día {{ $session->trainingDay->day_number }})</div>
                            @else
                                <div class="text-sm text-gray-500 italic">Día Libre / No Asignado</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($session->end_time)
                                {{ \Carbon\Carbon::parse($session->start_time)->diffInMinutes(\Carbon\Carbon::parse($session->end_time)) }} min
                            @else
                                En progreso
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.workouts.show', $session) }}" class="text-indigo-600 hover:text-indigo-900">Ver Detalles</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
@endsection
