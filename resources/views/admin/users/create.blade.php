@extends('layouts.admin')

@section('header', 'Crear Usuario')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form id="user-form" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Profile Photo -->
                <div class="sm:col-span-6">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="profile_photo" class="w-fit pl-0.5 text-sm">Foto de Perfil</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewImage(event, 'preview_profile_photo')"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('profile_photo') border border-red-500 @enderror">
                        <div class="mt-2">
                            <img id="preview_profile_photo" class="hidden rounded-lg object-cover h-32 w-32 border border-gray-300 dark:border-gray-700" />
                        </div>
                        @error('profile_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="name" class="w-fit pl-0.5 text-sm">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('name') border border-red-500 @enderror"
                            placeholder="Ej. Juan Pérez">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="username" class="w-fit pl-0.5 text-sm">Nombre de Usuario</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('username') border border-red-500 @enderror"
                            placeholder="Ej. juanperez">
                        @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="email" class="w-fit pl-0.5 text-sm">Correo Electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
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
                            <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario (Básico)</option>
                            <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                            <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Super Administrador</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="is_active" class="w-fit pl-0.5 text-sm">Estatus</label>
                        <select name="is_active" id="is_active" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('is_active') border border-red-500 @enderror">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('is_active') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3" id="coach-selector" style="display: {{ old('role') === 'client' ? 'block' : 'none' }};">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="coach_id" class="w-fit pl-0.5 text-sm">Asignar a Coach</label>
                        <select name="coach_id" id="coach_id" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark @error('coach_id') border border-red-500 @enderror">
                            <option value="">-- Sin Coach (Desligado) --</option>
                            @foreach($coaches as $coach)
                                 <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>
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
                        const clientFields = document.getElementById('client-fields');
                        const coachSelect = document.getElementById('coach_id');
                        
                        function toggleCoachSelector() {
                            if (roleSelect && roleSelect.value === 'client') {
                                coachSelector.style.display = 'block';
                                if (clientFields) {
                                    clientFields.classList.remove('hidden');
                                    clientFields.classList.add('grid');
                                }
                            } else if (coachSelector) {
                                coachSelector.style.display = 'none';
                                if (clientFields) {
                                    clientFields.classList.add('hidden');
                                    clientFields.classList.remove('grid');
                                }
                                if (coachSelect) coachSelect.value = '';
                            }
                        }
                        
                        if (roleSelect) {
                            roleSelect.addEventListener('change', toggleCoachSelector);
                        }
                    });
                </script>
                @endif

                <!-- Client Only Fields -->
                <div class="sm:col-span-6 grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 {{ old('role') === 'client' || auth()->user()->role !== 'admin' ? 'grid' : 'hidden' }}" id="client-fields">
                    
                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="weight" class="w-fit pl-0.5 text-sm">Peso Inicial (kg)</label>
                            <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="height" class="w-fit pl-0.5 text-sm">Altura (cm)</label>
                            <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="age" class="w-fit pl-0.5 text-sm">Edad</label>
                            <input type="number" name="age" id="age" value="{{ old('age') }}" 
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="training_time" class="w-fit pl-0.5 text-sm">Tiempo Entrenando</label>
                            <input type="text" name="training_time" id="training_time" value="{{ old('training_time') }}" placeholder="Ej. 6 meses"
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="objectives" class="w-fit pl-0.5 text-sm">Objetivos</label>
                            <input type="text" name="objectives" id="objectives" value="{{ old('objectives') }}" placeholder="Ej. Perder peso"
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex w-full flex-col gap-2 text-on-surface dark:text-on-surface-dark">
                            <label class="w-fit pl-0.5 text-sm font-bold">Métricas Adicionales (Opcional)</label>
                            <div id="dynamic-metrics-container" class="space-y-2">
                                <!-- Javascript will inject metric rows here -->
                            </div>
                            <button type="button" onclick="addMetricRow()" class="mt-2 w-fit text-sm text-primary hover:text-primary-dark font-semibold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Agregar Métrica (Grasa, Cintura, etc.)
                            </button>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="front_photo" class="w-fit pl-0.5 text-sm">Foto Frente (Progreso Inicial)</label>
                            <input type="file" name="front_photo" id="front_photo" accept="image/*" onchange="previewImage(event, 'preview_front_photo')"
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                            <div class="mt-2 flex justify-center">
                                <img id="preview_front_photo" class="hidden rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" />
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="side_photo" class="w-fit pl-0.5 text-sm">Foto Perfil</label>
                            <input type="file" name="side_photo" id="side_photo" accept="image/*" onchange="previewImage(event, 'preview_side_photo')"
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                            <div class="mt-2 flex justify-center">
                                <img id="preview_side_photo" class="hidden rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" />
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                            <label for="back_photo" class="w-fit pl-0.5 text-sm">Foto Espalda</label>
                            <input type="file" name="back_photo" id="back_photo" accept="image/*" onchange="previewImage(event, 'preview_back_photo')"
                                class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                            <div class="mt-2 flex justify-center">
                                <img id="preview_back_photo" class="hidden rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="password" class="w-fit pl-0.5 text-sm">Contraseña</label>
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
                            placeholder="Detalles sobre la formación del coach...">{{ old('training_info') }}</textarea>
                        @error('training_info') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm mr-2 text-center">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center flex items-center justify-center min-w-[140px]">
                    <span id="btn-text">Crear Usuario</span>
                    <svg id="loading-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event, previewId) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function(){
            var dataURL = reader.result;
            var img = document.getElementById(previewId);
            img.src = dataURL;
            img.classList.remove('hidden');
        };
        if(input.files && input.files[0]){
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addMetricRow() {
        const container = document.getElementById('dynamic-metrics-container');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2';
        
        row.innerHTML = `
            <input type="text" name="metrics[keys][]" placeholder="Ej. Grasa Corporal" required
                class="w-1/2 rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
            <input type="text" name="metrics[values][]" placeholder="Ej. 15%" required
                class="w-1/2 rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        
        container.appendChild(row);
    }

    document.getElementById('user-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        const text = document.getElementById('btn-text');
        const spinner = document.getElementById('loading-spinner');
        
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        text.innerText = 'Guardando...';
        spinner.classList.remove('hidden');
    });
</script>

@endsection
