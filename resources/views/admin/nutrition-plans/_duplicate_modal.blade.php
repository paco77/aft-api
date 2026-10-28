<!-- Modal Duplicar Plan de Alimentación -->
<div id="duplicate-nutrition-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop oscuro con desenfoque -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity duration-200" onclick="closeDuplicateNutritionModal()"></div>

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
                                Duplicar Plan de Alimentación
                            </h3>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Copia este plan nutricional con todas sus comidas y alimentos para otro cliente o para el mismo.
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDuplicateNutritionModal()" class="text-slate-400 hover:text-white p-1 rounded-xl hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Badge del plan base -->
                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 border border-white/10 text-xs text-slate-200">
                    <span class="text-slate-400">Plan base:</span>
                    <strong id="modal-nutr-base-name" class="text-white font-semibold"></strong>
                </div>
            </div>

            <!-- Formulario de Duplicación -->
            <form id="duplicate-nutrition-form" method="POST" action="" class="p-6 sm:p-7 space-y-5">
                @csrf

                <!-- Selector de Cliente -->
                <div>
                    <label for="modal-nutr-client-id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Asignar al Cliente <span class="text-rose-500">*</span>
                    </label>
                    <select name="client_id" id="modal-nutr-client-id" required
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
                        Puedes asignar este plan al mismo cliente (por ejemplo, para crear una nueva versión) o a cualquier otro cliente.
                    </p>
                </div>

                <!-- Nombre del Nuevo Plan -->
                <div>
                    <label for="modal-nutr-name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nombre del Nuevo Plan
                    </label>
                    <input type="text" name="name" id="modal-nutr-name" placeholder="Ej: Plan de Definición - Julio"
                        class="w-full rounded-xl border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-sm py-2.5 px-3.5 bg-white transition-colors">
                    <p class="text-2xs text-slate-400 mt-1">Si lo dejas vacío, se guardará como copia del plan original.</p>
                </div>

                <!-- Pregunta Clave: ¿Modificar o Guardar Directamente? -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        ¿Cómo deseas proceder? <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Opción 1: Duplicar y Modificar Ahora -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 cursor-pointer transition hover:bg-indigo-50 select-none group has-checked:border-indigo-600 has-checked:bg-indigo-50/70" id="card-nutr-action-edit">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-900">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Duplicar y Editar
                                </span>
                                <input type="radio" name="action_type" value="edit" checked onchange="updateNutrActionCards()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                            </div>
                            <span class="text-2xs text-indigo-700/80 mt-1.5 leading-snug">
                                Abre de inmediato la pantalla de edición para ajustar calorías, alimentos y porciones.
                            </span>
                            <span class="mt-2 inline-flex items-center self-start px-2 py-0.5 rounded-full text-3xs font-extrabold uppercase tracking-wider bg-indigo-600 text-white">
                                Recomendado
                            </span>
                        </label>

                        <!-- Opción 2: Solo Duplicar (Guardar Idéntico) -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none group has-checked:border-indigo-600 has-checked:bg-indigo-50/70" id="card-nutr-action-direct">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-800">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Solo Duplicar
                                </span>
                                <input type="radio" name="action_type" value="direct" onchange="updateNutrActionCards()" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                            </div>
                            <span class="text-2xs text-slate-500 mt-1.5 leading-snug">
                                Guarda el plan idéntico asignado a este cliente sin abrir la pantalla de edición.
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
                            <span class="text-2xs text-slate-500 block">Se abrirá WhatsApp con un mensaje predefinido anunciándole su plan nutricional.</span>
                        </div>
                    </label>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeDuplicateNutritionModal()"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                        Cancelar
                    </button>
                    <button type="submit" id="btn-submit-nutr-duplicate"
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
    function openDuplicateNutritionModal(planId, planName, currentClientId) {
        const modal = document.getElementById('duplicate-nutrition-modal');
        const form = document.getElementById('duplicate-nutrition-form');
        const nameEl = document.getElementById('modal-nutr-base-name');
        const clientSelect = document.getElementById('modal-nutr-client-id');
        const nameInput = document.getElementById('modal-nutr-name');

        if (!modal || !form) return;

        form.action = `/admin/nutrition-plans/${planId}/duplicate`;
        if (nameEl) nameEl.textContent = planName || 'Plan de Alimentación';
        if (nameInput) nameInput.value = (planName ? planName + ' (Copia)' : 'Plan de Alimentación');

        if (clientSelect && currentClientId) {
            clientSelect.value = currentClientId;
        }

        updateNutrActionCards();

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDuplicateNutritionModal() {
        const modal = document.getElementById('duplicate-nutrition-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function updateNutrActionCards() {
        const editCard = document.getElementById('card-nutr-action-edit');
        const directCard = document.getElementById('card-nutr-action-direct');
        const selected = document.querySelector('input[name="action_type"]:checked');

        if (!selected) return;

        if (selected.value === 'edit') {
            if (editCard) {
                editCard.className = 'relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 cursor-pointer transition select-none';
            }
            if (directCard) {
                directCard.className = 'relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none';
            }
        } else {
            if (directCard) {
                directCard.className = 'relative flex flex-col p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 cursor-pointer transition select-none';
            }
            if (editCard) {
                editCard.className = 'relative flex flex-col p-4 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50 select-none';
            }
        }
    }
</script>
