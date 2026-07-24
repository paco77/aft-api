<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use Illuminate\Support\Facades\Storage;
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
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt,gif|max:50000',
        ]);

        $muscleGroup = \App\Models\MuscleGroup::where('name', $data['muscle_group'])->first();
        if ($muscleGroup) {
            $data['muscle_group_id'] = $muscleGroup->id;
        }
        unset($data['muscle_group']);

        $data['user_id'] = auth()->id();
        
        $insertData = $data;
        unset($insertData['video_file']);

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('exercises/videos', 'public');
            $insertData['video_url'] = Storage::url($path);
        }

        $exercise = Exercise::create($insertData);
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
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt,gif|max:50000',
        ]);

        if (isset($data['muscle_group'])) {
            $muscleGroup = \App\Models\MuscleGroup::where('name', $data['muscle_group'])->first();
            if ($muscleGroup) {
                $data['muscle_group_id'] = $muscleGroup->id;
            }
            unset($data['muscle_group']);
        }

        $updateData = $data;
        unset($updateData['video_file']);

        if ($request->hasFile('video_file')) {
            if ($exercise->video_url) {
                $oldPath = str_replace('/storage/', '', $exercise->video_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('video_file')->store('exercises/videos', 'public');
            $updateData['video_url'] = Storage::url($path);
        }

        $exercise->update($updateData);

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
