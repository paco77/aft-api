@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    @if(auth()->user()->role === 'gimnasio')
        <!-- Gimnasio Specific Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Gym Routines Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-600 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Rutinas de Gimnasio</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['gym_routines'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Exercises Catalog Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Ejercicios en Catálogo</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['exercises'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- QR Sessions Recorded Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Sesiones Vía QR</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['sessions'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access for Gym -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-lg border border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider border border-amber-500/30 mb-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Módulo de Gimnasio AF</span>
                    </span>
                    <h3 class="text-xl font-bold">Bienvenido, {{ auth()->user()->name }}</h3>
                    <p class="text-sm text-slate-300 mt-1 max-w-xl">
                        Gestiona las rutinas de tu centro deportivo y proporciona a los usuarios el código QR para realizar entrenamientos guiados en la sala de pesas.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.gym-routines.qr') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span>Ver Código QR</span>
                    </a>
                    <a href="{{ route('admin.gym-routines.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-xs border border-white/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Administrar Rutinas</span>
                    </a>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role === 'independiente')
        <!-- Independiente Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Plans Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-600 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Mis Planes de Entrenamiento</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['plans'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Exercises Catalog Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Ejercicios en Catálogo</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['exercises'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Sessions Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Sesiones Registradas</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['sessions'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access for Independiente -->
        <div class="bg-gradient-to-r from-slate-900 to-purple-950 rounded-2xl p-6 text-white shadow-lg border border-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-bold uppercase tracking-wider border border-purple-500/30 mb-2">
                        <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                        <span>Perfil Atleta Independiente</span>
                    </span>
                    <h3 class="text-xl font-bold">Bienvenido, {{ auth()->user()->name }}</h3>
                    <p class="text-sm text-slate-300 mt-1 max-w-xl">
                        Gestiona tus propios entrenamientos de forma autónoma. Consulta la biblioteca de ejercicios, diseña tus rutinas y registra tu evolución.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Ver Mis Planes</span>
                    </a>
                    <a href="{{ route('admin.exercises.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-xs border border-white/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Catálogo de Ejercicios</span>
                    </a>
                </div>
            </div>
        </div>

    @else
        <!-- Admin & Coach Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Users Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            {{ auth()->user()->role === 'coach' ? 'Mis Clientes' : 'Total Usuarios' }}
                        </p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Exercises Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Total Ejercicios</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['exercises'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Plans Stat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500 border border-slate-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Planes Mensuales</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $stats['plans'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Bienvenido al Panel de Administración</h3>
            <p class="text-sm text-slate-600">Desde aquí puedes gestionar los usuarios, clientes, ejercicios, planes de entrenamiento y rutinas de gimnasio de tu plataforma AFTraining.</p>
        </div>
    @endif
</div>
@endsection

