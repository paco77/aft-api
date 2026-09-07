@extends('layouts.admin')

@section('header', 'Actividades al Aire Libre (Cardio GPS)')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.outdoor-activities.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Filtrar por Cliente</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Todos los clientes --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('user_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} ({{ $client->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Actividad</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Todos los tipos --</option>
                    <option value="running" {{ request('type') == 'running' ? 'selected' : '' }}>Carrera (Running)</option>
                    <option value="walking" {{ request('type') == 'walking' ? 'selected' : '' }}>Caminata (Walking)</option>
                    <option value="cycling" {{ request('type') == 'cycling' ? 'selected' : '' }}>Ciclismo (Cycling)</option>
                    <option value="hiking" {{ request('type') == 'hiking' ? 'selected' : '' }}>Senderismo (Hiking)</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow text-sm font-medium">
                    Filtrar
                </button>
                @if(request()->hasAny(['user_id', 'type']))
                    <a href="{{ route('admin.outdoor-activities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm font-medium">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Recorridos -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Historial de Recorridos GPS</h3>
            <span class="text-xs text-gray-500">Total: {{ $activities->total() }} registros</span>
        </div>

        <div class="border-t border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actividad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distancia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ritmo Prom.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'Usuario Eliminado' }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeColor = match($activity->type) {
                                    'running' => 'bg-emerald-100 text-emerald-800',
                                    'cycling' => 'bg-blue-100 text-blue-800',
                                    'hiking' => 'bg-amber-100 text-amber-800',
                                    default => 'bg-purple-100 text-purple-800'
                                };
                                $label = match($activity->type) {
                                    'running' => 'Carrera',
                                    'cycling' => 'Ciclismo',
                                    'hiking' => 'Senderismo',
                                    'walking' => 'Caminata',
                                    default => ucfirst($activity->type)
                                };
                            @endphp
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($activity->distance_km, 2) }} km
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @php
                                $hours = floor($activity->duration_seconds / 3600);
                                $minutes = floor(($activity->duration_seconds % 3600) / 60);
                                $seconds = $activity->duration_seconds % 60;
                            @endphp
                            @if($hours > 0)
                                {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                            @else
                                {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ number_format($activity->avg_pace_min_km, 2) }} min/km
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $activity->created_at ? $activity->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @if(!empty($activity->coordinates) && is_array($activity->coordinates))
                                <button type="button" 
                                    onclick="showMapModal({{ json_encode($activity->coordinates) }}, '{{ addslashes($activity->user->name ?? '') }}', '{{ number_format($activity->distance_km, 2) }} km')"
                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-semibold text-xs">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ver Mapa
                                </button>
                            @endif

                            <form action="{{ route('admin.outdoor-activities.destroy', $activity->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 font-semibold text-xs" onclick="return confirm('¿Deseas eliminar este registro de actividad GPS?')">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No hay recorridos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $activities->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Mapa Leaflet -->
<div id="mapModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-75 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 bg-indigo-600 text-white flex justify-between items-center">
            <div>
                <h4 id="modalTitle" class="text-lg font-bold">Ruta Recorrida</h4>
                <p id="modalSub" class="text-xs text-indigo-100"></p>
            </div>
            <button onclick="closeMapModal()" class="text-white hover:text-indigo-200 text-2xl font-bold">&times;</button>
        </div>
        <div class="p-4 flex-1">
            <div id="leafletMap" class="w-full h-96 rounded-lg border border-gray-200"></div>
        </div>
        <div class="px-6 py-3 bg-gray-50 text-right">
            <button onclick="closeMapModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium">
                Cerrar
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map = null;
let polyline = null;

function showMapModal(coords, userName, distStr) {
    if (!coords || !Array.isArray(coords) || coords.length === 0) return;

    document.getElementById('modalTitle').innerText = `Ruta de ${userName}`;
    document.getElementById('modalSub').innerText = `Distancia: ${distStr} - ${coords.length} puntos de rastreo GPS`;
    document.getElementById('mapModal').classList.remove('hidden');

    setTimeout(() => {
        const latLngs = coords.map(c => [c.latitude || c.lat, c.longitude || c.lng || c.lon]);

        if (!map) {
            map = L.map('leafletMap');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        } else {
            map.invalidateSize();
            if (polyline) map.removeLayer(polyline);
        }

        polyline = L.polyline(latLngs, { color: '#4f46e5', weight: 4 }).addTo(map);
        map.fitBounds(polyline.getBounds(), { padding: [30, 30] });

        // Marcador Inicio
        L.circleMarker(latLngs[0], { color: '#10b981', radius: 8 }).addTo(map).bindPopup('Inicio');
        // Marcador Fin
        L.circleMarker(latLngs[latLngs.length - 1], { color: '#ef4444', radius: 8 }).addTo(map).bindPopup('Fin');
    }, 200);
}

function closeMapModal() {
    document.getElementById('mapModal').classList.add('hidden');
}
</script>
@endsection
