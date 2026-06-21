@extends('layouts.admin')

@section('header', 'Detalle del Plan de Alimentación')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
        <!-- Decorative Header Background -->
        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700"></div>
        
        <div class="px-8 pb-8">
            <div class="flex justify-between items-end -mt-12 mb-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-2xl bg-white p-2 shadow-lg flex items-center justify-center">
                        <div class="w-full h-full rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-3xl font-bold">
                            {{ substr($nutritionPlan->client->name ?? 'C', 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mt-12">{{ $nutritionPlan->name ?: 'Plan Personalizado' }}</h1>
                        <p class="text-gray-500 font-medium">Cliente: <span class="text-gray-800">{{ $nutritionPlan->client->name ?? 'N/A' }}</span></p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.nutrition-plans.pdf', $nutritionPlan) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-4 py-2 flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Exportar PDF
                    </a>
                    <a href="{{ route('admin.nutrition-plans.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                        Volver
                    </a>
                </div>
            </div>

            @if($nutritionPlan->description)
                <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Comentarios Adicionales:</h3>
                    <p class="text-gray-600">{{ $nutritionPlan->description }}</p>
                </div>
            @endif

            <!-- Macros Overview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-sm text-gray-500 font-semibold mb-1">Calorías Totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $nutritionPlan->total_calories ?? $nutritionPlan->target_calories }} <span class="text-sm font-medium text-gray-500">kcal</span></p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-sm text-blue-600 font-semibold mb-1">Proteínas</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $nutritionPlan->total_protein }} <span class="text-sm font-medium text-blue-600/70">g</span></p>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                    <p class="text-sm text-orange-600 font-semibold mb-1">Carbohidratos</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $nutritionPlan->total_carbs }} <span class="text-sm font-medium text-orange-600/70">g</span></p>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                    <p class="text-sm text-yellow-600 font-semibold mb-1">Grasas</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $nutritionPlan->total_fat }} <span class="text-sm font-medium text-yellow-600/70">g</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Información Base
                </h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">Coach</span>
                        <span class="font-medium text-gray-900">{{ $nutritionPlan->coach->name ?? 'N/A' }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">Objetivo</span>
                        <span class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $nutritionPlan->objective) }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">TDEE</span>
                        <span class="font-medium text-gray-900">{{ $nutritionPlan->tdee }} kcal</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500">Nivel de Actividad</span>
                        <span class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $nutritionPlan->activity_level) }}</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Fisiología
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-xl text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Peso</p>
                        <p class="text-lg font-bold text-gray-900">{{ $nutritionPlan->weight }} kg</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Altura</p>
                        <p class="text-lg font-bold text-gray-900">{{ $nutritionPlan->height }} cm</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Edad</p>
                        <p class="text-lg font-bold text-gray-900">{{ $nutritionPlan->age }} años</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Género</p>
                        <p class="text-lg font-bold text-gray-900 capitalize">{{ $nutritionPlan->gender === 'male' ? 'Masculino' : ($nutritionPlan->gender === 'female' ? 'Femenino' : $nutritionPlan->gender) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meals List -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Comidas del Día</h2>
            
            @forelse($nutritionPlan->meals as $meal)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:border-blue-200 transition-colors">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center group-hover:bg-blue-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $meal->name }}</h3>
                                @if($meal->time)
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($meal->time)->format('h:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-0">
                        @if($meal->foods->count() > 0)
                            <ul class="divide-y divide-gray-100">
                                @foreach($meal->foods as $food)
                                    <li class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/50">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $food->name }}</p>
                                            <p class="text-sm text-gray-500 mt-0.5">{{ $food->serving_size }} {{ $food->serving_unit }}</p>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm bg-gray-50 px-4 py-2 rounded-lg">
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 font-medium">Cal</p>
                                                <p class="font-bold text-gray-900">{{ $food->calories }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-blue-500 font-medium">Pro</p>
                                                <p class="font-bold text-blue-900">{{ $food->protein }}g</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-orange-500 font-medium">Car</p>
                                                <p class="font-bold text-orange-900">{{ $food->carbs }}g</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-yellow-600 font-medium">Gra</p>
                                                <p class="font-bold text-yellow-900">{{ $food->fat }}g</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-6 py-8 text-center text-gray-500">
                                <p>No hay alimentos registrados para esta comida.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Sin comidas registradas</h3>
                    <p class="text-gray-500">Este plan de alimentación aún no tiene comidas asignadas.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
