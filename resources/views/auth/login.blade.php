<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black font-sans antialiased flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
    <div class="absolute inset-0 z-0 bg-black/80"></div>
    
    <!-- Giant decorative "AF" on the bottom-left -->
    <div class="absolute bottom-0 left-0 text-white/[0.03] text-[24rem] font-black leading-none select-none pointer-events-none transform translate-y-20 -translate-x-10 z-0">
        AF
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-[420px] px-6">
        <!-- Card -->
        <div class="w-full bg-[#0d0d0d]/85 border border-zinc-800/80 backdrop-blur-md rounded-2xl p-8 shadow-2xl">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="AFTraining Logo" class="mx-auto w-[280px] h-auto object-contain">
            </div>

            <!-- Status Alerts -->
            @if (session('status'))
                <div class="mb-5 bg-green-950/40 border border-green-900/50 rounded-lg p-3 text-sm text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 bg-red-950/40 border border-red-900/50 rounded-lg p-3 text-sm text-red-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-semibold text-zinc-400 mb-1.5">Email</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                        placeholder="admin@example.com"
                        class="block w-full px-4 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-400 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="block w-full pl-4 pr-11 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                        <button type="button" onclick="togglePasswordVisibility()" id="togglePasswordBtn"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-500 hover:text-zinc-200 focus:outline-none transition-colors"
                            title="Mostrar u ocultar contraseña" aria-label="Mostrar u ocultar contraseña">
                            <!-- Icono ojo (abierto) -->
                            <svg id="eyeIcon" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Icono ojo tachado (cerrado) -->
                            <svg id="eyeSlashIcon" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                        class="h-4 w-4 bg-zinc-900/50 border-zinc-800 text-red-600 focus:ring-red-600 focus:ring-offset-zinc-950 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-xs text-zinc-400 font-medium cursor-pointer select-none">Recordarme</label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-[#E53E3E] hover:bg-[#C53030] active:bg-[#9B2C2C] text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 text-sm tracking-wider uppercase">
                        ENTRAR AL SISTEMA
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center pt-2">
                        <a href="{{ route('password.request') }}" class="text-xs text-zinc-500 hover:text-zinc-300 transition-colors font-medium">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800/90"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-[#0d0d0d] px-3 text-zinc-500 font-bold tracking-widest text-[10px]">¿Aún no tienes cuenta?</span>
                </div>
            </div>

            <!-- Botones Crear Cuenta por Rol -->
            <div class="space-y-3">
                <div class="text-center">
                    <p class="text-xs font-semibold text-zinc-300">Crear cuenta en AF Training</p>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Selecciona tu perfil para registrarte:</p>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <!-- Coach -->
                    <a href="{{ route('register', ['role' => 'coach']) }}" 
                       class="group flex flex-col items-center justify-center p-3 rounded-xl bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800 hover:border-red-500/50 transition-all duration-200 text-center">
                        <div class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center mb-1 group-hover:scale-110 group-hover:bg-red-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-zinc-200 group-hover:text-red-400">Coach</span>
                        <span class="text-[10px] text-zinc-500 mt-0.5">Entrenador</span>
                    </a>

                    <!-- Independiente -->
                    <a href="{{ route('register', ['role' => 'independiente']) }}" 
                       class="group flex flex-col items-center justify-center p-3 rounded-xl bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800 hover:border-purple-500/50 transition-all duration-200 text-center">
                        <div class="w-8 h-8 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center mb-1 group-hover:scale-110 group-hover:bg-purple-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-zinc-200 group-hover:text-purple-400">Atleta</span>
                        <span class="text-[10px] text-zinc-500 mt-0.5">Independiente</span>
                    </a>

                    <!-- Gimnasio -->
                    <a href="{{ route('register', ['role' => 'gimnasio']) }}" 
                       class="group flex flex-col items-center justify-center p-3 rounded-xl bg-zinc-900/60 hover:bg-zinc-800/80 border border-zinc-800 hover:border-amber-500/50 transition-all duration-200 text-center">
                        <div class="w-8 h-8 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center mb-1 group-hover:scale-110 group-hover:bg-amber-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-zinc-200 group-hover:text-amber-400">Gimnasio</span>
                        <span class="text-[10px] text-zinc-500 mt-0.5">Centro / Sala</span>
                    </a>
                </div>

                <div class="pt-1">
                    <a href="{{ route('register') }}" 
                       class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-zinc-900/50 hover:bg-zinc-800 border border-zinc-800/90 text-zinc-300 hover:text-white font-medium text-xs transition-colors">
                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Crear Cuenta</span>
                    </a>
                </div>
            </div>

            <!-- Back to Landing Link -->
            <div class="text-center pt-5 border-t border-zinc-800/50 mt-5">
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
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
