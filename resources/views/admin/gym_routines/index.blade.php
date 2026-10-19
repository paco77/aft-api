@extends('layouts.admin')

@section('header', 'Rutinas de Gimnasio')

@section('content')
<div class="space-y-6">
    <!-- Header with actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rutinas Independientes de Gimnasio</h1>
            <p class="text-sm text-slate-500 mt-1">
                Rutinas directas vinculadas a este gimnasio, sin depender de planes individuales. Los usuarios pueden escanear el QR y entrenar al instante.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.gym-routines.qr') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Código QR Gimnasio</span>
            </a>
            <a href="{{ route('admin.gym-routines.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nueva Rutina</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Routines Grid/Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($routines->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800">Aún no hay rutinas configuradas</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Crea sesiones de entrenamiento (ej. Espalda & Bíceps, Pierna, Pecho & Hombros) para que tus clientes y visitantes las realicen escaneando el código QR.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.gym-routines.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Crear mi primera rutina</span>
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Rutina / Sesión</th>
                            <th class="px-6 py-3.5">Gimnasio</th>
                            <th class="px-6 py-3.5">Tiempo Est.</th>
                            <th class="px-6 py-3.5">Ejercicios</th>
                            <th class="px-6 py-3.5">Músculos</th>
                            <th class="px-6 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($routines as $routine)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $routine->label }}</div>
                                    @if($routine->description)
                                        <div class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $routine->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $routine->user->name ?? 'Gimnasio' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $routine->estimated_minutes ?? 60 }} min</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $routine->plannedExercises->count() }} ejercicios
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($routine->muscle_groups ?? [] as $m)
                                            <span class="text-2xs uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">{{ $m }}</span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">General</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Public QR link -->
                                        @php
                                            $gymIdentifier = $routine->user->username ?: $routine->user->id;
                                        @endphp
                                        <a href="{{ route('gym.routines', ['identifier' => $gymIdentifier, 'selected' => $routine->id]) }}" 
                                           target="_blank"
                                           title="Ver vista pública móvil / QR"
                                           class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.gym-routines.edit', $routine) }}" 
                                           class="p-1.5 text-slate-600 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition"
                                           title="Editar rutina">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.gym-routines.destroy', $routine) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta rutina?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                                    title="Eliminar rutina">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($routines->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $routines->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
