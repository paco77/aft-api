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
<body class="bg-slate-900 font-sans antialiased flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-8 bg-slate-800/80 border border-slate-700/50 backdrop-blur-md rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="AFTraining Logo" class="mx-auto h-24 w-auto -mb-2">
            <p class="text-slate-400 mt-4 text-sm font-medium">Inicia sesión para acceder al panel</p>
        </div>

        @if (session('status'))
            <div class="mb-4 bg-green-950/30 border border-green-800/50 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-300 font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="mb-4 bg-red-950/30 border border-red-800/50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-300 font-medium">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="login" class="block text-sm font-semibold text-slate-300">Usuario o Email</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                        class="mt-2 block w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-slate-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-300">Contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="mt-2 block w-full px-4 py-3 rounded-lg bg-slate-900/50 border border-slate-700 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-slate-500">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500 rounded">
                        <label for="remember" class="ml-2 block text-sm text-slate-300 font-medium">Recordarme</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors font-medium">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-indigo-600/20">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</body>
</html>
