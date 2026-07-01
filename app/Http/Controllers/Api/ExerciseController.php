<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Http\Resources\ExerciseResource;

class ExerciseController extends Controller
{
    public function index()
    {
        return ExerciseResource::collection(Exercise::with('muscleGroup')->where('is_active', true)->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group' => 'required|string',
            'equipment' => 'string|nullable',
            'description' => 'string|nullable',
            'is_custom' => 'boolean|nullable',
            'primary_muscles' => 'array|nullable',
            'secondary_muscles' => 'array|nullable',
            'benefits' => 'array|nullable',
            'level' => 'string|nullable',
        ]);

        $muscleGroup = \App\Models\MuscleGroup::where('name', $data['muscle_group'])->first();
        if ($muscleGroup) {
            $data['muscle_group_id'] = $muscleGroup->id;
        }
        unset($data['muscle_group']);

        $data['user_id'] = auth()->id();
        $exercise = Exercise::create($data);
        $exercise->load('muscleGroup');

        return new ExerciseResource($exercise);
    }

    public function show(Exercise $exercise)
    {
        return new ExerciseResource($exercise);
    }

    public function update(Request $request, Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== null && $exercise->user_id != auth()->id()) {
            return response()->json(['message' => 'No tienes permiso para editar este ejercicio.'], 403);
        }

        $data = $request->validate([
            'name' => 'string|max:255',
            'muscle_group' => 'string',
            'equipment' => 'string',
            'description' => 'string|nullable',
            'primary_muscles' => 'array|nullable',
            'secondary_muscles' => 'array|nullable',
            'benefits' => 'array|nullable',
            'level' => 'string|nullable',
            'is_custom' => 'boolean',
        ]);

        if (isset($data['muscle_group'])) {
            $muscleGroup = \App\Models\MuscleGroup::where('name', $data['muscle_group'])->first();
            if ($muscleGroup) {
                $data['muscle_group_id'] = $muscleGroup->id;
            }
            unset($data['muscle_group']);
        }

        $exercise->update($data);

        return new ExerciseResource($exercise);
    }

    public function destroy(Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== null && $exercise->user_id != auth()->id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este ejercicio.'], 403);
        }

        $exercise->update(['is_active' => false]);

        return response()->json([
            'message' => 'Ejercicio eliminado correctamente.',
        ]);
    }
}
