<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutdoorActivity;
use Illuminate\Http\Request;

class OutdoorActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = OutdoorActivity::query()->with('user');

        if ($user->role !== 'coach' && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        } elseif ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string',
            'distance_km' => 'required|numeric',
            'duration_seconds' => 'required|integer',
            'avg_pace_min_km' => 'nullable|numeric',
            'coordinates' => 'nullable|array',
        ]);

        $activity = OutdoorActivity::create([
            'user_id' => $request->user()->id,
            'type' => $request->type ?? 'Running',
            'distance_km' => $request->distance_km,
            'duration_seconds' => $request->duration_seconds,
            'avg_pace_min_km' => $request->avg_pace_min_km,
            'coordinates' => $request->coordinates,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Actividad al aire libre registrada exitosamente',
            'data' => $activity->load('user')
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $activity = OutdoorActivity::findOrFail($id);

        if ($activity->user_id !== $user->id && $user->role !== 'coach' && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $activity->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Actividad eliminada'
        ]);
    }
}
