<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white min-h-screen">
            <div class=" border-b border-slate-800 flex justify-center items-center">
                <img src="{{ asset('images/logo.png') }}" alt="AFTraining Logo" class="h-40 w-auto max-w-full">
            </div>
            <nav class="mt-6 px-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.users.*') ? 'bg-slate-700' : '' }}">
                    Usuarios
                </a>
                <a href="{{ route('admin.exercises.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.exercises.*') ? 'bg-slate-700' : '' }}">
                    Ejercicios
                </a>
                <a href="{{ route('admin.plans.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.plans.*') ? 'bg-slate-700' : '' }}">
                    Planes Mensuales
                </a>
                <a href="{{ route('admin.profile.edit') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.profile.*') ? 'bg-slate-700' : '' }}">
                    Mi Perfil
                </a>
                <div class="mt-10 pt-4 border-t border-slate-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left py-2.5 px-4 rounded transition duration-200 hover:bg-red-600">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow h-16 flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('header', 'Panel de Administración')
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 font-medium">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                    @else
                        <div
                            class="w-10 h-10 rounded-full bg-slate-500 flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </header>

            <div class="p-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>