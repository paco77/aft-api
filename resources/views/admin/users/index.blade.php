@extends('layouts.admin')

@section('header', 'Gestión de Usuarios')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Lista de Usuarios</h3>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
            Nuevo Usuario
        </a>
    </div>
    <div class="border-t border-gray-200 overflow-x-auto p-4">
        <table id="users-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre / Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estatus</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->username }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col gap-1 items-start">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-red-100 text-red-800 
                                @elseif($user->role == 'coach') bg-green-100 text-green-800 
                                @elseif($user->role == 'client') bg-blue-100 text-blue-800 
                                @elseif($user->role == 'independiente') bg-purple-100 text-purple-800 
                                @elseif($user->role == 'gimnasio') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->role === 'client')
                                @if($user->coach)
                                    <span class="text-xs text-slate-500">Coach: <span class="font-medium text-slate-700">{{ $user->coach->name }}</span></span>
                                @else
                                    <span class="text-xs text-amber-600 font-medium italic">Sin Coach</span>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(auth()->user()->isSuperAdmin())
                            @if($user->id === auth()->id())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" title="Tu cuenta (siempre activa)">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Activo (Tú)
                                </span>
                            @else
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline toggle-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" 
                                        data-user-name="{{ $user->name }}" 
                                        data-is-active="{{ $user->is_active ? '1' : '0' }}"
                                        class="toggle-status-btn group inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full transition-all duration-200 cursor-pointer shadow-sm border
                                        {{ $user->is_active 
                                            ? 'bg-green-50 text-green-700 border-green-200 hover:bg-red-50 hover:text-red-700 hover:border-red-300' 
                                            : 'bg-red-50 text-red-700 border-red-200 hover:bg-green-50 hover:text-green-700 hover:border-green-300' }}"
                                        title="Clic para {{ $user->is_active ? 'desactivar' : 'activar' }} usuario">
                                        <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500 group-hover:bg-red-500' : 'bg-red-500 group-hover:bg-green-500' }} transition-colors"></span>
                                        <span>{{ $user->is_active ? 'Activo' : 'Inactivo' }}</span>
                                        <svg class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @else
                            @if($user->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactivo
                                </span>
                            @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($user->role === 'client')
                            <a href="{{ route('admin.users.workouts', $user) }}" class="inline-flex items-center px-2.5 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 rounded-md text-xs font-medium mr-2" title="Ver Entrenamientos">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Ejercicios
                            </a>
                            <a href="{{ route('admin.users.progress', $user) }}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md text-xs font-medium mr-2" title="Ver Progreso">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Progreso
                            </a>
                            <a href="{{ route('admin.users.nutrition-plans', $user) }}" class="inline-flex items-center px-2.5 py-1.5 bg-orange-50 text-orange-700 hover:bg-orange-100 rounded-md text-xs font-medium mr-2" title="Ver Planes Alimentación">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Alimentación
                            </a>
                        @endif
                        @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline toggle-status-form mr-3">
                                @csrf
                                @method('PATCH')
                                <button type="button" 
                                    data-user-name="{{ $user->name }}" 
                                    data-is-active="{{ $user->is_active ? '1' : '0' }}"
                                    class="toggle-status-btn inline-flex items-center {{ $user->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}" 
                                    title="{{ $user->is_active ? 'Desactivar Usuario' : 'Activar Usuario' }}">
                                    @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3" title="Ver Perfil">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3" title="Editar">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="inline-flex items-center text-red-600 hover:text-red-900 delete-btn" title="Eliminar">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#users-table', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        // SweetAlert2 for Toggle Status Confirmation
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.toggle-status-btn');
            if (toggleBtn) {
                e.preventDefault();
                const form = toggleBtn.closest('form');
                const userName = toggleBtn.getAttribute('data-user-name');
                const isActive = toggleBtn.getAttribute('data-is-active') === '1';

                const title = isActive ? '¿Desactivar usuario?' : '¿Activar usuario?';
                const text = isActive 
                    ? `El usuario "${userName}" no podrá iniciar sesión en la app ni en el sistema.`
                    : `El usuario "${userName}" tendrá acceso nuevamente al sistema.`;
                const confirmText = isActive ? 'Sí, desactivar' : 'Sí, activar';
                const confirmColor = isActive ? '#ef4444' : '#10b981';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: isActive ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // SweetAlert2 for Delete Confirmation
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará este usuario y todos sus progresos, fotografías y planes. ¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endpush
