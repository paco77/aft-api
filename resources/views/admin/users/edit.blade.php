@extends('layouts.admin')

@section('header', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Profile Photo -->
                <div class="sm:col-span-6 flex items-center gap-4">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-200">
                    @endif
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="profile_photo" class="w-fit pl-0.5 text-sm">Foto de Perfil</label>
                        <input type="file" name="profile_photo" id="profile_photo" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('profile_photo') border border-red-500 @enderror">
                        @error('profile_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="name" class="w-fit pl-0.5 text-sm">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('name') border border-red-500 @enderror"
                            placeholder="Ej. Juan Pérez">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="username" class="w-fit pl-0.5 text-sm">Nombre de Usuario</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('username') border border-red-500 @enderror"
                            placeholder="Ej. juanperez">
                        @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="email" class="w-fit pl-0.5 text-sm">Correo Electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('email') border border-red-500 @enderror"
                            placeholder="juan@ejemplo.com">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="role" class="w-fit pl-0.5 text-sm">Rol</label>
                        <select name="role" id="role" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('role') border border-red-500 @enderror">
                            <option value="usuario" {{ old('role', $user->role) == 'usuario' ? 'selected' : '' }}>Usuario (Básico)</option>
                            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Cliente</option>
                            <option value="coach" {{ old('role', $user->role) == 'coach' ? 'selected' : '' }}>Coach</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Super Administrador</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3" id="coach-selector" style="display: {{ old('role', $user->role) === 'client' ? 'block' : 'none' }};">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="coach_id" class="w-fit pl-0.5 text-sm">Asignar a Coach</label>
                        <select name="coach_id" id="coach_id" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('coach_id') border border-red-500 @enderror">
                            <option value="">Ninguno</option>
                            @foreach($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id', $user->coach_id) == $coach->id ? 'selected' : '' }}>
                                    {{ $coach->name }} ({{ $coach->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('coach_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const roleSelect = document.getElementById('role');
                        const coachSelector = document.getElementById('coach-selector');
                        
                        function toggleCoachSelector() {
                            if (roleSelect.value === 'client') {
                                coachSelector.style.display = 'block';
                            } else {
                                coachSelector.style.display = 'none';
                            }
                        }
                        
                        roleSelect.addEventListener('change', toggleCoachSelector);
                    });
                </script>
                @endif

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="password" class="w-fit pl-0.5 text-sm">Contraseña (Dejar en blanco para no cambiar)</label>
                        <input type="password" name="password" id="password" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('password') border border-red-500 @enderror"
                            placeholder="********">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <!-- Training Info -->
                <div class="sm:col-span-6">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="training_info" class="w-fit pl-0.5 text-sm">Información de Formación / Bio</label>
                        <textarea name="training_info" id="training_info" rows="4" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('training_info') border border-red-500 @enderror"
                            placeholder="Detalles sobre la formación del coach...">{{ old('training_info', $user->training_info) }}</textarea>
                        @error('training_info') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm mr-2 text-center">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
