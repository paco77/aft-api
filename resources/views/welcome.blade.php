<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AF Training - Elite Performance</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,600,800&display=swap" rel="stylesheet" />
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'class',
                    theme: {
                        extend: {
                            colors: {
                                primary: '#ccff00', // Neon Green
                                dark: '#0a0a0a',
                                card: '#161615',
                            },
                            fontFamily: {
                                sans: ['Outfit', 'sans-serif'],
                            },
                        }
                    }
                }
            </script>
        @endif
        <style>
            .neon-text {
                text-shadow: 0 0 10px rgba(204, 255, 0, 0.5);
            }
            .neon-border {
                box-shadow: 0 0 15px rgba(204, 255, 0, 0.2);
            }
            .gradient-overlay {
                background: linear-gradient(to right, rgba(10, 10, 10, 1) 30%, rgba(10, 10, 10, 0.4) 100%);
            }
        </style>
    </head>
    <body class="bg-dark text-white font-sans antialiased overflow-x-hidden">
        <div class="relative min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="absolute top-0 w-full z-50 flex items-center justify-between px-8 py-6 bg-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <span class="text-black font-extrabold text-xl italic">AF</span>
                    </div>
                    <span class="font-bold text-xl tracking-tighter">AF<span class="text-primary italic">TRAINING</span></span>
                </div>
                <div class="flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            @if(auth()->user()->role === 'superUser')
                                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold hover:text-primary transition-colors">Usuarios</a>
                                <a href="{{ route('admin.exercises.index') }}" class="text-sm font-semibold hover:text-primary transition-colors">Ejercicios</a>
                                <a href="{{ route('admin.plans.index') }}" class="text-sm font-semibold hover:text-primary transition-colors">Planes</a>
                            @endif
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold hover:text-primary transition-colors">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-red-500/80 hover:text-red-500 transition-colors cursor-pointer">
                                    Cerrar Sesión
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-primary transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-primary text-black px-6 py-2 rounded-full font-bold text-sm hover:scale-105 transition-transform">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <!-- Hero Section -->
            <section class="relative flex-1 flex items-center pt-20">
                <!-- Background Image -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/hero_athlete.png') }}" class="w-full h-full object-cover object-center opacity-60" alt="AF Training Hero">
                    <div class="absolute inset-0 gradient-overlay"></div>
                </div>

                <div class="relative z-10 px-8 lg:px-24 max-w-4xl">
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Unleash Your Potential</span>
                    <h1 class="text-6xl lg:text-8xl font-black leading-tight mb-6">
                        DOMINATE <br>
                        <span class="text-primary neon-text italic">EVERY REP</span>
                    </h1>
                    <p class="text-gray-400 text-lg lg:text-xl max-w-lg mb-10 leading-relaxed">
                        The ultimate fitness management platform for elite athletes and coaches. Track performance, scale goals, and reach peak condition.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary text-black px-10 py-4 rounded-xl font-extrabold text-lg flex items-center justify-center hover:shadow-[0_0_20px_rgba(204,255,0,0.4)] transition-all">
                                Join the Elite
                            </a>
                        @endif
                        <a href="#features" class="border border-white/20 px-10 py-4 rounded-xl font-bold text-lg flex items-center justify-center hover:bg-white/10 transition-all backdrop-blur-sm">
                            Learn More
                        </a>
                    </div>
                </div>
            </section>

            <!-- Feature Snapshot -->
            <section id="features" class="relative z-10 bg-dark py-24 px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="bg-card p-10 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Precision Tracking</h3>
                            <p class="text-gray-400">Log every set, rep, and weight with millisecond precision. Analyze progress with real-time data.</p>
                        </div>
                        <!-- Feature 2 -->
                        <div class="bg-card p-10 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Coach Connectivity</h3>
                            <p class="text-gray-400">Seamless integration between coaches and clients. Real-time plan assignments and feedback loops.</p>
                        </div>
                        <!-- Feature 3 -->
                        <div class="bg-card p-10 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Elite Planning</h3>
                            <p class="text-gray-400">Create complex training cycles with ease. Scale from individual sessions to multi-month periodization.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="mt-auto py-12 px-8 border-t border-white/5 bg-dark">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-gray-500 text-sm">© {{ date('Y') }} AF Training. Engineered for Greatness.</p>
                    <div class="flex gap-8 text-gray-500 text-sm">
                        <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                        <a href="#" class="hover:text-primary transition-colors">Terms</a>
                        <a href="#" class="hover:text-primary transition-colors">Twitter</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
