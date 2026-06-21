@extends('layouts.admin')

@section('header', 'Planes de Alimentación')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Listado de Planes de Alimentación</h2>
        <a href="{{ route('admin.nutrition-plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Plan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="nutrition-plans-table" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">ID</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nombre del Plan</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Cliente</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Coach</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Calorías Totales</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Objetivo</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($plans as $plan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-800">#{{ $plan->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                            {{ $plan->name ?: 'Plan de Alimentación' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">
                                    {{ substr($plan->client->name ?? 'C', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $plan->client->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $plan->coach->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $plan->total_calories ?? $plan->target_calories }} kcal
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $plan->objective)) }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.nutrition-plans.show', $plan) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 rounded-md text-sm font-medium transition-colors" title="Ver Detalles">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Ver
                            </a>
                            <form action="{{ route('admin.nutrition-plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-md text-sm font-medium transition-colors" title="Eliminar Plan">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#nutrition-plans-table', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
@endpush
