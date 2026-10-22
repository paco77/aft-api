@extends('layouts.admin')

@section('header', auth()->user()->role === 'client' ? 'Mis Planes de Entrenamiento' : 'Gestión de Planes Mensuales')

@section('content')

@if(auth()->user()->role === 'client')
    <!-- VISTA PERSONALIZADA PARA CLIENTES / ATLETAS -->
    <div class="space-y-6">
        <!-- Header Banner -->
        <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 border border-blue-500/30 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Tus Entrenamientos</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    Elige tu Plan y Sesión de Hoy
                </h2>
                <p class="text-xs sm:text-sm text-slate-300">
                    Selecciona cualquier día de tu rutina para comenzar el entrenamiento en vivo y registrar tus pesos y repeticiones.
                </p>
            </div>
            <div class="text-right sm:self-center shrink-0">
                <span class="px-3.5 py-1.5 rounded-xl bg-blue-600/30 border border-blue-400/30 text-blue-200 text-xs font-bold">
                    Total: <strong class="text-white">{{ $plans->count() }}</strong> {{ $plans->count() == 1 ? 'Plan activo' : 'Planes activos' }}
                </span>
            </div>
        </div>

        @if($plans->isEmpty())
            <div class="p-12 text-center bg-white rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Aún no tienes planes asignados</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Tu coach aún no te ha configurado un plan de entrenamiento mensual. En cuanto lo cree, podrás verlo y entrenarlo aquí.
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($plans as $plan)
                    @php
                        $gymUser = \App\Models\User::where('role', 'gimnasio')->first() ?? $plan->user ?? auth()->user();
                        $gymIdent = $gymUser ? ($gymUser->username ?: $gymUser->id) : 1;
                        $firstDay = $plan->trainingDays->first();
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition hover:shadow-md">
                        <!-- Plan Header (Clickable to toggle days) -->
                        <div onclick="togglePlanDays({{ $plan->id }})"
                             class="p-5 sm:p-6 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 cursor-pointer select-none hover:bg-slate-800/90 transition group">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        {{ $plan->month }} {{ $plan->year }}
                                    </span>
                                    @if($plan->days_per_week)
                                        <span class="text-2xs text-slate-400 font-medium">
                                            &bull; {{ $plan->days_per_week }} días / semana
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <h3 class="text-lg sm:text-xl font-black tracking-tight text-white group-hover:text-blue-400 transition">
                                        {{ $plan->split_type ?: $plan->name }}
                                    </h3>
                                    <span class="text-xs text-slate-400 font-semibold hidden sm:inline-flex items-center gap-1 group-hover:text-slate-200">
                                        &bull; Toca para desplegar
                                    </span>
                                </div>
                                <p class="text-xs text-slate-300">
                                    Coach: <strong class="text-white">{{ $plan->user->name ?? 'Coach' }}</strong>
                                    &bull; {{ $plan->trainingDays->count() }} sesiones configuradas
                                </p>
                            </div>

                            <!-- Plan Quick Action Buttons -->
                            <div class="flex flex-wrap items-center gap-2" onclick="event.stopPropagation()">
                                <!-- Botón Desplegable Ver Días / Sesiones -->
                                <button type="button" onclick="togglePlanDays({{ $plan->id }})" id="btn-toggle-plan-{{ $plan->id }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-600/30 transition active:scale-95"
                                        title="Mostrar u ocultar los días de entrenamiento">
                                    <span id="text-toggle-plan-{{ $plan->id }}">Ver Sesiones ({{ $plan->trainingDays->count() }})</span>
                                    <svg id="icon-toggle-plan-{{ $plan->id }}" class="w-4 h-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                @if($firstDay)
                                    <a href="{{ route('gym.workout', ['identifier' => $gymIdent, 'trainingDay' => $firstDay->id]) }}"
                                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs uppercase tracking-wider border border-slate-700 transition active:scale-95">
                                        <svg class="w-3.5 h-3.5 fill-current text-blue-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                        <span>Día 1</span>
                                    </a>
                                @endif

                                <a href="{{ route('gym.routines', ['identifier' => $gymIdent, 'tab' => 'client', 'plan_id' => $plan->id]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition"
                                   title="Abrir en pantalla de rutinas">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    <span>Ver Rutinas</span>
                                </a>

                                <a href="{{ route('admin.plans.pdf', $plan) }}"
                                   class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold transition"
                                   title="Descargar PDF">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>PDF</span>
                                </a>
                            </div>
                        </div>

                        <!-- Collapsible Body: Note and Training Days / Sessions List -->
                        <div id="plan-days-{{ $plan->id }}" class="hidden border-t border-slate-200 bg-white">
                            @if($plan->comment)
                                <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-100 text-xs text-slate-600 italic">
                                    <strong class="text-slate-700 not-italic">Nota del Coach:</strong> {{ $plan->comment }}
                                </div>
                            @endif

                            <div class="p-5 sm:p-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-2xs font-extrabold uppercase tracking-wider text-slate-400">
                                        SESIONES DISPONIBLES EN ESTE PLAN:
                                    </p>
                                    <span class="text-2xs font-semibold text-slate-400">
                                        {{ $plan->trainingDays->count() }} {{ $plan->trainingDays->count() == 1 ? 'sesión' : 'sesiones' }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                    @forelse($plan->trainingDays as $day)
                                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 hover:border-blue-400 transition flex flex-col justify-between gap-3 group">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="inline-flex items-center gap-1.5 text-2xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200/60">
                                                        Día {{ $day->day_number }}
                                                    </span>
                                                    <span class="text-2xs text-slate-500 font-medium">
                                                        {{ $day->estimated_minutes ?? 60 }} min &bull; {{ $day->plannedExercises->count() }} ejercicios
                                                    </span>
                                                </div>

                                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition">
                                                    {{ $day->label }}
                                                </h4>

                                                @if($day->description)
                                                    <p class="text-2xs text-slate-500 line-clamp-1">
                                                        {{ $day->description }}
                                                    </p>
                                                @endif

                                                @php
                                                    $sampleExercises = $day->plannedExercises->take(3)->map(fn($p) => $p->exercise->name ?? '')->filter()->implode(', ');
                                                @endphp
                                                @if($sampleExercises)
                                                    <p class="text-2xs text-slate-400 truncate">
                                                        <span class="font-semibold text-slate-500">Ejercicios:</span> {{ $sampleExercises }}...
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between gap-2">
                                                <span class="text-2xs text-slate-400">
                                                    {{ $day->plannedExercises->sum('sets') }} series totales
                                                </span>

                                                <a href="{{ route('gym.workout', ['identifier' => $gymIdent, 'trainingDay' => $day->id]) }}"
                                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition active:scale-95">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                                    <span>Realizar Sesión</span>
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-xs text-slate-400 italic p-4 text-center">
                                            No hay días configurados en este plan aún.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@else
    <!-- VISTA ADMINISTRATIVA PARA COACH, GIMNASIO, ADMIN E INDEPENDIENTE -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Lista de Planes
            </h3>
            <a href="{{ route('admin.plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                Nuevo Plan
            </a>
        </div>
        <div class="border-t border-gray-200 overflow-x-auto p-4">
            <table id="plans-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creador (Coach)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($plans as $plan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                            <div class="text-xs text-gray-500">{{ $plan->month }} {{ $plan->year }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $plan->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $plan->assignedClient->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $plan->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.plans.show', $plan) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-bold mr-3" title="Ver Detalles">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Ver Plan
                            </a>
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 font-bold mr-3" title="Editar Plan">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </a>
                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 font-bold" title="Eliminar Plan" onclick="return confirm('¿Estás seguro de eliminar este plan?')">
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
@endif

@endsection

@push('scripts')
@if(auth()->user()->role === 'client')
<script>
    function togglePlanDays(planId) {
        const container = document.getElementById(`plan-days-${planId}`);
        const icon = document.getElementById(`icon-toggle-plan-${planId}`);
        const text = document.getElementById(`text-toggle-plan-${planId}`);
        if (!container) return;

        const isHidden = container.classList.contains('hidden');
        if (isHidden) {
            container.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
            if (text) text.textContent = 'Ocultar Sesiones';
        } else {
            container.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
            const total = container.querySelectorAll('.grid > div').length;
            if (text) text.textContent = `Ver Sesiones (${total})`;
        }
    }
</script>
@else
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#plans-table', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
@endif
@endpush
