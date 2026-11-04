@extends('layouts.admin')

@section('header', 'Crear Ejercicio')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Nuevo Ejercicio</h3>
                <p class="text-xs text-slate-500 mt-0.5">Ingresa los datos del ejercicio y añade su animación GIF o video demostrativo.</p>
            </div>
            <a href="{{ route('admin.exercises.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
        </div>

        <form action="{{ route('admin.exercises.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
                <!-- Name -->
                <div class="sm:col-span-4">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nombre del Ejercicio <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all @error('name') border-rose-500 ring-rose-500/10 @enderror"
                        placeholder="Ej. Press de Banca con Barra">
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Muscle Group -->
                <div class="sm:col-span-2">
                    <label for="muscle_group_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Grupo Muscular <span class="text-rose-500">*</span>
                    </label>
                    <select name="muscle_group_id" id="muscle_group_id" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-semibold focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all @error('muscle_group_id') border-rose-500 ring-rose-500/10 @enderror">
                        <option value="">Seleccionar grupo...</option>
                        @foreach($muscleGroups as $group)
                            <option value="{{ $group->id }}" {{ old('muscle_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('muscle_group_id') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Descripción e Instrucciones de Técnica <span class="text-slate-400 font-normal lowercase">(opcional)</span>
                    </label>
                    <textarea name="description" id="description" rows="3" 
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400"
                        placeholder="Describe la posición inicial, trayectoria de la barra o mancuernas y respiración durante la ejecución...">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Inputs para Video / GIF -->
                <div class="sm:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                    <div>
                        <label for="video_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>Subir Archivo de Video o GIF</span>
                            <span class="text-2xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">Recomendado</span>
                        </label>
                        <input type="file" name="video_file" id="video_file" accept="video/*,image/gif"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer border border-slate-300 rounded-xl px-2 py-1.5 bg-slate-50/50 focus:outline-none @error('video_file') border-rose-500 @enderror">
                        <p class="mt-1 text-2xs text-slate-400">Acepta archivos animaciones GIF, MP4 o WebM (máx. 50 MB).</p>
                        @error('video_file') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            O Enlace Directo (YouTube / Web)
                        </label>
                        <input type="text" name="video_url" id="video_url" value="{{ old('video_url') }}"
                            placeholder="https://youtube.com/watch?v=... o enlace a GIF"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400 @error('video_url') border-rose-500 @enderror">
                        <p class="mt-1 text-2xs text-slate-400">Si pegas un link de YouTube, se extraerá la portada automáticamente.</p>
                        @error('video_url') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- CARD DE VISTA PREVIA DE LA MINIATURA / GIF -->
                <div class="sm:col-span-6 bg-slate-50/80 rounded-2xl p-5 border border-slate-200/90 shadow-2xs">
                    <div class="flex items-center justify-between mb-3.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Vista Previa de la Miniatura</span>
                        </label>
                        <span id="preview-badge" class="px-2.5 py-0.5 rounded-full text-2xs font-bold bg-slate-200 text-slate-600 transition-colors">
                            Sin multimedia seleccionada
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <!-- Recuadro visual miniatura -->
                        <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden bg-white border-2 border-slate-200 shadow-sm shrink-0 flex items-center justify-center">
                            <!-- Placeholder -->
                            <div id="preview-placeholder" class="flex flex-col items-center justify-center text-slate-300 p-2 text-center">
                                <svg class="w-8 h-8 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px] font-bold text-slate-400">Sin Miniatura</span>
                            </div>

                            <!-- Imagen / GIF -->
                            <img id="preview-img" src="" alt="Vista previa" class="hidden w-full h-full object-cover">

                            <!-- Video MP4 / WebM -->
                            <video id="preview-video" src="" class="hidden w-full h-full object-cover" autoplay loop muted playsinline></video>
                        </div>

                        <!-- Texto explicativo -->
                        <div class="flex-1 text-center sm:text-left space-y-1">
                            <h5 id="preview-filename" class="text-sm font-bold text-slate-800">No se ha cargado ningún GIF o video aún</h5>
                            <p id="preview-hint" class="text-xs text-slate-500 leading-relaxed">
                                Selecciona un archivo GIF/video o pega una URL para previsualizar aquí exactamente la animación en miniatura que verán los alumnos en su rutina.
                            </p>
                            <div id="preview-actions" class="hidden pt-1.5">
                                <button type="button" id="btn-clear-preview" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Quitar archivo seleccionado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.exercises.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition-colors text-sm">
                    Cancelar
                </a>
                <button type="submit" 
                    style="background-color: #2563eb; color: #ffffff;"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition-all text-sm flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Guardar Ejercicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('video_file');
        const urlInput = document.getElementById('video_url');
        const previewImg = document.getElementById('preview-img');
        const previewVideo = document.getElementById('preview-video');
        const previewPlaceholder = document.getElementById('preview-placeholder');
        const previewBadge = document.getElementById('preview-badge');
        const previewFilename = document.getElementById('preview-filename');
        const previewHint = document.getElementById('preview-hint');
        const previewActions = document.getElementById('preview-actions');
        const btnClear = document.getElementById('btn-clear-preview');

        let currentObjectUrl = null;

        function resetPreview() {
            if (currentObjectUrl) {
                URL.revokeObjectURL(currentObjectUrl);
                currentObjectUrl = null;
            }
            previewImg.src = '';
            previewImg.classList.add('hidden');
            previewVideo.pause();
            previewVideo.src = '';
            previewVideo.classList.add('hidden');
            previewPlaceholder.classList.remove('hidden');
            previewActions.classList.add('hidden');

            previewBadge.textContent = 'Sin multimedia seleccionada';
            previewBadge.className = 'px-2.5 py-0.5 rounded-full text-2xs font-bold bg-slate-200 text-slate-600 transition-colors';
            previewFilename.textContent = 'No se ha cargado ningún GIF o video aún';
            previewHint.textContent = 'Selecciona un archivo GIF/video o pega una URL para previsualizar aquí exactamente la animación en miniatura que verán los alumnos en su rutina.';
        }

        function showImagePreview(src, title, badgeText, badgeClass = 'bg-emerald-100 text-emerald-800') {
            if (currentObjectUrl && src !== currentObjectUrl) {
                URL.revokeObjectURL(currentObjectUrl);
                currentObjectUrl = null;
            }
            previewVideo.pause();
            previewVideo.src = '';
            previewVideo.classList.add('hidden');

            previewImg.src = src;
            previewImg.classList.remove('hidden');
            previewPlaceholder.classList.add('hidden');

            previewBadge.textContent = badgeText;
            previewBadge.className = `px-2.5 py-0.5 rounded-full text-2xs font-bold ${badgeClass} transition-colors`;
            previewFilename.textContent = title;
            previewHint.textContent = 'Vista previa de la animación o imagen que se mostrará en las tablas y rutinas.';
        }

        function showVideoPreview(src, title, badgeText) {
            previewImg.src = '';
            previewImg.classList.add('hidden');

            previewVideo.src = src;
            previewVideo.classList.remove('hidden');
            previewPlaceholder.classList.add('hidden');
            previewVideo.play().catch(() => {});

            previewBadge.textContent = badgeText;
            previewBadge.className = 'px-2.5 py-0.5 rounded-full text-2xs font-bold bg-blue-100 text-blue-800 transition-colors';
            previewFilename.textContent = title;
            previewHint.textContent = 'Vista previa del video MP4 en reproducción continua silenciada.';
        }

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                currentObjectUrl = URL.createObjectURL(file);
                previewActions.classList.remove('hidden');

                if (file.type.startsWith('video/')) {
                    showVideoPreview(currentObjectUrl, `Archivo: ${file.name}`, 'Video MP4 / Local');
                } else {
                    showImagePreview(currentObjectUrl, `Animación: ${file.name}`, 'Animación GIF / Imagen');
                }
            } else {
                checkUrlInput();
            }
        });

        btnClear.addEventListener('click', function () {
            fileInput.value = '';
            resetPreview();
            checkUrlInput();
        });

        urlInput.addEventListener('input', function () {
            if (!fileInput.files || fileInput.files.length === 0) {
                checkUrlInput();
            }
        });

        function checkUrlInput() {
            const val = urlInput.value.trim();
            if (!val) {
                resetPreview();
                return;
            }

            // YouTube
            const ytMatch = val.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            if (ytMatch) {
                const ytThumb = `https://img.youtube.com/vi/${ytMatch[1]}/hqdefault.jpg`;
                showImagePreview(ytThumb, 'Video de YouTube (Portada)', 'YouTube Video', 'bg-red-100 text-red-800');
                return;
            }

            // Direct Video
            if (/\.(mp4|webm|mov|ogg|qt)($|\?)/i.test(val)) {
                showVideoPreview(val, 'Enlace de Video Directo', 'Video en Línea');
                return;
            }

            // Direct Image / GIF
            showImagePreview(val, 'Enlace Web a GIF / Imagen', 'Enlace en Línea');
        }

        // Check if there is an old value
        if (urlInput.value.trim()) {
            checkUrlInput();
        }
    });
</script>
@endpush
