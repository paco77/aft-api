@extends('layouts.admin')

@section('header', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

    <!-- Accesos Directos a Planes y Métricas Personales -->
    <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-indigo-500/30 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                <span>Tu Cuenta Personal</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Tus Planes y Seguimiento Personal
            </h2>
            <p class="text-xs sm:text-sm text-slate-300">
                Consulta y gestiona tus propios planes de entrenamiento, alimentación y métricas de rendimiento.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.users.plans', auth()->user()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-indigo-600/30 transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>Mis Planes Entrenamiento</span>
            </a>
            <a href="{{ route('admin.users.nutrition-plans', auth()->user()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#ec6a06] hover:bg-[#ec6a06]/90 text-white font-bold text-xs uppercase tracking-wider shadow-md transition active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Mis Planes Alimentación</span>
            </a>
            <a href="{{ route('admin.users.show', auth()->user()) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold text-xs uppercase tracking-wider transition active:scale-95"
               title="Ver ficha completa de rendimiento personal">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span>Mi Ficha</span>
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-2xl border border-gray-100">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-8">
                <!-- Foto de Perfil -->
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile_photo_url)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="h-24 w-24 rounded-full object-cover border-2 border-primary" onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden h-24 w-24 rounded-full bg-slate-800 items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="h-24 w-24 rounded-full bg-slate-500 flex items-center justify-center text-white text-3xl">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="profile_photo" class="w-fit pl-0.5 text-sm font-medium">Foto de Perfil</label>
                        <input type="file" name="profile_photo" id="profile_photo" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('profile_photo') border border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Recomendado: Imagen cuadrada, Máx 2MB.</p>
                        @error('profile_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Logo para PDF -->
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->logo_path)
                            <img src="{{ asset('storage/' . auth()->user()->logo_path) }}" alt="Logo Coach" class="h-20 w-auto object-contain border border-gray-200 p-1 bg-white">
                        @else
                            <div class="h-20 w-32 bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs text-center p-2">
                                Sin Logo
                            </div>
                        @endif
                    </div>
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="logo" class="w-fit pl-0.5 text-sm font-medium">Logo para PDF (Opcional)</label>
                        <input type="file" name="logo" id="logo" accept="image/*"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('logo') border border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Aparecerá en la cabecera de tus planes PDF. Máx 2MB.</p>
                        @error('logo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Datos Personales -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 border-t border-gray-100 pt-6">
                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="name" class="w-fit pl-0.5 text-sm font-medium">Nombre Completo</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('name') border border-red-500 @enderror"
                                placeholder="Ej. Juan Pérez" required>
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="username" class="w-fit pl-0.5 text-sm font-medium">Nombre de Usuario</label>
                            <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('username') border border-red-500 @enderror"
                                placeholder="Ej. juanperez" required>
                            @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="email" class="w-fit pl-0.5 text-sm font-medium">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('email') border border-red-500 @enderror"
                                placeholder="juan@ejemplo.com" required>
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="password" class="w-fit pl-0.5 text-sm font-medium">Nueva Contraseña (Opcional)</label>
                            <input type="password" name="password" id="password" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('password') border border-red-500 @enderror"
                                placeholder="Dejar en blanco para no cambiar">
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="password_confirmation" class="w-fit pl-0.5 text-sm font-medium">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                placeholder="Repetir contraseña">
                        </div>
                    </div>
                </div>

                <!-- Información de Formación -->
                <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark border-t border-gray-100 pt-6">
                    <label for="training_info" class="w-fit pl-0.5 text-sm font-medium">Información de Formación / Bio</label>
                    <textarea name="training_info" id="training_info" rows="6" 
                        class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('training_info') border border-red-500 @enderror"
                        placeholder="Describe tu experiencia, certificaciones y especialidades...">{{ old('training_info', auth()->user()->training_info) }}</textarea>
                    @error('training_info') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-orange-500 text-white font-bold py-2 px-6 rounded-radius text-sm transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
