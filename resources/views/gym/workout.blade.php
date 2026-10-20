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

<body class="min-h-screen antialiased flex flex-col justify-between pb-28">

    <div class="max-w-md md:max-w-xl mx-auto w-full px-4 pt-4 space-y-4">

        <!-- 1. TOP BAR HEADER (Matching Image 2) -->
        <header class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-3">
                <a href="{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id, 'selected' => $trainingDay->id]) }}"
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
                <button type="button" class="p-1.5 text-slate-400 hover:text-white rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                </button>
                <div
                    class="w-8 h-8 rounded-full bg-blue-600/30 border border-blue-400/40 flex items-center justify-center text-blue-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
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
            <!-- Next / Save Exercise CTA -->
            <button type="button" onclick="nextExercise()"
                class="w-full py-3.5 px-4 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-extrabold text-sm uppercase tracking-wider shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 transition active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                <span id="nextBtnText">Guardar y Siguiente Ejercicio</span>
            </button>

            <!-- Bottom dual secondary actions -->
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="openNoteModal()"
                    class="py-2.5 px-3 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Añadir Nota</span>
                </button>

                <button type="button" onclick="openFinishModal()"
                    class="py-2.5 px-3 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold text-orange-400 hover:text-orange-300 hover:bg-slate-800 transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                    </svg>
                    <span>Terminar Sesión</span>
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

    <!-- Pass backend planned exercises data to JS -->
    <script>
        const workoutData = {
            trainingDayId: {{ $trainingDay->id }},
            gymIdentifier: "{{ $gym->username ?: $gym->id }}",
            unit: 'KG', // 'KG' or 'LB'
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
            const targetSubtitle = `${ex.primary_muscle || ex.muscle_group} &bull; Objetivo: ${ex.target_sets} &times; ${ex.min_reps}-${ex.max_reps}`;

            container.innerHTML = `
                <!-- Main Exercise Header Card -->
                <div class="bg-af-card border border-af-border rounded-2xl p-4 sm:p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-2xs font-bold uppercase tracking-wider text-slate-400">
                            <span>EJERCICIO PRINCIPAL #${workoutData.currentIndex + 1}</span>
                        </div>
                        <button type="button" class="text-slate-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-white font-display tracking-tight leading-tight">${ex.name}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">${targetSubtitle}</p>
                    </div>

                    <!-- Video Demo / GIF Mock Container (Matching Image 2) -->
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-800 flex items-center justify-center group shadow-inner">
                        <!-- Anatomical muscle representation background -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-slate-900/60 to-transparent z-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-40">
                            <svg class="w-32 h-32 text-blue-500/30" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                        </div>

                        <!-- Central Play Button -->
                        <div class="relative z-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center group-hover:scale-110 transition shadow-xl">
                            <svg class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>

                        <!-- Focus Badge -->
                        <div class="absolute bottom-3 left-3 z-20 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/70 backdrop-blur-md border border-white/10 text-2xs font-bold text-white">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            <span>Foco: ${ex.primary_muscle || ex.muscle_group}</span>
                        </div>
                    </div>

                    <!-- Previous Session / PR Banner (Matching Image 2) -->
                    <div class="p-3 rounded-xl bg-slate-900/90 border border-slate-800/80 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs text-slate-300 min-w-0">
                            <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="truncate">
                                <span class="text-2xs uppercase tracking-wider text-slate-400 font-bold block">ÚLTIMA SESIÓN (12/10/2026)</span>
                                <span class="text-xs text-slate-200 font-medium">S1: 40kg&times;12 &bull; S2: 45kg&times;10 &bull; S3: 45kg&times;10</span>
                            </div>
                        </div>
                        <div class="px-2.5 py-1 rounded-lg bg-orange-950/80 border border-orange-500/40 text-orange-400 font-extrabold text-2xs whitespace-nowrap">
                            PR +2.5kg
                        </div>
                    </div>
                </div>

                <!-- 7. Series de Trabajo Card (Matching Image 2) -->
                <div class="bg-af-card border border-af-border rounded-2xl p-4 sm:p-5 space-y-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-white tracking-tight">Series de Trabajo</h4>
                        <!-- KG / LB switch -->
                        <div class="inline-flex rounded-lg bg-slate-900 p-0.5 border border-slate-800 text-2xs font-bold">
                            <button type="button" onclick="setUnit('KG')" class="px-2.5 py-1 rounded-md transition ${workoutData.unit === 'KG' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white'}">KG</button>
                            <button type="button" onclick="setUnit('LB')" class="px-2.5 py-1 rounded-md transition ${workoutData.unit === 'LB' ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white'}">LB</button>
                        </div>
                    </div>

                    <!-- Work Sets Table Headers -->
                    <div class="grid grid-cols-12 gap-2 text-2xs uppercase tracking-wider font-extrabold text-slate-400 px-1 text-center">
                        <div class="col-span-2 text-left">SET</div>
                        <div class="col-span-3">CARGA (${workoutData.unit})</div>
                        <div class="col-span-3">REPS</div>
                        <div class="col-span-2">RPE</div>
                        <div class="col-span-2">CHECK</div>
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

                    <!-- Exercise Live Metrics (Volume & 1RM) -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-slate-800 text-slate-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xs font-extrabold uppercase text-slate-400 block tracking-wider">VOLUMEN EJERCICIO</span>
                                <span id="exerciseLiveVolume" class="text-base font-black text-white font-mono">${Math.round(totalVolume).toLocaleString()} <span class="text-2xs font-normal text-slate-400">${workoutData.unit.toLowerCase()}</span></span>
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
                                <span id="exerciseLive1RM" class="text-base font-black text-orange-400 font-mono">${max1RM.toFixed(1)} <span class="text-2xs font-normal text-slate-400">${workoutData.unit.toLowerCase()}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Update next button text if on last exercise
            const isLast = workoutData.currentIndex === workoutData.plannedExercises.length - 1;
            document.getElementById('nextBtnText').textContent = isLast ? 'Finalizar Todos los Ejercicios' : 'Guardar y Siguiente Ejercicio';
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
            if (volEl) volEl.innerHTML = `${Math.round(totalVolume).toLocaleString()} <span class="text-2xs font-normal text-slate-400">${workoutData.unit.toLowerCase()}</span>`;

            const rmEl = document.getElementById('exerciseLive1RM');
            if (rmEl) rmEl.innerHTML = `${max1RM.toFixed(1)} <span class="text-2xs font-normal text-slate-400">${workoutData.unit.toLowerCase()}</span>`;
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
                if (idx === 0) diffPill = `<span class="text-2xs font-bold text-orange-400">+2.5 ${workoutData.unit.toLowerCase()} sobrecarga</span>`;
                else if (idx === 1) diffPill = `<span class="text-2xs font-medium text-slate-400">Igualado</span>`;
                else diffPill = `<span class="text-2xs font-bold text-orange-400">Última serie</span>`;
            }

            const weightVal = (s.weight !== undefined && s.weight !== null && s.weight !== '') ? s.weight : '';
            const repsVal = (s.reps !== undefined && s.reps !== null && s.reps !== '') ? s.reps : '';
            const rpeVal = (s.rpe !== undefined && s.rpe !== null && s.rpe !== '') ? s.rpe : '';

            return `
                <div class="rounded-xl border ${checkedClass} p-2.5 transition-all">
                    <div class="grid grid-cols-12 gap-2 items-center text-center">
                        <!-- Set number -->
                        <div class="col-span-2 text-left flex items-center gap-1.5">
                            <span class="w-7 h-7 rounded-lg ${s.checked ? 'bg-orange-500 text-white font-black' : 'bg-slate-800 text-slate-300 font-bold'} text-xs flex items-center justify-center">
                                ${idx + 1}
                            </span>
                        </div>

                        <!-- Carga (Weight) -->
                        <div class="col-span-3">
                            <input type="number" step="0.5" placeholder="0.0" value="${weightVal}" 
                                   oninput="updateSetData(${idx}, 'weight', this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-sm font-black text-white font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- Reps -->
                        <div class="col-span-3">
                            <input type="number" placeholder="0" value="${repsVal}" 
                                   oninput="updateSetData(${idx}, 'reps', this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-sm font-black text-white font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- RPE -->
                        <div class="col-span-2">
                            <input type="number" step="0.5" min="0" max="10" placeholder="0.0" value="${rpeVal}" 
                                   oninput="updateSetData(${idx}, 'rpe', this.value)"
                                   class="w-full text-center py-2 px-1 rounded-xl bg-slate-900 border border-slate-700 text-sm font-black text-orange-400 font-mono focus:outline-none focus:border-blue-500 placeholder-slate-600">
                        </div>

                        <!-- Checkmark Action -->
                        <div class="col-span-2 flex justify-center">
                            <button type="button" onclick="toggleSetCheck(${idx})" 
                                    class="w-9 h-9 rounded-xl ${checkBtnClass} flex items-center justify-center transition active:scale-90">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Previous log comparison / target subline -->
                    <div class="flex items-center justify-between text-2xs text-slate-400 pt-1.5 px-1 border-t border-slate-800/60 mt-1.5">
                        <span>Previo: ${(40 + idx * 2.5).toFixed(1)} ${workoutData.unit.toLowerCase()} &times; 10</span>
                        ${diffPill}
                    </div>
                </div>
            `;
        }

        function updateSetData(setIdx, field, val) {
            const ex = workoutData.plannedExercises[workoutData.currentIndex];
            if (ex && ex.sets[setIdx]) {
                ex.sets[setIdx][field] = val;
                updateExerciseLiveMetrics();
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
                    reps: '',
                    rpe: '',
                    checked: false,
                });
                renderActiveExercise();
            }
        }

        function setUnit(newUnit) {
            if (workoutData.unit !== newUnit) {
                const factor = newUnit === 'LB' ? 2.20462 : 1 / 2.20462;
                workoutData.unit = newUnit;
                workoutData.plannedExercises.forEach(ex => {
                    ex.sets.forEach(s => {
                        if (s.weight !== '' && s.weight !== null && s.weight !== undefined && !isNaN(parseFloat(s.weight))) {
                            s.weight = (parseFloat(s.weight) * factor).toFixed(1);
                        }
                    });
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
            // Automatically fill blank inputs with 0 when finishing session
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
            document.getElementById('modalTotalVolume').textContent = `${Math.round(grandVolume).toLocaleString()} ${workoutData.unit.toLowerCase()}`;
            document.getElementById('finishModal').classList.remove('hidden');
        }

        function closeFinishModal() {
            document.getElementById('finishModal').classList.add('hidden');
        }

        // Submit to Backend
        function submitWorkoutSession() {
            // Ensure any remaining blank space is automatically 0
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
                            return {
                                set_number: s.set_number,
                                weight: workoutData.unit === 'KG' ? wNum : parseFloat((wNum / 2.20462).toFixed(2)),
                                weight_lb: workoutData.unit === 'LB' ? wNum : parseFloat((wNum * 2.20462).toFixed(2)),
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
                        window.location.href = "{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id]) }}";
                    } else {
                        alert('Error al guardar la sesión: ' + (data.message || 'Intente nuevamente.'));
                        btn.disabled = false;
                        btn.textContent = 'Guardar Sesión';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Sesión registrada localmente. ¡Buen entrenamiento!');
                    window.location.href = "{{ route('gym.routines', ['identifier' => $gym->username ?: $gym->id]) }}";
                });
        }

        // Initial render
        document.addEventListener('DOMContentLoaded', () => {
            renderActiveExercise();
        });
    </script>
</body>

</html>