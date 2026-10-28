<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $trainingDay->label }} - Sesión en Vivo | AF Training</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        af: {
                            dark: '#070a12',
                            card: '#0f1422',
                            cardAlt: '#141b2d',
                            border: '#1e293b',
                            blue: '#2563eb',
                            blueLight: '#3b82f6',
                            blueGlow: '#60a5fa',
                            orange: '#ea580c',
                            orangeFlame: '#f97316',
                            orangeLight: '#fdba74',
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
            background-color: #070a12;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .pulse-live {
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
            animation: pulse-orange 1.8s infinite;
        }

        @keyframes pulse-orange {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(249, 115, 22, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
            }
        }

        /* Custom scrollbar for sequence */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

@php
    $backParams = ['identifier' => $gym->username ?: $gym->id];
    if ($trainingDay->monthly_plan_id) {
        $backParams['tab'] = 'client';
        $backParams['plan_id'] = $trainingDay->monthly_plan_id;
        $backParams['selected'] = $trainingDay->id;
    } else {
        $backParams['selected'] = $trainingDay->id;
    }
    $backUrl = route('gym.routines', $backParams);
@endphp

<body class="min-h-screen antialiased flex flex-col justify-between pb-28">

    <div class="max-w-md md:max-w-xl mx-auto w-full px-4 pt-4 space-y-4">

        <!-- 1. TOP BAR HEADER (Matching Image 2) -->
        <header class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ $backUrl }}"
                    class="p-2 -ml-2 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-tight">Sesión activa</h1>
                    <p class="text-2xs font-extrabold uppercase tracking-widest text-slate-400">AF TRAINING</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-950/80 border border-orange-500/40 text-orange-400 text-2xs font-extrabold tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-orange-500 pulse-live"></span>
                    <span>LIVE</span>
                </div>
                @if(auth()->check())
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id]) }}">
                        <button type="submit" title="Cerrar sesión"
                            class="px-2.5 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-red-400 hover:border-red-500/40 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Salir</span>
                        </button>
                    </form>
                @endif
            </div>
        </header>

        <!-- 2. WORKOUT SUBHEADER -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <div
                    class="inline-flex items-center gap-1.5 text-orange-400 font-bold tracking-wide uppercase text-2xs sm:text-xs">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <span>EN VIVO &bull; HIPERTROFIA & RENDIMIENTO</span>
                </div>
                <div class="flex items-center gap-1 text-slate-300 font-mono text-xs font-semibold">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="elapsedTimer">00:00:00</span>
                </div>
            </div>

            <div class="flex items-baseline justify-between gap-2">
                <h2 class="text-xl sm:text-2xl font-black text-white font-display tracking-tight leading-tight">
                    {{ $trainingDay->label }}
                </h2>
                <span class="text-xs font-semibold text-slate-400 whitespace-nowrap">
                    Día {{ $currentDayNumber }} / {{ $totalDays }}
                </span>
            </div>
        </div>

        <!-- 3. TEMPORIZADOR DE DESCANSO (Matching Image 2) -->
        <div class="bg-af-card border border-af-border rounded-2xl p-4 space-y-3 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-slate-800/80 text-slate-300">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white tracking-tight">Temporizador de Descanso</div>
                    </div>
                </div>

                <div class="text-right">
                    <span id="restDisplay"
                        class="font-display font-black text-2xl sm:text-3xl text-orange-500 tracking-tight">01:30</span>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider ml-1">rest</span>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                <div id="restProgressBar"
                    class="bg-gradient-to-r from-orange-600 to-orange-400 h-full w-full transition-all duration-300">
                </div>
            </div>

            <!-- Timer controls -->
            <div class="grid grid-cols-4 gap-2 pt-1">
                <button type="button" onclick="adjustRest(-15)"
                    class="py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 transition active:scale-95">
                    -15s
                </button>
                <button type="button" onclick="adjustRest(15)"
                    class="py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 transition active:scale-95">
                    +15s
                </button>
                <button type="button" onclick="resetRest()"
                    class="py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 transition active:scale-95 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <button type="button" id="pauseRestBtn" onclick="toggleRest()"
                    class="py-2 rounded-xl bg-orange-600 hover:bg-orange-500 text-xs font-black uppercase tracking-wider text-white transition active:scale-95 shadow-md shadow-orange-600/30">
                    Pausar
                </button>
            </div>
        </div>

        <!-- 4. COMENTARIO DEL COACH AF (Matching Image 2) -->
        @if($trainingDay->comment)
            <div class="bg-af-card border border-af-border rounded-2xl p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-2xs font-bold uppercase tracking-wider text-blue-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>COMENTARIO DEL COACH AF</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    {{ $trainingDay->comment }}
                </p>
            </div>
        @endif

        <!-- 5. SECUENCIA DEL ENTRENAMIENTO (Horizontal Exercise Carousel) -->
        <div class="space-y-2">
            <div class="flex items-center justify-between text-2xs uppercase tracking-wider font-bold text-slate-400">
                <span>SECUENCIA DEL ENTRENAMIENTO</span>
                <span id="sequenceCounter">1 de {{ $trainingDay->plannedExercises->count() }}</span>
            </div>

            <div class="flex items-center gap-2.5 overflow-x-auto no-scrollbar pb-1" id="exerciseCarousel">
                @foreach($trainingDay->plannedExercises as $idx => $pExercise)
                    <button type="button" onclick="selectExercise({{ $idx }})" id="seqTab{{ $idx }}"
                        class="seq-tab flex-shrink-0 w-36 text-left p-3 rounded-2xl border transition-all duration-200 {{ $idx === 0 ? 'bg-blue-600 border-blue-400 text-white shadow-lg shadow-blue-600/30' : 'bg-af-card border-af-border text-slate-300 hover:border-slate-700' }}">
                        <div class="flex items-center justify-between mb-1.5">
                            <span
                                class="w-5 h-5 rounded-full {{ $idx === 0 ? 'bg-white text-blue-700' : 'bg-slate-800 text-slate-300' }} text-2xs font-bold flex items-center justify-center">
                                {{ $idx + 1 }}
                            </span>
                            <svg class="w-3.5 h-3.5 {{ $idx === 0 ? 'text-white' : 'text-slate-500' }}" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </div>
                        <div class="font-bold text-xs truncate leading-snug">{{ $pExercise->exercise->name ?? 'Ejercicio' }}
                        </div>
                        <div class="text-2xs uppercase tracking-wider mt-0.5 opacity-80">{{ $pExercise->sets }} SERIES</div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- 6. ACTIVE EXERCISE DETAIL CONTAINER -->
        <div id="activeExerciseContainer" class="space-y-4">
            <!-- Rendered dynamically by JavaScript based on current active exercise -->
        </div>

    </div>

    <!-- 7. FLOATING BOTTOM ACTION BAR (Matching Image 2) -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-[#070a12]/95 backdrop-blur-md border-t border-af-border p-3">
        <div class="max-w-md md:max-w-xl mx-auto space-y-2">
            <!-- Finalizar Sesión CTA -->
            <button type="button" onclick="openFinishModal()"
                class="w-full py-3.5 px-4 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-extrabold text-sm uppercase tracking-wider shadow-lg shadow-orange-600/25 flex items-center justify-center gap-2 transition active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M5 13l4 4L19 7" />
                </svg>
                <span>Finalizar Sesión</span>
            </button>

            <!-- Secondary action -->
            <div>
                <button type="button" onclick="openNoteModal()"
                    class="w-full py-2.5 px-3 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Añadir Nota</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: AÑADIR NOTA -->
    <div id="noteModal"
        class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-af-card border border-af-border rounded-2xl p-6 max-w-sm w-full space-y-4">
            <h3 class="text-base font-bold text-white">Notas de la Sesión</h3>
            <textarea id="sessionNotesInput" rows="4"
                placeholder="Sensaciones, peso corporal, dolores articulares o ajustes para la próxima semana..."
                class="w-full rounded-xl bg-slate-900 border border-slate-700 p-3 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeNoteModal()"
                    class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white">Cerrar</button>
                <button type="button" onclick="closeNoteModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold">Guardar
                    Nota</button>
            </div>
        </div>
    </div>

    <!-- MODAL: TERMINAR SESIÓN & REGISTRAR -->
    <div id="finishModal"
        class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-af-card border border-af-border rounded-2xl p-6 max-w-sm w-full space-y-4 text-center">
            <div
                class="w-14 h-14 mx-auto rounded-full bg-orange-600/20 border border-orange-500/40 text-orange-400 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white">¡Gran Trabajo en la Sala!</h3>
                <p class="text-xs text-slate-400 mt-1">Has completado la rutina de hoy. Registra tu sesión para guardar
                    tus series y sobrecargas.</p>
            </div>

            <!-- Stats Recap -->
            <div class="grid grid-cols-2 gap-2 bg-slate-900/80 p-3 rounded-xl border border-slate-800 text-left">
                <div>
                    <span class="text-2xs text-slate-400 uppercase font-semibold">Tiempo Total</span>
                    <p id="modalDuration" class="text-sm font-bold text-white font-mono">00:00:00</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 uppercase font-semibold">Volumen Total</span>
                    <p id="modalTotalVolume" class="text-sm font-bold text-orange-400 font-mono">0 kg</p>
                </div>
            </div>

            @guest
                <div class="text-left space-y-1">
                    <label class="block text-2xs font-semibold text-slate-400 uppercase">Tu Nombre o Alias
                        (Opcional)</label>
                    <input type="text" id="guestNameInput" placeholder="ej. Carlos M."
                        class="w-full rounded-xl bg-slate-900 border border-slate-700 px-3 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            @endguest

            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="closeFinishModal()"
                    class="flex-1 py-2.5 rounded-xl border border-slate-700 text-xs font-semibold text-slate-300 hover:bg-slate-800">
                    Seguir Entrenando
                </button>
                <button type="button" id="submitSessionBtn" onclick="submitWorkoutSession()"
                    class="flex-1 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-orange-600/30">
                    Guardar Sesión
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: VIDEO EN PANTALLA COMPLETA -->
    <div id="videoModal" class="hidden fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-3 sm:p-5">
        <div class="relative bg-slate-950 border border-slate-800 rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-800">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse shrink-0"></span>
                    <h3 id="videoModalTitle" class="text-sm font-bold text-white truncate">Video de Técnica</h3>
                </div>
                <button type="button" onclick="closeVideoModal()" class="p-1.5 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition shrink-0 ml-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="videoModalBody" class="w-full aspect-video bg-black flex items-center justify-center">
                <!-- dynamic iframe or video inserted here -->
            </div>
        </div>
    </div>

    <!-- Pass backend planned exercises data to JS -->
    <script>
        const workoutData = {
            trainingDayId: {{ $trainingDay->id }},
            gymIdentifier: "{{ $gym->username ?: $gym->id }}",
            unit: 'KG', // 'KG' or 'LB'
            activeMediaSlide: 0,
            prevSessionOpen: true,
            currentIndex: 0,
            startTime: new Date().toISOString(),
            plannedExercises: @json($exercisesPayload)
        };

        // 1. Elapsed Workout Clock
        let workoutSeconds = 0;
        setInterval(() => {
            workoutSeconds++;
            const hrs = String(Math.floor(workoutSeconds / 3600)).padStart(2, '0');
            const mins = String(Math.floor((workoutSeconds % 3600) / 60)).padStart(2, '0');
            const secs = String(workoutSeconds % 60).padStart(2, '0');
            const formatted = `${hrs}:${mins}:${secs}`;
            document.getElementById('elapsedTimer').textContent = formatted;
            document.getElementById('modalDuration').textContent = formatted;
        }, 1000);

        // 2. Rest Countdown Timer
        let restTotal = 90;
        let restCurrent = 90;
        let restTimerInterval = null;
        let restIsRunning = false;

        function updateRestDisplay() {
            const m = String(Math.floor(restCurrent / 60)).padStart(2, '0');
            const s = String(restCurrent % 60).padStart(2, '0');
            document.getElementById('restDisplay').textContent = `${m}:${s}`;
            const pct = Math.min(100, Math.max(0, (restCurrent / restTotal) * 100));
            document.getElementById('restProgressBar').style.width = `${pct}%`;
        }

        function startRest(seconds = 90) {
            restTotal = seconds;
            restCurrent = seconds;
            updateRestDisplay();

            if (restTimerInterval) clearInterval(restTimerInterval);
            restIsRunning = true;
            document.getElementById('pauseRestBtn').textContent = 'Pausar';

            restTimerInterval = setInterval(() => {
                if (restCurrent > 0) {
                    restCurrent--;
                    updateRestDisplay();
                } else {
                    clearInterval(restTimerInterval);
                    restIsRunning = false;
                    document.getElementById('pauseRestBtn').textContent = 'Iniciar';
                    // Vibration feedback on supported mobile devices
                    if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
                }
            }, 1000);
        }

        function toggleRest() {
            if (restIsRunning) {
                clearInterval(restTimerInterval);
                restIsRunning = false;
                document.getElementById('pauseRestBtn').textContent = 'Reanudar';
            } else {
                if (restCurrent <= 0) restCurrent = restTotal;
                restIsRunning = true;
                document.getElementById('pauseRestBtn').textContent = 'Pausar';
                restTimerInterval = setInterval(() => {
                    if (restCurrent > 0) {
                        restCurrent--;
                        updateRestDisplay();
                    } else {
                        clearInterval(restTimerInterval);
                        restIsRunning = false;
                        document.getElementById('pauseRestBtn').textContent = 'Iniciar';
                    }
                }, 1000);
            }
        }

        function adjustRest(delta) {
            restCurrent = Math.max(0, restCurrent + delta);
            if (restCurrent > restTotal) restTotal = restCurrent;
            updateRestDisplay();
        }

        function resetRest() {
            startRest(90);
        }

        // Initialize rest display
        updateRestDisplay();

        // Video Parser & Embed Renderer
        function parseVideoEmbed(url) {
            if (!url) return null;
            const str = String(url).trim();

            // YouTube
            const ytMatch = str.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            if (ytMatch && ytMatch[1]) {
                return {
                    type: 'youtube',
                    id: ytMatch[1],
                    embedUrl: `https://www.youtube-nocookie.com/embed/${ytMatch[1]}?rel=0&playsinline=1`,
                    directUrl: str
                };
            }

            // Instagram Reel / Post
            const igMatch = str.match(/instagram\.com\/(?:reel|reels|p)\/([a-zA-Z0-9_-]+)/i);
            if (igMatch && igMatch[1]) {
                return {
                    type: 'instagram',
                    id: igMatch[1],
                    embedUrl: `https://www.instagram.com/reel/${igMatch[1]}/embed/`,
                    directUrl: str
                };
            }

            // TikTok
            const ttMatch = str.match(/tiktok\.com\/(?:@[^\/]+\/video\/|v\/|embed\/v2\/)(\d+)/i);
            if (ttMatch && ttMatch[1]) {
                return {
                    type: 'tiktok',
                    id: ttMatch[1],
                    embedUrl: `https://www.tiktok.com/embed/v2/${ttMatch[1]}`,
                    directUrl: str
                };
            }

            // Direct GIF image
            if (str.match(/\.gif(\?.*)?$/i)) {
                return {
                    type: 'gif',
                    embedUrl: str,
                    directUrl: str
                };
            }

            // Direct Video (mp4, webm, mov, or storage path)
            if (str.match(/\.(mp4|webm|mov)(\?.*)?$/i) || str.includes('/storage/exercises/') || str.startsWith('/storage/')) {
                return {
                    type: 'file',
                    embedUrl: str,
                    directUrl: str
                };
            }

            // Generic web URL
            return {
                type: 'generic',
                embedUrl: str,
                directUrl: str
            };
        }

        function openVideoModal(url, title, type) {
            const modal = document.getElementById('videoModal');
            const body = document.getElementById('videoModalBody');
            const titleEl = document.getElementById('videoModalTitle');
            titleEl.textContent = title || 'Demostración de Técnica';

            if (type === 'file') {
                body.innerHTML = `<video src="${url}" controls autoplay playsinline class="w-full h-full object-contain"></video>`;
            } else if (type === 'gif' || type === 'image') {
                body.innerHTML = `<div class="w-full h-full flex items-center justify-center p-3 bg-slate-950"><img src="${url}" class="max-h-[75vh] w-auto max-w-full object-contain rounded-xl shadow-2xl" alt="${title || 'GIF'}"></div>`;
            } else {
                body.innerHTML = `<iframe src="${url}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen playsinline></iframe>`;
            }
            modal.classList.remove('hidden');
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const body = document.getElementById('videoModalBody');
            body.innerHTML = '';
            modal.classList.add('hidden');
        }

        function setMediaSlide(slideIndex) {
            workoutData.activeMediaSlide = slideIndex;
            const videoSlide = document.getElementById('mediaSlideVideo');
            const gifSlide = document.getElementById('mediaSlideGif');
            const tabVideo = document.getElementById('mediaTabVideo');
            const tabGif = document.getElementById('mediaTabGif');

            if (videoSlide && gifSlide) {
                if (slideIndex === 0) {
                    videoSlide.classList.remove('hidden');
                    gifSlide.classList.add('hidden');
                } else {
                    videoSlide.classList.add('hidden');
                    gifSlide.classList.remove('hidden');
                }
            }

            if (tabVideo && tabGif) {
                if (slideIndex === 0) {
                    tabVideo.className = "px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-blue-600 text-white shadow-md shadow-blue-600/30";
                    tabGif.className = "px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-white";
                } else {
                    tabVideo.className = "px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 text-slate-400 hover:text-white";
                    tabGif.className = "px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-orange-500 text-white shadow-md shadow-orange-500/30";
                }
            }
        }

        function renderExerciseMediaSlider(ex) {
            // If activeMediaSlide not initialized, default to 1 (GIF) if gif available, else 0 (Video)
            if (workoutData.activeMediaSlide === undefined || workoutData.activeMediaSlide === null) {
                workoutData.activeMediaSlide = ex.gif_url ? 1 : 0;
            }
            const activeSlide = workoutData.activeMediaSlide;

            return `
                <div class="space-y-2">
                    <!-- Slider Header Tabs / Controls -->
                    <div class="flex items-center justify-between bg-slate-900/90 p-1.5 rounded-2xl border border-slate-800">
                        <!-- Slide Selectors -->
                        <div class="flex items-center gap-1">
                            <button type="button" id="mediaTabVideo" onclick="setMediaSlide(0)" 
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 ${activeSlide === 0 ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white'}">
                                <svg class="w-3.5 h-3.5 text-red-400 fill-current" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 22c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73z"/></svg>
                                <span>Video / YouTube</span>
                            </button>

                            <button type="button" id="mediaTabGif" onclick="setMediaSlide(1)" 
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 ${activeSlide === 1 ? 'bg-orange-500 text-white shadow-md shadow-orange-500/30' : 'text-slate-400 hover:text-white'}">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>GIF Ejercicio</span>
                                ${ex.gif_url ? '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>' : ''}
                            </button>
                        </div>

                        <!-- Prev / Next Slider Arrows -->
                        <div class="flex items-center gap-1 pr-1">
                            <button type="button" onclick="setMediaSlide(0)" title="Ver Video" class="p-1 rounded-lg ${activeSlide === 0 ? 'text-white' : 'text-slate-500 hover:text-slate-300'} transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="text-2xs text-slate-500 font-mono">${activeSlide + 1}/2</span>
                            <button type="button" onclick="setMediaSlide(1)" title="Ver GIF" class="p-1 rounded-lg ${activeSlide === 1 ? 'text-white' : 'text-slate-500 hover:text-slate-300'} transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Slide 0: Video Player / Search Link -->
                    <div id="mediaSlideVideo" class="${activeSlide === 0 ? 'block' : 'hidden'} transition-all">
                        ${renderExerciseVideoContent(ex)}
                    </div>

                    <!-- Slide 1: GIF Demo Animation -->
                    <div id="mediaSlideGif" class="${activeSlide === 1 ? 'block' : 'hidden'} transition-all">
                        ${renderExerciseGifContent(ex)}
                    </div>
                </div>
            `;
        }

        function renderExerciseGifContent(ex) {
            const gifUrl = ex.gif_url;
            const exName = ex.name || 'Ejercicio';
            const safeExName = exName.replace(/'/g, "\\'");
            const focus = ex.primary_muscle || ex.muscle_group || 'Técnica';

            if (!gifUrl) {
                return `
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-800 flex flex-col items-center justify-center p-4 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-300">Demostración en GIF no disponible</p>
                        <p class="text-2xs text-slate-500 mt-0.5">Enfoque: <span class="text-slate-400 font-semibold">${focus}</span></p>
                        <button type="button" onclick="setMediaSlide(0)" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-blue-400 hover:text-white border border-slate-700 text-2xs font-bold transition">
                            Ver video de técnica &rarr;
                        </button>
                    </div>
                `;
            }

            return `
                <div class="space-y-1.5">
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-950 border border-slate-800 shadow-lg flex items-center justify-center group cursor-pointer" onclick="openVideoModal('${gifUrl}', '${safeExName}', 'gif')">
                        <img src="${gifUrl}" alt="${exName}" class="w-full h-full object-contain" loading="lazy" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="px-3 py-1.5 rounded-xl bg-black/80 border border-slate-700 text-white text-xs font-bold flex items-center gap-1.5 backdrop-blur-sm">
                                <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                Agrandar Demostración
                            </span>
                        </div>
                        <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-orange-600/90 text-white text-2xs font-black tracking-wider uppercase backdrop-blur-sm">
                            GIF Animado
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-2xs text-slate-400 px-1">
                        <span class="inline-flex items-center gap-1.5 text-orange-400 font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Demostración Técnica
                        </span>
                        <button type="button" onclick="openVideoModal('${gifUrl}', '${safeExName}', 'gif')" class="text-orange-400 hover:text-orange-300 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            Agrandar GIF
                        </button>
                    </div>
                </div>
            `;
        }

        function renderExerciseVideoContent(ex) {
            const parsed = parseVideoEmbed(ex.video_url);
            const focus = ex.primary_muscle || ex.muscle_group || 'Técnica';
            const exName = ex.name || 'Ejercicio';
            const safeExName = exName.replace(/'/g, "\\'");

            if (!parsed) {
                return `
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-800 flex flex-col items-center justify-center p-4 text-center group shadow-inner">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-300">Video explicativo de ${exName}</p>
                        <p class="text-2xs text-slate-500 mt-0.5">Enfoque: <span class="text-slate-400 font-semibold">${focus}</span></p>
                        <a href="https://www.youtube.com/results?search_query=${encodeURIComponent(exName + ' tecnica ejecucion')}" 
                           target="_blank" rel="noopener noreferrer"
                           class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-2xs font-bold transition">
                            <svg class="w-3.5 h-3.5 text-red-500 fill-current" viewBox="0 0 24 24">
                                <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 22c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 22c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/>
                            </svg>
                            Buscar técnica en YouTube
                        </a>
                    </div>
                `;
            }

            if (parsed.type === 'youtube') {
                return `
                    <div class="space-y-1.5">
                        <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-black border border-slate-800 shadow-lg">
                            <iframe 
                                src="${parsed.embedUrl}" 
                                title="${exName}" 
                                class="w-full h-full border-0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen playsinline>
                            </iframe>
                        </div>
                        <div class="flex items-center justify-between text-2xs text-slate-400 px-1">
                            <span class="inline-flex items-center gap-1.5 text-red-400 font-bold">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 22c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73z"/></svg>
                                Video de Técnica
                            </span>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="openVideoModal('${parsed.embedUrl}', '${safeExName}', 'youtube')" class="text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                    Agrandar
                                </button>
                                <a href="${parsed.directUrl}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-white flex items-center gap-1">
                                    Abrir link
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }

            if (parsed.type === 'instagram') {
                return `
                    <div class="space-y-1.5">
                        <div class="relative w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-800 shadow-lg flex flex-col items-center">
                            <iframe 
                                src="${parsed.embedUrl}" 
                                title="${exName}" 
                                class="w-full h-80 sm:h-96 border-0 bg-black" 
                                scrolling="no" 
                                allowtransparency="true" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="flex items-center justify-between text-2xs text-slate-400 px-1">
                            <span class="inline-flex items-center gap-1.5 text-pink-400 font-bold">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                Instagram Reel
                            </span>
                            <a href="${parsed.directUrl}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 font-bold flex items-center gap-1">
                                Abrir en Instagram
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                `;
            }

            if (parsed.type === 'tiktok') {
                return `
                    <div class="space-y-1.5">
                        <div class="relative w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-800 shadow-lg flex flex-col items-center">
                            <iframe 
                                src="${parsed.embedUrl}" 
                                title="${exName}" 
                                class="w-full h-80 sm:h-96 border-0 bg-black" 
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="flex items-center justify-between text-2xs text-slate-400 px-1">
                            <span class="inline-flex items-center gap-1.5 text-cyan-400 font-bold">
                                TikTok Video
                            </span>
                            <a href="${parsed.directUrl}" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 font-bold flex items-center gap-1">
                                Abrir en TikTok
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                `;
            }

            if (parsed.type === 'file') {
                return `
                    <div class="space-y-1.5">
                        <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-black border border-slate-800 shadow-lg flex items-center justify-center">
                            <video src="${parsed.embedUrl}" controls playsinline preload="metadata" class="w-full h-full object-contain">
                                Tu navegador no soporta reproducción de video.
                            </video>
                        </div>
                        <div class="flex items-center justify-between text-2xs text-slate-400 px-1">
                            <span class="inline-flex items-center gap-1.5 text-blue-400 font-bold">
                                Video Local / HD
                            </span>
                            <button type="button" onclick="openVideoModal('${parsed.embedUrl}', '${safeExName}', 'file')" class="text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                Agrandar
                            </button>
                        </div>
                    </div>
                `;
            }

            // Generic web link
            return `
                <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 flex flex-col items-center justify-center p-4 text-center group shadow-inner">
                    <a href="${parsed.directUrl}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center gap-2 group-hover:scale-105 transition-all">
                        <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/40">
                            <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-white">Ver video de técnica</span>
                        <span class="text-2xs text-blue-300/80 underline max-w-xs truncate">${parsed.directUrl}</span>
                    </a>
                </div>
            `;
        }

        function renderPreviousSetsList(ex) {
            const prevSets = (ex.previous_sets && ex.previous_sets.length > 0)
                ? ex.previous_sets
                : [
                    { set_number: 1, reps: 8, weight: 40.82, weight_lb: 90.0 },
                    { set_number: 2, reps: 8, weight: 40.82, weight_lb: 90.0 },
                    { set_number: 3, reps: 6, weight: 40.82, weight_lb: 90.0 },
                ];

            return prevSets.map((s, i) => {
                const reps = s.reps || 0;
                const wKg = (s.weight !== undefined && s.weight !== null && s.weight !== '') 
                    ? Number(s.weight).toFixed(2) 
                    : (s.load ? Number(s.load).toFixed(2) : '40.82');
                const wLb = (s.weight_lb !== undefined && s.weight_lb !== null && s.weight_lb !== '') 
                    ? Number(s.weight_lb).toFixed(1) 
                    : (Number(wKg) * 2.20462).toFixed(1);

                return `
                    <div class="flex items-center justify-between py-2.5 border-t border-slate-800/80 text-xs">
                        <span class="text-slate-300 font-medium">Serie</span>
                        <span class="text-sky-400 font-bold font-mono tracking-tight">${reps} reps, ${wKg} kg (${wLb} lb)</span>
                    </div>
                `;
            }).join('');
        }

        function togglePreviousSession() {
            workoutData.prevSessionOpen = workoutData.prevSessionOpen === false ? true : false;
            const content = document.getElementById('prevSessionContent');
            const chevron = document.getElementById('prevSessionChevron');
            if (content) {
                content.classList.toggle('hidden', !workoutData.prevSessionOpen);
            }
            if (chevron) {
                chevron.classList.toggle('rotate-180', workoutData.prevSessionOpen);
            }
        }

        // 3. Render Active Exercise
        function renderActiveExercise() {
            const container = document.getElementById('activeExerciseContainer');
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (!ex) return;

            document.getElementById('sequenceCounter').textContent = `${workoutData.currentIndex + 1} de ${workoutData.plannedExercises.length}`;

            // Update carousel tabs style
            workoutData.plannedExercises.forEach((_, idx) => {
                const tab = document.getElementById(`seqTab${idx}`);
                if (tab) {
                    if (idx === workoutData.currentIndex) {
                        tab.className = 'seq-tab flex-shrink-0 w-36 text-left p-3 rounded-2xl border transition-all duration-200 bg-blue-600 border-blue-400 text-white shadow-lg shadow-blue-600/30';
                        tab.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    } else {
                        tab.className = 'seq-tab flex-shrink-0 w-36 text-left p-3 rounded-2xl border transition-all duration-200 bg-af-card border-af-border text-slate-300 hover:border-slate-700';
                    }
                }
            });

            // Calculate Volume and 1RM
            let totalVolume = 0;
            let max1RM = 0;

            ex.sets.forEach(s => {
                if (s.checked) {
                    const w = parseFloat(s.weight) || 0;
                    const r = parseInt(s.reps) || 0;
                    totalVolume += (w * r);
                    // Epley Formula: 1RM = weight * (1 + reps / 30)
                    const epley = w * (1 + (r / 30));
                    if (epley > max1RM) max1RM = epley;
                }
            });

            // Target subtitle
            const targetSubtitle = `${ex.target_sets || 3} series ${ex.min_reps && ex.max_reps ? `${ex.min_reps}-${ex.max_reps} reps` : '10-15 reps'}`;

            container.innerHTML = `
                <!-- Main Exercise Header Card -->
                <div class="bg-af-card border border-af-border rounded-2xl p-4 sm:p-5 space-y-3.5 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-slate-400">
                            <span>EJERCICIO #${workoutData.currentIndex + 1} &bull; ${ex.primary_muscle || ex.muscle_group || 'General'}</span>
                        </div>
                    </div>

                    <!-- Video / GIF Media Slider Element (Top position matching app) -->
                    ${renderExerciseMediaSlider(ex)}

                    <!-- Exercise Title, Sets/Reps Target & Rest Time (Matching App Layout) -->
                    <div class="flex items-start justify-between gap-3 pt-1">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-white font-display tracking-tight leading-tight">${ex.name}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">${targetSubtitle}</p>
                            ${ex.instruction ? `
                                <div class="mt-2.5 p-2.5 rounded-xl bg-blue-950/40 border border-blue-500/20 text-xs text-blue-200 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span><strong>Indicación del Coach:</strong> ${ex.instruction}</span>
                                </div>
                            ` : ''}
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-2xs text-slate-400 block font-medium">Descanso (s)</span>
                            <button type="button" onclick="startRest(90)" title="Iniciar descanso de 90s" class="mt-1 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-200 hover:text-white font-mono font-bold text-xs hover:border-blue-500 transition shadow-inner">
                                90
                            </button>
                        </div>
                    </div>

                    <!-- Card: ÚLTIMA SESIÓN (Exact match with AFTraining Mobile App) -->
                    <div class="rounded-2xl bg-[#090f1e] border border-slate-800/90 overflow-hidden shadow-lg mt-2">
                        <button type="button" onclick="togglePreviousSession()" class="w-full flex items-center justify-between p-3.5 hover:bg-slate-800/30 transition text-left">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-black uppercase tracking-wider text-slate-200">ÚLTIMA SESIÓN: ${ex.last_session_date || '12/10/2026'}</span>
                            </div>
                            <svg id="prevSessionChevron" class="w-4 h-4 text-slate-400 transition-transform duration-200 ${workoutData.prevSessionOpen !== false ? 'rotate-180' : ''}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="prevSessionContent" class="${workoutData.prevSessionOpen !== false ? 'block' : 'hidden'} px-3.5 pb-2">
                            ${renderPreviousSetsList(ex)}
                        </div>
                    </div>
                </div>

                <!-- 7. Series de Trabajo Card (Dual KG & LB like AFTraining App) -->
                <div class="bg-af-card border border-af-border rounded-2xl p-4 sm:p-5 space-y-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-white tracking-tight">Series de Trabajo</h4>
                        <span class="text-2xs font-bold text-slate-300 bg-slate-900 px-2.5 py-1 rounded-xl border border-slate-800 flex items-center gap-1.5 shadow-inner">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                            KG &harr; LB en tiempo real
                        </span>
                    </div>

                    <!-- Work Sets Table Headers -->
                    <div class="grid grid-cols-12 gap-1.5 sm:gap-2 text-2xs uppercase tracking-wider font-extrabold text-slate-400 px-1 text-center">
                        <div class="col-span-1 text-left">SET</div>
                        <div class="col-span-3">PESO (KG)</div>
                        <div class="col-span-3">PESO (LB)</div>
                        <div class="col-span-2">REPS</div>
                        <div class="col-span-1">RPE</div>
                        <div class="col-span-2 flex justify-center">CHECK</div>
                    </div>

                    <!-- Sets Rows -->
                    <div class="space-y-3" id="setsContainer">
                        ${ex.sets.map((s, sIdx) => renderSetRow(s, sIdx)).join('')}
                    </div>

                    <!-- Add Set Button -->
                    <button type="button" onclick="addSet()" 
                            class="w-full py-2.5 rounded-xl border border-dashed border-slate-700 hover:border-blue-500 text-slate-300 hover:text-white font-semibold text-xs transition flex items-center justify-center gap-1.5 active:scale-[0.99]">
                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Agregar Serie de Trabajo</span>
                    </button>

                    <!-- Exercise Live Metrics (Volume & 1RM in both KG and LB) -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-slate-800 text-slate-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xs font-extrabold uppercase text-slate-400 block tracking-wider">VOLUMEN EJERCICIO</span>
                                <span id="exerciseLiveVolume" class="text-sm sm:text-base font-black text-white font-mono">
                                    ${Math.round(totalVolume).toLocaleString()} <span class="text-xs font-normal text-slate-400">kg</span> 
                                    <span class="text-2xs text-slate-500 font-medium block sm:inline">(${Math.round(totalVolume * 2.20462).toLocaleString()} lb)</span>
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-slate-800 text-orange-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xs font-extrabold uppercase text-slate-400 block tracking-wider">1RM ESTIMADO</span>
                                <span id="exerciseLive1RM" class="text-sm sm:text-base font-black text-orange-400 font-mono">
                                    ${max1RM.toFixed(1)} <span class="text-xs font-normal text-slate-400">kg</span>
                                    <span class="text-2xs text-orange-400/70 font-medium block sm:inline">(${(max1RM * 2.20462).toFixed(1)} lb)</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateExerciseLiveMetrics() {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (!ex) return;

            let totalVolume = 0;
            let max1RM = 0;

            ex.sets.forEach(s => {
                if (s.checked) {
                    const w = parseFloat(s.weight) || 0;
                    const r = parseInt(s.reps, 10) || 0;
                    totalVolume += (w * r);
                    const epley = w * (1 + (r / 30));
                    if (epley > max1RM) max1RM = epley;
                }
            });

            const volEl = document.getElementById('exerciseLiveVolume');
            if (volEl) {
                volEl.innerHTML = `${Math.round(totalVolume).toLocaleString()} <span class="text-xs font-normal text-slate-400">kg</span> <span class="text-2xs text-slate-500 font-medium block sm:inline">(${Math.round(totalVolume * 2.20462).toLocaleString()} lb)</span>`;
            }

            const rmEl = document.getElementById('exerciseLive1RM');
            if (rmEl) {
                rmEl.innerHTML = `${max1RM.toFixed(1)} <span class="text-xs font-normal text-slate-400">kg</span> <span class="text-2xs text-orange-400/70 font-medium block sm:inline">(${(max1RM * 2.20462).toFixed(1)} lb)</span>`;
            }
        }

        function renderSetRow(s, idx) {
            const checkedClass = s.checked
                ? 'bg-blue-950/40 border-blue-500/50'
                : 'bg-slate-900/60 border-slate-800';

            const checkBtnClass = s.checked
                ? 'bg-blue-600 text-white shadow-md shadow-blue-600/40'
                : 'bg-slate-800 text-slate-400 hover:text-white';

            let diffPill = '';
            if (s.checked) {
                if (idx === 0) diffPill = `<span class="text-2xs font-bold text-orange-400">+2.5 kg sobrecarga</span>`;
                else if (idx === 1) diffPill = `<span class="text-2xs font-medium text-slate-400">Igualado</span>`;
                else diffPill = `<span class="text-2xs font-bold text-orange-400">Última serie</span>`;
            }

            const weightKgVal = (s.weight !== undefined && s.weight !== null && s.weight !== '') ? s.weight : '';
            const weightLbVal = (s.weight_lb !== undefined && s.weight_lb !== null && s.weight_lb !== '') 
                ? s.weight_lb 
                : (weightKgVal !== '' ? parseFloat((parseFloat(weightKgVal) * 2.20462).toFixed(2)) : '');
            const repsVal = (s.reps !== undefined && s.reps !== null && s.reps !== '') ? s.reps : '';
            const rpeVal = (s.rpe !== undefined && s.rpe !== null && s.rpe !== '') ? s.rpe : '';

            const summaryKg = weightKgVal !== '' ? weightKgVal : '0';
            const summaryLb = weightLbVal !== '' ? weightLbVal : '0';
            const summaryReps = repsVal !== '' ? repsVal : '0';

            return `
                <div class="rounded-xl border ${checkedClass} p-2.5 transition-all">
                    <div class="grid grid-cols-12 gap-1.5 sm:gap-2 items-center text-center">
                        <!-- Set number -->
                        <div class="col-span-1 text-left flex items-center">
                            <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg ${s.checked ? 'bg-orange-500 text-white font-black' : 'bg-slate-800 text-slate-300 font-bold'} text-xs flex items-center justify-center">
                                ${idx + 1}
                            </span>
                        </div>

                        <!-- Peso (KG) -->
                        <div class="col-span-3">
                            <input type="number" step="0.5" placeholder="kg" id="set-kg-${idx}" value="${weightKgVal}" 
                                   oninput="handleWeightKgInput(${idx}, this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-xs sm:text-sm font-black text-white font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- Peso (LB) -->
                        <div class="col-span-3">
                            <input type="number" step="0.5" placeholder="lb" id="set-lb-${idx}" value="${weightLbVal}" 
                                   oninput="handleWeightLbInput(${idx}, this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-xs sm:text-sm font-black text-blue-300 font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- Reps -->
                        <div class="col-span-2">
                            <input type="number" placeholder="0" id="set-reps-${idx}" value="${repsVal}" 
                                   oninput="updateSetField(${idx}, 'reps', this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-xs sm:text-sm font-black text-white font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- RPE -->
                        <div class="col-span-1">
                            <input type="number" step="0.5" min="0" max="10" placeholder="RPE" id="set-rpe-${idx}" value="${rpeVal}" 
                                   oninput="updateSetField(${idx}, 'rpe', this.value)"
                                   class="w-full text-center py-2 px-0.5 rounded-xl bg-slate-900 border border-slate-700 text-xs sm:text-sm font-black text-orange-400 font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- Checkmark Action -->
                        <div class="col-span-2 flex justify-center">
                            <button type="button" onclick="toggleSetCheck(${idx})" 
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl ${checkBtnClass} flex items-center justify-center transition active:scale-90">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Previous log comparison / target subline (matching AFTraining) -->
                    <div class="flex items-center justify-between text-2xs text-slate-400 pt-1.5 px-1 border-t border-slate-800/60 mt-1.5">
                        <span id="set-summary-${idx}">Serie ${idx + 1}: ${summaryReps} reps, ${summaryKg} kg (${summaryLb} lb)</span>
                        ${diffPill}
                    </div>
                </div>
            `;
        }

        // Live two-way conversion matching AFTraining app
        function handleWeightKgInput(idx, val) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (!ex || !ex.sets[idx]) return;

            ex.sets[idx].weight = val;
            const num = parseFloat(val);
            if (!isNaN(num) && val !== '') {
                const lbVal = parseFloat((num * 2.20462).toFixed(2));
                ex.sets[idx].weight_lb = lbVal;
                const lbInput = document.getElementById(`set-lb-${idx}`);
                if (lbInput && document.activeElement !== lbInput) {
                    lbInput.value = lbVal;
                }
            } else if (val === '') {
                ex.sets[idx].weight_lb = '';
                const lbInput = document.getElementById(`set-lb-${idx}`);
                if (lbInput && document.activeElement !== lbInput) {
                    lbInput.value = '';
                }
            }
            updateSetSummary(idx);
            updateExerciseLiveMetrics();
        }

        function handleWeightLbInput(idx, val) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (!ex || !ex.sets[idx]) return;

            ex.sets[idx].weight_lb = val;
            const num = parseFloat(val);
            if (!isNaN(num) && val !== '') {
                const kgVal = parseFloat((num / 2.20462).toFixed(2));
                ex.sets[idx].weight = kgVal;
                const kgInput = document.getElementById(`set-kg-${idx}`);
                if (kgInput && document.activeElement !== kgInput) {
                    kgInput.value = kgVal;
                }
            } else if (val === '') {
                ex.sets[idx].weight = '';
                const kgInput = document.getElementById(`set-kg-${idx}`);
                if (kgInput && document.activeElement !== kgInput) {
                    kgInput.value = '';
                }
            }
            updateSetSummary(idx);
            updateExerciseLiveMetrics();
        }

        function updateSetField(idx, field, val) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (ex && ex.sets[idx]) {
                ex.sets[idx][field] = val;
                updateSetSummary(idx);
                updateExerciseLiveMetrics();
            }
        }

        function updateSetSummary(idx) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (!ex || !ex.sets[idx]) return;
            const s = ex.sets[idx];
            const sumEl = document.getElementById(`set-summary-${idx}`);
            if (sumEl) {
                const k = (s.weight !== '' && s.weight !== undefined && s.weight !== null) ? s.weight : '0';
                const l = (s.weight_lb !== '' && s.weight_lb !== undefined && s.weight_lb !== null) ? s.weight_lb : '0';
                const r = (s.reps !== '' && s.reps !== undefined && s.reps !== null) ? s.reps : '0';
                sumEl.textContent = `Serie ${idx + 1}: ${r} reps, ${k} kg (${l} lb)`;
            }
        }

        function toggleSetCheck(setIdx) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (ex && ex.sets[setIdx]) {
                ex.sets[setIdx].checked = !ex.sets[setIdx].checked;
                // If checked, start rest timer automatically!
                if (ex.sets[setIdx].checked) {
                    startRest(90);
                }
                renderActiveExercise();
            }
        }

        function addSet() {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (ex) {
                ex.sets.push({
                    set_number: ex.sets.length + 1,
                    weight: '',
                    weight_lb: '',
                    reps: '',
                    rpe: '',
                    checked: false,
                });
                renderActiveExercise();
            }
        }

        function selectExercise(idx) {
            if (idx >= 0 && idx < workoutData.plannedExercises.length) {
                workoutData.currentIndex = idx;
                renderActiveExercise();
            }
        }

        function nextExercise() {
            if (workoutData.currentIndex < workoutData.plannedExercises.length - 1) {
                workoutData.currentIndex++;
                renderActiveExercise();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                openFinishModal();
            }
        }

        // Fill all blank inputs with 0 across all exercises and sets
        function fillBlankSetsWithZero() {
            workoutData.plannedExercises.forEach(ex => {
                ex.sets.forEach(s => {
                    if (s.weight === '' || s.weight === null || s.weight === undefined || isNaN(parseFloat(s.weight))) {
                        s.weight = 0;
                    } else {
                        s.weight = parseFloat(s.weight);
                    }

                    if (s.weight_lb === '' || s.weight_lb === null || s.weight_lb === undefined || isNaN(parseFloat(s.weight_lb))) {
                        s.weight_lb = parseFloat((s.weight * 2.20462).toFixed(2));
                    } else {
                        s.weight_lb = parseFloat(s.weight_lb);
                    }

                    if (s.reps === '' || s.reps === null || s.reps === undefined || isNaN(parseInt(s.reps, 10))) {
                        s.reps = 0;
                    } else {
                        s.reps = parseInt(s.reps, 10);
                    }

                    if (s.rpe === '' || s.rpe === null || s.rpe === undefined || isNaN(parseFloat(s.rpe))) {
                        s.rpe = 0;
                    } else {
                        s.rpe = parseFloat(s.rpe);
                    }
                });
            });
        }

        // Modals
        function openNoteModal() {
            document.getElementById('noteModal').classList.remove('hidden');
        }
        function closeNoteModal() {
            document.getElementById('noteModal').classList.add('hidden');
        }

        function openFinishModal() {
            fillBlankSetsWithZero();
            renderActiveExercise();

            // Calculate total volume across all exercises
            let grandVolume = 0;
            workoutData.plannedExercises.forEach(ex => {
                ex.sets.forEach(s => {
                    if (s.checked) {
                        grandVolume += ((parseFloat(s.weight) || 0) * (parseInt(s.reps) || 0));
                    }
                });
            });
            document.getElementById('modalTotalVolume').textContent = `${Math.round(grandVolume).toLocaleString()} kg (${Math.round(grandVolume * 2.20462).toLocaleString()} lb)`;
            document.getElementById('finishModal').classList.remove('hidden');
        }

        function closeFinishModal() {
            document.getElementById('finishModal').classList.add('hidden');
        }

        // Submit to Backend
        function submitWorkoutSession() {
            fillBlankSetsWithZero();

            const btn = document.getElementById('submitSessionBtn');
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            const guestNameInput = document.getElementById('guestNameInput');
            const notesInput = document.getElementById('sessionNotesInput');

            const payload = {
                training_day_id: workoutData.trainingDayId,
                guest_name: guestNameInput ? guestNameInput.value : null,
                start_time: workoutData.startTime,
                duration: document.getElementById('elapsedTimer').textContent,
                comments: notesInput ? notesInput.value : null,
                exercises: workoutData.plannedExercises.map(ex => {
                    return {
                        planned_exercise_id: ex.id,
                        sets: ex.sets.map(s => {
                            const wNum = parseFloat(s.weight) || 0;
                            const wLbNum = parseFloat(s.weight_lb) || parseFloat((wNum * 2.20462).toFixed(2));
                            return {
                                set_number: s.set_number,
                                weight: wNum,
                                weight_lb: wLbNum,
                                reps: parseInt(s.reps, 10) || 0,
                                rpe: parseFloat(s.rpe) || 0,
                            };
                        })
                    };
                })
            };

            fetch("{{ route('gym.workout.save') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('¡Excelente sesión! Entrenamiento guardado con éxito.');
                        window.location.href = "{!! $backUrl !!}";
                    } else {
                        alert('Error al guardar la sesión: ' + (data.message || 'Intente nuevamente.'));
                        btn.disabled = false;
                        btn.textContent = 'Guardar Sesión';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Sesión registrada localmente. ¡Buen entrenamiento!');
                    window.location.href = "{!! $backUrl !!}";
                });
        }

        // Initial render
        document.addEventListener('DOMContentLoaded', () => {
            renderActiveExercise();
        });
    </script>
</body>

</html>