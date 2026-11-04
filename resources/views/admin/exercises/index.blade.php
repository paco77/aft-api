@extends('layouts.admin')

@section('header', 'Gestión de Ejercicios')

@section('content')
<div class="bg-white shadow-sm border border-slate-200/80 rounded-2xl overflow-hidden">
    <div class="px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Biblioteca de Ejercicios</h3>
            <p class="text-xs text-slate-500 mt-0.5">Consulta la lista completa con animaciones GIF, grupos musculares y videos de ejecución.</p>
        </div>
        <a href="{{ route('admin.exercises.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Ejercicio
        </a>
    </div>

    <div class="overflow-x-auto p-4 sm:p-6">
        <table id="exercises-table" class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100">
                    <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-600 uppercase tracking-wider w-24">GIF / Miniatura</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Nombre del Ejercicio</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Grupo Muscular</th>
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Equipo / Nivel</th>
                    <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach($exercises as $exercise)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <!-- Columna GIF / Miniatura -->
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="inline-flex items-center justify-center">
                            @if($exercise->thumbnail_url)
                                <div class="relative group cursor-pointer" onclick="openExercisePreview('{{ addslashes($exercise->name) }}', '{{ addslashes($exercise->muscleGroup?->name ?? 'General') }}', '{{ $exercise->thumbnail_url }}', {{ $exercise->is_video ? 'true' : 'false' }}, '{{ $exercise->youtube_id ?? '' }}')" title="Clic para ampliar GIF / Video">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-900/5 border border-slate-200 shadow-xs group-hover:shadow-md group-hover:scale-105 transition-all flex items-center justify-center relative">
                                        @if($exercise->is_video)
                                            <video src="{{ $exercise->thumbnail_url }}" class="w-full h-full object-cover" autoplay loop muted playsinline></video>
                                            <span class="absolute bottom-1 right-1 bg-slate-900/80 text-white text-[9px] px-1 rounded font-bold">MP4</span>
                                        @elseif($exercise->is_youtube)
                                            <img src="{{ $exercise->thumbnail_url }}" alt="{{ $exercise->name }}" class="w-full h-full object-cover" loading="lazy">
                                            <span class="absolute inset-0 bg-slate-900/30 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white drop-shadow" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </span>
                                        @else
                                            <img src="{{ $exercise->thumbnail_url }}" alt="{{ $exercise->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                            <div class="hidden w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-300" title="Sin animación GIF">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Sin GIF</span>
                                </div>
                            @endif
                        </div>
                    </td>

                    <!-- Columna Nombre -->
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="text-sm font-bold text-slate-900">{{ $exercise->name }}</div>
                        @if($exercise->is_custom)
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Personalizado
                            </span>
                        @endif
                    </td>

                    <!-- Columna Grupo Muscular -->
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $exercise->muscleGroup?->name ?? 'General' }}
                        </span>
                    </td>

                    <!-- Columna Equipo / Nivel -->
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="text-xs text-slate-700 font-medium">{{ $exercise->equipment ?: 'Sin equipo especificado' }}</div>
                        @if($exercise->level)
                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $exercise->level }}</div>
                        @endif
                    </td>

                    <!-- Columna Acciones -->
                    <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.exercises.edit', $exercise) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold transition" title="Editar">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Editar
                            </a>
                            <form action="{{ route('admin.exercises.destroy', $exercise) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este ejercicio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-semibold transition cursor-pointer" title="Eliminar">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Vista Previa de GIF / Video -->
<div id="exercise-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs transition-opacity duration-200" onclick="closeExercisePreview()">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h4 id="modal-exercise-title" class="font-bold text-slate-900 text-base">Ejercicio</h4>
                <p id="modal-exercise-group" class="text-xs text-indigo-600 font-semibold"></p>
            </div>
            <button type="button" onclick="closeExercisePreview()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modal-media-container" class="w-full bg-slate-950 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#exercises-table', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            columnDefs: [
                { orderable: false, targets: [0, 4] } // Desactiva ordenamiento en miniatura y acciones
            ]
        });
    });

    function openExercisePreview(name, group, url, isVideo, youtubeId) {
        document.getElementById('modal-exercise-title').textContent = name;
        document.getElementById('modal-exercise-group').textContent = group;
        const container = document.getElementById('modal-media-container');

        if (youtubeId) {
            container.innerHTML = `<iframe class="w-full aspect-video" src="https://www.youtube-nocookie.com/embed/${youtubeId}?autoplay=1&rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        } else if (isVideo) {
            container.innerHTML = `<video src="${url}" class="w-full max-h-[70vh] object-contain" autoplay loop muted controls playsinline></video>`;
        } else {
            container.innerHTML = `<img src="${url}" alt="${name}" class="w-full max-h-[70vh] object-contain">`;
        }

        const modal = document.getElementById('exercise-preview-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeExercisePreview() {
        const modal = document.getElementById('exercise-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('modal-media-container').innerHTML = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeExercisePreview();
        }
    });
</script>
@endpush
