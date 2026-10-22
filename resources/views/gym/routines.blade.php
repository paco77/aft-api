<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Programas de Rutinas - {{ $gym->name }} | AF Training</title>
    <!-- Google Fonts: Plus Jakarta Sans / Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#1d4ed8',
                            lightBlue: '#3b82f6',
                            glow: '#60a5fa',
                            emerald: '#10b981',
                            emeraldGlow: '#34d399',
                            orange: '#f97316',
                            flame: '#ff5722',
                            dark: '#080c14',
                            surface: '#0f1422',
                            surfaceAlt: '#161c2d',
                            cardBorder: 'rgba(59, 130, 246, 0.25)',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', '"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #060911;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .glow-card {
            background: linear-gradient(180deg, #163a8a 0%, #102660 100%);
            border: 1px solid rgba(96, 165, 250, 0.4);
            box-shadow: 0 0 40px rgba(37, 99, 235, 0.25);
        }
        .glow-card-client {
            background: linear-gradient(180deg, #064e3b 0%, #062c24 100%);
            border: 1px solid rgba(52, 211, 153, 0.4);
            box-shadow: 0 0 40px rgba(16, 185, 129, 0.22);
        }
        .exercise-item-pill {
            background: rgba(13, 27, 62, 0.55);
            border: 1px solid rgba(59, 130, 246, 0.25);
            backdrop-filter: blur(8px);
        }
        .exercise-item-pill-client {
            background: rgba(6, 44, 36, 0.55);
            border: 1px solid rgba(52, 211, 153, 0.25);
            backdrop-filter: blur(8px);
        }
        .exercise-item-pill:hover, .exercise-item-pill-client:hover {
            border-color: rgba(96, 165, 250, 0.5);
            background: rgba(13, 27, 62, 0.8);
        }
        .routine-item-card {
            background: #0f1523;
            border: 1px solid rgba(30, 41, 59, 0.7);
            transition: all 0.2s ease;
        }
        .routine-item-card:hover {
            border-color: rgba(59, 130, 246, 0.4);
            background: #131b2e;
        }
        .active-tab {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(96, 165, 250, 0.6);
            color: #ffffff;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }
        .active-tab-client {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(52, 211, 153, 0.6);
            color: #ffffff;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-between py-6 px-4 sm:px-6">

    <div class="max-w-md md:max-w-xl mx-auto w-full space-y-4">

        <!-- Top User Status Bar (Client info or Login Prompt) -->
        @if(auth()->check())
            <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between gap-3 text-xs shadow-md">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-400 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-md shadow-emerald-900/40">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-white truncate max-w-[140px] sm:max-w-[190px]">{{ auth()->user()->name }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {{ auth()->user()->role ?? 'Atleta' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @if(in_array(auth()->user()->role, ['admin', 'coach', 'gimnasio']))
                        <a href="{{ route('admin.dashboard') }}" class="text-[11px] text-blue-400 hover:text-blue-300 font-semibold transition" title="Ir al Panel">
                            Mi Panel
                        </a>
                        <span class="text-slate-700">&bull;</span>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ url()->full() }}">
                        <button type="submit" class="text-[11px] text-slate-400 hover:text-red-400 font-semibold transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Salir</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest Call to Action Banner -->
            <div class="p-3.5 rounded-2xl bg-gradient-to-r from-blue-950/80 via-slate-900/90 to-blue-950/80 border border-blue-500/30 flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-blue-600/30 border border-blue-400/30 text-blue-300 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white tracking-tight truncate">¿Eres alumno o tienes un plan personalizado?</p>
                        <p class="text-[11px] text-slate-400 truncate">Inicia sesión para cargar tu entrenamiento y registrar cargas.</p>
                    </div>
                </div>
                <button type="button" onclick="openLoginModal()" 
                        class="shrink-0 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md shadow-blue-600/30 transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Entrar</span>
                </button>
            </div>
        @endif

        <!-- Main Header -->
        <header class="flex items-center justify-between pt-1 pb-1">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/30">
                    <span class="font-display font-black text-white text-sm tracking-tighter">AF</span>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white font-display leading-tight">
                        Programas de Rutinas
                    </h1>
                    <p class="text-[11px] text-slate-400">{{ $gym->name }}</p>
                </div>
            </div>

            <div class="text-right">
                <span class="text-2xs sm:text-xs font-semibold text-slate-400">
                    @if($activeTab === 'client' && $activePlan)
                        <span class="text-emerald-400 font-bold">{{ $activePlan->trainingDays->count() }}</span> sesiones en tu plan
                    @else
                        Total: <span class="text-white font-bold">{{ $routines->count() }}</span> sesiones del gym
                    @endif
                </span>
            </div>
        </header>

        <!-- Tab Selector: Mis Planes vs Rutinas del Gimnasio (when logged in or when client plans exist) -->
        @if(auth()->check())
            <div class="grid grid-cols-2 p-1 rounded-2xl bg-slate-900 border border-slate-800 gap-1 text-xs">
                <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'client']) }}"
                   class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl font-bold transition text-center {{ $activeTab === 'client' ? 'active-tab-client' : 'text-slate-400 hover:text-white' }}">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Mis Planes Asignados</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'client' ? 'bg-emerald-500/30 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                        {{ $clientPlans->count() }}
                    </span>
                </a>

                <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'gym']) }}"
                   class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl font-bold transition text-center {{ $activeTab === 'gym' ? 'active-tab' : 'text-slate-400 hover:text-white' }}">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Rutinas del Gimnasio</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'gym' ? 'bg-blue-500/30 text-blue-300' : 'bg-slate-800 text-slate-400' }}">
                        {{ $routines->count() }}
                    </span>
                </a>
            </div>
        @endif

        {{-- TAB 1: CLIENT PLANS --}}
        @if($activeTab === 'client')
            @if($clientPlans->isEmpty())
                <div class="p-8 text-center bg-brand-surface rounded-2xl border border-slate-800 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Sin planes asignados</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">
                            Aún no tienes un plan de entrenamiento mensual asignado por tu coach. Puedes entrenar libremente con las rutinas públicas de este gimnasio.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'gym']) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-600/30 transition">
                            <span>Ver Rutinas de {{ $gym->name }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <!-- Multiple Plans Selector -->
                @if($clientPlans->count() > 1)
                    <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 space-y-1.5">
                        <label class="text-2xs font-extrabold uppercase tracking-wider text-slate-400 block">
                            Seleccionar Plan de Entrenamiento:
                        </label>
                        <select onchange="window.location.href = this.value"
                                class="w-full bg-slate-800 text-white text-xs font-semibold rounded-xl px-3 py-2 border border-slate-700 focus:outline-none focus:border-emerald-500">
                            @foreach($clientPlans as $plan)
                                <option value="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'client', 'plan_id' => $plan->id]) }}"
                                        {{ $activePlan && $activePlan->id === $plan->id ? 'selected' : '' }}>
                                    {{ $plan->split_type ?: 'Plan Personalizado' }} &bull; {{ $plan->month }} {{ $plan->year }} ({{ $plan->trainingDays->count() }} días)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="px-4 py-2.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="font-bold text-emerald-200">Plan: {{ $activePlan->split_type ?: 'Personalizado' }}</span>
                            <span class="text-emerald-400/70 font-medium">({{ $activePlan->month }} {{ $activePlan->year }})</span>
                        </div>
                        @if($activePlan->user)
                            <span class="text-2xs text-emerald-300/80 font-medium">Coach: {{ $activePlan->user->name }}</span>
                        @endif
                    </div>
                @endif

                @php
                    $active = $selectedRoutine ?? ($activePlan ? $activePlan->trainingDays->first() : null);
                @endphp

                @if($active)
                    <!-- ACTIVE / FEATURED CLIENT SESSION CARD -->
                    <div class="glow-card-client rounded-2xl sm:rounded-3xl p-5 sm:p-6 space-y-4 relative overflow-hidden transition-all duration-300">
                        <!-- Top Badges Row -->
                        <div class="flex items-center justify-between gap-2 text-2xs sm:text-xs">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/30 border border-emerald-400/40 text-emerald-200 font-bold uppercase tracking-wider">
                                <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.17 2.15-1.17 3.522 0 .092.004.184.012.275-1.157-.754-2.128-1.748-2.613-2.607a1 1 0 00-1.706.994c.82 1.408 2.228 3.046 3.655 3.99A6.99 6.99 0 006 14a7 7 0 1014 0 6.99 6.99 0 00-1.605-4.48c-.469-.56-1.156-1.282-1.92-1.942a15.7 15.7 0 00-1.638-1.218 8.01 8.01 0 01-1.442-3.807z" clip-rule="evenodd" />
                                </svg>
                                <span>SESIÓN SELECCIONADA</span>
                            </div>

                            <div class="text-emerald-200/90 font-medium">
                                Tiempo est.: <span class="font-bold text-white">{{ $active->estimated_minutes ?? 60 }} min</span>
                            </div>

                            <div class="px-2.5 py-1 rounded-full bg-emerald-900/60 border border-emerald-400/30 text-emerald-200 text-2xs font-semibold">
                                {{ $active->plannedExercises->count() }} ejercicios
                            </div>
                        </div>

                        <!-- Session Title & Focus -->
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-display leading-tight">
                                {{ $active->label }}
                            </h2>
                            @if($active->description)
                                <p class="text-xs sm:text-sm text-emerald-100/80 mt-1 leading-relaxed">
                                    {{ $active->description }}
                                </p>
                            @endif
                        </div>

                        <!-- DESGLOSE DEL ENTRENAMIENTO -->
                        <div class="space-y-2 pt-1">
                            <div class="flex items-center gap-1.5 text-2xs uppercase tracking-wider text-emerald-200/80 font-bold">
                                <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <span>DESGLOSE DE TU RUTINA</span>
                            </div>

                            <div class="space-y-2">
                                @forelse($active->plannedExercises as $idx => $item)
                                    @php
                                        $exerciseVideo = $item->video_url ?: ($item->exercise->video_url ?? null);
                                    @endphp
                                    <div class="exercise-item-pill-client rounded-xl px-3.5 py-2.5 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-6 h-6 rounded-lg bg-emerald-600/60 border border-emerald-400/40 text-emerald-100 font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $idx + 1 }}
                                            </span>
                                            <span class="text-sm font-semibold text-white truncate">
                                                {{ $item->exercise->name ?? 'Ejercicio' }}
                                            </span>
                                            @if($exerciseVideo)
                                                <span class="inline-flex items-center gap-1 text-[10px] text-red-400 font-bold bg-red-950/60 border border-red-500/30 px-1.5 py-0.5 rounded shrink-0" title="Video de técnica disponible">
                                                    <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    Video
                                                </span>
                                            @endif
                                        </div>
                                        <div class="px-2.5 py-1 rounded-md bg-emerald-900/80 border border-emerald-400/30 text-emerald-100 text-xs font-mono font-bold whitespace-nowrap">
                                            {{ $item->sets }} x {{ $item->min_reps }}-{{ $item->max_reps }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-emerald-200/60 p-3 italic">No hay ejercicios configurados en este día.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- CTA Action Button & Print -->
                        <div class="flex items-center gap-2 pt-2">
                            <a href="{{ route('gym.workout', ['identifier' => $gym->username ?: $gym->id, 'trainingDay' => $active->id]) }}" 
                               class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl bg-white text-emerald-950 hover:bg-slate-100 font-black text-sm uppercase tracking-wider shadow-lg shadow-white/10 transition active:scale-[0.98]">
                                <svg class="w-4 h-4 fill-emerald-600" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                                <span>COMENZAR ENTRENAMIENTO</span>
                            </a>

                            <button type="button" onclick="window.print()" title="Imprimir rutina"
                                    class="p-3.5 rounded-xl bg-emerald-900/60 hover:bg-emerald-800 text-emerald-200 border border-emerald-400/40 transition flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- OTHER SESSIONS OF THIS CLIENT PLAN -->
                    @if($activePlan && $activePlan->trainingDays->count() > 1)
                        <div class="space-y-2 pt-2">
                            <p class="text-2xs font-extrabold uppercase tracking-wider text-slate-400">
                                OTRAS SESIONES DE TU PLAN:
                            </p>
                            @foreach($activePlan->trainingDays as $day)
                                @if($day->id !== $active->id)
                                    <div class="routine-item-card rounded-2xl p-4 flex items-center justify-between gap-3">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 mt-2 shrink-0"></span>
                                            <div class="min-w-0">
                                                <h3 class="text-sm sm:text-base font-bold text-white tracking-tight truncate font-display">
                                                    {{ $day->label }}
                                                </h3>
                                                <p class="text-xs text-slate-400 truncate mt-0.5">
                                                    {{ $day->plannedExercises->count() }} Ejercicios &bull; 
                                                    @php
                                                        $sample = $day->plannedExercises->take(3)->map(fn($p) => $p->exercise->name ?? '')->filter()->implode(', ');
                                                    @endphp
                                                    <span>{{ $sample ?: ($day->description ?? 'Sesión planificada') }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'client', 'plan_id' => $activePlan->id, 'selected' => $day->id]) }}" 
                                           class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-950/60 hover:bg-emerald-900/80 text-xs font-semibold text-emerald-300 hover:text-white border border-emerald-500/40 transition">
                                            <span>Elegir sesión</span>
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif
            @endif

        {{-- TAB 2: GYM ROUTINES --}}
        @else
            @if($routines->isEmpty())
                <div class="p-8 text-center bg-brand-surface rounded-2xl border border-slate-800">
                    <p class="text-slate-400 text-sm">Este gimnasio aún no tiene rutinas públicas configuradas.</p>
                </div>
            @else
                @php
                    $active = $selectedRoutine ?? $routines->first();
                @endphp

                <!-- ACTIVE / FEATURED GYM SESSION CARD -->
                <div class="glow-card rounded-2xl sm:rounded-3xl p-5 sm:p-6 space-y-4 relative overflow-hidden transition-all duration-300">
                    <!-- Top Badges Row -->
                    <div class="flex items-center justify-between gap-2 text-2xs sm:text-xs">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/30 border border-blue-400/40 text-blue-200 font-bold uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.17 2.15-1.17 3.522 0 .092.004.184.012.275-1.157-.754-2.128-1.748-2.613-2.607a1 1 0 00-1.706.994c.82 1.408 2.228 3.046 3.655 3.99A6.99 6.99 0 006 14a7 7 0 1014 0 6.99 6.99 0 00-1.605-4.48c-.469-.56-1.156-1.282-1.92-1.942a15.7 15.7 0 00-1.638-1.218 8.01 8.01 0 01-1.442-3.807z" clip-rule="evenodd" />
                            </svg>
                            <span>SESIÓN SELECCIONADA</span>
                        </div>

                        <div class="text-blue-200/90 font-medium">
                            Tiempo est.: <span class="font-bold text-white">{{ $active->estimated_minutes ?? 60 }} min</span>
                        </div>

                        <div class="px-2.5 py-1 rounded-full bg-blue-800/60 border border-blue-400/30 text-blue-200 text-2xs font-semibold">
                            {{ $active->plannedExercises->count() }} ejercicios disponibles
                        </div>
                    </div>

                    <!-- Session Title & Focus -->
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-display leading-tight">
                            {{ $active->label }}
                        </h2>
                        @if($active->description)
                            <p class="text-xs sm:text-sm text-blue-100/80 mt-1 leading-relaxed">
                                {{ $active->description }}
                            </p>
                        @endif
                    </div>

                    <!-- DESGLOSE DEL ENTRENAMIENTO -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center gap-1.5 text-2xs uppercase tracking-wider text-blue-200/80 font-bold">
                            <svg class="w-3.5 h-3.5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span>DESGLOSE DEL ENTRENAMIENTO</span>
                        </div>

                        <div class="space-y-2">
                            @forelse($active->plannedExercises as $idx => $item)
                                @php
                                    $exerciseVideo = $item->video_url ?: ($item->exercise->video_url ?? null);
                                @endphp
                                <div class="exercise-item-pill rounded-xl px-3.5 py-2.5 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-lg bg-blue-600/60 border border-blue-400/40 text-blue-100 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="text-sm font-semibold text-white truncate">
                                            {{ $item->exercise->name ?? 'Ejercicio' }}
                                        </span>
                                        @if($exerciseVideo)
                                            <span class="inline-flex items-center gap-1 text-[10px] text-red-400 font-bold bg-red-950/60 border border-red-500/30 px-1.5 py-0.5 rounded shrink-0" title="Video demostrativo disponible">
                                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                Video
                                            </span>
                                        @endif
                                    </div>
                                    <div class="px-2.5 py-1 rounded-md bg-blue-900/80 border border-blue-400/30 text-blue-100 text-xs font-mono font-bold whitespace-nowrap">
                                        {{ $item->sets }} x {{ $item->min_reps }}-{{ $item->max_reps }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-blue-200/60 p-3 italic">No hay ejercicios configurados en esta rutina.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CTA Action Button & Print -->
                    <div class="flex items-center gap-2 pt-2">
                        <a href="{{ route('gym.workout', ['identifier' => $gym->username ?: $gym->id, 'trainingDay' => $active->id]) }}" 
                           class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl bg-white text-blue-900 hover:bg-slate-100 font-black text-sm uppercase tracking-wider shadow-lg shadow-white/10 transition active:scale-[0.98]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            <span>COMENZAR ENTRENAMIENTO</span>
                        </a>

                        <button type="button" onclick="window.print()" title="Imprimir rutina"
                                class="p-3.5 rounded-xl bg-blue-900/60 hover:bg-blue-800 text-blue-200 border border-blue-400/40 transition flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- OTHER SESSIONS LIST (Below) -->
                <div class="space-y-2.5 pt-2">
                    <p class="text-2xs font-extrabold uppercase tracking-wider text-slate-400">
                        OTRAS RUTINAS DEL GIMNASIO:
                    </p>
                    @foreach($routines as $routine)
                        @if($routine->id !== $active->id)
                            <div class="routine-item-card rounded-2xl p-4 flex items-center justify-between gap-3">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 mt-2 shrink-0"></span>
                                    <div class="min-w-0">
                                        <h3 class="text-sm sm:text-base font-bold text-white tracking-tight truncate font-display">
                                            {{ $routine->label }}
                                        </h3>
                                        <p class="text-xs text-slate-400 truncate mt-0.5">
                                            {{ $routine->plannedExercises->count() }} Ejercicios &bull; 
                                            @php
                                                $sample = $routine->plannedExercises->take(3)->map(fn($p) => $p->exercise->name ?? '')->filter()->implode(', ');
                                            @endphp
                                            <span>{{ $sample ?: ($routine->description ?? 'Sesión de entrenamiento') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'gym', 'selected' => $routine->id]) }}" 
                                   class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-xs font-semibold text-slate-300 hover:text-white border border-slate-700/60 transition">
                                    <span>Ver detalles</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Footer -->
        <footer class="pt-6 text-center text-xs text-slate-500">
            <p>{{ $gym->name }} &bull; Powered by <span class="font-bold text-slate-400">AF TRAINING</span></p>
        </footer>
    </div>

    <!-- Quick In-Page Client Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md hidden opacity-0 transition-opacity duration-200">
        <div class="w-full max-w-sm bg-slate-900 border border-slate-700/80 rounded-3xl p-6 shadow-2xl relative space-y-4">
            <!-- Close Button -->
            <button type="button" onclick="closeLoginModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Header -->
            <div class="text-center space-y-1">
                <div class="w-10 h-10 rounded-2xl bg-blue-600/30 border border-blue-500/40 text-blue-400 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white tracking-tight font-display">Acceso de Atleta / Cliente</h3>
                <p class="text-xs text-slate-400">Ingresa para ver tus planes personalizados y registrar tus marcas</p>
            </div>

            <!-- Error Alert -->
            <div id="loginError" class="p-3 rounded-xl bg-red-950/60 border border-red-500/40 text-red-200 text-xs hidden"></div>

            <!-- Login Form -->
            <form id="ajaxLoginForm" onsubmit="handleAjaxLogin(event)" class="space-y-3.5">
                @csrf
                <input type="hidden" name="redirect" value="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'tab' => 'client']) }}">

                <div>
                    <label class="block text-2xs font-extrabold uppercase tracking-wider text-slate-400 mb-1">Usuario o Correo Electrónico</label>
                    <input type="text" name="login" required placeholder="tu_usuario o correo@ejemplo.com"
                           class="w-full bg-slate-800/90 text-white text-xs rounded-xl px-3.5 py-2.5 border border-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-500">
                </div>

                <div>
                    <label class="block text-2xs font-extrabold uppercase tracking-wider text-slate-400 mb-1">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full bg-slate-800/90 text-white text-xs rounded-xl px-3.5 py-2.5 border border-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-500">
                </div>

                <div class="flex items-center justify-between text-2xs text-slate-400 pt-0.5">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0">
                        <span>Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-blue-400 hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" id="loginSubmitBtn"
                        class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                    <span>Iniciar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10);
            const input = modal.querySelector('input[name="login"]');
            if (input) input.focus();
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLoginModal();
        });

        // Close when clicking backdrop
        document.getElementById('loginModal')?.addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeLoginModal();
        });

        // AJAX Login submit
        function handleAjaxLogin(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('loginSubmitBtn');
            const errBox = document.getElementById('loginError');
            errBox.classList.add('hidden');
            errBox.textContent = '';

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg> Conectando...`;

            const formData = new FormData(form);

            fetch("{{ route('login') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    btn.innerHTML = '¡Acceso Correcto!';
                    window.location.href = data.redirect || window.location.href;
                } else {
                    let msg = data.message || 'Credenciales inválidas.';
                    if (data.errors && data.errors.login) {
                        msg = data.errors.login[0];
                    }
                    errBox.textContent = msg;
                    errBox.classList.remove('hidden');
                    btn.disabled = false;
                    btn.innerHTML = '<span>Iniciar Sesión</span>';
                }
            })
            .catch(err => {
                console.error(err);
                errBox.textContent = 'Ocurrió un error al procesar el ingreso. Intente nuevamente.';
                errBox.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = '<span>Iniciar Sesión</span>';
            });
        }
    </script>
</body>
</html>
