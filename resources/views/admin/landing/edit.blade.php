@extends('layouts.admin')

@section('title', 'Administración del Landing Page - Super Admin')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-white tracking-tight">Gestor de Landing Page</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-400/10 text-amber-400 border border-amber-400/20">
                        Super Admin
                    </span>
                </div>
                <p class="text-sm text-slate-400 mt-1">Control total de textos, métricas, imágenes, enlaces de contacto y visibilidad de secciones.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Ver Landing Público -->
            <a href="{{ route('landing.home', ['preview' => 1]) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-200 bg-slate-800 hover:bg-slate-700 hover:text-white border border-slate-700 transition duration-150 shadow-sm">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Ver Landing en Vivo</span>
            </a>

            <!-- Restaurar Valores de Fábrica -->
            <form action="{{ route('admin.landing.reset') }}" method="POST" onsubmit="return confirm('¿Estás seguro de restaurar todos los textos e imágenes a los valores predeterminados de fábrica?');">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-500/10 border border-slate-700/60 hover:border-red-500/30 transition duration-150">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Restaurar Fábrica</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm animate-fade-in">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <p class="font-bold mb-1">Por favor verifica los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Configuration Form -->
    <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data" id="landing-form">
        @csrf
        @method('PUT')

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Navigation Tabs Bar -->
            <div class="border-b border-slate-800 bg-slate-900/60 backdrop-blur-md px-4 sm:px-6 flex overflow-x-auto gap-2 py-3" id="tab-buttons">
                <button type="button" onclick="switchTab('hero')" id="tab-btn-hero"
                    class="tab-btn active px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap bg-indigo-600 text-white shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Hero Principal</span>
                </button>

                <button type="button" onclick="switchTab('metrics')" id="tab-btn-metrics"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Métricas & Cifras</span>
                </button>

                <button type="button" onclick="switchTab('mockups')" id="tab-btn-mockups"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Mockups & Pantallas</span>
                </button>

                <button type="button" onclick="switchTab('contact')" id="tab-btn-contact"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Contacto & WhatsApp</span>
                </button>

                <button type="button" onclick="switchTab('social')" id="tab-btn-social"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span>Redes & App Stores</span>
                </button>

                <button type="button" onclick="switchTab('cta')" id="tab-btn-cta"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                    <span>CTA Final & Filosofía</span>
                </button>

                <button type="button" onclick="switchTab('visibility')" id="tab-btn-visibility"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Visibilidad de Secciones</span>
                </button>

                <button type="button" onclick="switchTab('seo')" id="tab-btn-seo"
                    class="tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 whitespace-nowrap text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>SEO & Marca</span>
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="p-6 sm:p-8 space-y-8">

                <!-- TAB 1: HERO PRINCIPAL -->
                <div id="tab-pane-hero" class="tab-pane space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Encabezado y Mensaje Principal (Hero Section)
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Configura el título de impacto, la descripción principal y las llamadas a la acción superiores.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Badge superior -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Insignia Superior (Badge)
                            </label>
                            <input type="text" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                            <p class="text-xs text-slate-500 mt-1.5">Ej: Plataforma Todo-en-Uno • Versión 3.0</p>
                        </div>

                        <!-- Título Prefix -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Título (Parte 1 - Texto Inicial Blanco)
                            </label>
                            <input type="text" name="hero_title_prefix" value="{{ old('hero_title_prefix', $settings['hero_title_prefix'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                            <p class="text-xs text-slate-500 mt-1.5">Ej: La Plataforma Definitiva de</p>
                        </div>

                        <!-- Título Highlight (Gradient) -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Título (Parte 2 - Destacado con Gradiente Azul/Naranja)
                            </label>
                            <input type="text" name="hero_title_highlight" value="{{ old('hero_title_highlight', $settings['hero_title_highlight'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-indigo-500/50 rounded-xl text-indigo-300 font-semibold placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                            <p class="text-xs text-slate-500 mt-1.5">Ej: Entrenamiento, Nutrición</p>
                        </div>

                        <!-- Título Suffix -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Título (Parte 3 - Cierre del Título)
                            </label>
                            <input type="text" name="hero_title_suffix" value="{{ old('hero_title_suffix', $settings['hero_title_suffix'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                            <p class="text-xs text-slate-500 mt-1.5">Ej: &amp; Gestión Deportiva</p>
                        </div>

                        <!-- Subtítulo / Descripción -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Subtítulo / Descripción Principal
                            </label>
                            <textarea name="hero_subtitle" rows="3"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1.5">Párrafo de valor que explica la propuesta a coaches, gimnasios y atletas.</p>
                        </div>

                        <!-- Botón Primario -->
                        <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 space-y-3">
                            <div class="flex items-center gap-2 text-indigo-400 font-semibold text-sm">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                <span>Botón Principal (Azul Neón)</span>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Texto del Botón</label>
                                <input type="text" name="hero_cta_primary_text" value="{{ old('hero_cta_primary_text', $settings['hero_cta_primary_text'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Enlace / Destino (URL o #seccion)</label>
                                <input type="text" name="hero_cta_primary_url" value="{{ old('hero_cta_primary_url', $settings['hero_cta_primary_url'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500 font-mono">
                            </div>
                        </div>

                        <!-- Botón Secundario -->
                        <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 space-y-3">
                            <div class="flex items-center gap-2 text-slate-300 font-semibold text-sm">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <span>Botón Secundario (Oscuro)</span>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Texto del Botón</label>
                                <input type="text" name="hero_cta_secondary_text" value="{{ old('hero_cta_secondary_text', $settings['hero_cta_secondary_text'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Enlace / Destino (URL o #seccion)</label>
                                <input type="text" name="hero_cta_secondary_url" value="{{ old('hero_cta_secondary_url', $settings['hero_cta_secondary_url'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500 font-mono">
                            </div>
                        </div>

                        <!-- Prueba Social (Footer del Hero) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                                Frase de Confianza / Prueba Social
                            </label>
                            <input type="text" name="hero_proof_text" value="{{ old('hero_proof_text', $settings['hero_proof_text'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
                            <p class="text-xs text-slate-500 mt-1.5">Aparece junto a los avatares de entrenadores al pie del hero.</p>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: MÉTRICAS & IMPACTO -->
                <div id="tab-pane-metrics" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Indicadores Clave y Métricas del Hero
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Las 3 tarjetas numéricas que demuestran la escala y capacidad de la plataforma.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Métrica 1 -->
                        <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Métrica 1</span>
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Cifra / Valor</label>
                                <input type="text" name="metric_1_value" value="{{ old('metric_1_value', $settings['metric_1_value'] ?? '+5,000') }}"
                                    class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white font-bold text-lg focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Etiqueta Descriptiva</label>
                                <input type="text" name="metric_1_label" value="{{ old('metric_1_label', $settings['metric_1_label'] ?? 'Planes Activos') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-200 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Métrica 2 -->
                        <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Métrica 2</span>
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Cifra / Valor</label>
                                <input type="text" name="metric_2_value" value="{{ old('metric_2_value', $settings['metric_2_value'] ?? '100%') }}"
                                    class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white font-bold text-lg focus:outline-none focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Etiqueta Descriptiva</label>
                                <input type="text" name="metric_2_label" value="{{ old('metric_2_label', $settings['metric_2_label'] ?? 'TDEE Auto') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-200 text-sm focus:outline-none focus:border-orange-500">
                            </div>
                        </div>

                        <!-- Métrica 3 -->
                        <div class="bg-slate-800/50 p-5 rounded-2xl border border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-cyan-400">Métrica 3</span>
                                <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Cifra / Valor</label>
                                <input type="text" name="metric_3_value" value="{{ old('metric_3_value', $settings['metric_3_value'] ?? '360°') }}"
                                    class="w-full px-3 py-2.5 bg-slate-800 border border-slate-700 rounded-lg text-white font-bold text-lg focus:outline-none focus:border-cyan-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Etiqueta Descriptiva</label>
                                <input type="text" name="metric_3_label" value="{{ old('metric_3_label', $settings['metric_3_label'] ?? 'Biometría') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-200 text-sm focus:outline-none focus:border-cyan-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: MOCKUPS & PANTALLAS -->
                <div id="tab-pane-mockups" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            Mockups y Capturas de Pantalla en el Hero
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Sube capturas reales de tu aplicación móvil o ingresa URLs para los dos dispositivos interactivos.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Mockup 1: Frontal -->
                        <div class="bg-slate-800/40 p-6 rounded-2xl border border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-indigo-400">Dispositivo Frontal (Principal)</span>
                                <span class="text-xs bg-indigo-500/10 text-indigo-400 px-2.5 py-1 rounded-full border border-indigo-500/20">Planes de Entrenamiento</span>
                            </div>

                            <div class="w-full h-56 rounded-xl bg-slate-900 border border-slate-700/80 overflow-hidden flex items-center justify-center p-2 relative group">
                                <img src="{{ $settings['hero_image_1'] ?? '' }}" alt="Vista Previa Dispositivo 1" id="preview-img-1"
                                    class="max-h-full max-w-full object-contain rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-300 font-semibold mb-1.5">Subir Nueva Imagen</label>
                                <input type="file" name="hero_image_1" accept="image/*" onchange="previewImage(this, 'preview-img-1')"
                                    class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-400 mb-1">O mantener URL externa:</label>
                                <input type="text" name="hero_image_1_url" value="{{ old('hero_image_1_url', $settings['hero_image_1'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-xs font-mono focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Mockup 2: Trasero -->
                        <div class="bg-slate-800/40 p-6 rounded-2xl border border-slate-700 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-orange-400">Dispositivo Trasero (Secundario)</span>
                                <span class="text-xs bg-orange-500/10 text-orange-400 px-2.5 py-1 rounded-full border border-orange-500/20">Perfil Head Coach</span>
                            </div>

                            <div class="w-full h-56 rounded-xl bg-slate-900 border border-slate-700/80 overflow-hidden flex items-center justify-center p-2 relative group">
                                <img src="{{ $settings['hero_image_2'] ?? '' }}" alt="Vista Previa Dispositivo 2" id="preview-img-2"
                                    class="max-h-full max-w-full object-contain rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-300 font-semibold mb-1.5">Subir Nueva Imagen</label>
                                <input type="file" name="hero_image_2" accept="image/*" onchange="previewImage(this, 'preview-img-2')"
                                    class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-600 file:text-white hover:file:bg-orange-500 cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs text-slate-400 mb-1">O mantener URL externa:</label>
                                <input type="text" name="hero_image_2_url" value="{{ old('hero_image_2_url', $settings['hero_image_2'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 text-xs font-mono focus:outline-none focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: CONTACTO & WHATSAPP -->
                <div id="tab-pane-contact" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Canales Directos de Soporte y Ventas
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Conecta a prospectos y atletas directamente con tu WhatsApp corporativo y correo oficial.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- WhatsApp -->
                        <div class="bg-emerald-950/20 border border-emerald-500/30 p-5 rounded-2xl space-y-4">
                            <div class="flex items-center gap-2 text-emerald-400 font-bold text-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.585 1.77.893 2.79.894h.002c3.18 0 5.767-2.586 5.768-5.766.001-3.181-2.586-5.768-5.769-5.768zm7.558 5.766c-.002 4.167-3.391 7.556-7.559 7.556-1.332 0-2.593-.349-3.696-.988l-4.103 1.077 1.096-4.004c-.732-1.157-1.12-2.518-1.12-3.641.002-4.167 3.392-7.556 7.56-7.556 4.168 0 7.558 3.389 7.559 7.556z"/>
                                </svg>
                                <span>Línea Directa de WhatsApp</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Número de Teléfono (con código de país)</label>
                                <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}"
                                    placeholder="Ej: 521XXXXXXXXXX"
                                    class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-emerald-500">
                                <p class="text-[11px] text-slate-500 mt-1">Sin espacios ni símbolos (+). Si lo completas, se habilitará el botón de WhatsApp flotante en el landing.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Mensaje Prellenado al Abrir WhatsApp</label>
                                <input type="text" name="contact_whatsapp_message" value="{{ old('contact_whatsapp_message', $settings['contact_whatsapp_message'] ?? '') }}"
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="bg-slate-800/40 border border-slate-700 p-5 rounded-2xl space-y-4">
                            <div class="flex items-center gap-2 text-indigo-400 font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Correo Electrónico de Contacto</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 mb-1">Email Corporativo / Soporte</label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                    class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                                <p class="text-[11px] text-slate-500 mt-1">Se muestra en el footer y en los enlaces de contacto.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 5: REDES SOCIALES & APP STORES -->
                <div id="tab-pane-social" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Redes Sociales y Enlaces a Tiendas de Apps
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">URLs para las descargas de iOS/Android y los perfiles de la marca.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- App Store -->
                        <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700 space-y-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                                App Store (Apple iOS) URL
                            </label>
                            <input type="text" name="app_store_url" value="{{ old('app_store_url', $settings['app_store_url'] ?? '') }}"
                                class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm font-mono focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Google Play -->
                        <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700 space-y-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                                Google Play (Android APK) URL
                            </label>
                            <input type="text" name="play_store_url" value="{{ old('play_store_url', $settings['play_store_url'] ?? '') }}"
                                class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm font-mono focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Instagram URL</label>
                            <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Facebook -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Facebook URL</label>
                            <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- YouTube -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">YouTube URL</label>
                            <input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- TikTok -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">TikTok URL</label>
                            <input type="text" name="social_tiktok" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- TAB 6: CTA FINAL & FILOSOFÍA -->
                <div id="tab-pane-cta" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Banner de Conversión Final &amp; Filosofía de Marca
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">El llamado a la acción final antes del pie de página y la narrativa oficial del isotipo.</p>
                    </div>

                    <div class="space-y-6">
                        <!-- Filosofía -->
                        <div class="bg-slate-800/30 p-5 rounded-2xl border border-slate-700/60 space-y-4">
                            <span class="text-xs font-bold text-orange-400 uppercase tracking-wider">Sección Historia de Marca & Isotipo</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Insignia (Badge)</label>
                                    <input type="text" name="philosophy_badge" value="{{ old('philosophy_badge', $settings['philosophy_badge'] ?? '') }}"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Título de Filosofía</label>
                                    <input type="text" name="philosophy_title" value="{{ old('philosophy_title', $settings['philosophy_title'] ?? '') }}"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm font-bold">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-slate-400 mb-1">Descripción / Narrativa</label>
                                    <textarea name="philosophy_description" rows="2"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm">{{ old('philosophy_description', $settings['philosophy_description'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Final -->
                        <div class="bg-slate-800/30 p-5 rounded-2xl border border-slate-700/60 space-y-4">
                            <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Banner de Conversión (14 Días de Prueba)</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-slate-400 mb-1">Insignia Superior</label>
                                    <input type="text" name="final_cta_badge" value="{{ old('final_cta_badge', $settings['final_cta_badge'] ?? '') }}"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Título del Banner</label>
                                    <input type="text" name="final_cta_title" value="{{ old('final_cta_title', $settings['final_cta_title'] ?? '') }}"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Subtítulo</label>
                                    <input type="text" name="final_cta_subtitle" value="{{ old('final_cta_subtitle', $settings['final_cta_subtitle'] ?? '') }}"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm">
                                </div>

                                <!-- Botón 1 -->
                                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-700 space-y-2">
                                    <span class="text-xs font-semibold text-orange-400">Botón 1 (Naranja Primario)</span>
                                    <input type="text" name="final_cta_btn1_text" value="{{ old('final_cta_btn1_text', $settings['final_cta_btn1_text'] ?? '') }}" placeholder="Texto" class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded text-xs text-white">
                                    <input type="text" name="final_cta_btn1_url" value="{{ old('final_cta_btn1_url', $settings['final_cta_btn1_url'] ?? '') }}" placeholder="URL" class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded text-xs text-white font-mono">
                                </div>

                                <!-- Botón 2 -->
                                <div class="bg-slate-900/60 p-3 rounded-xl border border-slate-700 space-y-2">
                                    <span class="text-xs font-semibold text-slate-300">Botón 2 (Secundario)</span>
                                    <input type="text" name="final_cta_btn2_text" value="{{ old('final_cta_btn2_text', $settings['final_cta_btn2_text'] ?? '') }}" placeholder="Texto" class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded text-xs text-white">
                                    <input type="text" name="final_cta_btn2_url" value="{{ old('final_cta_btn2_url', $settings['final_cta_btn2_url'] ?? '') }}" placeholder="URL" class="w-full px-3 py-1.5 bg-slate-800 border border-slate-700 rounded text-xs text-white font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 7: VISIBILIDAD DE SECCIONES -->
                <div id="tab-pane-visibility" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                            Interruptores de Visibilidad de Secciones
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Activa o desactiva en cualquier momento bloques enteros de la página web principal.</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Toggle Roles -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Sección de Roles &amp; Ecosistema (#roles)</span>
                                <span class="text-xs text-slate-400">Pilares para Coach, Gimnasios &amp; Boxes, Atletas y Clientes.</span>
                            </div>
                            <input type="checkbox" name="section_roles_enabled" value="1" {{ ($settings['section_roles_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Módulos -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Sección de Módulos de Alto Rendimiento (#modulos)</span>
                                <span class="text-xs text-slate-400">Detalles interactivos de Calculadora TDEE, Constructor de Rutinas, QR y Biometría.</span>
                            </div>
                            <input type="checkbox" name="section_modules_enabled" value="1" {{ ($settings['section_modules_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Workflow -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Sección de Flujo de Trabajo (#workflow)</span>
                                <span class="text-xs text-slate-400">Paso a paso del proceso de diagnóstico, periodización y prescripción.</span>
                            </div>
                            <input type="checkbox" name="section_workflow_enabled" value="1" {{ ($settings['section_workflow_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Filosofía -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Sección de Filosofía &amp; Isotipo Oficial</span>
                                <span class="text-xs text-slate-400">Bloque representativo de la marca y las credenciales de rendimiento.</span>
                            </div>
                            <input type="checkbox" name="section_philosophy_enabled" value="1" {{ ($settings['section_philosophy_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Final CTA -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Banner de Conversión Final</span>
                                <span class="text-xs text-slate-400">Caja llamativa con botones de registro y prueba de 14 días.</span>
                            </div>
                            <input type="checkbox" name="section_final_cta_enabled" value="1" {{ ($settings['section_final_cta_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>

                        <!-- Toggle Descargas -->
                        <label class="flex items-center justify-between p-4 rounded-xl bg-slate-800/40 border border-slate-700 cursor-pointer hover:bg-slate-800/70 transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm">Insignias de Descarga App Store / Google Play</span>
                                <span class="text-xs text-slate-400">Mostrar u ocultar los botones de descarga de aplicaciones móviles.</span>
                            </div>
                            <input type="checkbox" name="show_app_downloads" value="1" {{ ($settings['show_app_downloads'] ?? '1') == '1' ? 'checked' : '' }}
                                class="w-5 h-5 rounded text-indigo-600 bg-slate-700 border-slate-600 focus:ring-indigo-500">
                        </label>
                    </div>
                </div>

                <!-- TAB 8: SEO & MARCA -->
                <div id="tab-pane-seo" class="tab-pane hidden space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                            Metadatos SEO y Nombre de Marca
                        </h2>
                        <p class="text-sm text-slate-400 mt-1">Optimización para motores de búsqueda (Google, Bing) y redes sociales al compartir el enlace.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Nombre Comercial de la Marca</label>
                            <input type="text" name="brand_name" value="{{ old('brand_name', $settings['brand_name'] ?? 'AF Training') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Título de la Pestaña / Google (&lt;title&gt;)</label>
                            <input type="text" name="site_title" value="{{ old('site_title', $settings['site_title'] ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Meta Descripción (&lt;meta name="description"&gt;)</label>
                            <textarea name="meta_description" rows="3"
                                class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1">Recomendado entre 140 y 160 caracteres para un snippet óptimo en Google.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Save Bar at bottom -->
            <div class="border-t border-slate-800 bg-slate-900/90 backdrop-blur-md p-4 sm:p-6 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Los cambios se guardan y reflejan al instante en la web pública.</span>
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl font-bold text-sm bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white shadow-lg shadow-indigo-500/25 transition duration-150 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span>Guardar Cambios</span>
                </button>
            </div>

        </div>
    </form>

</div>

<!-- Tab Switching & Preview Scripts -->
<script>
    function switchTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));

        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
            btn.classList.add('text-slate-400', 'hover:text-white', 'hover:bg-slate-800');
        });

        // Show target pane
        const targetPane = document.getElementById('tab-pane-' + tabId);
        if (targetPane) {
            targetPane.classList.remove('hidden');
        }

        // Activate button
        const targetBtn = document.getElementById('tab-btn-' + tabId);
        if (targetBtn) {
            targetBtn.classList.remove('text-slate-400', 'hover:text-white', 'hover:bg-slate-800');
            targetBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
        }
    }

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById(previewId);
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
