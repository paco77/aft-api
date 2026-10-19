@extends('layouts.admin')

@section('header', 'Código QR del Gimnasio')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Código QR de Rutinas</h1>
            <p class="text-sm text-slate-500 mt-1">
                Tus atletas y clientes escanean este QR en la sala de pesas para abrir la lista de rutinas y registrar sus entrenamientos.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Imprimir Cartel</span>
            </button>
            <a href="{{ $publicUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Abrir Vista</span>
            </a>
        </div>
    </div>

    @if(auth()->user()->role === 'admin' && $allGyms->count() > 1)
        <!-- Admin Gym Selector -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-4">
            <label class="text-sm font-semibold text-slate-700 whitespace-nowrap">Ver QR de otro Gimnasio:</label>
            <select onchange="window.location.href = '{{ route('admin.gym-routines.qr') }}?gym_id=' + this.value" 
                    class="rounded-lg border border-slate-300 text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach($allGyms as $g)
                    <option value="{{ $g->id }}" {{ $gym->id == $g->id ? 'selected' : '' }}>
                        {{ $g->name }} (@ {{ $g->username }})
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- QR Card for Display & Printing -->
    <div id="printArea" class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 text-center relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- AF Header & Logo -->
        <div class="relative z-10 flex flex-col items-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800/80 border border-slate-700 text-xs font-bold uppercase tracking-wider text-blue-400 mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Zona Oficial AF Training</span>
            </div>

            <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-white mb-2">
                {{ $gym->name }}
            </h2>
            <p class="text-slate-400 text-sm sm:text-base max-w-md mx-auto mb-8">
                Escanea el código con tu celular para elegir tu rutina de hoy y registrar tus series en tiempo real.
            </p>

            <!-- QR Container Card -->
            <div class="bg-white p-5 rounded-2xl shadow-xl border-4 border-blue-500/30 inline-block mb-6 relative group">
                <div id="qrcode" class="flex items-center justify-center"></div>
                <div class="mt-2 text-center text-slate-800 font-extrabold text-xs tracking-wider uppercase">
                    AF TRAINING
                </div>
            </div>

            <!-- Badges -->
            <div class="flex flex-wrap items-center justify-center gap-3 text-xs text-slate-300 mb-6">
                <div class="flex items-center gap-1.5 bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-700/60">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>{{ $routinesCount }} {{ Str::plural('Rutina', $routinesCount) }} Disponibles</span>
                </div>
                <div class="flex items-center gap-1.5 bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-700/60">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Temporizador & Registro en Vivo</span>
                </div>
                <div class="flex items-center gap-1.5 bg-slate-800/60 px-3 py-1.5 rounded-lg border border-slate-700/60">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Sin descargas obligatorias</span>
                </div>
            </div>

            <!-- URL Copy Field -->
            <div class="w-full max-w-lg bg-slate-800/80 border border-slate-700 rounded-xl p-2 flex items-center gap-2">
                <input type="text" readonly id="publicUrlInput" value="{{ $publicUrl }}" 
                       class="bg-transparent text-xs text-slate-300 px-2 py-1 flex-1 font-mono focus:outline-none">
                <button type="button" onclick="copyUrl()" id="copyBtn"
                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-semibold transition shrink-0">
                    Copiar Enlace
                </button>
            </div>
        </div>
    </div>
</div>

<!-- QRCode.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const qrContainer = document.getElementById('qrcode');
    const url = "{{ $publicUrl }}";

    // Generate QR code
    new QRCode(qrContainer, {
        text: url,
        width: 200,
        height: 200,
        colorDark: "#0f172a",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
});

function copyUrl() {
    const input = document.getElementById('publicUrlInput');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);

    const btn = document.getElementById('copyBtn');
    btn.textContent = '¡Copiado!';
    btn.classList.add('bg-emerald-600');
    setTimeout(() => {
        btn.textContent = 'Copiar Enlace';
        btn.classList.remove('bg-emerald-600');
    }, 2000);
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printArea, #printArea * {
        visibility: visible;
    }
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: #0f172a !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection
