@extends('layouts.admin')

@section('header', 'Planes de Alimentación: ' . $user->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Usuarios
        </a>
    </div>
    <a href="{{ route('admin.nutrition-plans.create', ['client_id' => $user->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Crear Nuevo Plan
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Historial de Planes de Alimentación</h2>
    </div>

    <div class="overflow-x-auto">
        <table id="user-nutrition-plans-table" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">ID</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nombre del Plan</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Coach</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Calorías Totales</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Objetivo</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Creado</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($nutritionPlans as $plan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-800">#{{ $plan->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                            {{ $plan->name ?: 'Plan de Alimentación' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $plan->coach->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $plan->total_calories ?? $plan->target_calories }} kcal
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 capitalize">
                            {{ str_replace('_', ' ', $plan->objective) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $plan->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.nutrition-plans.show', $plan) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 rounded-md text-sm font-medium transition-colors" title="Ver Detalles">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function() {
        if (window.DataTable) {
            new DataTable('#user-nutrition-plans-table', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        }
    });
</script>
@endpush
