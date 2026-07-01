<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;

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
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['is_custom'] = true;
        Exercise::create($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio creado correctamente.');
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'muscle_group_id', 'description']);
        $data['user_id'] = auth()->id();
        $data['is_custom'] = true; // Assuming coaches creating on the fly are custom exercises

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
        ]);

        $exercise->update($request->all());

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
