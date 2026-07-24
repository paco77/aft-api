<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with('muscleGroup')->where('is_active', true)->latest()->get();
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        $muscleGroups = \App\Models\MuscleGroup::all();
        return view('admin.exercises.create', compact('muscleGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt,gif|max:50000',
        ]);

        $data = $request->except('video_file');
        $data['user_id'] = auth()->id();
        $data['is_custom'] = true;

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('exercises/videos', 'public');
            $data['video_url'] = Storage::url($path);
        }
        Exercise::create($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio creado correctamente.');
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt,gif|max:50000',
        ]);

        $data = $request->only(['name', 'muscle_group_id', 'description']);
        $data['user_id'] = auth()->id();
        $data['is_custom'] = true; // Assuming coaches creating on the fly are custom exercises

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('exercises/videos', 'public');
            $data['video_url'] = Storage::url($path);
        }

        $exercise = Exercise::create($data);
        
        // Load relation for response
        $exercise->load('muscleGroup');

        return response()->json([
            'success' => true,
            'exercise' => $exercise
        ]);
    }

    public function edit(Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este ejercicio.');
        }
        $muscleGroups = \App\Models\MuscleGroup::all();
        return view('admin.exercises.edit', compact('exercise', 'muscleGroups'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este ejercicio.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt,gif|max:50000',
        ]);

        $data = $request->except('video_file');

        if ($request->hasFile('video_file')) {
            if ($exercise->video_url) {
                $oldPath = str_replace('/storage/', '', $exercise->video_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('video_file')->store('exercises/videos', 'public');
            $data['video_url'] = Storage::url($path);
        }

        $exercise->update($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio actualizado correctamente.');
    }

    public function destroy(Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este ejercicio.');
        }
        $exercise->update(['is_active' => false]);
        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio eliminado (deshabilitado) correctamente.');
    }
}
