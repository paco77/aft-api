@extends('layouts.admin')

@section('header')
    Ficha del Atleta &middot; {{ $user->name }}
@endsection

@section('content')
    <!-- Google Fonts & Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0..1,0" rel="stylesheet"/>

    <style>
        .af-athlete-dashboard {
            --af-bg: #0f131d;
            --af-surface: #0f131d;
            --af-surface-low: #171c25;
            --af-surface-container: #1b2029;
            --af-surface-high: #262a34;
            --af-surface-highest: #31353f;
            --af-surface-bright: #353944;
            --af-primary: #adc6ff;
            --af-primary-container: #4d8eff;
            --af-on-primary: #002e6a;
            --af-on-primary-container: #00285d;
            --af-secondary: #ffb690;
            --af-secondary-container: #ec6a06;
            --af-on-secondary-container: #4a1c00;
            --af-on-surface: #dfe2f0;
            --af-on-surface-variant: #c2c6d6;
            --af-outline: #8c909f;
            --af-outline-variant: #424754;
            background-color: var(--af-bg);
            color: var(--af-on-surface);
            font-family: 'Inter', sans-serif;
        }
        .af-athlete-dashboard .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .af-athlete-dashboard .text-metric-val {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            line-height: 28px;
            font-weight: 700;
        }
        .af-athlete-dashboard .text-headline-lg {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            line-height: 36px;
            letter-spacing: -0.01em;
            font-weight: 700;
        }
        .af-athlete-dashboard .text-headline-md {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            line-height: 26px;
            font-weight: 700;
        }
        .af-athlete-dashboard .text-title-sm {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            line-height: 22px;
            font-weight: 600;
        }
        .af-athlete-dashboard .text-label-caps {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            line-height: 14px;
            letter-spacing: 0.08em;
            font-weight: 800;
        }
        .af-athlete-dashboard .text-body-md {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            line-height: 20px;
            font-weight: 400;
        }
        .af-athlete-dashboard .text-body-sm {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            line-height: 16px;
            font-weight: 400;
        }
        @media print {
            #admin-sidebar, header, .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
                overflow: visible !important;
            }
            .af-athlete-dashboard {
                border-radius: 0 !important;
            }
        }
    </style>

    @php
        $defaultProfileImg = 'https://lh3.googleusercontent.com/aida-public/AB6AXuDQXtpXvmAuiUeUn-B_9sHPaaheUzZXHyN41pKEb2KFwdIx2W2N-t3LOBgH_P12qFyZ_hcXfdinypNsuxEfBuJHzps9YB270TCHVTEKfoeYLqQHemGmoYvn6T63RGGOtTMsSjxXkW2EhezTaa93CB--KR0P6-XiOG2gQEecZUsJbi7M9nimg02V_Pzd6oCg68RDAPK4niaWOm6kDpFFHVi_CCagVS4V8u6F2K2XD2tC';
        $profilePhotoUrl = $user->profile_photo_path ? Storage::disk('s3')->url($user->profile_photo_path) : null;

        // Fotos del Mes Seleccionado (o Fotos Iniciales de Registro como respaldo)
        $monthFrontPhoto = ($selectedProgress && $selectedProgress->front_photo_path)
            ? Storage::disk('s3')->url($selectedProgress->front_photo_path)
            : ($user->front_photo ? Storage::disk('s3')->url($user->front_photo) : null);
        $monthSidePhoto = ($selectedProgress && $selectedProgress->side_photo_path)
            ? Storage::disk('s3')->url($selectedProgress->side_photo_path)
            : ($user->side_photo ? Storage::disk('s3')->url($user->side_photo) : null);
        $monthBackPhoto = ($selectedProgress && $selectedProgress->back_photo_path)
            ? Storage::disk('s3')->url($selectedProgress->back_photo_path)
            : ($user->back_photo ? Storage::disk('s3')->url($user->back_photo) : null);

        // Fotos Iniciales de Registro para comparar
        $initialFrontPhoto = $user->front_photo
            ? Storage::disk('s3')->url($user->front_photo)
            : (($initialProgress && $initialProgress->front_photo_path) ? Storage::disk('s3')->url($initialProgress->front_photo_path) : $monthFrontPhoto);
        $initialSidePhoto = $user->side_photo
            ? Storage::disk('s3')->url($user->side_photo)
            : (($initialProgress && $initialProgress->side_photo_path) ? Storage::disk('s3')->url($initialProgress->side_photo_path) : $monthSidePhoto);
        $initialBackPhoto = $user->back_photo
            ? Storage::disk('s3')->url($user->back_photo)
            : (($initialProgress && $initialProgress->back_photo_path) ? Storage::disk('s3')->url($initialProgress->back_photo_path) : $monthBackPhoto);

        $selectedCheckinDate = $selectedProgress && $selectedProgress->recorded_at
            ? \Carbon\Carbon::parse($selectedProgress->recorded_at)->format('d/m/Y')
            : ($user->created_at ? $user->created_at->format('d/m/Y') : now()->format('d/m/Y'));
        $initialCheckinDate = $user->created_at
            ? $user->created_at->format('d/m/Y')
            : ($initialProgress && $initialProgress->recorded_at ? \Carbon\Carbon::parse($initialProgress->recorded_at)->format('d/m/Y') : $selectedCheckinDate);

        $athleteIdFormatted = '#AF-' . str_pad((string)$user->id, 4, '0', STR_PAD_LEFT);
        $programPhase = $user->objectives ?: 'Sin objetivo especificado';
        $programTitle = $monthPlan ? ($monthPlan->split_type ?: ($monthPlan->month . ' ' . $monthPlan->year)) : ($latestPlan ? ($latestPlan->split_type ?: ($latestPlan->month . ' ' . $latestPlan->year)) : 'Personalizado');
        $coachName = $user->coach ? $user->coach->name : 'Sin Coach Asignado';
        $selectedMonthLabel = $selectedMonthObj['label'] ?? now()->translatedFormat('F Y');
    @endphp

    <!-- Barra Superior de Navegación -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4 no-print">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Volver a Lista de Usuarios</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            @if($user->role === 'client' || $user->role === 'independiente')
                <a href="{{ route('admin.users.progress', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-200/80 hover:bg-slate-300 text-slate-800 text-xs font-semibold transition">
                    <span class="material-symbols-outlined text-[16px]">monitoring</span>
                    <span>Bitácora de Progreso ({{ $progressLogs->count() }})</span>
                </a>
                <a href="{{ route('admin.users.workouts', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-200/80 hover:bg-slate-300 text-slate-800 text-xs font-semibold transition">
                    <span class="material-symbols-outlined text-[16px]">fitness_center</span>
                    <span>Todas las Sesiones ({{ $allWorkoutSessions->count() }})</span>
                </a>
            @endif
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                <span>Editar Perfil</span>
            </a>
        </div>
    </div>

    <!-- Contenedor Principal de la Ficha del Atleta -->
    <div class="af-athlete-dashboard rounded-2xl p-4 sm:p-6 lg:p-8 shadow-2xl space-y-8 overflow-hidden">

        <!-- ========================================================================= -->
        <!-- 1. FICHA DEL ATLETA & DATOS INICIALES DE REGISTRO                         -->
        <!-- ========================================================================= -->
        <section class="bg-[#1b2029] rounded-xl p-6 shadow-xl relative overflow-hidden border border-white/5">
            <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#adc6ff]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-80 h-80 bg-[#ec6a06]/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col xl:flex-row gap-8 justify-between items-start xl:items-center">
                <!-- Identificación del Atleta -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <div class="relative">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden shadow-md bg-[#262a34] shrink-0 border border-white/10 flex items-center justify-center">
                            @if($profilePhotoUrl)
                                <img class="w-full h-full object-cover"
                                     alt="{{ $user->name }}"
                                     src="{{ $profilePhotoUrl }}"
                                     onerror="this.src='{{ $defaultProfileImg }}'"/>
                            @else
                                <span class="text-4xl font-extrabold text-[#adc6ff] font-jakarta">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="absolute -bottom-2 -right-2 bg-[#ec6a06] text-[#4a1c00] text-label-caps px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow font-extrabold">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-headline-lg text-[#dfe2f0]">{{ $user->name }}</h1>
                            <span class="bg-[#262a34] text-[#c2c6d6] text-label-caps px-2.5 py-1 rounded-full uppercase">
                                ID: {{ $athleteIdFormatted }}
                            </span>
                            <span class="bg-[#adc6ff]/20 text-[#adc6ff] text-label-caps px-3 py-1 rounded-full uppercase font-bold tracking-wider">
                                {{ $programPhase }}
                            </span>
                        </div>

                        <p class="text-body-md text-[#c2c6d6] flex flex-wrap items-center gap-3">
                            <span>{{ $user->age ? $user->age . ' años' : 'Edad no registrada' }}</span>
                            <span class="w-1 h-1 rounded-full bg-[#8c909f]"></span>
                            <span>Programa: {{ $programTitle }}</span>
                            <span class="w-1 h-1 rounded-full bg-[#8c909f]"></span>
                            <span class="text-[#ffb690] text-title-sm">Registro: {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : 'N/A' }}</span>
                        </p>

                        <div class="pt-1.5 flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-[#adc6ff]" style="font-variation-settings: 'FILL' 1;">verified</span>
                                <span class="text-body-sm text-[#8c909f]">Coach Asignado: <strong class="text-[#dfe2f0]">{{ $coachName }}</strong></span>
                            </div>
                            @if($user->phone)
                                <div class="flex items-center gap-1 text-body-sm text-[#8c909f]">
                                    <span class="material-symbols-outlined text-[15px] text-[#adc6ff]">call</span>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-1 text-body-sm text-[#8c909f]">
                                <span class="material-symbols-outlined text-[15px] text-[#adc6ff]">mail</span>
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas del Coach -->
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full xl:w-auto no-print">
                    <button type="button" onclick="openNewMeasurementModal()"
                        class="flex-1 sm:flex-initial h-12 px-5 bg-[#262a34] hover:bg-[#353944] text-[#adc6ff] text-title-sm rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer border border-white/5">
                        <span class="material-symbols-outlined text-[20px]">add_chart</span>
                        <span>Nueva Medición</span>
                    </button>

                    <button type="button" onclick="window.print()"
                        class="flex-1 sm:flex-initial h-12 px-5 bg-[#262a34] hover:bg-[#353944] text-[#dfe2f0] text-title-sm rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer border border-white/5">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        <span>Reporte PDF</span>
                    </button>

                    <a href="{{ route('admin.users.nutrition-plans', $user) }}"
                        class="w-full sm:w-auto h-12 px-6 bg-[#ec6a06] hover:bg-[#ec6a06]/90 text-[#4a1c00] text-title-sm rounded-lg flex items-center justify-center gap-2 transition-all shadow-md font-bold">
                        <span class="material-symbols-outlined text-[20px]">tune</span>
                        <span>Ajustar Nutrición</span>
                    </a>
                </div>
            </div>

            <!-- Panel de Datos Iniciales de Registro -->
            <div class="mt-8 pt-4 border-t border-white/10">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-label-caps text-[#adc6ff] uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">assignment_ind</span>
                        <span>Datos Iniciales de Registro (Línea Base del Atleta)</span>
                    </span>
                    <span class="text-body-sm text-[#8c909f]">Usuario: &#64;{{ $user->username }}</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 bg-[#171c25]/80 rounded-xl p-4 border border-white/5">
                    <!-- Item 1: Peso Inicial de Registro -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Peso Inicial</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-metric-val text-[#dfe2f0]">{{ $initialWeight > 0 ? number_format($initialWeight, 1) : 'N/A' }}</span>
                            @if($initialWeight > 0)
                                <span class="text-body-sm text-[#c2c6d6]">kg</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 text-body-sm text-[#adc6ff]">
                            <span class="material-symbols-outlined text-[16px]">flag</span>
                            <span>Peso al registrarse</span>
                        </div>
                    </div>

                    <!-- Item 2: Estatura & IMC Inicial -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Estatura &amp; IMC</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-metric-val text-[#dfe2f0]">{{ $heightCm > 0 ? round($heightCm) : 'N/A' }}</span>
                            @if($heightCm > 0)
                                <span class="text-body-sm text-[#c2c6d6]">cm</span>
                            @endif
                        </div>
                        <span class="text-body-sm text-[#c2c6d6]">
                            IMC Inicial: {{ $initialBmi > 0 ? number_format($initialBmi, 1) . ' kg/m²' : 'N/A' }}
                        </span>
                    </div>

                    <!-- Item 3: Edad y Nivel / Tiempo Entrenando -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Tiempo Entrenando</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-metric-val text-[#dfe2f0] truncate">{{ $user->training_time ?: 'Inicial' }}</span>
                        </div>
                        <span class="text-body-sm text-[#c2c6d6]">Experiencia reportada</span>
                    </div>

                    <!-- Item 4: Grasa / Masa Magra Inicial -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">% Grasa / Magra Ini.</span>
                        <div class="flex items-baseline gap-1.5">
                            @if($initialBodyFat)
                                <span class="text-metric-val text-[#dfe2f0]">{{ number_format($initialBodyFat, 1) }}</span>
                                <span class="text-body-sm text-[#c2c6d6]">%</span>
                            @else
                                <span class="text-title-sm text-[#c2c6d6]">Sin medición</span>
                            @endif
                        </div>
                        <span class="text-body-sm text-[#ffb690]">
                            {{ $initialLeanMass ? 'Magra: ' . number_format($initialLeanMass, 1) . ' kg' : 'Registra en Nueva Medición' }}
                        </span>
                    </div>

                    <!-- Item 5: Gasto TDEE Inicial -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Gasto TDEE Base</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-metric-val text-[#dfe2f0]">{{ $initialTdee > 0 ? number_format($initialTdee) : 'N/A' }}</span>
                            @if($initialTdee > 0)
                                <span class="text-body-sm text-[#c2c6d6]">kcal</span>
                            @endif
                        </div>
                        <span class="text-body-sm text-[#8c909f]">Metabolismo basal est.</span>
                    </div>

                    <!-- Item 6: Target Calórico / Objetivo Inicial -->
                    <div class="flex flex-col space-y-1">
                        <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Target Calórico</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-metric-val text-[#ffb690]">{{ $initialTargetCalories > 0 ? number_format($initialTargetCalories) : 'N/A' }}</span>
                            @if($initialTargetCalories > 0)
                                <span class="text-body-sm text-[#c2c6d6]">kcal</span>
                            @endif
                        </div>
                        <div class="inline-flex items-center gap-1.5 text-body-sm text-[#ffb690] font-medium truncate">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ec6a06] shrink-0"></span>
                            <span class="truncate">{{ $user->objectives ?: 'Meta general' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 2. SELECTOR DE MES A ANALIZAR & BIOMETRÍA DEL MES                         -->
        <!-- ========================================================================= -->
        <section class="space-y-4">
            <!-- Header con Selector de Mes para Analizar -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-[#1b2029] p-5 rounded-xl border border-white/5 shadow-md">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#adc6ff]">calendar_month</span>
                        <h2 class="text-headline-md text-[#dfe2f0]">Análisis Mensual: {{ $selectedMonthLabel }}</h2>
                    </div>
                    <p class="text-body-md text-[#c2c6d6]">Selecciona el mes para analizar sus métricas biométricas, fotografías de progreso y sesiones realizadas</p>
                </div>

                <!-- Botones y Selector de Meses Disponibles -->
                <div class="flex flex-wrap items-center gap-2 no-print">
                    <div class="flex flex-wrap items-center bg-[#171c25] p-1 rounded-xl border border-white/5 gap-1">
                        @foreach($availableMonths->slice(-4) as $mItem)
                            <a href="{{ route('admin.users.show', ['user' => $user->id, 'month' => $mItem['key']]) }}"
                               class="px-3.5 py-1.5 rounded-lg text-body-sm transition-all {{ $mItem['key'] === $selectedMonthKey ? 'bg-[#4d8eff] text-[#00285d] font-bold shadow-sm' : 'text-[#c2c6d6] hover:text-[#dfe2f0] hover:bg-[#262a34]' }}">
                                {{ $mItem['label'] }}
                            </a>
                        @endforeach
                    </div>

                    @if($availableMonths->count() > 4)
                        <select onchange="window.location.href = this.value"
                                class="bg-[#262a34] text-[#dfe2f0] text-body-sm rounded-xl px-3 py-2 border border-white/10 focus:outline-none focus:border-[#adc6ff]">
                            @foreach($availableMonths as $mItem)
                                <option value="{{ route('admin.users.show', ['user' => $user->id, 'month' => $mItem['key']]) }}"
                                        {{ $mItem['key'] === $selectedMonthKey ? 'selected' : '' }}>
                                    {{ $mItem['label'] }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>

            <!-- Resumen de Datos del Mes Seleccionado + Gráfico de Evolución -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Columna Izquierda (8 cols): Datos del Mes Seleccionado y Gráfico -->
                <div class="lg:col-span-8 bg-[#1b2029] p-6 rounded-xl shadow-md flex flex-col justify-between border border-white/5 space-y-6">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#262a34] flex items-center justify-center text-[#adc6ff]">
                                <span class="material-symbols-outlined text-[20px]">show_chart</span>
                            </div>
                            <div>
                                <span class="text-title-sm text-[#dfe2f0]">Biometría Registrada en {{ $selectedMonthLabel }}</span>
                                <p class="text-body-sm text-[#8c909f]">
                                    @if($monthProgressLogs->count() > 0)
                                        {{ $monthProgressLogs->count() }} evaluación(es) registrada(s) en este mes (Última: {{ $selectedCheckinDate }})
                                    @else
                                        Mostrando última medición disponible hasta {{ $selectedMonthLabel }} ({{ $selectedCheckinDate }})
                                    @endif
                                </p>
                            </div>
                        </div>

                        <button type="button" onclick="openNewMeasurementModal()" class="text-xs bg-[#262a34] hover:bg-[#353944] text-[#adc6ff] px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1 transition cursor-pointer no-print">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Agregar Medición al Mes</span>
                        </button>
                    </div>

                    <!-- Tarjetas de Biometría del Mes Seleccionado -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-[#171c25] p-3.5 rounded-xl border border-white/5">
                            <span class="text-label-caps text-[#8c909f] uppercase block">Peso del Mes</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-metric-val text-[#dfe2f0]">{{ $monthWeight > 0 ? number_format($monthWeight, 1) : 'N/A' }}</span>
                                @if($monthWeight > 0)
                                    <span class="text-body-sm text-[#c2c6d6]">kg</span>
                                @endif
                            </div>
                            <span class="text-body-sm {{ $weightDiff <= 0 ? 'text-[#adc6ff]' : 'text-[#ffb690]' }} block mt-1">
                                @if($weightDiff != 0)
                                    {{ $weightDiff > 0 ? '+' . number_format($weightDiff, 1) : number_format($weightDiff, 1) }} kg vs previo
                                @else
                                    Sin variación
                                @endif
                            </span>
                        </div>

                        <div class="bg-[#171c25] p-3.5 rounded-xl border border-white/5">
                            <span class="text-label-caps text-[#8c909f] uppercase block">% Grasa del Mes</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-metric-val text-[#dfe2f0]">{{ $monthBodyFat ? number_format($monthBodyFat, 1) . '%' : 'N/A' }}</span>
                            </div>
                            <span class="text-body-sm text-[#adc6ff] block mt-1">
                                {{ $bodyFatDiff != 0 ? ($bodyFatDiff > 0 ? '+' : '') . number_format($bodyFatDiff, 1) . '% delta' : 'Control mensual' }}
                            </span>
                        </div>

                        <div class="bg-[#171c25] p-3.5 rounded-xl border border-white/5">
                            <span class="text-label-caps text-[#8c909f] uppercase block">Masa Magra</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-metric-val text-[#dfe2f0]">{{ $monthLeanMass ? number_format($monthLeanMass, 1) . ' kg' : 'N/A' }}</span>
                            </div>
                            <span class="text-body-sm text-[#ffb690] block mt-1">Composición magra</span>
                        </div>

                        <div class="bg-[#171c25] p-3.5 rounded-xl border border-white/5">
                            <span class="text-label-caps text-[#8c909f] uppercase block">Diferencia vs Inicio</span>
                            @php
                                $totalDiffFromInitial = ($monthWeight > 0 && $initialWeight > 0) ? round($monthWeight - $initialWeight, 1) : 0;
                            @endphp
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-metric-val {{ $totalDiffFromInitial <= 0 ? 'text-[#adc6ff]' : 'text-[#ffb690]' }}">
                                    {{ $totalDiffFromInitial > 0 ? '+' . number_format($totalDiffFromInitial, 1) : number_format($totalDiffFromInitial, 1) }}
                                </span>
                                <span class="text-body-sm text-[#c2c6d6]">kg</span>
                            </div>
                            <span class="text-body-sm text-[#8c909f] block mt-1">Inicial: {{ number_format($initialWeight, 1) }} kg</span>
                        </div>
                    </div>

                    <!-- Si el registro del mes tiene medidas adicionales personalizadas (Cintura, Pecho, Brazo, etc.) -->
                    @if($selectedProgress && is_array($selectedProgress->measurements) && count($selectedProgress->measurements) > 0)
                        <div class="bg-[#171c25]/70 p-4 rounded-xl border border-white/5 space-y-2">
                            <span class="text-label-caps text-[#adc6ff] uppercase tracking-wider block">Medidas Corporales Registradas ({{ $selectedCheckinDate }})</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                                @foreach($selectedProgress->measurements as $mKey => $mVal)
                                    <div class="bg-[#262a34] px-3 py-2 rounded-lg">
                                        <span class="text-[11px] text-[#8c909f] block truncate">{{ ucfirst($mKey) }}</span>
                                        <span class="text-title-sm text-[#dfe2f0] font-bold">{{ $mVal }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Gráfico SVG de Evolución de Peso -->
                    @php
                        // Construir puntos del gráfico con los logs reales ordenados cronológicamente
                        $chronologicalLogs = $progressLogs->reverse()->values();
                        if ($chronologicalLogs->isEmpty() && $initialWeight > 0) {
                            $chartWeights = [$initialWeight, $initialWeight];
                            $chartLabels = ['Inicio', 'Actual'];
                        } elseif ($chronologicalLogs->count() === 1) {
                            $wOnly = (float) ($chronologicalLogs->first()->weight ?: $initialWeight ?: 70);
                            $chartWeights = [$initialWeight ?: $wOnly, $wOnly];
                            $chartLabels = ['Registro Inicial', \Carbon\Carbon::parse($chronologicalLogs->first()->recorded_at)->format('d/m/Y')];
                        } else {
                            $chartWeights = $chronologicalLogs->take(8)->map(fn($l) => (float)($l->weight ?: $initialWeight ?: 70))->toArray();
                            $chartLabels = $chronologicalLogs->take(8)->map(fn($l) => \Carbon\Carbon::parse($l->recorded_at)->format('d M'))->toArray();
                        }

                        $minW = min($chartWeights) - 2;
                        $maxW = max($chartWeights) + 2;
                        $rangeW = max(1, $maxW - $minW);
                        $countPts = count($chartWeights);
                        $svgPoints = [];
                        foreach ($chartWeights as $i => $wVal) {
                            $x = $countPts > 1 ? round(($i / ($countPts - 1)) * 600) : 300;
                            $y = round(150 - ((($wVal - $minW) / $rangeW) * 110));
                            $svgPoints[] = "{$x},{$y}";
                        }
                        $polylineStr = implode(' ', $svgPoints);
                        $polygonStr = $polylineStr . ' 600,165 0,165';
                    @endphp
                    <div class="w-full h-44 relative pt-2">
                        <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 600 175">
                            <defs>
                                <linearGradient id="primaryArea" x1="0%" x2="0%" y1="0%" y2="100%">
                                    <stop offset="0%" stop-color="#4d8eff" stop-opacity="0.35"></stop>
                                    <stop offset="100%" stop-color="#4d8eff" stop-opacity="0.0"></stop>
                                </linearGradient>
                            </defs>
                            <line stroke="#31353f" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="600" y1="30" y2="30"></line>
                            <line stroke="#31353f" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="600" y1="85" y2="85"></line>
                            <line stroke="#31353f" stroke-dasharray="4 4" stroke-width="1" x1="0" x2="600" y1="135" y2="135"></line>
                            <line stroke="#31353f" stroke-width="1" x1="0" x2="600" y1="165" y2="165"></line>
                            <polygon fill="url(#primaryArea)" points="{{ $polygonStr }}"></polygon>
                            <polyline fill="none" points="{{ $polylineStr }}" stroke="#4d8eff" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></polyline>
                            @foreach($svgPoints as $pt)
                                @php [$px, $py] = explode(',', $pt); @endphp
                                <circle cx="{{ $px }}" cy="{{ $py }}" fill="#4d8eff" r="4.5"></circle>
                            @endforeach
                        </svg>
                    </div>
                    <div class="flex justify-between items-center text-[#8c909f] text-label-caps px-1">
                        @foreach($chartLabels as $lbl)
                            <span>{{ $lbl }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Columna Derecha (4 cols): Bloques Analíticos de Carga de Fuerza del Mes -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <!-- KPI 1: Tonelaje Mensual Acumulado -->
                    <div class="bg-[#1b2029] p-5 rounded-xl shadow-md flex items-center justify-between border border-white/5 flex-1">
                        <div class="space-y-1">
                            <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Tonelaje ({{ $selectedMonthLabel }})</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-headline-lg text-[#dfe2f0]">{{ number_format($monthlyTonnage) }}</span>
                                <span class="text-body-sm text-[#c2c6d6]">kg</span>
                            </div>
                            <p class="text-body-sm text-[#ffb690] font-medium">
                                {{ $workoutSessions->count() }} sesión(es) completada(s) en el mes
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#262a34] flex items-center justify-center text-[#ffb690]">
                            <span class="material-symbols-outlined text-[24px]">fitness_center</span>
                        </div>
                    </div>

                    <!-- KPI 2: RPE Promedio Global del Mes -->
                    <div class="bg-[#1b2029] p-5 rounded-xl shadow-md flex items-center justify-between border border-white/5 flex-1">
                        <div class="space-y-1">
                            <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">RPE Promedio ({{ $selectedMonthLabel }})</span>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-headline-lg text-[#dfe2f0]">{{ $globalRpe ? number_format($globalRpe, 1) : 'N/A' }}</span>
                                @if($globalRpe)
                                    <span class="text-body-sm text-[#adc6ff] font-semibold">/ 10</span>
                                @endif
                            </div>
                            <p class="text-body-sm text-[#c2c6d6]">
                                {{ $globalRpe ? 'Esfuerzo percibido registrado' : 'Sin RPE registrado este mes' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#262a34] flex items-center justify-center text-[#adc6ff]">
                            <span class="material-symbols-outlined text-[24px]">speed</span>
                        </div>
                    </div>

                    <!-- KPI 3: Tasa de Cumplimiento de Sesiones del Mes -->
                    <div class="bg-[#1b2029] p-5 rounded-xl shadow-md flex items-center justify-between border border-white/5 flex-1">
                        <div class="space-y-1">
                            <span class="text-label-caps text-[#8c909f] uppercase tracking-wider">Cumplimiento de Sesiones</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-headline-lg text-[#dfe2f0]">{{ $completedSessionsCount }}</span>
                                <span class="text-headline-md text-[#c2c6d6]">/ {{ $prescribedSessionsCount }}</span>
                            </div>
                            <p class="text-body-sm text-[#adc6ff]">{{ $adherenceRate }}% de asistencia en {{ $selectedMonthLabel }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#262a34] flex items-center justify-center text-[#adc6ff]">
                            <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">task_alt</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 3. INSPECCIÓN FOTOGRÁFICA 360° (LOS 3 PERFILES DEL MES SELECCIONADO)      -->
        <!-- ========================================================================= -->
        <section class="bg-[#1b2029] rounded-xl p-6 shadow-xl space-y-6 border border-white/5">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[22px] text-[#ffb690]">photo_camera</span>
                        <h2 class="text-headline-md text-[#dfe2f0]">Inspección Fotográfica 360° &middot; {{ $selectedMonthLabel }}</h2>
                    </div>
                    <p class="text-body-md text-[#c2c6d6]">Fotografías de los 3 perfiles del atleta (Frente, Perfil Lateral y Espalda)</p>
                </div>

                <!-- Selector Comparativo: Fotos Iniciales vs Fotos del Mes Seleccionado -->
                <div class="flex items-center bg-[#262a34] p-1 rounded-xl no-print">
                    <button type="button"
                        id="btn-photo-initial"
                        data-front="{{ $initialFrontPhoto }}"
                        data-side="{{ $initialSidePhoto }}"
                        data-back="{{ $initialBackPhoto }}"
                        data-date="{{ $initialCheckinDate }}"
                        data-weight="{{ $initialWeight > 0 ? number_format($initialWeight, 1) . ' kg' : 'Inicial' }}"
                        class="px-4 py-1.5 rounded-lg text-body-sm text-[#c2c6d6] hover:text-[#dfe2f0] transition-all cursor-pointer">
                        Registro Inicial ({{ $initialWeight > 0 ? number_format($initialWeight, 1) . ' kg' : 'Base' }})
                    </button>
                    <button type="button"
                        id="btn-photo-month"
                        data-front="{{ $monthFrontPhoto }}"
                        data-side="{{ $monthSidePhoto }}"
                        data-back="{{ $monthBackPhoto }}"
                        data-date="{{ $selectedCheckinDate }}"
                        data-weight="{{ $monthWeight > 0 ? number_format($monthWeight, 1) . ' kg' : 'Actual' }}"
                        class="px-4 py-1.5 rounded-lg text-body-sm bg-[#adc6ff] text-[#002e6a] font-semibold shadow-sm transition-all cursor-pointer">
                        {{ $selectedMonthLabel }} ({{ $monthWeight > 0 ? number_format($monthWeight, 1) . ' kg' : 'Mes' }})
                    </button>
                </div>
            </div>

            <!-- Galería de los 3 Ángulos Estandarizados -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ángulo 1: Vista Frontal -->
                <div class="bg-[#171c25] rounded-xl overflow-hidden shadow-md flex flex-col group border border-white/5">
                    <div class="relative aspect-[3/4] bg-[#262a34] overflow-hidden flex items-center justify-center">
                        @if($monthFrontPhoto)
                            <img id="photo-angle-front"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-zoom-in"
                                 alt="Vista Frontal"
                                 src="{{ $monthFrontPhoto }}"
                                 onclick="window.open(this.src, '_blank')"/>
                        @else
                            <div class="text-center p-6 space-y-2">
                                <span class="material-symbols-outlined text-[48px] text-[#8c909f]">person</span>
                                <p class="text-body-sm text-[#8c909f]">Sin foto frontal registrada</p>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-[#0f131d]/90 backdrop-blur-md px-3 py-1 rounded-lg">
                            <span class="text-label-caps text-[#dfe2f0] uppercase">Vista Frontal</span>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 bg-[#0f131d]/85 backdrop-blur-md p-2 rounded-lg flex justify-between items-center">
                            <span class="photo-date-label text-body-sm text-[#dfe2f0]">{{ $selectedCheckinDate }}</span>
                            <span class="photo-weight-label text-title-sm text-[#adc6ff] font-bold">{{ $monthWeight > 0 ? number_format($monthWeight, 1) . ' kg' : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-title-sm text-[#dfe2f0]">Perfil Frontal</span>
                            <span class="text-label-caps text-[#ffb690] uppercase">{{ $monthFrontPhoto ? 'Registrado' : 'Pendiente' }}</span>
                        </div>
                        <p class="text-body-sm text-[#c2c6d6]">Control visual de pared abdominal, pectoral y simetría anterior.</p>
                    </div>
                </div>

                <!-- Ángulo 2: Vista de Perfil Lateral -->
                <div class="bg-[#171c25] rounded-xl overflow-hidden shadow-md flex flex-col group border border-white/5">
                    <div class="relative aspect-[3/4] bg-[#262a34] overflow-hidden flex items-center justify-center">
                        @if($monthSidePhoto)
                            <img id="photo-angle-side"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-zoom-in"
                                 alt="Vista de Perfil Lateral"
                                 src="{{ $monthSidePhoto }}"
                                 onclick="window.open(this.src, '_blank')"/>
                        @else
                            <div class="text-center p-6 space-y-2">
                                <span class="material-symbols-outlined text-[48px] text-[#8c909f]">accessibility_new</span>
                                <p class="text-body-sm text-[#8c909f]">Sin foto de perfil registrada</p>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-[#0f131d]/90 backdrop-blur-md px-3 py-1 rounded-lg">
                            <span class="text-label-caps text-[#dfe2f0] uppercase">Vista de Perfil Lateral</span>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 bg-[#0f131d]/85 backdrop-blur-md p-2 rounded-lg flex justify-between items-center">
                            <span class="photo-date-label text-body-sm text-[#dfe2f0]">{{ $selectedCheckinDate }}</span>
                            <span class="photo-weight-label text-title-sm text-[#adc6ff] font-bold">{{ $monthWeight > 0 ? number_format($monthWeight, 1) . ' kg' : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-title-sm text-[#dfe2f0]">Perfil Lateral</span>
                            <span class="text-label-caps text-[#adc6ff] uppercase">{{ $monthSidePhoto ? 'Registrado' : 'Pendiente' }}</span>
                        </div>
                        <p class="text-body-sm text-[#c2c6d6]">Control postural, curvatura lumbar y cintura pélvica.</p>
                    </div>
                </div>

                <!-- Ángulo 3: Vista Posterior / Espalda -->
                <div class="bg-[#171c25] rounded-xl overflow-hidden shadow-md flex flex-col group border border-white/5">
                    <div class="relative aspect-[3/4] bg-[#262a34] overflow-hidden flex items-center justify-center">
                        @if($monthBackPhoto)
                            <img id="photo-angle-back"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-zoom-in"
                                 alt="Vista Posterior / Espalda"
                                 src="{{ $monthBackPhoto }}"
                                 onclick="window.open(this.src, '_blank')"/>
                        @else
                            <div class="text-center p-6 space-y-2">
                                <span class="material-symbols-outlined text-[48px] text-[#8c909f]">sports_martial_arts</span>
                                <p class="text-body-sm text-[#8c909f]">Sin foto de espalda registrada</p>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-[#0f131d]/90 backdrop-blur-md px-3 py-1 rounded-lg">
                            <span class="text-label-caps text-[#dfe2f0] uppercase">Vista Posterior / Espalda</span>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 bg-[#0f131d]/85 backdrop-blur-md p-2 rounded-lg flex justify-between items-center">
                            <span class="photo-date-label text-body-sm text-[#dfe2f0]">{{ $selectedCheckinDate }}</span>
                            <span class="photo-weight-label text-title-sm text-[#adc6ff] font-bold">{{ $monthWeight > 0 ? number_format($monthWeight, 1) . ' kg' : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-title-sm text-[#dfe2f0]">Perfil Posterior / Espalda</span>
                            <span class="text-label-caps text-[#ffb690] uppercase">{{ $monthBackPhoto ? 'Registrado' : 'Pendiente' }}</span>
                        </div>
                        <p class="text-body-sm text-[#c2c6d6]">Detalle escapular, dorsal ancho y cadena posterior.</p>
                    </div>
                </div>
            </div>

            <!-- Dictamen Clínico / Comentarios del Mes -->
            <div class="bg-[#262a34] rounded-xl p-6 flex flex-col md:flex-row items-start gap-4 relative overflow-hidden border border-white/5">
                <div class="w-12 h-12 rounded-xl bg-[#4d8eff] text-[#00285d] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">rate_review</span>
                </div>
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-title-sm text-[#dfe2f0]">Dictamen y Observaciones del Mes &middot; {{ $selectedMonthLabel }}</span>
                        <span class="text-label-caps text-[#8c909f]">
                            Fecha: {{ $selectedCheckinDate }} &middot; Coach: {{ $coachName }}
                        </span>
                    </div>
                    <p class="text-body-md text-[#c2c6d6] leading-relaxed">
                        @if($selectedProgress && $selectedProgress->comments)
                            “{{ $selectedProgress->comments }}”
                        @elseif($user->objectives)
                            “Objetivo actual del atleta: {{ $user->objectives }}. Registra una nueva medición en este mes para añadir notas clínicas específicas.”
                        @else
                            “Sin observaciones registradas para este mes. Utiliza el botón Nueva Medición para adjuntar comentarios técnicos y fotografías actualizadas.”
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <!-- ========================================================================= -->
        <!-- 4. HISTORIAL GRANULAR DE SESIONES DE ENTRENAMIENTO                        -->
        <!-- ========================================================================= -->
        <section class="space-y-4">
            <!-- Section Title & Filter Controls -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-headline-md text-[#dfe2f0]">
                        Sesiones de Entrenamiento &middot; {{ $selectedMonthLabel }}
                        <span class="text-body-md text-[#adc6ff] font-normal">({{ $workoutSessions->count() }} en este mes)</span>
                    </h2>
                    <p class="text-body-md text-[#c2c6d6]">Desglose de ejercicios realizados, cargas registradas, volumen movido y comentarios</p>
                </div>

                <!-- Filtros Rápidos -->
                <div class="flex flex-wrap items-center gap-2 no-print">
                    @if($workoutSessions->isEmpty() && $allWorkoutSessions->isNotEmpty())
                        <button type="button" onclick="toggleAllSessionsView()" id="btn-show-all-sessions"
                            class="bg-[#4d8eff] text-[#00285d] font-bold rounded-lg px-3.5 py-2 flex items-center gap-2 transition cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">history</span>
                            <span class="text-body-sm">Ver todas las sesiones ({{ $allWorkoutSessions->count() }})</span>
                        </button>
                    @endif

                    <button type="button" id="filter-rpe-btn" data-active="0"
                        class="bg-[#1b2029] hover:bg-[#262a34] rounded-lg px-3.5 py-2 flex items-center gap-2 border border-white/5 transition cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-[#ffb690]">local_fire_department</span>
                        <span id="filter-rpe-label" class="text-body-sm text-[#dfe2f0]">RPE &gt; 8.0</span>
                    </button>

                    <a href="{{ route('admin.users.workouts', $user) }}"
                        class="bg-[#1b2029] hover:bg-[#262a34] rounded-lg px-3.5 py-2 flex items-center gap-2 border border-white/5 transition text-[#adc6ff]">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        <span class="text-body-sm">Tabla Completa</span>
                    </a>
                </div>
            </div>

            @php
                // Si el mes seleccionado tiene sesiones, las mostramos; si no tiene pero el usuario tiene en otros meses, permitimos verlas también
                $sessionsToDisplay = $workoutSessions->isNotEmpty() ? $workoutSessions : $allWorkoutSessions;
            @endphp

            @if($workoutSessions->isEmpty() && $allWorkoutSessions->isNotEmpty())
                <div class="bg-[#262a34]/70 border border-white/10 rounded-xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#ffb690]">info</span>
                        <p class="text-body-sm text-[#c2c6d6]">
                            No se encontraron sesiones en <strong>{{ $selectedMonthLabel }}</strong>. Mostrando las últimas <strong>{{ $allWorkoutSessions->count() }}</strong> sesiones registradas del atleta en otros meses.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Lista de Tarjetas de Sesiones -->
            <div id="sessions-stack" class="space-y-4">
                @forelse($sessionsToDisplay as $idx => $sess)
                    @php
                        // Calcular métricas si la sesión proviene de $allWorkoutSessions y no estaba en $sessionMetrics
                        if (isset($sessionMetrics[$sess->id])) {
                            $meta = $sessionMetrics[$sess->id];
                        } else {
                            $sTonnage = 0;
                            $sRpes = [];
                            $sMaxLift = 0;
                            foreach ($sess->exerciseLogs as $eLog) {
                                foreach ($eLog->setLogs as $set) {
                                    $w = (float) ($set->weight ?: 0);
                                    $r = (int) ($set->reps ?: 0);
                                    $sTonnage += ($w * $r);
                                    if ($w > $sMaxLift) $sMaxLift = $w;
                                    if (!empty($set->rpe) && is_numeric($set->rpe)) $sRpes[] = (float) $set->rpe;
                                }
                            }
                            $sDur = $sess->duration;
                            if (!$sDur && $sess->start_time && $sess->end_time) {
                                $sDur = \Carbon\Carbon::parse($sess->start_time)->diffInMinutes(\Carbon\Carbon::parse($sess->end_time));
                            }
                            $meta = [
                                'tonnage' => $sTonnage,
                                'rpe' => count($sRpes) > 0 ? round(array_sum($sRpes) / count($sRpes), 1) : null,
                                'duration' => $sDur ?: ($sess->trainingDay->estimated_minutes ?? 60),
                                'is_pr' => $sMaxLift >= 60,
                            ];
                        }
                        $sessionTitle = $sess->trainingDay ? $sess->trainingDay->label : 'Sesión de Entrenamiento';
                        $planName = ($sess->trainingDay && $sess->trainingDay->monthlyPlan) ? $sess->trainingDay->monthlyPlan->name : 'Plan Asignado';
                    @endphp

                    <div class="session-card bg-[#1b2029] rounded-xl p-6 shadow-md space-y-4 border border-white/5"
                         data-rpe="{{ $meta['rpe'] ?? 8.0 }}">
                        <!-- Cabecera de la Sesión -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $idx === 0 ? 'bg-[#4d8eff] text-[#00285d]' : 'bg-[#262a34] text-[#adc6ff]' }} flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined text-[20px]">fitness_center</span>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-title-sm text-[#dfe2f0]">{{ $sessionTitle }}</h3>
                                        <span class="bg-[#262a34] text-[#adc6ff] text-label-caps px-2.5 py-0.5 rounded-full">
                                            {{ $planName }}
                                        </span>
                                        @if($meta['is_pr'])
                                            <span class="bg-[#ec6a06] text-[#4a1c00] text-label-caps px-2 py-0.5 rounded-full font-bold">PR Superado</span>
                                        @endif
                                    </div>
                                    <span class="text-body-sm text-[#8c909f]">
                                        Realizada el {{ \Carbon\Carbon::parse($sess->start_time)->translatedFormat('d \d\e F, Y') }} &middot; Hora: {{ \Carbon\Carbon::parse($sess->start_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 text-[#c2c6d6] text-body-sm">
                                <div class="flex items-center gap-1.5 bg-[#262a34] px-3 py-1 rounded-lg">
                                    <span class="material-symbols-outlined text-[18px] text-[#adc6ff]">schedule</span>
                                    <span>{{ $meta['duration'] }} min</span>
                                </div>
                                <div class="flex items-center gap-1.5 bg-[#262a34] px-3 py-1 rounded-lg">
                                    <span class="material-symbols-outlined text-[18px] text-[#ffb690]">weight</span>
                                    <span class="font-bold text-[#dfe2f0]">{{ number_format($meta['tonnage']) }} kg</span>
                                </div>
                                @if($meta['rpe'])
                                    <div class="flex items-center gap-1.5 bg-[#262a34] px-3 py-1 rounded-lg">
                                        <span class="material-symbols-outlined text-[18px] text-[#ffb690]">speed</span>
                                        <span>RPE {{ number_format($meta['rpe'], 1) }}</span>
                                    </div>
                                @endif
                                <a href="{{ route('admin.workouts.show', $sess) }}"
                                   class="bg-[#4d8eff]/20 hover:bg-[#4d8eff] text-[#adc6ff] hover:text-[#00285d] px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1 no-print">
                                    <span>Ver Series</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>

                        <!-- Grid de Ejercicios Realizados en la Sesión -->
                        @if($sess->exerciseLogs->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                                @foreach($sess->exerciseLogs as $exIdx => $exLog)
                                    @php
                                        $topSet = $exLog->setLogs->sortByDesc('weight')->first();
                                        $setsCount = $exLog->setLogs->count() ?: ($exLog->plannedExercise->sets ?? 0);
                                        $exName = $exLog->plannedExercise->exercise->name ?? 'Ejercicio';
                                    @endphp
                                    <div class="bg-[#171c25] p-3 rounded-lg space-y-1 border border-white/5">
                                        <div class="flex justify-between items-center gap-1">
                                            <span class="text-body-sm text-[#dfe2f0] font-semibold truncate" title="{{ $exName }}">{{ $exName }}</span>
                                            <span class="text-label-caps text-[#adc6ff] shrink-0">{{ $setsCount }} series</span>
                                        </div>
                                        @if($topSet)
                                            <p class="text-title-sm text-[#ffb690]">
                                                {{ $topSet->weight }} kg
                                                <span class="text-body-sm text-[#8c909f]">&times; {{ $topSet->reps }} reps (Top Set)</span>
                                            </p>
                                        @else
                                            <p class="text-body-sm text-[#8c909f]">Sin series registradas</p>
                                        @endif
                                        @if($exLog->setLogs->count() > 1)
                                            <span class="text-[11px] text-[#8c909f] block truncate">
                                                Series: {{ $exLog->setLogs->map(fn($s) => $s->weight . 'kg×' . $s->reps)->implode(' | ') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-[#171c25] p-3 rounded-lg text-body-sm text-[#8c909f]">
                                Sesión registrada sin desglose de series individuales.
                            </div>
                        @endif

                        <!-- Comentarios de la Sesión (Coach y Atleta) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                            <div class="bg-[#262a34]/70 p-3 rounded-lg flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#adc6ff] shrink-0 mt-0.5">sports</span>
                                <div class="space-y-0.5">
                                    <span class="text-label-caps text-[#adc6ff] uppercase font-bold">Indicaciones del Día / Coach</span>
                                    <p class="text-body-sm text-[#c2c6d6]">
                                        “{{ ($sess->trainingDay && $sess->trainingDay->comment) ? $sess->trainingDay->comment : ($sess->trainingDay && $sess->trainingDay->description ? $sess->trainingDay->description : 'Cumplir rango de repeticiones prescrito con control técnico.') }}”
                                    </p>
                                </div>
                            </div>
                            <div class="bg-[#262a34]/70 p-3 rounded-lg flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#ffb690] shrink-0 mt-0.5">sentiment_very_satisfied</span>
                                <div class="space-y-0.5">
                                    <span class="text-label-caps text-[#ffb690] uppercase font-bold">Comentarios del Atleta</span>
                                    <p class="text-body-sm text-[#c2c6d6]">
                                        “{{ $sess->comments ?: 'Sesión finalizada sin comentarios adicionales.' }}”
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#1b2029] rounded-xl p-10 text-center space-y-3 border border-white/5">
                        <div class="w-12 h-12 rounded-full bg-[#262a34] text-[#8c909f] flex items-center justify-center mx-auto">
                            <span class="material-symbols-outlined text-[24px]">fitness_center</span>
                        </div>
                        <h3 class="text-title-sm text-[#dfe2f0]">Sin sesiones de entrenamiento registradas</h3>
                        <p class="text-body-sm text-[#8c909f] max-w-md mx-auto">
                            Este atleta aún no tiene sesiones de entrenamiento guardadas. Cuando registre entrenamientos desde la aplicación o vía QR, aparecerán desglosadas aquí.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        @if($user->role === 'coach' && $user->clients->count() > 0)
            <!-- Lista de Clientes Asignados (Si el perfil visitado es un Coach) -->
            <section class="bg-[#1b2029] rounded-xl p-6 shadow-xl space-y-4 border border-white/5">
                <h3 class="text-headline-md text-[#dfe2f0] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#adc6ff]">groups</span>
                    <span>Atletas Asignados a este Coach ({{ $user->clients->count() }})</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($user->clients as $client)
                        <a href="{{ route('admin.users.show', $client) }}" class="bg-[#171c25] hover:bg-[#262a34] p-4 rounded-xl flex items-center justify-between transition border border-white/5">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-[#4d8eff] text-[#00285d] flex items-center justify-center font-bold shrink-0">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-title-sm text-[#dfe2f0] truncate">{{ $client->name }}</p>
                                    <p class="text-body-sm text-[#8c909f] truncate">{{ $client->email }}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-[#8c909f]">chevron_right</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>

    <!-- MODAL: NUEVA MEDICIÓN / CHECK-IN 360° -->
    <div id="newMeasurementModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/75 backdrop-blur-sm no-print">
        <div class="bg-[#1b2029] text-[#dfe2f0] border border-white/10 rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-[#4d8eff] text-[#00285d] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">add_chart</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-jakarta">Registrar Nueva Medición &amp; Fotos 360°</h3>
                        <p class="text-xs text-[#8c909f]">Atleta: {{ $user->name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeNewMeasurementModal()" class="text-[#8c909f] hover:text-white p-1">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('admin.users.progress.store', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Fecha Evaluación</label>
                        <input type="date" name="recorded_at" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-3 py-2 text-sm text-[#dfe2f0] focus:outline-none focus:border-[#adc6ff]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Peso (kg)</label>
                        <input type="number" step="0.1" name="weight" value="{{ $monthWeight ?: $initialWeight }}" required
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-3 py-2 text-sm text-[#dfe2f0] focus:outline-none focus:border-[#adc6ff]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">% Grasa Corporal</label>
                        <input type="number" step="0.1" name="body_fat" value="{{ $monthBodyFat }}" placeholder="Ej. 18.2"
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-3 py-2 text-sm text-[#dfe2f0] focus:outline-none focus:border-[#adc6ff]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Masa Magra (kg)</label>
                        <input type="number" step="0.1" name="lean_mass" value="{{ $monthLeanMass }}" placeholder="Ej. 59.8"
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-3 py-2 text-sm text-[#dfe2f0] focus:outline-none focus:border-[#adc6ff]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Foto Frente</label>
                        <input type="file" name="front_photo" accept="image/*"
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-2.5 py-2 text-xs text-[#c2c6d6]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Foto Perfil Lateral</label>
                        <input type="file" name="side_photo" accept="image/*"
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-2.5 py-2 text-xs text-[#c2c6d6]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Foto Espalda</label>
                        <input type="file" name="back_photo" accept="image/*"
                            class="w-full rounded-lg bg-[#171c25] border border-white/10 px-2.5 py-2 text-xs text-[#c2c6d6]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#8c909f] mb-1">Dictamen Clínico del Coach / Notas del Mes</label>
                    <textarea name="comments" rows="3" placeholder="Escribe las observaciones del mes, ajustes de agua, cardio o postura..."
                        class="w-full rounded-lg bg-[#171c25] border border-white/10 px-3 py-2 text-sm text-[#dfe2f0] focus:outline-none focus:border-[#adc6ff]"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-white/10">
                    <button type="button" onclick="closeNewMeasurementModal()"
                        class="px-4 py-2 rounded-lg bg-[#262a34] hover:bg-[#353944] text-sm font-semibold text-[#c2c6d6] transition cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-[#4d8eff] hover:bg-[#adc6ff] text-[#00285d] text-sm font-bold shadow-md transition cursor-pointer">
                        Guardar Medición
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNewMeasurementModal() {
            const modal = document.getElementById('newMeasurementModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeNewMeasurementModal() {
            const modal = document.getElementById('newMeasurementModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        (function() {
            // Comparador de Fotos 360° (Registro Inicial vs Mes Seleccionado)
            const btnInitial = document.getElementById('btn-photo-initial');
            const btnMonth = document.getElementById('btn-photo-month');
            const imgFront = document.getElementById('photo-angle-front');
            const imgSide = document.getElementById('photo-angle-side');
            const imgBack = document.getElementById('photo-angle-back');
            const dateLabels = document.querySelectorAll('.photo-date-label');
            const weightLabels = document.querySelectorAll('.photo-weight-label');

            function switchPhotos(activeBtn, inactiveBtn) {
                activeBtn.className = 'px-4 py-1.5 rounded-lg text-body-sm bg-[#adc6ff] text-[#002e6a] font-semibold shadow-sm transition-all cursor-pointer';
                inactiveBtn.className = 'px-4 py-1.5 rounded-lg text-body-sm text-[#c2c6d6] hover:text-[#dfe2f0] transition-all cursor-pointer';

                if (imgFront && activeBtn.dataset.front) imgFront.src = activeBtn.dataset.front;
                if (imgSide && activeBtn.dataset.side) imgSide.src = activeBtn.dataset.side;
                if (imgBack && activeBtn.dataset.back) imgBack.src = activeBtn.dataset.back;

                dateLabels.forEach(el => el.textContent = activeBtn.dataset.date);
                weightLabels.forEach(el => el.textContent = activeBtn.dataset.weight);
            }

            if (btnInitial && btnMonth) {
                btnInitial.addEventListener('click', () => switchPhotos(btnInitial, btnMonth));
                btnMonth.addEventListener('click', () => switchPhotos(btnMonth, btnInitial));
            }

            // Filtro rápido de RPE > 8.0 en Sesiones
            const filterRpeBtn = document.getElementById('filter-rpe-btn');
            const filterRpeLabel = document.getElementById('filter-rpe-label');
            const sessionCards = document.querySelectorAll('.session-card');
            let minRpeFilter = 0;

            if (filterRpeBtn) {
                filterRpeBtn.addEventListener('click', function() {
                    if (minRpeFilter === 0) {
                        minRpeFilter = 8.0;
                        this.classList.add('ring-1', 'ring-[#ffb690]');
                        filterRpeLabel.textContent = 'Solo RPE > 8.0';
                    } else {
                        minRpeFilter = 0;
                        this.classList.remove('ring-1', 'ring-[#ffb690]');
                        filterRpeLabel.textContent = 'RPE > 8.0';
                    }
                    sessionCards.forEach(card => {
                        const rpe = parseFloat(card.dataset.rpe || '8.5');
                        card.style.display = (minRpeFilter === 0 || rpe > 8.0) ? '' : 'none';
                    });
                });
            }
        })();
    </script>
@endsection
