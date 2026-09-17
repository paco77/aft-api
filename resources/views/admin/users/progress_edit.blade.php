@extends('layouts.admin')

@section('header', 'Editar Progreso de ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('admin.users.progress', $user) }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Progreso
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form id="progress-form" action="{{ route('admin.users.progress.update', ['user' => $user->id, 'progress' => $progress->id]) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                
                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="recorded_at" class="w-fit pl-0.5 text-sm">Fecha de Evaluación</label>
                        <input type="date" name="recorded_at" id="recorded_at" value="{{ old('recorded_at', \Carbon\Carbon::parse($progress->recorded_at)->format('Y-m-d')) }}" required
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50 @error('recorded_at') border border-red-500 @enderror">
                        @error('recorded_at') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="weight" class="w-fit pl-0.5 text-sm">Peso (kg)</label>
                        <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $progress->weight) }}" 
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50 @error('weight') border border-red-500 @enderror">
                        @error('weight') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="sm:col-span-6 mt-4 border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="w-fit pl-0.5 text-sm font-semibold text-slate-700">Métricas Adicionales (Opcional)</label>
                        <button type="button" onclick="addMetricRow()" class="text-xs bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-1.5 rounded flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar
                        </button>
                    </div>
                    <div id="dynamic-metrics-container" class="space-y-3">
                        @php
                            $currentMetrics = old('metrics', is_array($progress->measurements) ? $progress->measurements : []);
                        @endphp
                        @foreach($currentMetrics as $key => $value)
                            <div class="flex items-center gap-2">
                                <input type="text" name="metrics[keys][]" value="{{ $key }}" placeholder="Ej. Grasa Corporal" required
                                    class="w-1/2 rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                                <input type="text" name="metrics[values][]" value="{{ $value }}" placeholder="Ej. 15%" required
                                    class="w-1/2 rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="sm:col-span-2 mt-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="front_photo" class="w-fit pl-0.5 text-sm">Foto Frente</label>
                        <input type="file" name="front_photo" id="front_photo" accept="image/*" onchange="previewImage(event, 'preview_front_photo')"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        @error('front_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div class="mt-2 flex justify-center">
                            <img id="preview_front_photo" class="{{ $progress->front_photo_path ? '' : 'hidden' }} rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" 
                                 src="{{ $progress->front_photo_path ? Storage::disk('s3')->url($progress->front_photo_path) : '' }}" />
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2 mt-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="side_photo" class="w-fit pl-0.5 text-sm">Foto Perfil</label>
                        <input type="file" name="side_photo" id="side_photo" accept="image/*" onchange="previewImage(event, 'preview_side_photo')"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        @error('side_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div class="mt-2 flex justify-center">
                            <img id="preview_side_photo" class="{{ $progress->side_photo_path ? '' : 'hidden' }} rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" 
                                 src="{{ $progress->side_photo_path ? Storage::disk('s3')->url($progress->side_photo_path) : '' }}" />
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2 mt-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="back_photo" class="w-fit pl-0.5 text-sm">Foto Espalda</label>
                        <input type="file" name="back_photo" id="back_photo" accept="image/*" onchange="previewImage(event, 'preview_back_photo')"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">
                        @error('back_photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <div class="mt-2 flex justify-center">
                            <img id="preview_back_photo" class="{{ $progress->back_photo_path ? '' : 'hidden' }} rounded-lg object-cover h-40 w-32 border border-gray-300 dark:border-gray-700" 
                                 src="{{ $progress->back_photo_path ? Storage::disk('s3')->url($progress->back_photo_path) : '' }}" />
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-6 mt-4">
                    <div class="flex w-full flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="comments" class="w-fit pl-0.5 text-sm">Comentarios / Observaciones</label>
                        <textarea name="comments" id="comments" rows="3"
                            class="w-full rounded-radius bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark-alt/50">{{ old('comments', $progress->comments) }}</textarea>
                        @error('comments') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.users.progress', $user) }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors relative flex items-center justify-center">
                    <span id="btn-text">Guardar Cambios</span>
                    <svg id="btn-spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addMetricRow() {
        const container = document.getElementById('dynamic-metrics-container');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 mt-2';
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

    document.getElementById('progress-form').addEventListener('submit', function() {
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const submitBtn = document.getElementById('submit-btn');

        btnText.textContent = 'Guardando...';
        btnSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endpush
