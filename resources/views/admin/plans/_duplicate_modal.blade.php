<!-- Modal Duplicar Plan de Entrenamiento -->
<div id="duplicate-plan-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop oscuro con desenfoque -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity duration-200" onclick="closeDuplicateModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200/80">
            
            <!-- Modal Header con gradiente premium -->
            <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shrink-0 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-extrabold tracking-tight text-white" id="modal-title">
                                Duplicar Plan de Entrenamiento
                            </h3>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Copia todas las sesiones, días y ejercicios para un nuevo periodo o cliente.
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDuplicateModal()" class="text-slate-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Badge del plan original -->
                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 border border-white/10 text-xs text-slate-200">
                    <span class="text-slate-400">Plan base:</span>
                    <strong id="modal-plan-name" class="text-white font-semibold"></strong>
                </div>
            </div>

            <!-- Formulario de Duplicación -->
            <form id="duplicate-plan-form" method="POST" action="" class="p-6 sm:p-7 space-y-5">
                @csrf

                <!-- Selector de Cliente -->
                <div>
                    <label for="modal-assigned-client-id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Asignar al Cliente <span class="text-rose-500">*</span>
                    </label>
                    <select name="assigned_client_id" id="modal-assigned-client-id" required
                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 bg-slate-50/70 transition-colors">
                        <option value="">-- Selecciona el cliente --</option>
                        @if(isset($clients) && count($clients) > 0)
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-name="{{ $client->name }}">
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-2xs text-slate-500 mt-1">
                        Puedes conservar el plan para el mismo cliente (por ejemplo, para el siguiente mes) o asignárselo a otro cliente.
                    </p>
                </div>

                <!-- Periodo: Mes y Año -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="modal-month" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Mes del Nuevo Plan <span class="text-rose-500">*</span>
                        </label>
                        <select name="month" id="modal-month" required
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 bg-white transition-colors">
                            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="modal-year" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Año <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="year" id="modal-year" value="{{ date('Y') }}" required min="2020" max="2100"
                            class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3 transition-colors">
                    </div>
                </div>

                <!-- Pregunta Clave: ¿Modificar o Guardar Directamente? -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        ¿Deseas personalizar el nuevo plan? <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Opción 1: Duplicar y Modificar Ahora -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 cursor-pointer transition hover:bg-indigo-50 select-none group has-checked:border-indigo-600 has-checked:bg-indigo-50/70" id="card-action-edit">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-900">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Duplicar y Modificar
                                </span>
                                <input type="radio" name="action_type" value="edit" checked onchange="updateActionCards()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                            </div>
                            <span class="text-2xs text-indigo-700/80 mt-1.5 leading-snug">
                                Abre de inmediato la pantalla de edición para cambiar ejercicios, series o cargas para este cliente.
                            </span>
                            <span class="mt-2 inline-flex items-center self-start px-2 py-0.5 rounded-full text-3xs font-extrabold uppercase tracking-wider bg-indigo-600 text-white">
                                Recomendado
                            </span>
                        </label>

                        <!-- Opción 2: Solo Duplicar (Guardar Idéntico) -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none group has-checked:border-indigo-600 has-checked:bg-indigo-50/70" id="card-action-save">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Solo Duplicar
                                </span>
                                <input type="radio" name="action_type" value="save" onchange="updateActionCards()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                            </div>
                            <span class="text-2xs text-slate-500 mt-1.5 leading-snug">
                                Crea el plan idéntico con las mismas especificaciones y permanece en la lista de planes.
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Checkbox de WhatsApp -->
                <div class="pt-2 border-t border-slate-100">
                    <label class="relative flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="notify_whatsapp" value="1" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <div class="text-xs">
                            <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.668-.699c.969.586 1.761.884 2.792.884 3.18 0 5.766-2.586 5.767-5.766.001-3.181-2.585-5.77-5.767-5.77zm0-2.172c4.418 0 8 3.582 8 8 0 4.418-3.582 8-8 8-1.391 0-2.696-.356-3.834-.981l-4.197 1.101 1.119-4.093c-.684-1.183-1.088-2.559-1.088-4.027 0-4.418 3.582-8 8-8z"/>
                                </svg>
                                Notificar al cliente por WhatsApp
                            </span>
                            <span class="text-2xs text-slate-500 block">Se abrirá WhatsApp con un mensaje predefinido anunciándole su nuevo plan.</span>
                        </div>
                    </label>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeDuplicateModal()"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                        Cancelar
                    </button>
                    <button type="submit" id="btn-submit-duplicate"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>Duplicar y Continuar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const MONTH_NAMES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    function openDuplicateModal(planId, planName, currentClientId, currentMonth, currentYear) {
        const modal = document.getElementById('duplicate-plan-modal');
        const form = document.getElementById('duplicate-plan-form');
        const nameEl = document.getElementById('modal-plan-name');
        const clientSelect = document.getElementById('modal-assigned-client-id');
        const monthSelect = document.getElementById('modal-month');
        const yearInput = document.getElementById('modal-year');

        if (!modal || !form) return;

        // Establecer la acción del formulario
        form.action = `/admin/plans/${planId}/duplicate`;

        // Nombre del plan base
        if (nameEl) {
            nameEl.textContent = planName || 'Plan Seleccionado';
        }

        // Seleccionar el cliente actual por defecto si existe
        if (clientSelect) {
            if (currentClientId) {
                clientSelect.value = currentClientId;
            } else {
                clientSelect.selectedIndex = 0;
            }
        }

        // Calcular el mes sugerido: siguiente mes por defecto
        let nextMonthIndex = -1;
        if (currentMonth) {
            const idx = MONTH_NAMES.indexOf(currentMonth);
            if (idx !== -1) {
                nextMonthIndex = (idx + 1) % 12;
            }
        }
        if (nextMonthIndex === -1) {
            nextMonthIndex = new Date().getMonth();
        }

        if (monthSelect) {
            monthSelect.value = MONTH_NAMES[nextMonthIndex];
        }

        // Año sugerido
        if (yearInput) {
            let yr = currentYear || new Date().getFullYear();
            // Si el mes pasa de diciembre a enero, incrementar el año
            if (currentMonth === 'Diciembre' && MONTH_NAMES[nextMonthIndex] === 'Enero') {
                yr = parseInt(yr) + 1;
            }
            yearInput.value = yr;
        }

        // Reset radio buttons a 'edit' por defecto
        const editRadio = document.querySelector('input[name="action_type"][value="edit"]');
        if (editRadio) {
            editRadio.checked = true;
            updateActionCards();
        }

        // Mostrar modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDuplicateModal() {
        const modal = document.getElementById('duplicate-plan-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function updateActionCards() {
        const editRadio = document.querySelector('input[name="action_type"][value="edit"]');
        const cardEdit = document.getElementById('card-action-edit');
        const cardSave = document.getElementById('card-action-save');

        if (editRadio && editRadio.checked) {
            cardEdit.className = "relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/70 cursor-pointer transition select-none shadow-xs";
            cardSave.className = "relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none";
        } else {
            cardSave.className = "relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/70 cursor-pointer transition select-none shadow-xs";
            cardEdit.className = "relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none";
        }
    }

    // Cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDuplicateModal();
        }
    });
</script>
