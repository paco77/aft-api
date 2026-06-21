@extends('layouts.admin')

@section('header', 'Crear Plan de Entrenamiento')

@section('content')
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8 relative">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('admin.plans.store') }}" method="POST" class="p-6" id="planForm">
                @csrf
            
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">¡Uy! Hubo algunos problemas con tus datos:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 mb-8 border-b pb-6">
                    <div class="sm:col-span-3">
                        <label for="assigned_client_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select name="assigned_client_id" id="assigned_client_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                            <option value="">Selecciona un cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="month" class="block text-sm font-medium text-gray-700">Mes</label>
                        <select name="month" id="month"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="year" class="block text-sm font-medium text-gray-700">Año</label>
                        <input type="number" name="year" id="year" value="{{ date('Y') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="split_type" class="block text-sm font-medium text-gray-700">Tipo de Rutina
                            (Split)</label>
                        <select name="split_type" id="split_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                            <option value="">Seleccionar...</option>
                            <option value="Empuje">Empuje</option>
                            <option value="Traccion">Traccion</option>
                            <option value="Pierna">Pierna</option>
                            <option value="Full Body">Full Body</option>
                            <option value="Personalizado">Personalizado</option>
                        </select>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="days_per_week" class="block text-sm font-medium text-gray-700">Días por semana</label>
                        <input type="number" name="days_per_week" id="days_per_week" min="1" max="7" value="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required>
                    </div>
                </div>

                <div id="days-container">
                    <!-- Días dinámicos -->
                </div>

                <div class="mt-4 mb-8">
                    <button type="button" onclick="addDay()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded text-sm text-center border border-gray-400">
                        + Añadir Día de Entrenamiento
                    </button>
                </div>

                <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.plans.index') }}"
                        class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded text-sm mr-2 text-center">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                        Guardar Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Seleccionar/Crear Ejercicios -->
    <div id="exercise-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeExerciseModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Añadir Ejercicios</h3>
                        <button type="button" onclick="closeExerciseModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Buscador -->
                    <div class="mb-4">
                        <input type="text" id="exercise-search" placeholder="Buscar ejercicio..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Lista de Ejercicios -->
                    <div class="max-h-[60vh] overflow-y-auto border border-gray-200 rounded-md p-4 mb-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3"
                        id="modal-exercises-list">
                        @foreach($exercises as $ex)
                            <div class="flex items-start p-2 hover:bg-gray-50 rounded border border-gray-100 exercise-item"
                                data-name="{{ strtolower($ex->name) }}" data-mg="{{ $ex->muscle_group_id }}">
                                <input type="checkbox" id="ex-{{ $ex->id }}" value="{{ $ex->id }}" data-name="{{ $ex->name }}"
                                    class="modal-exercise-checkbox mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="ex-{{ $ex->id }}" class="ml-2 block text-sm text-gray-900 cursor-pointer w-full leading-snug">
                                    {{ $ex->name }}<br><span
                                        class="text-xs text-gray-500">({{ $ex->muscleGroup->name ?? 'N/A' }})</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Acordeón para crear ejercicio rápido -->
                    <div class="border-t border-gray-200 pt-4">
                        <button type="button" onclick="toggleQuickCreate()"
                            class="text-sm text-indigo-600 hover:text-indigo-900 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Crear nuevo ejercicio si no está en la lista
                        </button>

                        <div id="quick-create-form" class="hidden mt-3 p-3 bg-gray-50 rounded border border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Nombre</label>
                                    <input type="text" id="qc-name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Grupo Muscular</label>
                                    <select id="qc-muscle-group"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccionar...</option>
                                        @foreach($muscleGroups as $mg)
                                            <option value="{{ $mg->id }}" data-name="{{ $mg->name }}">{{ $mg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700">Descripción</label>
                                    <textarea id="qc-description" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="quickCreateExercise()"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-3 rounded text-sm flex items-center">
                                    <span id="qc-spinner" class="hidden mr-2">...</span>
                                    Guardar Ejercicio
                                </button>
                            </div>
                            <p id="qc-error" class="text-xs text-red-600 mt-2 hidden"></p>
                        </div>
                    </div>

                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="addSelectedExercises()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Añadir Seleccionados
                    </button>
                    <button type="button" onclick="closeExerciseModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template para Ejercicio en la lista -->
    <template id="exercise-template">
        <div
            class="exercise-row flex items-center gap-4 mb-3 bg-gray-50 p-3 rounded border border-gray-200 cursor-move transition hover:bg-gray-100">
            <div class="flex-none text-gray-400 cursor-move">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <input type="hidden" class="exercise-id" value="">
                <p class="text-sm font-bold text-gray-900 exercise-name-display truncate"></p>
            </div>
            <div class="w-20">
                <label class="block text-xs text-gray-500">Series</label>
                <input type="number"
                    class="exercise-sets mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="3" min="1" required>
            </div>
            <div class="w-20">
                <label class="block text-xs text-gray-500">Min Reps</label>
                <input type="number"
                    class="exercise-min mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="8" min="1" required>
            </div>
            <div class="w-20">
                <label class="block text-xs text-gray-500">Max Reps</label>
                <input type="number"
                    class="exercise-max mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    value="12" min="1" required>
            </div>
            <div>
                <button type="button" class="text-red-500 hover:text-red-700 mt-5 remove-exercise transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        let dayIndex = 0;
        let currentDayBlockForModal = null;

        // Search in modal
        document.getElementById('exercise-search').addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.exercise-item');
            items.forEach(item => {
                if (item.classList.contains('filtered-out')) return; // ignore filtered out by muscle group

                const name = item.getAttribute('data-name');
                if (name.includes(term)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        function addDay() {
            const container = document.getElementById('days-container');

            let muscleGroupsHtml = '';
            @foreach($muscleGroups as $mg)
                muscleGroupsHtml += `
                                        <div class="flex items-center">
                                            <input id="mg-${dayIndex}-{{ $mg->id }}" name="days[${dayIndex}][muscle_groups][]" value="{{ $mg->id }}" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label for="mg-${dayIndex}-{{ $mg->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ $mg->name }}
                                            </label>
                                        </div>
                                    `;
            @endforeach

                    const dayHtml = `
                        <div class="day-block bg-white border border-gray-200 shadow-sm rounded-xl p-5 mb-6" data-day-index="${dayIndex}">
                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Día ${dayIndex + 1}
                                </h4>
                                <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium remove-day flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Eliminar Día
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Etiqueta del Día (Ej. Pecho y Tríceps)</label>
                                    <input type="text" name="days[${dayIndex}][label]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Número de Día</label>
                                    <input type="number" name="days[${dayIndex}][day_number]" value="${dayIndex + 1}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                            </div>

                            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <label class="block text-sm font-bold text-gray-700 mb-3">Grupos Musculares Tratados (Opcional)</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    ${muscleGroupsHtml}
                                </div>
                            </div>

                            <div class="exercises-container" id="exercises-container-${dayIndex}">
                                <!-- Ejercicios aquí -->
                            </div>

                            <button type="button" class="mt-3 w-full border-2 border-dashed border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 font-bold py-3 px-4 rounded-lg text-sm text-center transition flex items-center justify-center gap-2 open-modal-btn">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Añadir Ejercicios
                            </button>
                        </div>
                    `;

            container.insertAdjacentHTML('beforeend', dayHtml);

            const newBlock = container.lastElementChild;

            // Setup Sortable for drag & drop
            const exContainer = newBlock.querySelector('.exercises-container');
            new Sortable(exContainer, {
                animation: 150,
                handle: '.cursor-move',
                onEnd: function () {
                    updateExerciseIndices(newBlock);
                }
            });

            newBlock.querySelector('.remove-day').addEventListener('click', function () {
                newBlock.remove();
            });

            newBlock.querySelector('.open-modal-btn').addEventListener('click', function () {
                openExerciseModal(newBlock);
            });

            dayIndex++;
        }

        function openExerciseModal(dayBlock) {
            currentDayBlockForModal = dayBlock;
            
            // Get selected muscle groups in this day block
            const checkboxes = dayBlock.querySelectorAll('input[type="checkbox"]:checked');
            const selectedMgIds = Array.from(checkboxes).map(cb => cb.value);

            // Filter items in modal based on selectedMgIds
            const items = document.querySelectorAll('.exercise-item');
            items.forEach(item => {
                const itemMg = item.getAttribute('data-mg');
                if (selectedMgIds.length === 0 || selectedMgIds.includes(itemMg)) {
                    item.classList.remove('filtered-out');
                    item.style.display = 'flex';
                } else {
                    item.classList.add('filtered-out');
                    item.style.display = 'none';
                }
            });

            // Reset checkboxes and search
            document.querySelectorAll('.modal-exercise-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('exercise-search').value = '';

            document.getElementById('exercise-modal').classList.remove('hidden');
        }

        function closeExerciseModal() {
            document.getElementById('exercise-modal').classList.add('hidden');
            currentDayBlockForModal = null;
        }

        function toggleQuickCreate() {
            const form = document.getElementById('quick-create-form');
            form.classList.toggle('hidden');
        }

        async function quickCreateExercise() {
            const nameInput = document.getElementById('qc-name');
            const mgSelect = document.getElementById('qc-muscle-group');
            const descInput = document.getElementById('qc-description');
            const errP = document.getElementById('qc-error');

            if (!nameInput.value.trim() || !mgSelect.value) {
                errP.textContent = 'Nombre y Grupo Muscular son obligatorios.';
                errP.classList.remove('hidden');
                return;
            }

            errP.classList.add('hidden');

            try {
                const res = await fetch("{{ route('admin.exercises.api-store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: nameInput.value.trim(),
                        muscle_group_id: mgSelect.value,
                        description: descInput.value.trim()
                    })
                });

                const data = await res.json();
                if (data.success) {
                    // Add to list
                    const list = document.getElementById('modal-exercises-list');
                    const ex = data.exercise;
                    const mgName = mgSelect.options[mgSelect.selectedIndex].getAttribute('data-name');

                    const itemHtml = `
                                <div class="flex items-center p-2 hover:bg-gray-50 rounded exercise-item" data-name="${ex.name.toLowerCase()}">
                                    <input type="checkbox" id="ex-${ex.id}" value="${ex.id}" data-name="${ex.name}" class="modal-exercise-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" checked>
                                    <label for="ex-${ex.id}" class="ml-3 block text-sm text-gray-900 cursor-pointer w-full">
                                        ${ex.name} <span class="text-xs text-gray-500">(${mgName})</span>
                                    </label>
                                </div>
                            `;
                    list.insertAdjacentHTML('afterbegin', itemHtml);

                    // Clear and close quick create form
                    nameInput.value = '';
                    mgSelect.value = '';
                    descInput.value = '';
                    toggleQuickCreate();

                } else {
                    errP.textContent = 'Error al crear ejercicio.';
                    errP.classList.remove('hidden');
                }
            } catch (e) {
                errP.textContent = 'Error de conexión.';
                errP.classList.remove('hidden');
            }
        }

        function addSelectedExercises() {
            if (!currentDayBlockForModal) return;

            const checkboxes = document.querySelectorAll('.modal-exercise-checkbox:checked');
            const container = currentDayBlockForModal.querySelector('.exercises-container');

            checkboxes.forEach(cb => {
                const exId = cb.value;
                const exName = cb.getAttribute('data-name');

                const template = document.getElementById('exercise-template').content.cloneNode(true);
                const newRow = template.querySelector('.exercise-row');

                newRow.querySelector('.exercise-id').value = exId;
                newRow.querySelector('.exercise-name-display').textContent = exName;

                newRow.querySelector('.remove-exercise').addEventListener('click', function () {
                    newRow.remove();
                    updateExerciseIndices(currentDayBlockForModal);
                });

                container.appendChild(newRow);
            });

            updateExerciseIndices(currentDayBlockForModal);
            closeExerciseModal();
        }

        function updateExerciseIndices(dayBlock) {
            const dIndex = dayBlock.getAttribute('data-day-index');
            const rows = dayBlock.querySelectorAll('.exercise-row');

            rows.forEach((row, index) => {
                row.querySelector('.exercise-id').name = `days[${dIndex}][exercises][${index}][exercise_id]`;
                row.querySelector('.exercise-sets').name = `days[${dIndex}][exercises][${index}][sets]`;
                row.querySelector('.exercise-min').name = `days[${dIndex}][exercises][${index}][min_reps]`;
                row.querySelector('.exercise-max').name = `days[${dIndex}][exercises][${index}][max_reps]`;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            addDay(); // Añadir un día inicial al cargar
        });
    </script>
@endpush