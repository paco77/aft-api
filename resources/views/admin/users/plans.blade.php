@extends('layouts.admin')

@section('header', 'Planes de Entrenamiento: ' . $user->name)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Usuarios
        </a>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.plans.create', ['client_id' => $user->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl flex items-center gap-2 transition-colors text-sm shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear Nuevo Plan
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Historial de Planes de Entrenamiento</h2>
            <p class="text-xs text-slate-500 mt-0.5">Rutinas mensuales y divisiones de entrenamiento asignadas a este usuario</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                Total: {{ $plans->count() }} {{ $plans->count() == 1 ? 'Plan' : 'Planes' }}
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="user-plans-table" class="w-full text-left border-collapse min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                    <th class="px-5 py-3.5">ID</th>
                    <th class="px-5 py-3.5">Plan / División</th>
                    <th class="px-5 py-3.5">Coach</th>
                    <th class="px-5 py-3.5">Periodo</th>
                    <th class="px-5 py-3.5">Sesiones</th>
                    <th class="px-5 py-3.5">Fecha</th>
                    <th class="px-5 py-3.5 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($plans as $plan)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-4 text-sm font-semibold text-slate-800">
                            #{{ $plan->id }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-bold text-slate-900">{{ $plan->name }}</div>
                            @if($plan->days_per_week)
                                <div class="text-xs text-slate-500">{{ $plan->days_per_week }} días / semana</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600 font-medium">
                            {{ $plan->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $plan->month }} {{ $plan->year }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60">
                                {{ $plan->trainingDays->count() }} {{ $plan->trainingDays->count() == 1 ? 'sesión' : 'sesiones' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500 whitespace-nowrap" data-order="{{ $plan->created_at?->timestamp ?? 0 }}">
                            {{ $plan->created_at ? $plan->created_at->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap space-x-1.5 text-sm font-medium">
                            <a href="{{ route('admin.plans.show', $plan) }}" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 rounded-lg text-xs font-semibold transition-colors" title="Ver Detalles">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Ver
                            </a>
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-900 rounded-lg text-xs font-semibold transition-colors" title="Editar Plan">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </a>
                            <button type="button" 
                                onclick="openDuplicateModal({{ $plan->id }}, '{{ addslashes($plan->split_type ?: ($plan->month . ' ' . $plan->year)) }}', {{ $plan->assigned_client_id ?? 'null' }}, '{{ $plan->month }}', {{ $plan->year }})" 
                                class="inline-flex items-center px-2.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:text-emerald-900 rounded-lg text-xs font-semibold transition-colors cursor-pointer" 
                                title="Duplicar Plan">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Duplicar
                            </button>
                            <a href="{{ route('admin.plans.pdf', $plan) }}" class="inline-flex items-center px-2.5 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-900 rounded-lg text-xs font-semibold transition-colors" title="Exportar PDF">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                PDF
                            </a>
                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este plan de entrenamiento?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_to" value="user">
                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="Eliminar Plan">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <p class="text-sm font-medium text-slate-500">No hay planes de entrenamiento registrados para este usuario.</p>
                                <a href="{{ route('admin.plans.create', ['client_id' => $user->id]) }}" class="text-xs text-blue-600 hover:underline font-bold">
                                    + Crear primer plan de entrenamiento
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('admin.plans._duplicate_modal')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#user-plans-table', {
            language: {
                search: "Buscar:",
                searchPlaceholder: "Filtrar planes...",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ planes",
                infoEmpty: "Mostrando 0 planes",
                infoFiltered: "(de _MAX_ totales)",
                zeroRecords: "No se encontraron planes",
                emptyTable: "No hay planes de entrenamiento registrados",
                paginate: {
                    first: "«",
                    previous: "‹",
                    next: "›",
                    last: "»"
                }
            },
            pageLength: 10,
            order: [[5, 'desc']] // Columna 5 (Fecha) ordenada del más reciente al más antiguo
        });
    });
</script>
@endpush
