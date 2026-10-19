<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Programas de Rutinas - {{ $gym->name }} | AF Training</title>
    <!-- Google Fonts: Plus Jakarta Sans / Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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
        .exercise-item-pill {
            background: rgba(13, 27, 62, 0.55);
            border: 1px solid rgba(59, 130, 246, 0.25);
            backdrop-filter: blur(8px);
        }
        .exercise-item-pill:hover {
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
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-between py-6 px-4 sm:px-6">

    <div class="max-w-md md:max-w-xl mx-auto w-full space-y-5">
        <!-- Top Bar Header -->
        <header class="flex items-center justify-between pt-1 pb-2">
            <div class="flex items-center gap-2.5">
                <!-- AF Geometric Logo -->
                <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/30">
                    <span class="font-display font-black text-white text-sm tracking-tighter">AF</span>
                </div>
                <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white font-display">
                    Programas de Rutinas
                </h1>
            </div>
            <div class="text-right">
                <span class="text-2xs sm:text-xs font-semibold text-slate-400">
                    Total: <span class="text-white font-bold">{{ $routines->count() }}</span> sesiones Configuradas
                </span>
            </div>
        </header>

        @if($routines->isEmpty())
            <div class="p-8 text-center bg-brand-surface rounded-2xl border border-slate-800">
                <p class="text-slate-400 text-sm">Este gimnasio aún no tiene rutinas públicas configuradas.</p>
            </div>
        @else
            @php
                $active = $selectedRoutine ?? $routines->first();
            @endphp

            <!-- ACTIVE / FEATURED SESSION CARD (Matching Image 1) -->
            <div class="glow-card rounded-2xl sm:rounded-3xl p-5 sm:p-6 space-y-4 relative overflow-hidden transition-all duration-300">
                <!-- Top Badges Row -->
                <div class="flex items-center justify-between gap-2 text-2xs sm:text-xs">
                    <!-- Session Selected Badge -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/30 border border-blue-400/40 text-blue-200 font-bold uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.17 2.15-1.17 3.522 0 .092.004.184.012.275-1.157-.754-2.128-1.748-2.613-2.607a1 1 0 00-1.706.994c.82 1.408 2.228 3.046 3.655 3.99A6.99 6.99 0 006 14a7 7 0 1014 0 6.99 6.99 0 00-1.605-4.48c-.469-.56-1.156-1.282-1.92-1.942a15.7 15.7 0 00-1.638-1.218 8.01 8.01 0 01-1.442-3.807z" clip-rule="evenodd" />
                        </svg>
                        <span>SESIÓN SELECCIONADA</span>
                    </div>

                    <!-- Time Est -->
                    <div class="text-blue-200/90 font-medium">
                        Tiempo est.: <span class="font-bold text-white">{{ $active->estimated_minutes ?? 60 }} min</span>
                    </div>

                    <!-- Exercises Available -->
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
                            <div class="exercise-item-pill rounded-xl px-3.5 py-2.5 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="w-6 h-6 rounded-lg bg-blue-600/60 border border-blue-400/40 text-blue-100 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-sm font-semibold text-white truncate">
                                        {{ $item->exercise->name ?? 'Ejercicio' }}
                                    </span>
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
                                        {{ $routine->plannedExercises->count() }} Ejercicios disponibles &bull; 
                                        @php
                                            $sample = $routine->plannedExercises->take(3)->map(fn($p) => $p->exercise->name ?? '')->filter()->implode(', ');
                                        @endphp
                                        <span>{{ $sample ?: ($routine->description ?? 'Sesión de entrenamiento') }}</span>
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'selected' => $routine->id]) }}" 
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

        <!-- Footer -->
        <footer class="pt-6 text-center text-xs text-slate-500">
            <p>{{ $gym->name }} &bull; Powered by <span class="font-bold text-slate-400">AF TRAINING</span></p>
        </footer>
    </div>

</body>
</html>
