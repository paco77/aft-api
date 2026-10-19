<!DOCTYPE html>
<html class="dark" lang="es" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=5.0" name="viewport"/>
    <meta content="web_standard" name="shell-type"/>
    <title>AF Training | Plataforma Definitiva de Entrenamiento, Nutrición & Gestión Deportiva</title>
    <meta name="description" content="AF Training - La plataforma de alto rendimiento para Head Coaches, gimnasios, atletas independientes y clientes. TDEE automatizado, periodización y biometría 360°.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #0f131d;
        }
        .page-wrapper {
            width: 100%;
            overflow-x: clip;
            position: relative;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0f131d;
        }
        ::-webkit-scrollbar-thumb {
            background: #262a34;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #424754;
        }
    </style>

    <!-- Tailwind CSS with AF Training Brand Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-low": "#171c25",
                        "inverse-on-surface": "#2c303b",
                        "on-error-container": "#ffdad6",
                        "surface": "#0f131d",
                        "primary": "#adc6ff",
                        "outline-variant": "#424754",
                        "secondary": "#ffb690",
                        "surface-dim": "#0f131d",
                        "on-tertiary-fixed": "#001c39",
                        "background": "#0f131d",
                        "surface-tint": "#adc6ff",
                        "inverse-surface": "#dfe2f0",
                        "on-surface-variant": "#c2c6d6",
                        "surface-container-high": "#262a34",
                        "tertiary-container": "#4c93e7",
                        "primary-fixed-dim": "#adc6ff",
                        "on-primary-fixed-variant": "#004395",
                        "on-secondary": "#552100",
                        "secondary-fixed-dim": "#ffb690",
                        "surface-container-highest": "#31353f",
                        "on-surface": "#dfe2f0",
                        "on-tertiary": "#00315d",
                        "surface-container-lowest": "#0a0e17",
                        "on-secondary-fixed": "#341100",
                        "inverse-primary": "#005ac2",
                        "primary-fixed": "#d8e2ff",
                        "error": "#ffb4ab",
                        "on-primary-fixed": "#001a42",
                        "on-tertiary-container": "#002a51",
                        "surface-bright": "#353944",
                        "outline": "#8c909f",
                        "surface-variant": "#31353f",
                        "on-primary": "#002e6a",
                        "tertiary-fixed-dim": "#a4c9ff",
                        "surface-container": "#1b2029",
                        "on-secondary-container": "#4a1c00",
                        "error-container": "#93000a",
                        "on-primary-container": "#00285d",
                        "secondary-container": "#ec6a06",
                        "on-error": "#690005",
                        "tertiary-fixed": "#d4e3ff",
                        "secondary-fixed": "#ffdbca",
                        "on-tertiary-fixed-variant": "#004883",
                        "primary-container": "#4d8eff",
                        "on-secondary-fixed-variant": "#783200",
                        "tertiary": "#a4c9ff",
                        "on-background": "#dfe2f0"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-xl": "2rem",
                        "gutter-desktop": "2rem",
                        "gutter-tablet": "1.5rem",
                        "space-lg": "1.5rem",
                        "gutter": "1rem",
                        "space-md": "1rem",
                        "margin": "1rem",
                        "margin-desktop": "3rem",
                        "margin-tablet": "2rem"
                    },
                    fontFamily: {
                        "metric-val": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Inter", "sans-serif"],
                        "display-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-caps": ["Plus Jakarta Sans", "sans-serif"],
                        "display-lg-mobile": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Inter", "sans-serif"],
                        "title-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "body-sm": ["Inter", "sans-serif"]
                    },
                    fontSize: {
                        "metric-val": ["22px", { lineHeight: "28px", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "display-lg": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-lg": ["28px", { lineHeight: "36px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "headline-md": ["20px", { lineHeight: "26px", fontWeight: "700" }],
                        "label-caps": ["11px", { lineHeight: "14px", letterSpacing: "0.08em", fontWeight: "800" }],
                        "display-lg-mobile": ["32px", { lineHeight: "38px", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-lg-mobile": ["24px", { lineHeight: "30px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "title-sm": ["16px", { lineHeight: "22px", fontWeight: "600" }],
                        "body-sm": ["12px", { lineHeight: "16px", fontWeight: "400" }]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-background font-body-md text-on-surface antialiased selection:bg-secondary-container selection:text-white">

    <!-- HEADER / NAVIGATION -->
    <header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl border-b border-outline-variant/20 shadow-[0_1px_8px_rgba(0,0,0,0.25)]">
        <div class="h-16 sm:h-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex items-center justify-between">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-2 sm:gap-space-lg">
                <a class="flex items-center gap-2 sm:gap-space-sm group" href="#">
                    <img alt="AF Training Logo" class="h-7 sm:h-9 w-auto object-contain transition-transform group-hover:scale-105" 
                         src="https://lh3.googleusercontent.com/aida/AEtjO1XvZZkhZyrpeO-BCxXjjpe8ZNbIV3A6Vw6q3ElUbGCB_QtPYPsMxLgCjicFw0EykjxxIqN2XBz--QN28zTl69ydN91S70kX_pbR1ahRXSFH7SBzie5LgXXvmGRx5EFxhGm4bGfcfoNXFz0L2EUm9gOGzS0BjdBqg5rRljvS0ZGUfc2x2yCTLdlZGtdYYGjysWyrzWpYttaKJq2gDZibTckN8Agr600vfdCRu8rM_Ei0xkLtmck4ELn_1fsq0_kQodBhtW5lo_4dD9A"
                         onerror="this.src='{{ asset('images/logo.png') }}'"/>
                    <div class="flex flex-col">
                        <span class="font-headline-md text-base sm:text-headline-md tracking-tight uppercase text-on-surface leading-tight">AF Training</span>
                        <span class="font-label-caps text-[9px] sm:text-label-caps text-primary tracking-widest uppercase leading-none hidden xs:inline-block">Elevate Your Fitness</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden xl:flex items-center gap-space-md ml-space-md">
                    <a class="transition-colors bg-surface-container-high text-primary font-title-sm rounded-lg px-space-sm py-space-xs" href="#roles">Perfiles</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface px-space-sm py-space-xs transition-colors" href="#roles">Para Coaches</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface px-space-sm py-space-xs transition-colors" href="#roles">Independientes &amp; Gimnasios</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface px-space-sm py-space-xs transition-colors" href="#modulos">Módulos</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface px-space-sm py-space-xs transition-colors" href="#workflow">Flujo Operativo</a>
                </nav>
            </div>

            <!-- Right Actions (Desktop & Tablet) -->
            <div class="flex items-center gap-2 sm:gap-space-md">
                @if (Route::has('login'))
                    @auth
                        <a class="hidden sm:inline-flex font-title-sm text-sm sm:text-title-sm text-primary hover:text-on-surface transition-colors px-3 py-2" href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                            @csrf
                            <button type="submit" class="font-title-sm text-sm sm:text-title-sm text-on-surface-variant hover:text-error transition-colors px-2 py-2">
                                Salir
                            </button>
                        </form>
                    @else
                        <a class="hidden md:inline-flex font-title-sm text-sm sm:text-title-sm text-on-surface hover:text-primary transition-colors px-3 py-2" href="{{ route('login') }}">
                            Iniciar Sesión
                        </a>
                        <a class="hidden sm:inline-flex items-center justify-center font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-secondary-container text-on-secondary-container hover:bg-secondary hover:text-on-secondary px-3.5 sm:px-space-lg py-2 sm:py-space-sm rounded-lg transition-all shadow-[0_0_16px_rgba(236,106,6,0.3)] hover:scale-[1.02]" href="{{ route('login') }}">
                            Comenzar Gratis
                        </a>
                    @endauth
                @else
                    <a class="hidden md:inline-flex font-title-sm text-sm sm:text-title-sm text-on-surface hover:text-primary transition-colors px-3 py-2" href="/login">
                        Iniciar Sesión
                    </a>
                    <a class="hidden sm:inline-flex items-center justify-center font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-secondary-container text-on-secondary-container hover:bg-secondary hover:text-on-secondary px-3.5 sm:px-space-lg py-2 sm:py-space-sm rounded-lg transition-all shadow-[0_0_16px_rgba(236,106,6,0.3)] hover:scale-[1.02]" href="/login">
                        Comenzar Gratis
                    </a>
                @endif

                <!-- User profile badge (Desktop) -->
                <div class="hidden sm:flex w-8 h-8 rounded-full bg-primary/20 border border-primary/40 items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                </div>

                <!-- Mobile Menu Button (Hamburger) -->
                <button id="mobile-menu-btn" 
                        aria-label="Abrir menú de navegación" 
                        class="xl:hidden p-2 rounded-lg bg-surface-container-high border border-outline-variant/30 text-on-surface hover:text-primary hover:bg-surface-bright transition-colors focus:outline-none">
                    <span id="mobile-menu-icon" class="material-symbols-outlined text-[24px]">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation (Slide-Down) -->
        <div id="mobile-menu-drawer" class="hidden xl:hidden bg-surface-container-low/98 backdrop-blur-2xl border-b border-outline-variant/30 px-6 py-6 transition-all duration-300">
            <nav class="flex flex-col gap-3">
                <a class="mobile-nav-link text-primary font-title-sm py-2 px-3 rounded-lg bg-surface-container-high" href="#roles">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">badge</span>
                        <span>Perfiles de Usuario</span>
                    </span>
                </a>
                <a class="mobile-nav-link text-on-surface-variant hover:text-on-surface font-title-sm py-2 px-3 rounded-lg hover:bg-surface-container" href="#roles">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">sports</span>
                        <span>Para Coaches</span>
                    </span>
                </a>
                <a class="mobile-nav-link text-on-surface-variant hover:text-on-surface font-title-sm py-2 px-3 rounded-lg hover:bg-surface-container" href="#roles">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">corporate_fare</span>
                        <span>Independientes &amp; Gimnasios</span>
                    </span>
                </a>
                <a class="mobile-nav-link text-on-surface-variant hover:text-on-surface font-title-sm py-2 px-3 rounded-lg hover:bg-surface-container" href="#modulos">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">view_in_ar</span>
                        <span>Módulos de Sistema</span>
                    </span>
                </a>
                <a class="mobile-nav-link text-on-surface-variant hover:text-on-surface font-title-sm py-2 px-3 rounded-lg hover:bg-surface-container" href="#workflow">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">checklist</span>
                        <span>Flujo Operativo</span>
                    </span>
                </a>

                <div class="h-px w-full bg-outline-variant/20 my-2"></div>

                <!-- Mobile Auth Buttons -->
                @if (Route::has('login'))
                    @auth
                        <a class="w-full flex items-center justify-center gap-2 font-headline-md text-sm uppercase tracking-wider bg-primary text-on-primary py-3 rounded-xl transition-all" href="{{ route('admin.dashboard') }}">
                            <span class="material-symbols-outlined text-[18px]">dashboard</span>
                            <span>Ir al Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 font-title-sm text-sm text-error bg-error-container/20 py-2.5 rounded-xl border border-error/30 mt-1">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    @else
                        <a class="w-full flex items-center justify-center gap-2 font-headline-md text-sm uppercase tracking-wider bg-secondary-container text-on-secondary-container py-3.5 rounded-xl shadow-lg shadow-secondary-container/25" href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                            <span>Comenzar Prueba Gratis</span>
                        </a>
                        <a class="w-full flex items-center justify-center gap-2 font-title-sm text-sm text-on-surface bg-surface-container-high py-3 rounded-xl border border-outline-variant/30 hover:bg-surface-bright transition-colors" href="{{ route('login') }}">
                            <span class="material-symbols-outlined text-[18px]">login</span>
                            <span>Iniciar Sesión</span>
                        </a>
                    @endauth
                @else
                    <a class="w-full flex items-center justify-center gap-2 font-headline-md text-sm uppercase tracking-wider bg-secondary-container text-on-secondary-container py-3.5 rounded-xl shadow-lg" href="/login">
                        <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                        <span>Comenzar Prueba Gratis</span>
                    </a>
                    <a class="w-full flex items-center justify-center gap-2 font-title-sm text-sm text-on-surface bg-surface-container-high py-3 rounded-xl border border-outline-variant/30" href="/login">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span>Iniciar Sesión</span>
                    </a>
                @endif
            </nav>
        </div>
    </header>

    <main class="w-full pt-16 sm:pt-20 bg-background">
        <div class="flex flex-col w-full">

            <!-- HERO SECTION -->
            <section class="relative w-full overflow-hidden bg-surface py-12 sm:py-16 lg:py-24">
                <!-- Ambient atmospheric glow spheres -->
                <div class="pointer-events-none absolute -top-40 -left-40 h-80 sm:h-96 w-80 sm:w-96 rounded-full bg-primary-container/15 blur-[100px] sm:blur-[120px]"></div>
                <div class="pointer-events-none absolute top-1/3 -right-32 h-80 sm:h-96 w-80 sm:w-96 rounded-full bg-secondary-container/15 blur-[120px] sm:blur-[140px]"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-8 items-center">
                        
                        <!-- Left: Copy & Value Proposition -->
                        <div class="lg:col-span-7 flex flex-col gap-4 sm:gap-space-lg text-left">
                            <div class="inline-flex items-center gap-2 px-3 sm:px-space-md py-1 sm:py-space-xs rounded-full bg-surface-container-high w-fit shadow-sm border border-outline-variant/30">
                                <span class="inline-block w-2 h-2 rounded-full bg-secondary animate-pulse shrink-0"></span>
                                <span class="font-label-caps text-[10px] sm:text-label-caps uppercase tracking-wider text-primary">Plataforma Todo-en-Uno • Versión 3.0</span>
                            </div>

                            <h1 class="font-display-lg text-3xl sm:text-4xl md:text-5xl lg:text-[48px] lg:leading-[54px] tracking-tight text-on-surface uppercase font-black">
                                La Plataforma Definitiva de <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-primary-container to-secondary">Entrenamiento, Nutrición</span> &amp; Gestión Deportiva
                            </h1>

                            <p class="font-body-lg text-sm sm:text-base lg:text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                                Diseñada para Head Coaches de alto rendimiento, gimnasios comerciales, atletas independientes y sus clientes. Integra prescripción de rutinas, bioimpedancia, ajuste de macros y control de staff en un ecosistema quirúrgico.
                            </p>

                            <!-- Metrics Badges (Responsive 3 Columns) -->
                            <div class="grid grid-cols-3 gap-2 sm:gap-space-md pt-1 sm:pt-space-xs">
                                <div class="flex flex-col p-2.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 shadow-sm text-center sm:text-left">
                                    <span class="font-headline-lg text-lg sm:text-2xl lg:text-headline-lg text-primary font-bold leading-tight">+5,000</span>
                                    <span class="font-body-sm text-[10px] sm:text-body-sm text-on-surface-variant leading-tight mt-0.5">Planes Activos</span>
                                </div>
                                <div class="flex flex-col p-2.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 shadow-sm text-center sm:text-left">
                                    <span class="font-headline-lg text-lg sm:text-2xl lg:text-headline-lg text-secondary font-bold leading-tight">100%</span>
                                    <span class="font-body-sm text-[10px] sm:text-body-sm text-on-surface-variant leading-tight mt-0.5">TDEE Auto</span>
                                </div>
                                <div class="flex flex-col p-2.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 shadow-sm text-center sm:text-left">
                                    <span class="font-headline-lg text-lg sm:text-2xl lg:text-headline-lg text-on-surface font-bold leading-tight">360°</span>
                                    <span class="font-body-sm text-[10px] sm:text-body-sm text-on-surface-variant leading-tight mt-0.5">Biometría</span>
                                </div>
                            </div>

                            <!-- CTAs (Stacked on Mobile, Side-by-Side on Desktop) -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-space-md pt-2 sm:pt-space-sm w-full sm:w-auto">
                                <a class="inline-flex items-center justify-center gap-2 font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-primary text-on-primary hover:bg-primary-container px-5 sm:px-space-xl py-3.5 sm:py-4 rounded-xl transition-all shadow-[0_0_24px_rgba(77,142,255,0.35)] hover:scale-[1.02] text-center" href="#roles">
                                    <span>Comenzar Prueba Gratuita</span>
                                    <span class="material-symbols-outlined text-[18px] sm:text-[20px]">arrow_forward</span>
                                </a>
                                <a class="inline-flex items-center justify-center gap-2 font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-surface-container-high text-on-surface hover:bg-surface-bright border border-outline-variant/30 px-5 sm:px-space-lg py-3.5 sm:py-4 rounded-xl transition-all text-center" href="#modulos">
                                    <span class="material-symbols-outlined text-primary text-[18px] sm:text-[20px]">view_in_ar</span>
                                    <span>Explorar Ecosistema</span>
                                </a>
                            </div>

                            <!-- Proof Quote & Client Pill -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-space-md pt-1 sm:pt-space-xs">
                                <div class="flex -space-x-2 sm:-space-x-3 overflow-hidden shrink-0">
                                    <div class="inline-block h-8 w-8 sm:h-9 sm:w-9 rounded-full ring-2 ring-surface bg-surface-container-high flex items-center justify-center text-primary font-bold text-xs">HC</div>
                                    <div class="inline-block h-8 w-8 sm:h-9 sm:w-9 rounded-full ring-2 ring-surface bg-primary text-on-primary flex items-center justify-center font-bold text-xs">AF</div>
                                    <div class="inline-block h-8 w-8 sm:h-9 sm:w-9 rounded-full ring-2 ring-surface bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-xs">PRO</div>
                                </div>
                                <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Usado por entrenadores certificados NSCA, IFBB Pro y centros élite.</span>
                            </div>
                        </div>

                        <!-- Right: Real App Screens Perspective Stack (Mobile Adaptive) -->
                        <div class="lg:col-span-5 relative flex justify-center items-center overflow-hidden py-4 sm:py-6">
                            <div class="relative w-full max-w-sm sm:max-w-md lg:max-w-none flex justify-center items-center py-2 sm:py-6">
                                <!-- Background Backdrop Glow -->
                                <div class="absolute inset-2 sm:inset-4 rounded-3xl bg-gradient-to-tr from-primary/20 via-transparent to-secondary-container/20 blur-2xl"></div>

                                <!-- Front Device (Planes de Entrenamiento) -->
                                <div class="relative z-20 w-48 sm:w-60 md:w-64 lg:w-72 rounded-[24px] sm:rounded-[32px] p-1.5 sm:p-2 bg-surface-container-highest border border-outline-variant/40 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)] -rotate-3 transition-transform duration-500 hover:rotate-0 hover:scale-105 shrink-0">
                                    <div class="w-full h-fit rounded-[18px] sm:rounded-[24px] overflow-hidden bg-surface-container-lowest">
                                        <img alt="Planes de Entrenamiento AF Training" class="w-full h-auto object-cover block" 
                                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-qYG8vfXEAYGPCa2ECcMFu7pkAHmAYwPtuCkmyLsg4ruvNnm9Z0eeeTeRYREjy4oV5zeE0SPX2Rc0hwRKZM2CxzmfIimTwdHaGgqZoJekJ4uEkOgZE8P1yKlfvu5EMBkouRRqtCZhzAJGiIqLXdxC0aAQtdcmQu3GHykN_k_D-vRBnuOhlf7O8ZB0SdRoSXL3SoxhRtJjQoOFPxC30cdkiHZXZIl-lg_hNIYKR5IWEAPltEnnwsWk2IkDppEdHyXx5BA"/>
                                    </div>
                                </div>

                                <!-- Rear Device (Perfil Head Coach) -->
                                <div class="relative z-10 w-44 sm:w-56 md:w-60 lg:w-68 rounded-[24px] sm:rounded-[32px] p-1.5 sm:p-2 bg-surface-container-high border border-outline-variant/30 shadow-2xl rotate-6 -ml-16 sm:-ml-20 md:-ml-24 mt-6 sm:mt-10 md:mt-12 transition-transform duration-500 hover:rotate-3 hover:scale-105 shrink-0">
                                    <div class="w-full h-fit rounded-[18px] sm:rounded-[24px] overflow-hidden bg-surface-container-lowest">
                                        <img alt="Perfil Head Coach Francisco Eduardo" class="w-full h-auto object-cover block" 
                                             src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLj6DXqEIz4H8L3cblxP2KF4DYgtYeMgH53WgTQofY8Ba5OwaxXgmFcN1XWb1uS2CiLUUgsl6go-HKftCm-oT9T7xJix9x78hMlmHHuveiMsAJOjpa-ENg2aXulO9wLlSzURtMDoYe50IWMxZNfSOPVd75xJrc5TpkfkkW9XJatJk0pLNCljEdgewUFNpI8W0zBd4xkeEsaO6uogHkKDXSaEyClWGwDXho0dlTMXNawfE4YoJKeDmegvY1I_p2bB9Ndgk"/>
                                    </div>
                                </div>

                                <!-- Floating Badge Indicator -->
                                <div class="absolute bottom-0 left-0 sm:-bottom-2 sm:-left-2 z-30 flex items-center gap-2 p-2 sm:p-space-sm px-3 sm:px-space-md rounded-xl bg-surface-container-high/95 backdrop-blur-md border border-outline-variant/40 shadow-xl scale-90 sm:scale-100 origin-bottom-left">
                                    <span class="material-symbols-outlined text-secondary text-[20px] sm:text-[22px]" style="font-variation-settings: 'FILL' 1;">bolt</span>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-xs sm:text-body-sm font-bold text-on-surface leading-tight">Cargas en Vivo</span>
                                        <span class="font-body-sm text-[10px] sm:text-[11px] text-on-surface-variant leading-tight">Sincronización instantánea</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ROLES & ADAPTIVE ECOSYSTEM SECTION -->
            <section class="w-full py-14 sm:py-20 bg-surface-container-lowest" id="roles">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex flex-col gap-8 sm:gap-space-xl">
                    
                    <!-- Section Header -->
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-space-md">
                        <div class="flex flex-col gap-1 sm:gap-space-xs">
                            <span class="font-label-caps text-xs text-primary uppercase tracking-widest font-bold">Flexibilidad Operativa</span>
                            <h2 class="font-headline-lg text-2xl sm:text-headline-lg uppercase text-on-surface">Un Ecosistema Adaptado a Cada Rol</h2>
                        </div>
                        <p class="font-body-md text-sm sm:text-body-md text-on-surface-variant max-w-md">
                            Ya sea que lideres una plantilla de atletas, gestiones un club comercial o disciplines tus propios levantamientos, AF Training tiene una interfaz nativa para ti.
                        </p>
                    </div>

                    <!-- 4 Pillars Grid (1 col on mobile, 2 on tablet, 4 on desktop) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-space-lg">
                        
                        <!-- CARD 1: COACH / ENTRENADOR -->
                        <div class="flex flex-col justify-between p-5 sm:p-space-lg rounded-2xl bg-surface-container border border-outline-variant/20 transition-all hover:bg-surface-container-high hover:border-primary/40 shadow-md">
                            <div class="flex flex-col gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[24px] sm:text-[26px]">sports</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-label-caps text-[10px] text-primary uppercase font-bold tracking-widest">Para Profesionales</span>
                                    <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Coach / Entrenador</h3>
                                </div>
                                <ul class="flex flex-col gap-2.5 pt-1">
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Registro biométrico integral (% grasa, tallas y peso actual).</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Revisión fotográfica 3 ángulos: Frente, Perfil y Espalda.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Prescripción nutricional TDEE con balance exacto de macros.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Programación de microciclos divididos por grupos musculares.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="pt-5 sm:pt-space-lg">
                                <a class="inline-flex items-center gap-1 font-title-sm text-xs sm:text-body-sm text-primary hover:text-on-surface transition-colors" href="#modulos">
                                    <span>Ver suite de coaches</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>
                        </div>

                        <!-- CARD 2: ATLETA INDEPENDIENTE -->
                        <div class="flex flex-col justify-between p-5 sm:p-space-lg rounded-2xl bg-surface-container border border-outline-variant/20 transition-all hover:bg-surface-container-high hover:border-secondary/40 shadow-md">
                            <div class="flex flex-col gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                    <span class="material-symbols-outlined text-[24px] sm:text-[26px]">fitness_center</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-label-caps text-[10px] text-secondary uppercase font-bold tracking-widest">Autonomía Total</span>
                                    <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Usuario Independiente</h3>
                                </div>
                                <ul class="flex flex-col gap-2.5 pt-1">
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Configura tus propias rutinas y planes de comidas sin intermediarios.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Bitácora activa para ingresar series, repeticiones efectivas y RPE.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Seguimiento de peso corporal y evolución fotográfica privada.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Calculadora integrada de déficit y superávit por ciclo.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="pt-5 sm:pt-space-lg">
                                <a class="inline-flex items-center gap-1 font-title-sm text-xs sm:text-body-sm text-secondary hover:text-on-surface transition-colors" href="#modulos">
                                    <span>Empezar por tu cuenta</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>
                        </div>

                        <!-- CARD 3: GIMNASIO / CENTRO DEPORTIVO -->
                        <div class="flex flex-col justify-between p-5 sm:p-space-lg rounded-2xl bg-surface-container border border-outline-variant/20 transition-all hover:bg-surface-container-high hover:border-tertiary/40 shadow-md">
                            <div class="flex flex-col gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-tertiary-container/20 flex items-center justify-center text-primary-fixed">
                                    <span class="material-symbols-outlined text-[24px] sm:text-[26px]">corporate_fare</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-label-caps text-[10px] text-tertiary uppercase font-bold tracking-widest">Sedes &amp; Staff</span>
                                    <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Gimnasio / Box</h3>
                                </div>
                                <ul class="flex flex-col gap-2.5 pt-1">
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-tertiary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Diseña rutinas según el inventario de máquinas de tu sede.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-tertiary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Plantillas unificadas para entrenadores de piso y staff personal.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-tertiary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Control de fidelidad, asistencia y cumplimiento de socios.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-tertiary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Monitoreo masivo del avance físico comunitario.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="pt-5 sm:pt-space-lg">
                                <a class="inline-flex items-center gap-1 font-title-sm text-xs sm:text-body-sm text-tertiary hover:text-on-surface transition-colors" href="#workflow">
                                    <span>Soluciones para gimnasios</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>
                        </div>

                        <!-- CARD 4: CLIENTE ASESORADO -->
                        <div class="flex flex-col justify-between p-5 sm:p-space-lg rounded-2xl bg-surface-container border border-outline-variant/20 transition-all hover:bg-surface-container-high hover:border-primary/40 shadow-md">
                            <div class="flex flex-col gap-4">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-surface-bright flex items-center justify-center text-on-surface">
                                    <span class="material-symbols-outlined text-[24px] sm:text-[26px]">person_check</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-label-caps text-[10px] text-on-surface-variant uppercase font-bold tracking-widest">Experiencia Guiada</span>
                                    <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Cliente / Atleta</h3>
                                </div>
                                <ul class="flex flex-col gap-2.5 pt-1">
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Acceso directo al plan asignado por tu preparador físico.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Botón directo para iniciar la sesión del día paso a paso.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Consulta de tomas de comida, menú y meta de calorías diarias.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5 font-body-sm text-xs sm:text-body-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                        <span>Carga sencilla de fotos y pesajes para retroalimentación instantánea.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="pt-5 sm:pt-space-lg">
                                <a class="inline-flex items-center gap-1 font-title-sm text-xs sm:text-body-sm text-on-surface hover:text-primary transition-colors" href="#workflow">
                                    <span>App de usuario final</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- DEEP-DIVE MODULES SECTION -->
            <section class="w-full py-16 sm:py-24 bg-surface relative" id="modulos">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex flex-col gap-16 sm:gap-24 lg:gap-28">
                    
                    <!-- MODULE 1: CALCULO CALORICO & NUTRICION -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Mockup Display Left -->
                        <div class="lg:col-span-5 order-2 lg:order-1 flex justify-center">
                            <div class="relative w-full max-w-xs sm:max-w-sm rounded-[28px] sm:rounded-[32px] p-2 sm:p-2.5 bg-surface-container-high border border-outline-variant/30 shadow-2xl">
                                <div class="w-full rounded-[20px] sm:rounded-[24px] overflow-hidden bg-surface-container-lowest">
                                    <img alt="Calculadora de Requerimientos Nutricionales AF Training" class="w-full h-auto object-cover block" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuB7-KPC_QyHNUHt79Pux5csGMNEjhtMx5dHj1wjhh7vUbml-6q5a2X2YqeUCofZUY09CyvXQISdJr43gwMDCC11JR5losU3qHrPTomH32kzgXshkwxIWoiWFeVWRIMX6zfTM-b_uLZMyIvDjQfdkA1nKTGBbrjJ7txYH9kbzaQZRXo8E5Ggg-lsRNw8726gJ0fTt_ldG0eGSTKE25WG42KKlBMEXsd8gvjBEz1i1O8VpT7iz602r3JBYD-wvlEPdydoyPk"/>
                                </div>
                                <!-- Inset Floating Data Pill -->
                                <div class="absolute -top-3 right-2 sm:-top-4 sm:-right-4 p-1.5 sm:p-space-sm px-3 sm:px-space-md rounded-xl bg-surface-container-highest border border-outline-variant/40 shadow-xl flex items-center gap-1.5 sm:gap-space-sm">
                                    <span class="material-symbols-outlined text-secondary text-[18px] sm:text-[20px]">nutrition</span>
                                    <span class="font-title-sm text-xs sm:text-body-sm font-bold text-on-surface">Mifflin-St Jeor Integrado</span>
                                </div>
                            </div>
                        </div>

                        <!-- Copy & Technical Features Right -->
                        <div class="lg:col-span-7 order-1 lg:order-2 flex flex-col gap-3 sm:gap-space-md">
                            <div class="inline-flex items-center gap-1.5 text-secondary">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">calculate</span>
                                <span class="font-label-caps text-xs sm:text-label-caps uppercase tracking-wider font-bold">Módulo 01 • Ciencias Biológicas</span>
                            </div>

                            <h3 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface uppercase font-bold">
                                Cálculo Calórico y Nutrición de <span class="text-secondary">Alta Precisión</span>
                            </h3>

                            <p class="font-body-lg text-sm sm:text-base lg:text-body-lg text-on-surface-variant leading-relaxed">
                                Elimina el tanteo y las hojas de cálculo confusas. El motor de prescripción nutricional procesa automáticamente variables biométricas (género, edad, peso, estatura, nivel de actividad) para calcular el TDEE exacto y programar ajustes milimétricos según el objetivo.
                            </p>

                            <!-- Feature Cards Grid (1 col on small phones, 2 on tablet/desktop) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-space-md pt-2 sm:pt-space-sm">
                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1 sm:gap-space-xs">
                                    <div class="flex items-center gap-2 text-primary">
                                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">tune</span>
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Déficit / Superávit</span>
                                    </div>
                                    <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Ajustes directos en Kcal (-300, -500 o volumen limpio) con adaptación de gasto total.</p>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1 sm:gap-space-xs">
                                    <div class="flex items-center gap-2 text-secondary">
                                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">pie_chart</span>
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Desglose de Macros</span>
                                    </div>
                                    <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Reparto automatizado de Proteínas, Carbohidratos y Lípidos tanto en gramos como en porcentaje calórico.</p>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1 sm:gap-space-xs">
                                    <div class="flex items-center gap-2 text-on-surface">
                                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">restaurant_menu</span>
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Tiempos de Comida</span>
                                    </div>
                                    <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Distribución del valor diario en 3, 4, 5 o más tomas nutricionales según la rutina del atleta.</p>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex flex-col gap-1 sm:gap-space-xs">
                                    <div class="flex items-center gap-2 text-tertiary">
                                        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">update</span>
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Ajuste Dinámico</span>
                                    </div>
                                    <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Modifica los requerimientos con un click cuando el atleta registre una variación de peso o biometría.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MODULE 2: PLANIFICADOR DE ENTRENAMIENTO -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Copy Left -->
                        <div class="lg:col-span-7 flex flex-col gap-3 sm:gap-space-md">
                            <div class="inline-flex items-center gap-1.5 text-primary">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">exercise</span>
                                <span class="font-label-caps text-xs sm:text-label-caps uppercase tracking-wider font-bold">Módulo 02 • Periodización Aplicada</span>
                            </div>

                            <h3 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface uppercase font-bold">
                                Planificador de Entrenamiento <span class="text-primary">Dinámico &amp; Estructurado</span>
                            </h3>

                            <p class="font-body-lg text-sm sm:text-base lg:text-body-lg text-on-surface-variant leading-relaxed">
                                Construye programas por microciclos y mesociclos organizados por sesiones (Espalda, Bíceps, Hombros, Cuádriceps o Fullbody). Tus clientes acceden a su rutina del día, leen las indicaciones técnicas y pulsan un solo botón para ejecutar.
                            </p>

                            <div class="flex flex-col gap-2.5 sm:gap-space-sm pt-1 sm:pt-space-xs">
                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-start gap-3 sm:gap-space-md">
                                    <div class="p-2 sm:p-space-xs rounded-lg bg-primary-container/20 text-primary shrink-0">
                                        <span class="material-symbols-outlined text-[20px] sm:text-[24px]">play_circle</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Modo Sesión Activa</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">El atleta marca ejercicios completados, añade notas de fatiga y registra kilos levantados en tiempo real.</span>
                                    </div>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-start gap-3 sm:gap-space-md">
                                    <div class="p-2 sm:p-space-xs rounded-lg bg-secondary-container/20 text-secondary shrink-0">
                                        <span class="material-symbols-outlined text-[20px] sm:text-[24px]">view_timeline</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Historial de Meses Anteriores</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Navegación fluida por pestañas de periodización (Junio 2026, Septiembre 2026, Octubre 2026) para revisar la sobrecarga progresiva.</span>
                                    </div>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-start gap-3 sm:gap-space-md">
                                    <div class="p-2 sm:p-space-xs rounded-lg bg-surface-bright text-on-surface shrink-0">
                                        <span class="material-symbols-outlined text-[20px] sm:text-[24px]">menu_book</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Biblioteca de Ejercicios Centralizada</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Catálogo completo de movimientos con asignación de repeticiones objetivo, series efectivas y variantes por máquina.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mockup Display Right -->
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="relative w-full max-w-xs sm:max-w-sm rounded-[28px] sm:rounded-[32px] p-2 sm:p-2.5 bg-surface-container-high border border-outline-variant/30 shadow-2xl">
                                <div class="w-full rounded-[20px] sm:rounded-[24px] overflow-hidden bg-surface-container-lowest">
                                    <img alt="Desglose de Rutinas y Sesiones AF Training" class="w-full h-auto object-cover block" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-qYG8vfXEAYGPCa2ECcMFu7pkAHmAYwPtuCkmyLsg4ruvNnm9Z0eeeTeRYREjy4oV5zeE0SPX2Rc0hwRKZM2CxzmfIimTwdHaGgqZoJekJ4uEkOgZE8P1yKlfvu5EMBkouRRqtCZhzAJGiIqLXdxC0aAQtdcmQu3GHykN_k_D-vRBnuOhlf7O8ZB0SdRoSXL3SoxhRtJjQoOFPxC30cdkiHZXZIl-lg_hNIYKR5IWEAPltEnnwsWk2IkDppEdHyXx5BA"/>
                                </div>
                                <!-- Bottom Action Floating Banner -->
                                <div class="absolute -bottom-3 inset-x-4 sm:-bottom-4 sm:inset-x-6 p-2 sm:p-space-sm bg-primary text-on-primary rounded-xl font-headline-md text-center text-xs sm:text-title-sm uppercase tracking-wider shadow-lg">
                                    Comenzar Entrenamiento
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MODULE 3: BIOMETRIA Y FOTOS 360 -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Dual Mockup Display Left -->
                        <div class="lg:col-span-6 order-2 lg:order-1 flex items-center justify-center gap-3 sm:gap-space-md">
                            <!-- Screen 3: Registrar Progreso -->
                            <div class="w-36 sm:w-52 md:w-64 rounded-[22px] sm:rounded-[28px] p-1.5 sm:p-2 bg-surface-container-high border border-outline-variant/30 shadow-xl shrink-0">
                                <div class="w-full rounded-[16px] sm:rounded-[20px] overflow-hidden bg-surface-container-lowest">
                                    <img alt="Registro de Progreso y Fotos AF Training" class="w-full h-auto object-cover block" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuBF_CKv5Ce9dr-eXbHQMMaVvNFtg7wLes2r27m05-iQ3sRiORy5mCSAL4sq493lm4qBYAWixOIFhAIvcxbtZpQobDm2YqB8Ch3qVmIhZmvrUJWA5nawV6DgbLs_BZAqJTrbaQ7xKWsSoLacis9Jj__SvKbFEzNpXzwClKEBItYvtackW-oboIldXSmEpoAJsBypmhzQlJPiUocuNMr8Gud5e7c8OsXCotZLiHiZngLqkS3KAarskogCCdAxY_GRrg6PNqM"/>
                                </div>
                            </div>

                            <!-- Screen 5: Expediente Nayeli Juarez -->
                            <div class="w-36 sm:w-52 md:w-64 rounded-[22px] sm:rounded-[28px] p-1.5 sm:p-2 bg-surface-container-highest border border-outline-variant/40 shadow-2xl mt-4 sm:mt-8 shrink-0">
                                <div class="w-full rounded-[16px] sm:rounded-[20px] overflow-hidden bg-surface-container-lowest">
                                    <img alt="Expediente de Evaluaciones del Atleta" class="w-full h-auto object-cover block" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuBETvSZw3GCDV2AuB6j4bHNMyt368DIwjJsRSGMmWJO0rmOP4bSD5bnqzhRkKfyN9Xd2QBNzV9qASByvojjcsg0WHeS7VysCCfiR_wXcT6e0zIIfcOCMO8AgJcgw9xpxVfeHyVMlYeCBZeh2IiBqqzeVVysDVX0auzun33Thv1bHf8Q0YOla250vG2qNTYXzFJN4PjD3SaiooQsBTXoBftA19SlwuOOl3hkMV-GAjC4UGc_mYOIzKO25DopGNRe_WDjAYc"/>
                                </div>
                            </div>
                        </div>

                        <!-- Copy Right -->
                        <div class="lg:col-span-6 order-1 lg:order-2 flex flex-col gap-3 sm:gap-space-md">
                            <div class="inline-flex items-center gap-1.5 text-primary">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">monitor_weight</span>
                                <span class="font-label-caps text-xs sm:text-label-caps uppercase tracking-wider font-bold">Módulo 03 • Evaluación Antropométrica</span>
                            </div>

                            <h3 class="font-headline-lg text-2xl sm:text-headline-lg text-on-surface uppercase font-bold">
                                Biometría Avanzada y Comparativa <span class="text-primary">Fotográfica 360°</span>
                            </h3>

                            <p class="font-body-lg text-sm sm:text-base lg:text-body-lg text-on-surface-variant leading-relaxed">
                                El progreso físico no es una báscula solitaria. Con AF Training vinculas composición corporal real con registro visual estandarizado. Cada revisión genera un hito evaluativo fechado con acceso a gráficos históricos.
                            </p>

                            <div class="grid grid-cols-1 gap-2.5 sm:gap-space-sm pt-1 sm:pt-space-xs">
                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center gap-3 sm:gap-space-md">
                                    <span class="material-symbols-outlined text-secondary text-[20px] sm:text-[24px] shrink-0">camera_alt</span>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Fotografía en 3 Ángulos Clave</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Carga organizada para Frente, Perfil y Espalda en cada pesaje programado.</span>
                                    </div>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center gap-3 sm:gap-space-md">
                                    <span class="material-symbols-outlined text-primary text-[20px] sm:text-[24px] shrink-0">timeline</span>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Historial de Evaluaciones Biométricas</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Archiva peso, edad metabólica, estatura y porcentaje de grasa con notas clínicas.</span>
                                    </div>
                                </div>

                                <div class="p-3.5 sm:p-space-md rounded-xl bg-surface-container-low border border-outline-variant/20 flex items-center gap-3 sm:gap-space-md">
                                    <span class="material-symbols-outlined text-tertiary text-[20px] sm:text-[24px] shrink-0">folder_shared</span>
                                    <div class="flex flex-col">
                                        <span class="font-title-sm text-sm sm:text-body-md font-bold text-on-surface">Expediente Centralizado por Atleta</span>
                                        <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">Planes de entrenamiento y de nutrición sincronizados en una sola tarjeta de atleta.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- STEP-BY-STEP WORKFLOW SECTION -->
            <section class="w-full py-14 sm:py-20 bg-surface-container-lowest" id="workflow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex flex-col gap-8 sm:gap-space-xl">
                    
                    <!-- Section Title -->
                    <div class="flex flex-col items-center text-center gap-1 sm:gap-space-xs">
                        <span class="font-label-caps text-xs text-secondary uppercase tracking-widest font-bold">Flujo Operativo</span>
                        <h2 class="font-headline-lg text-2xl sm:text-headline-lg uppercase text-on-surface">Diseñado para la Velocidad en el Gimnasio</h2>
                        <p class="font-body-md text-sm sm:text-body-md text-on-surface-variant max-w-xl">
                            Tres fases sistemáticas para llevar a tus clientes desde la biometría inicial hasta su máximo potencial atlético.
                        </p>
                    </div>

                    <!-- 3 Steps Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-space-lg relative">
                        <!-- Step 1 -->
                        <div class="p-5 sm:p-space-xl rounded-2xl bg-surface-container border border-outline-variant/20 flex flex-col gap-3 sm:gap-space-md relative overflow-hidden shadow-sm hover:border-primary/40 transition-colors">
                            <div class="font-display-lg text-3xl sm:text-display-lg font-black text-surface-bright/50">01</div>
                            <div class="flex flex-col gap-1 sm:gap-space-xs">
                                <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Alta &amp; Biometría</h3>
                                <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant leading-relaxed">
                                    Registra a tu cliente, introduce sus medidas de inicio, define el nivel de actividad y carga sus tres fotografías de referencia (Frente, Perfil, Espalda).
                                </p>
                            </div>
                            <div class="pt-2 sm:pt-space-sm flex items-center gap-1.5 text-primary font-body-sm text-[12px]">
                                <span class="material-symbols-outlined text-[16px]">fingerprint</span>
                                <span>Perfil biométrico blindado</span>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="p-5 sm:p-space-xl rounded-2xl bg-surface-container border border-outline-variant/20 flex flex-col gap-3 sm:gap-space-md relative overflow-hidden shadow-sm hover:border-secondary/40 transition-colors">
                            <div class="font-display-lg text-3xl sm:text-display-lg font-black text-secondary/40">02</div>
                            <div class="flex flex-col gap-1 sm:gap-space-xs">
                                <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Prescribe Plan &amp; Dieta</h3>
                                <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant leading-relaxed">
                                    La plataforma calcula el TDEE y desglosa los macros diarios. Selecciona ejercicios de la biblioteca, configura días de entreno y asígnalo con un toque.
                                </p>
                            </div>
                            <div class="pt-2 sm:pt-space-sm flex items-center gap-1.5 text-secondary font-body-sm text-[12px]">
                                <span class="material-symbols-outlined text-[16px]">bolt</span>
                                <span>Asignación en menos de 5 min</span>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="p-5 sm:p-space-xl rounded-2xl bg-surface-container border border-outline-variant/20 flex flex-col gap-3 sm:gap-space-md relative overflow-hidden shadow-sm hover:border-tertiary/40 transition-colors">
                            <div class="font-display-lg text-3xl sm:text-display-lg font-black text-surface-bright/50">03</div>
                            <div class="flex flex-col gap-1 sm:gap-space-xs">
                                <h3 class="font-headline-md text-lg sm:text-headline-md text-on-surface">Seguimiento &amp; Sobrecarga</h3>
                                <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant leading-relaxed">
                                    El atleta entrena en vivo registrando kilos y comidas. Evalúa el avance semanal y ajusta series o calorías sobre números objetivos.
                                </p>
                            </div>
                            <div class="pt-2 sm:pt-space-sm flex items-center gap-1.5 text-tertiary font-body-sm text-[12px]">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                <span>Sobrecarga progresiva real</span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- OFFICIAL LOGO SHOWCASE / BRAND STORY -->
            <section class="w-full py-12 sm:py-16 bg-surface">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
                    <div class="rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-12 bg-surface-container-low border border-outline-variant/30 flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-space-xl shadow-lg">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 sm:gap-space-lg w-full lg:w-auto">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-32 lg:h-32 rounded-2xl bg-surface-container-high p-3 sm:p-4 flex items-center justify-center shrink-0 shadow-md border border-outline-variant/20">
                                <img alt="AF Training Official Logo" class="max-h-full max-w-full object-contain" 
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxfLrSxWTXB5aUevwV5BAR3SlT67MWqLl4JqxEc8TybHIQSy4yxkGwBPolsoMBVoyGGNxBA9uo0T-VDYjcQaDth_NCUCoJdfyxS6LKUvi7i1zzu0EjbbTnSzvRb1DS1Y_PlazneME6Fea2NMjIiMOESUsO8tRBOq0wHXQhAmBAAGak4RLpufAdPaWihodj77grI4oUgbsx5gyTM554qwQbjJYn0-dj8Q_M0Krsei486fhzbYNoq7E5dih8uST6XAb9svI"
                                     onerror="this.src='{{ asset('images/logo.png') }}'"/>
                            </div>
                            <div class="flex flex-col gap-1 sm:gap-space-xs">
                                <span class="font-label-caps text-xs text-secondary uppercase font-bold tracking-widest">Filosofía de Rendimiento</span>
                                <h3 class="font-headline-md text-xl sm:text-headline-md text-on-surface uppercase">Elevate Your Fitness</h3>
                                <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant max-w-lg leading-relaxed">
                                    La llama naranja y la onda azul de nuestro isotipo representan la combustión metabólica y la precisión metódica. Convertimos el entrenamiento de fuerza en ciencia medible.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-space-sm w-full lg:w-auto">
                            <div class="px-3 sm:px-space-md py-1.5 sm:py-space-xs rounded-lg bg-surface-container border border-outline-variant/20 font-title-sm text-xs sm:text-body-sm text-on-surface-variant">
                                iOS Nativo
                            </div>
                            <div class="px-3 sm:px-space-md py-1.5 sm:py-space-xs rounded-lg bg-surface-container border border-outline-variant/20 font-title-sm text-xs sm:text-body-sm text-on-surface-variant">
                                Android APK
                            </div>
                            <div class="px-3 sm:px-space-md py-1.5 sm:py-space-xs rounded-lg bg-surface-container border border-outline-variant/20 font-title-sm text-xs sm:text-body-sm text-on-surface-variant">
                                Web Dashboard Coach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FINAL CTA & CONVERSION BANNER -->
            <section class="w-full py-16 sm:py-24 bg-surface-container-lowest relative overflow-hidden">
                <!-- Atmospheric Flare -->
                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-48 sm:h-64 bg-gradient-to-t from-primary/10 via-secondary-container/5 to-transparent"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
                    <div class="rounded-2xl sm:rounded-3xl p-6 sm:p-10 lg:p-16 bg-gradient-to-br from-surface-container-high via-surface-container to-surface-container-lowest border border-outline-variant/30 shadow-2xl flex flex-col items-center text-center gap-4 sm:gap-space-lg">
                        <div class="inline-flex items-center gap-1.5 sm:gap-space-sm px-3 sm:px-space-md py-1 sm:py-space-xs rounded-full bg-secondary-container/20 text-secondary border border-secondary/30">
                            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">verified</span>
                            <span class="font-label-caps text-xs sm:text-label-caps uppercase tracking-wider font-bold">14 Días de Prueba Ilimitada</span>
                        </div>

                        <h2 class="font-display-lg text-2xl sm:text-3xl md:text-4xl lg:text-[44px] uppercase text-on-surface max-w-3xl font-black">
                            ¿Listo para elevar tu estándar de <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">entrenamiento</span>?
                        </h2>

                        <p class="font-body-lg text-sm sm:text-base lg:text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                            Únete a la nueva era del fitness inteligente. Gestiona clientes, automatiza el cálculo de calorías y lleva el control absoluto de tus sesiones en una sola pantalla.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-space-md pt-2 sm:pt-space-sm w-full sm:w-auto">
                            <a class="inline-flex items-center justify-center gap-2 font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-secondary-container text-on-secondary-container hover:bg-secondary hover:text-on-secondary px-6 sm:px-space-xl py-3.5 sm:py-4 rounded-xl transition-all shadow-[0_0_24px_rgba(236,106,6,0.35)] hover:scale-[1.02] text-center" href="{{ Route::has('login') ? route('login') : '/login' }}">
                                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">rocket_launch</span>
                                <span>Crear Cuenta Gratis</span>
                            </a>
                            <a class="inline-flex items-center justify-center gap-2 font-headline-md text-xs sm:text-title-sm uppercase tracking-wider bg-surface-bright text-on-surface hover:bg-surface-container-highest border border-outline-variant/30 px-6 sm:px-space-xl py-3.5 sm:py-4 rounded-xl transition-all text-center" href="#roles">
                                <span class="material-symbols-outlined text-primary text-[18px] sm:text-[20px]">calendar_today</span>
                                <span>Agendar Demo de Gimnasio</span>
                            </a>
                        </div>

                        <!-- Download Buttons Strip -->
                        <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-space-md pt-4 sm:pt-space-lg text-on-surface-variant text-center">
                            <span class="font-body-sm text-xs sm:text-body-sm">Disponible para smartphones y tablets:</span>
                            <div class="flex items-center gap-2 sm:gap-space-sm">
                                <div class="flex items-center gap-1.5 px-3 sm:px-space-md py-1.5 sm:py-space-xs rounded-lg bg-surface-container border border-outline-variant/20 cursor-pointer hover:bg-surface-bright transition-colors">
                                    <span class="material-symbols-outlined text-[16px] sm:text-[18px] text-on-surface">phone_iphone</span>
                                    <span class="font-title-sm text-xs sm:text-body-sm text-on-surface font-semibold">App Store</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-3 sm:px-space-md py-1.5 sm:py-space-xs rounded-lg bg-surface-container border border-outline-variant/20 cursor-pointer hover:bg-surface-bright transition-colors">
                                    <span class="material-symbols-outlined text-[16px] sm:text-[18px] text-secondary">play_arrow</span>
                                    <span class="font-title-sm text-xs sm:text-body-sm text-on-surface font-semibold">Google Play</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="w-full bg-surface-container-lowest border-t border-outline-variant/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 pt-12 sm:pt-16 pb-8 sm:pb-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 sm:gap-space-xl pb-10 sm:pb-16">
                
                <div class="sm:col-span-2 lg:col-span-1 flex flex-col gap-3 sm:gap-space-md">
                    <div class="flex items-center gap-2 sm:gap-space-sm">
                        <img alt="AF Training Logo" class="h-6 sm:h-7 w-auto object-contain" 
                             src="https://lh3.googleusercontent.com/aida/AEtjO1XvZZkhZyrpeO-BCxXjjpe8ZNbIV3A6Vw6q3ElUbGCB_QtPYPsMxLgCjicFw0EykjxxIqN2XBz--QN28zTl69ydN91S70kX_pbR1ahRXSFH7SBzie5LgXXvmGRx5EFxhGm4bGfcfoNXFz0L2EUm9gOGzS0BjdBqg5rRljvS0ZGUfc2x2yCTLdlZGtdYYGjysWyrzWpYttaKJq2gDZibTckN8Agr600vfdCRu8rM_Ei0xkLtmck4ELn_1fsq0_kQodBhtW5lo_4dD9A"
                             onerror="this.src='{{ asset('images/logo.png') }}'"/>
                        <span class="font-headline-md text-base sm:text-headline-md text-on-surface uppercase">AF Training</span>
                    </div>
                    <p class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant leading-relaxed">
                        Plataforma integral de alto rendimiento para entrenadores, centros deportivos y atletas de élite. Ciencia y disciplina aplicadas.
                    </p>
                    <div class="flex flex-col gap-2 mt-2">
                        <span class="font-label-caps text-xs text-primary uppercase font-bold">Descarga Nuestra App</span>
                        <div class="flex flex-row sm:flex-col gap-2">
                            <div class="flex-1 flex items-center gap-2 px-3 py-2 bg-surface-container border border-outline-variant/20 rounded-lg hover:bg-surface-container-high cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-primary text-[18px]">phone_iphone</span>
                                <div class="flex flex-col">
                                    <span class="font-body-sm text-[9px] text-on-surface-variant leading-none">Disponible en</span>
                                    <span class="font-title-sm text-xs text-on-surface font-semibold leading-tight">App Store</span>
                                </div>
                            </div>
                            <div class="flex-1 flex items-center gap-2 px-3 py-2 bg-surface-container border border-outline-variant/20 rounded-lg hover:bg-surface-container-high cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-secondary text-[18px]">play_arrow</span>
                                <div class="flex flex-col">
                                    <span class="font-body-sm text-[9px] text-on-surface-variant leading-none">Consíguelo en</span>
                                    <span class="font-title-sm text-xs text-on-surface font-semibold leading-tight">Google Play</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:gap-space-sm">
                    <span class="font-title-sm text-sm sm:text-title-sm text-on-surface font-bold">Para Coaches</span>
                    <div class="flex flex-col gap-1.5 sm:gap-space-xs">
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Biblioteca de Ejercicios</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Constructor de Rutinas</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Seguimiento de Clientes</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Cobros &amp; Suscripciones</a>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:gap-space-sm">
                    <span class="font-title-sm text-sm sm:text-title-sm text-on-surface font-bold">Gimnasios &amp; Boxes</span>
                    <div class="flex flex-col gap-1.5 sm:gap-space-xs">
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Gestión de Sedes</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Control de Acceso</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Gestión de Staff</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Analíticas de Retención</a>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:gap-space-sm">
                    <span class="font-title-sm text-sm sm:text-title-sm text-on-surface font-bold">Atletas &amp; Clientes</span>
                    <div class="flex flex-col gap-1.5 sm:gap-space-xs">
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Registro de Cargas</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Métricas de Composición</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Nutrición &amp; Calorías</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Comunidad AF</a>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:gap-space-sm">
                    <span class="font-title-sm text-sm sm:text-title-sm text-on-surface font-bold">Plataforma</span>
                    <div class="flex flex-col gap-1.5 sm:gap-space-xs">
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#modulos">Todos los Módulos</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#roles">Precios y Planes</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Términos del Servicio</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Aviso de Privacidad</a>
                        <a class="font-body-md text-xs sm:text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Seguridad &amp; Cifrado</a>
                    </div>
                </div>

            </div>

            <div class="pt-6 sm:pt-8 border-t border-outline-variant/10 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <span class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant">© {{ date('Y') }} AF Training Technologies Inc. Todos los derechos reservados.</span>
                <div class="flex items-center gap-4 sm:gap-space-lg">
                    <a class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">Privacidad</a>
                    <a class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">Términos</a>
                    <a class="font-body-sm text-xs sm:text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">Seguridad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const menuDrawer = document.getElementById('mobile-menu-drawer');
            const menuIcon = document.getElementById('mobile-menu-icon');
            const navLinks = document.querySelectorAll('.mobile-nav-link');

            if (menuBtn && menuDrawer) {
                menuBtn.addEventListener('click', () => {
                    const isHidden = menuDrawer.classList.contains('hidden');
                    if (isHidden) {
                        menuDrawer.classList.remove('hidden');
                        menuIcon.textContent = 'close';
                    } else {
                        menuDrawer.classList.add('hidden');
                        menuIcon.textContent = 'menu';
                    }
                });

                // Auto close menu when a navigation anchor is clicked
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        menuDrawer.classList.add('hidden');
                        menuIcon.textContent = 'menu';
                    });
                });
            }
        });
    </script>

</body>
</html>
