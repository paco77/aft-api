@extends('layouts.admin')

@section('header', 'Editar Ejercicio')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Editar Ejercicio: {{ $exercise->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">Actualiza la información, grupo muscular o la animación GIF / video demostrativo.</p>
            </div>
            <a href="{{ route('admin.exercises.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
            </a>
        </div>

        <form action="{{ route('admin.exercises.update', $exercise) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
                <!-- Name -->
                <div class="sm:col-span-4">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nombre del Ejercicio <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $exercise->name) }}" required
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
                        @foreach($muscleGroups as $group)
                            <option value="{{ $group->id }}" {{ old('muscle_group_id', $exercise->muscle_group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
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
                        placeholder="Detalles del ejercicio, postura y técnica...">{{ old('description', $exercise->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Inputs para Video / GIF -->
                <div class="sm:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                    <div>
                        <label for="video_file" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>Reemplazar con Archivo de Video o GIF</span>
                            <span class="text-2xs font-normal text-slate-400">Opcional</span>
                        </label>
                        <input type="file" name="video_file" id="video_file" accept="video/*,image/gif"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer border border-slate-300 rounded-xl px-2 py-1.5 bg-slate-50/50 focus:outline-none @error('video_file') border-rose-500 @enderror">
                        <p class="mt-1 text-2xs text-slate-400">Si seleccionas un archivo, reemplazará al actual (GIF, MP4, WebM máx. 50 MB).</p>
                        @error('video_file') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            O Enlace Directo (YouTube / Web)
                        </label>
                        <input type="text" name="video_url" id="video_url" value="{{ old('video_url', (!str_contains($exercise->video_url ?? '', '/storage/') ? $exercise->video_url : '')) }}"
                            placeholder="https://youtube.com/watch?v=... o enlace a GIF"
                            class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400 @error('video_url') border-rose-500 @enderror">
                        <p class="mt-1 text-2xs text-slate-400">Puedes ingresar enlaces de YouTube o links directos a videos/GIFs web.</p>
                        @error('video_url') <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror

                        @if($exercise->video_url)
                            <div class="mt-3 pt-2.5 border-t border-slate-200">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-rose-600 hover:text-rose-700 cursor-pointer">
                                    <input type="checkbox" name="remove_video" id="remove_video" value="1" class="h-4 w-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500">
                                    <span>Eliminar multimedia actual (dejar sin video/GIF)</span>
                                </label>
                            </div>
                        @endif
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
                        <span id="preview-badge" class="px-2.5 py-0.5 rounded-full text-2xs font-bold {{ $exercise->thumbnail_url ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-200 text-slate-600' }} transition-colors">
                            {{ $exercise->thumbnail_url ? ($exercise->is_video ? 'Video actual' : 'Miniatura actual') : 'Sin multimedia' }}
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <!-- Recuadro visual miniatura -->
                        <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-2xl overflow-hidden bg-white border-2 border-slate-200 shadow-sm shrink-0 flex items-center justify-center">
                            <!-- Placeholder -->
                            <div id="preview-placeholder" class="{{ $exercise->thumbnail_url ? 'hidden' : '' }} flex flex-col items-center justify-center text-slate-300 p-2 text-center">
                                <svg class="w-8 h-8 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px] font-bold text-slate-400">Sin Miniatura</span>
                            </div>

                            <!-- Imagen / GIF -->
                            <img id="preview-img" 
                                src="{{ (!$exercise->is_video && $exercise->thumbnail_url) ? $exercise->thumbnail_url : '' }}" 
                                alt="Vista previa" 
                                class="{{ (!$exercise->is_video && $exercise->thumbnail_url) ? '' : 'hidden' }} w-full h-full object-cover">

                            <!-- Video MP4 / WebM -->
                            <video id="preview-video" 
                                src="{{ ($exercise->is_video && $exercise->thumbnail_url) ? $exercise->thumbnail_url : '' }}" 
                                class="{{ ($exercise->is_video && $exercise->thumbnail_url) ? '' : 'hidden' }} w-full h-full object-cover" 
                                autoplay loop muted playsinline></video>
                        </div>

                        <!-- Texto explicativo -->
                        <div class="flex-1 text-center sm:text-left space-y-1">
                            <h5 id="preview-filename" class="text-sm font-bold text-slate-800">
                                @if($exercise->thumbnail_url)
                                    {{ $exercise->is_youtube ? 'Video de YouTube configurado' : ($exercise->is_video ? 'Video MP4 en reproducción' : 'Animación GIF / Imagen actual') }}
                                @else
                                    No se ha cargado ningún GIF o video aún
                                @endif
                            </h5>
                            <p id="preview-hint" class="text-xs text-slate-500 leading-relaxed">
                                @if($exercise->thumbnail_url)
                                    Esta es la miniatura que los alumnos y coaches ven actualmente en la biblioteca de ejercicios y rutinas.
                                @else
                                    Selecciona un archivo GIF/video o pega una URL para previsualizar aquí exactamente la animación en miniatura que verán los alumnos en su rutina.
                                @endif
                            </p>
                            <div id="preview-actions" class="hidden pt-1.5">
                                <button type="button" id="btn-clear-preview" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Descartar nuevo archivo y restaurar actual
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
                    Actualizar Ejercicio
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
        const removeCheckbox = document.getElementById('remove_video');
        const previewImg = document.getElementById('preview-img');
        const previewVideo = document.getElementById('preview-video');
        const previewPlaceholder = document.getElementById('preview-placeholder');
        const previewBadge = document.getElementById('preview-badge');
        const previewFilename = document.getElementById('preview-filename');
        const previewHint = document.getElementById('preview-hint');
        const previewActions = document.getElementById('preview-actions');
        const btnClear = document.getElementById('btn-clear-preview');

        // Valores iniciales existentes
        const initialThumbnail = @json($exercise->thumbnail_url);
        const initialIsVideo = @json($exercise->is_video);
        const initialIsYoutube = @json($exercise->is_youtube);

        let currentObjectUrl = null;

        function restoreInitial() {
            if (currentObjectUrl) {
                URL.revokeObjectURL(currentObjectUrl);
                currentObjectUrl = null;
            }
            previewActions.classList.add('hidden');

            if (removeCheckbox && removeCheckbox.checked) {
                previewImg.src = '';
                previewImg.classList.add('hidden');
                previewVideo.pause();
                previewVideo.src = '';
                previewVideo.classList.add('hidden');
                previewPlaceholder.classList.remove('hidden');

                previewBadge.textContent = 'Marcado para eliminar';
                previewBadge.className = 'px-2.5 py-0.5 rounded-full text-2xs font-bold bg-rose-100 text-rose-700 transition-colors';
                previewFilename.textContent = 'El video/GIF actual se eliminará al guardar';
                previewHint.textContent = 'Desmarca la casilla si deseas conservar el archivo actual.';
                return;
            }

            if (initialThumbnail) {
                if (initialIsVideo) {
                    showVideoPreview(initialThumbnail, 'Video actual', 'Video actual');
                } else if (initialIsYoutube) {
                    showImagePreview(initialThumbnail, 'Video de YouTube (Portada)', 'YouTube Video', 'bg-red-100 text-red-800');
                } else {
                    showImagePreview(initialThumbnail, 'Animación GIF / Imagen actual', 'Multimedia actual', 'bg-indigo-100 text-indigo-800');
                }
                previewHint.textContent = 'Esta es la miniatura que los alumnos y coaches ven actualmente en la biblioteca de ejercicios y rutinas.';
            } else {
                resetPreviewEmpty();
            }
        }

        function resetPreviewEmpty() {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            previewVideo.pause();
            previewVideo.src = '';
            previewVideo.classList.add('hidden');
            previewPlaceholder.classList.remove('hidden');

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

                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }

                if (file.type.startsWith('video/')) {
                    showVideoPreview(currentObjectUrl, `Nuevo archivo: ${file.name}`, 'Nuevo Video Local');
                } else {
                    showImagePreview(currentObjectUrl, `Nuevo archivo: ${file.name}`, 'Nuevo GIF / Imagen', 'bg-emerald-100 text-emerald-800');
                }
            } else {
                restoreInitial();
            }
        });

        btnClear.addEventListener('click', function () {
            fileInput.value = '';
            restoreInitial();
            if (urlInput.value.trim()) {
                checkUrlInput();
            }
        });

        urlInput.addEventListener('input', function () {
            if (!fileInput.files || fileInput.files.length === 0) {
                if (urlInput.value.trim()) {
                    if (removeCheckbox) removeCheckbox.checked = false;
                    checkUrlInput();
                } else {
                    restoreInitial();
                }
            }
        });

        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    fileInput.value = '';
                    urlInput.value = '';
                    restoreInitial();
                } else {
                    restoreInitial();
                }
            });
        }

        function checkUrlInput() {
            const val = urlInput.value.trim();
            if (!val) {
                restoreInitial();
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
            showImagePreview(val, 'Enlace Web a GIF / Imagen', 'Enlace en Línea', 'bg-emerald-100 text-emerald-800');
        }
    });
</script>
@endpush
