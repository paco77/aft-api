@extends('layouts.admin')

@section('header', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-8">
                <!-- Foto de Perfil -->
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-24 w-24 rounded-full object-cover border-2 border-primary">
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

                <!-- Información de Formación -->
                <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
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
