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
                    <input type="password" name="password" id="password" required
                        placeholder="••••••••"
                        class="block w-full px-4 py-3 rounded-lg bg-zinc-900/40 border border-zinc-800/80 text-white placeholder-zinc-600 focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm">
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
        </div>
    </div>
</body>
</html>
