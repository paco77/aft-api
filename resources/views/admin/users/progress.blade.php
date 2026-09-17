@extends('layouts.admin')

@section('header')
    Progreso de
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
        </div>

        <!-- Client Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-6 md:items-center">
                <!-- Profile Image -->
                <div class="flex-shrink-0 mx-auto md:mx-0">
                    @if($user->profile_photo_path)
                        <img src="{{ Storage::disk('s3')->url($user->profile_photo_path) }}" alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full object-cover border-2 border-slate-100 shadow-sm">
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-slate-900 flex items-center justify-center text-white text-3xl font-bold shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Profile Info Details -->
                <div class="flex-grow text-center md:text-left space-y-2">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 justify-center md:justify-start">
                        <h2 class="text-2xl font-black text-slate-800">{{ $user->name }}</h2>

                    </div>
                    <p class="text-slate-600 font-medium">{{ $user->email }}</p>
                    <div
                        class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500 font-medium justify-center md:justify-start">
                        <div><span class="text-slate-400">Edad:</span> {{ $user->age ?? 'N/A' }} años</div>
                        <div><span class="text-slate-400">Peso Inicial:</span> {{ $user->weight ?? 'N/A' }} kg</div>
                        <div><span class="text-slate-400">Estatura:</span> {{ $user->height ?? 'N/A' }} m</div>
                        <div><span class="text-slate-400">Nivel/Tiempo:</span> {{ $user->training_time ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            @if($user->objectives)
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Objetivos</p>
                    <p class="text-slate-700 font-medium">{{ $user->objectives }}</p>
                </div>
            @endif
        </div>

        <!-- Progress Logs Timeline -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Historial de Progreso
            </h3>

            @if($progressLogs->isEmpty())
                <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center">
                    <div
                        class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-slate-700 font-bold mb-1">Aún no hay progreso registrado</h4>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto">Cuando el coach registre evaluaciones físicas de este
                        cliente desde la aplicación móvil, aparecerán listadas aquí.</p>
                </div>
            @else
                <div class="relative border-l-2 border-slate-200 ml-4 md:ml-6 space-y-8">
                    @foreach($progressLogs as $log)
                        <div class="relative pl-8 md:pl-10">
                            <!-- Timeline Point -->
                            <span
                                class="absolute -left-[9px] top-1.5 bg-indigo-600 border-4 border-white w-4 h-4 rounded-full shadow-sm"></span>

                            <!-- Log Card -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                                <!-- Card Header -->
                                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                                    <div class="space-y-0.5 flex-grow">
                                        <p class="text-sm font-bold text-slate-400">FECHA DE EVALUACIÓN</p>
                                        <p class="text-lg font-extrabold text-slate-800">
                                            {{ \Carbon\Carbon::parse($log->recorded_at)->translatedFormat('d \d\e F, Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.users.progress.edit', ['user' => $user->id, 'progress' => $log->id]) }}" 
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar Progreso">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.users.progress.destroy', ['user' => $user->id, 'progress' => $log->id]) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition delete-btn" title="Eliminar Progreso">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2 text-right">
                                            <p class="text-xs font-bold text-indigo-500">PESO REGISTRADO</p>
                                            <p class="text-xl font-black text-indigo-700">
                                                {{ $log->weight ? $log->weight . ' kg' : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Measurements section -->
                                @if(is_array($log->measurements) && count($log->measurements) > 0)
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Medidas Corporales</p>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                            @foreach($log->measurements as $key => $value)
                                                @if(!empty($value))
                                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center">
                                                        <p class="text-xs text-slate-500 font-semibold mb-0.5">{{ ucfirst($key) }}</p>
                                                        <p class="text-sm font-black text-slate-700">{{ $value }}</p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Comments -->
                                @if($log->comments)
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Comentarios y
                                            Observaciones</p>
                                        <p class="text-slate-600 text-sm italic font-medium">"{{ $log->comments }}"</p>
                                    </div>
                                @endif

                                <!-- Progression Photos -->
                                @if($log->front_photo_path || $log->side_photo_path || $log->back_photo_path)
                                    <div class="space-y-3">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Evidencia Fotográfica</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            @if($log->front_photo_path)
                                                <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                                    <img src="{{ Storage::disk('s3')->url($log->front_photo_path) }}" alt="Foto de Frente"
                                                        class="w-full h-64 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                                        onclick="window.open('{{ Storage::disk('s3')->url($log->front_photo_path) }}', '_blank')">
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/60 to-transparent p-3">
                                                        <p class="text-white text-xs font-bold text-center">Vista Frontal</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($log->side_photo_path)
                                                <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                                    <img src="{{ Storage::disk('s3')->url($log->side_photo_path) }}" alt="Foto Lateral"
                                                        class="w-full h-64 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                                        onclick="window.open('{{ Storage::disk('s3')->url($log->side_photo_path) }}', '_blank')">
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/60 to-transparent p-3">
                                                        <p class="text-white text-xs font-bold text-center">Vista Lateral</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($log->back_photo_path)
                                                <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                                    <img src="{{ Storage::disk('s3')->url($log->back_photo_path) }}" alt="Foto Posterior"
                                                        class="w-full h-64 object-cover cursor-zoom-in group-hover:scale-105 transition duration-300"
                                                        onclick="window.open('{{ Storage::disk('s3')->url($log->back_photo_path) }}', '_blank')">
                                                    <div
                                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/60 to-transparent p-3">
                                                        <p class="text-white text-xs font-bold text-center">Vista Posterior</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto, se eliminarán las fotos de progreso asociadas.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush