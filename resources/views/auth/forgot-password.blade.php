<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Restablecer Contraseña</title>
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
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="AFTraining Logo" class="mx-auto w-[200px] h-auto object-contain">
            </div>

            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-white">Restablecer Contraseña</h2>
                <p class="text-zinc-400 mt-2 text-sm">Ingresa tu correo y nueva contraseña.</p>
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

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-400 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        placeholder="admin@example.com"
                        class="block w-full px-4 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-400 mb-1.5">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" required
                        placeholder="••••••••"
                        class="block w-full px-4 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-400 mb-1.5">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="••••••••"
                        class="block w-full px-4 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full bg-[#E53E3E] hover:bg-[#C53030] active:bg-[#9B2C2C] text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 text-sm tracking-wider uppercase">
                        CAMBIAR CONTRASEÑA
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-xs text-zinc-500 hover:text-zinc-300 transition-colors font-medium flex items-center justify-center gap-1">
                        &larr; Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
