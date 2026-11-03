@extends('layouts.admin')

@section('header', 'Catálogo de Alimentos')

@section('content')
<div class="space-y-6">
    <!-- Top Summary & Filter Card -->
    <div class="bg-white shadow-xs border border-gray-200 rounded-xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-gray-900">Base Maestra de Alimentos y Macronutrientes</h2>
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-indigo-100 text-indigo-800">
                    {{ $foods->count() }} registros
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-0.5">
                Administra los alimentos disponibles en el creador de planes de alimentación.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.foods.index') }}" class="flex items-center gap-2">
                <select name="category_id" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none">
                    <option value="">Todas las categorías ({{ $categories->sum('foods_count') }})</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->foods_count }})
                        </option>
                    @endforeach
                </select>
                @if(request()->filled('category_id'))
                    <a href="{{ route('admin.foods.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">
                        Limpiar
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.foods.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Dar de Alta Alimento</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white shadow-xs border border-gray-200 overflow-hidden rounded-xl">
        <div class="overflow-x-auto p-4">
            <table id="foods-table" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alimento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Porción Base</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kcal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Prot (g)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Carbs (g)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Grasa (g)</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($foods as $food)
                    <tr class="hover:bg-gray-50/80">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $food->name }}</div>
                            @if($food->subcategory)
                                <div class="text-xs text-gray-500">{{ $food->subcategory }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $food->category?->name ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-600 text-xs">
                            {{ $food->state ?? 'Listo para consumo' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ $food->portion_type }}</span>
                            @if($food->portion_detail && $food->portion_detail !== $food->portion_type)
                                <div class="text-xs text-gray-500">{{ $food->portion_detail }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center font-bold text-orange-600">
                            {{ number_format($food->calories, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center font-semibold text-blue-600">
                            {{ number_format($food->protein, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center font-semibold text-emerald-600">
                            {{ number_format($food->carbs, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center font-semibold text-amber-600">
                            {{ number_format($food->fat, 1) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.foods.edit', $food) }}"
                                class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-semibold mr-3"
                                title="Editar">
                                Editar
                            </a>
                            <form action="{{ route('admin.foods.destroy', $food) }}" method="POST" class="inline"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este alimento del catálogo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 font-semibold">
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#foods-table', {
            pageLength: 25,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
@endpush

