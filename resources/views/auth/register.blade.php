<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Crear Cuenta</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black font-sans antialiased flex items-center justify-center min-h-screen relative overflow-x-hidden py-10">
    
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0 z-0 bg-cover bg-center fixed" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
    <div class="absolute inset-0 z-0 bg-black/85 fixed"></div>
    
    <!-- Giant decorative "AF" on the bottom-left -->
    <div class="absolute bottom-0 left-0 text-white/[0.03] text-[24rem] font-black leading-none select-none pointer-events-none transform translate-y-20 -translate-x-10 z-0">
        AF
    </div>

    <!-- Register Container -->
    <div class="relative z-10 w-full max-w-[520px] px-4 sm:px-6">
        <!-- Card -->
        <div class="w-full bg-[#0d0d0d]/90 border border-zinc-800/80 backdrop-blur-md rounded-2xl p-6 sm:p-8 shadow-2xl">
            <!-- Logo -->
            <div class="text-center mb-6">
                <a href="{{ route('landing.home') }}" class="inline-block transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="AFTraining Logo" class="mx-auto w-[240px] sm:w-[260px] h-auto object-contain">
                </a>
                <h1 class="text-lg font-bold text-white mt-3">Crear Cuenta Nueva</h1>
                <p class="text-xs text-zinc-400 mt-1">Únete a la plataforma de alto rendimiento</p>
            </div>

            <!-- Errors Alerts -->
            @if ($errors->any())
                <div class="mb-5 bg-red-950/40 border border-red-900/50 rounded-xl p-3.5 text-xs text-red-400 space-y-1">
                    <p class="font-bold flex items-center gap-1.5 text-red-300">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Revisa los siguientes campos:</span>
                    </p>
                    <ul class="list-disc list-inside space-y-0.5 pl-2 text-zinc-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="register-form" class="space-y-4">
                @csrf
                
                <!-- Hidden Role Input -->
                @php
                    $currentRole = old('role', $role ?? 'coach');
                @endphp
                <input type="hidden" name="role" id="role-input" value="{{ $currentRole }}">

                <!-- Selector de Rol (Tabs Visuales) -->
                <div>
                    <label class="block text-xs font-semibold text-zinc-400 mb-2">Selecciona tu Perfil</label>
                    <div class="grid grid-cols-3 gap-2 p-1 bg-zinc-950/80 rounded-xl border border-zinc-800">
                        
                        <!-- Botón Coach -->
                        <button type="button" onclick="selectRole('coach')" id="tab-coach"
                            class="role-tab flex flex-col items-center justify-center py-2.5 px-2 rounded-lg text-xs font-semibold transition-all {{ $currentRole === 'coach' ? 'bg-red-600/90 text-white shadow-md' : 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/50' }}">
                            <div class="flex items-center gap-1 mb-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Coach</span>
                            </div>
                            <span class="text-[9px] opacity-80 leading-tight">Entrenador</span>
                        </button>

                        <!-- Botón Independiente -->
                        <button type="button" onclick="selectRole('independiente')" id="tab-independiente"
                            class="role-tab flex flex-col items-center justify-center py-2.5 px-2 rounded-lg text-xs font-semibold transition-all {{ $currentRole === 'independiente' ? 'bg-purple-600/90 text-white shadow-md' : 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/50' }}">
                            <div class="flex items-center gap-1 mb-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Atleta</span>
                            </div>
                            <span class="text-[9px] opacity-80 leading-tight">Independiente</span>
                        </button>

                        <!-- Botón Gimnasio -->
                        <button type="button" onclick="selectRole('gimnasio')" id="tab-gimnasio"
                            class="role-tab flex flex-col items-center justify-center py-2.5 px-2 rounded-lg text-xs font-semibold transition-all {{ $currentRole === 'gimnasio' ? 'bg-amber-600/90 text-white shadow-md' : 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/50' }}">
                            <div class="flex items-center gap-1 mb-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>Gimnasio</span>
                            </div>
                            <span class="text-[9px] opacity-80 leading-tight">Centro / Sala</span>
                        </button>
                    </div>

                    <!-- Mensaje dinámico explicativo del rol -->
                    <div id="role-description" class="mt-2 px-3 py-2 rounded-lg bg-zinc-900/60 border border-zinc-800 text-[11px] text-zinc-400 flex items-center gap-2">
                        <span id="role-icon-indicator" class="text-sm">🏋️‍♂️</span>
                        <span id="role-text-indicator">Gestiona alumnos, diseña planes nutricionales y periodización avanzada.</span>
                    </div>
                </div>

                <!-- Campo Nombre / Razón Social -->
                <div>
                    <label for="name" id="label-name" class="block text-xs font-semibold text-zinc-400 mb-1.5">Nombre Completo</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="Ej. Juan Pérez"
                        class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <!-- Fila: Username y Teléfono -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="username" class="block text-xs font-semibold text-zinc-400 mb-1.5">Nombre de Usuario</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            placeholder="ej. coach_juan"
                            class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-zinc-400 mb-1.5">Teléfono (WhatsApp)</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            placeholder="ej. +52 55 1234 5678"
                            class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                    </div>
                </div>

                <!-- Campo Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-400 mb-1.5">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="correo@ejemplo.com"
                        class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <!-- Fila: Password y Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-zinc-400 mb-1.5">Contraseña</label>
                        <input type="password" name="password" id="password" required minlength="8"
                            placeholder="Mínimo 8 caracteres"
                            class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-zinc-400 mb-1.5">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            placeholder="Repite la contraseña"
                            class="block w-full px-4 py-2.5 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                    </div>
                </div>

                <!-- Botón Registrarse -->
                <div class="pt-2">
                    <button type="submit" id="submit-btn"
                        class="w-full bg-[#E53E3E] hover:bg-[#C53030] active:bg-[#9B2C2C] text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 text-sm tracking-wider uppercase shadow-lg shadow-red-900/20">
                        REGISTRARSE COMO COACH
                    </button>
                </div>
            </form>

            <!-- Already have an account link -->
            <div class="text-center pt-5 border-t border-zinc-800/80 mt-5">
                <p class="text-xs text-zinc-400">
                    ¿Ya tienes una cuenta registrada?
                    <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 font-semibold transition-colors ml-1">
                        Iniciar Sesión
                    </a>
                </p>
            </div>

            <!-- Back to Landing Link -->
            <div class="text-center pt-3">
                <a href="{{ route('landing.home') }}" class="text-xs text-zinc-500 hover:text-zinc-300 transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver a la página principal</span>
                </a>
            </div>

        </div>
    </div>

    <script>
        const roleData = {
            'coach': {
                nameLabel: 'Nombre Completo del Entrenador',
                namePlaceholder: 'Ej. Carlos Mendoza',
                usernamePlaceholder: 'ej. coach_carlos',
                btnText: 'REGISTRARSE COMO COACH',
                btnClass: 'bg-[#E53E3E] hover:bg-[#C53030]',
                tabClass: 'bg-red-600/90 text-white shadow-md',
                icon: '🏋️‍♂️',
                desc: 'Gestiona atletas, diseña planes nutricionales, entrenamientos y periodización avanzada.'
            },
            'independiente': {
                nameLabel: 'Nombre Completo del Atleta',
                namePlaceholder: 'Ej. Alejandro Silva',
                usernamePlaceholder: 'ej. alex_fitness',
                btnText: 'REGISTRARSE COMO ATLETA INDEPENDIENTE',
                btnClass: 'bg-purple-600 hover:bg-purple-700',
                tabClass: 'bg-purple-600/90 text-white shadow-md',
                icon: '⚡',
                desc: 'Entrena a tu propio ritmo, crea tus planes y registra tu progreso biometría 360° sin coach.'
            },
            'gimnasio': {
                nameLabel: 'Nombre del Gimnasio / Razón Social',
                namePlaceholder: 'Ej. Iron Gym Fitness Center',
                usernamePlaceholder: 'ej. irongym_oficial',
                btnText: 'REGISTRARSE COMO GIMNASIO',
                btnClass: 'bg-amber-600 hover:bg-amber-700',
                tabClass: 'bg-amber-600/90 text-white shadow-md',
                icon: '🏢',
                desc: 'Crea rutinas abiertas para la sala de pesas y genera códigos QR para tus clientes y socios.'
            }
        };

        const inactiveTabClasses = 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/50';

        function selectRole(role) {
            document.getElementById('role-input').value = role;
            const data = roleData[role] || roleData['coach'];

            // Update Tabs Styling
            ['coach', 'independiente', 'gimnasio'].forEach(r => {
                const tab = document.getElementById('tab-' + r);
                if (tab) {
                    if (r === role) {
                        tab.className = 'role-tab flex flex-col items-center justify-center py-2.5 px-2 rounded-lg text-xs font-semibold transition-all ' + data.tabClass;
                    } else {
                        tab.className = 'role-tab flex flex-col items-center justify-center py-2.5 px-2 rounded-lg text-xs font-semibold transition-all ' + inactiveTabClasses;
                    }
                }
            });

            // Update Labels and Placeholders
            const labelName = document.getElementById('label-name');
            const inputName = document.getElementById('name');
            const inputUsername = document.getElementById('username');
            const submitBtn = document.getElementById('submit-btn');
            const roleIcon = document.getElementById('role-icon-indicator');
            const roleText = document.getElementById('role-text-indicator');

            if (labelName) labelName.textContent = data.nameLabel;
            if (inputName) inputName.placeholder = data.namePlaceholder;
            if (inputUsername) inputUsername.placeholder = data.usernamePlaceholder;
            if (submitBtn) {
                submitBtn.textContent = data.btnText;
                submitBtn.className = 'w-full text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 text-sm tracking-wider uppercase shadow-lg ' + data.btnClass;
            }
            if (roleIcon) roleIcon.textContent = data.icon;
            if (roleText) roleText.textContent = data.desc;
        }

        // Initialize based on current role input
        document.addEventListener('DOMContentLoaded', () => {
            const currentRole = document.getElementById('role-input').value || 'coach';
            selectRole(currentRole);
        });
    </script>
</body>
</html>
