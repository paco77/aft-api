<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientProgressLog;
use App\Models\User;
use Illuminate\Http\Request;

class ClientProgressController extends Controller
{
    public function index(Request $request, User $client)
    {
        // Verificar que el cliente pertenece al coach logueado o que el usuario sea el propio cliente
        $user = $request->user();
        if ($user->role === 'coach' && $client->coach_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->role === 'client' && $user->id !== $client->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $logs = $client->progressLogs()->latest('recorded_at')->get();
        return response()->json($logs);
    }

    public function store(Request $request, User $client)
    {
        // Solo el coach de este cliente puede agregar progreso
        $user = $request->user();
        if ($user->role !== 'coach' || $client->coach_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. Solo el coach puede registrar el progreso.'], 403);
        }

        $validated = $request->validate([
            'weight' => 'nullable|numeric',
            'measurements' => 'nullable|json',
            'front_photo' => 'nullable|image|max:20480',
            'side_photo' => 'nullable|image|max:20480',
            'back_photo' => 'nullable|image|max:20480',
            'comments' => 'nullable|string',
            'recorded_at' => 'required|date',
        ]);

        $logData = [
            'client_id' => $client->id,
            'coach_id' => $user->id,
            'weight' => $validated['weight'] ?? null,
            'measurements' => isset($validated['measurements']) ? json_decode($validated['measurements'], true) : null,
            'comments' => $validated['comments'] ?? null,
            'recorded_at' => $validated['recorded_at'],
        ];

        if ($request->hasFile('front_photo')) {
            $logData['front_photo_path'] = $request->file('front_photo')->store('progress_photos', 'public');
        }
        if ($request->hasFile('side_photo')) {
            $logData['side_photo_path'] = $request->file('side_photo')->store('progress_photos', 'public');
        }
        if ($request->hasFile('back_photo')) {
            $logData['back_photo_path'] = $request->file('back_photo')->store('progress_photos', 'public');
        }

        $progressLog = ClientProgressLog::create($logData);

        return response()->json($progressLog, 201);
    }
}
