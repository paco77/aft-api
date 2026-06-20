<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::latest()->get();
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        return view('admin.exercises.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Exercise::create($data);

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio creado correctamente.');
    }

    public function edit(Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este ejercicio.');
        }
        return view('admin.exercises.edit', compact('exercise'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este ejercicio.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        $exercise->update($request->all());

        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio actualizado correctamente.');
    }

    public function destroy(Exercise $exercise)
    {
        if (auth()->user()->role === 'coach' && $exercise->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este ejercicio.');
        }
        $exercise->delete();
        return redirect()->route('admin.exercises.index')->with('success', 'Ejercicio eliminado correctamente.');
    }
}
