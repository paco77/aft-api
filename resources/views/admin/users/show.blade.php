@extends('layouts.admin')

@section('header')
    Perfil de Usuario
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Breadcrumb & Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a la Lista
            </a>
            
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm shadow-sm inline-flex items-center transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editar Perfil
            </a>
        </div>

        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 md:items-start">
                <!-- Profile Image -->
                <div class="flex-shrink-0 mx-auto md:mx-0 relative">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::disk('s3')->url($user->profile_photo_path) }}" alt="{{ $user->name }}"
                            class="w-32 h-32 rounded-full object-cover border-4 border-slate-50 shadow-sm">
                    @else
                        <div class="w-32 h-32 rounded-full bg-slate-900 flex items-center justify-center text-white text-4xl font-bold shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <span class="absolute bottom-1 right-1 w-6 h-6 rounded-full border-2 border-white 
                        {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}" title="{{ $user->is_active ? 'Activo' : 'Inactivo' }}"></span>
                </div>

                <!-- Profile Info Details -->
                <div class="flex-grow text-center md:text-left space-y-4 w-full">
                    <div>
                        <div class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start">
                            <h2 class="text-3xl font-black text-slate-800">{{ $user->name }}</h2>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-red-100 text-red-800 
                                @elseif($user->role == 'coach') bg-green-100 text-green-800 
                                @elseif($user->role == 'client') bg-blue-100 text-blue-800 
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <p class="text-slate-500 font-medium mt-1">@ {{ $user->username }} &bull; {{ $user->email }}</p>
                    </div>

                    <!-- Additional Details Based on Role -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Miembro desde</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->created_at->translatedFormat('d F, Y') }}</p>
                        </div>
                        
                        @if($user->role === 'client')
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Coach Asignado</p>
                                <p class="text-sm font-semibold text-slate-700">
                                    @if($user->coach)
                                        <a href="{{ route('admin.users.show', $user->coach) }}" class="text-indigo-600 hover:underline">{{ $user->coach->name }}</a>
                                    @else
                                        <span class="text-amber-600 italic">Sin Coach</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Edad</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->age ? $user->age . ' años' : 'No especificada' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estatura</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->height ? $user->height . ' cm' : 'No especificada' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peso Inicial</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->weight ? $user->weight . ' kg' : 'No especificado' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tiempo Entrenando</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->training_time ?: 'No especificado' }}</p>
                            </div>
                        @elseif($user->role === 'coach')
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Experiencia</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->experience_years ? $user->experience_years . ' años' : 'No especificada' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Clientes Asignados</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $user->clients()->count() }} clientes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Objectives or Bio -->
            @if($user->role === 'client' && $user->objectives)
                <div class="bg-indigo-50 px-6 md:px-8 py-5 border-t border-indigo-100">
                    <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Objetivos
                    </h3>
                    <p class="text-indigo-900 font-medium">{{ $user->objectives }}</p>
                </div>
            @elseif(in_array($user->role, ['coach', 'admin']) && $user->training_info)
                <div class="bg-indigo-50 px-6 md:px-8 py-5 border-t border-indigo-100">
                    <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                        Información de Formación / Biografía
                    </h3>
                    <p class="text-indigo-900 font-medium whitespace-pre-wrap">{{ $user->training_info }}</p>
                </div>
            @endif
        </div>

        <!-- Initial Progress Photos (Client Only) -->
        @if($user->role === 'client' && ($user->front_photo || $user->side_photo || $user->back_photo))
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Fotografías Iniciales
                </h3>
                
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @if($user->front_photo)
                            <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img src="{{ Storage::disk('s3')->url($user->front_photo) }}" alt="Foto Inicial de Frente"
                                    class="w-full h-80 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                    onclick="window.open('{{ Storage::disk('s3')->url($user->front_photo) }}', '_blank')">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/70 to-transparent p-4 pointer-events-none">
                                    <p class="text-white text-sm font-bold text-center">Frente</p>
                                </div>
                            </div>
                        @endif
                        @if($user->side_photo)
                            <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img src="{{ Storage::disk('s3')->url($user->side_photo) }}" alt="Foto Inicial de Perfil"
                                    class="w-full h-80 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                    onclick="window.open('{{ Storage::disk('s3')->url($user->side_photo) }}', '_blank')">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/70 to-transparent p-4 pointer-events-none">
                                    <p class="text-white text-sm font-bold text-center">Perfil</p>
                                </div>
                            </div>
                        @endif
                        @if($user->back_photo)
                            <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img src="{{ Storage::disk('s3')->url($user->back_photo) }}" alt="Foto Inicial de Espalda"
                                    class="w-full h-80 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                    onclick="window.open('{{ Storage::disk('s3')->url($user->back_photo) }}', '_blank')">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/70 to-transparent p-4 pointer-events-none">
                                    <p class="text-white text-sm font-bold text-center">Espalda</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Clients List (Coach Only) -->
        @if($user->role === 'coach')
            <div class="space-y-4 mt-8">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Clientes Asignados ({{ $user->clients()->count() }})
                </h3>
                
                @if($user->clients()->count() > 0)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <ul class="divide-y divide-slate-100">
                            @foreach($user->clients as $client)
                                <li class="hover:bg-slate-50 transition">
                                    <a href="{{ route('admin.users.show', $client) }}" class="flex items-center px-6 py-4 gap-4">
                                        @if($client->profile_photo_path)
                                            <img src="{{ Storage::disk('s3')->url($client->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                                {{ substr($client->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex-grow">
                                            <p class="text-sm font-bold text-slate-800">{{ $client->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $client->email }}</p>
                                        </div>
                                        <div>
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                        <p class="text-slate-500 font-medium">Este coach aún no tiene clientes asignados.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
