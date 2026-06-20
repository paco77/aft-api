<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $query = User::latest();
        if (auth()->user()->role === 'coach') {
            $query->where('role', 'client')->where('coach_id', auth()->id());
        }
        $users = $query->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->get();
        return view('admin.users.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin';
            $rules['coach_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        $role = auth()->user()->role === 'admin' ? $request->role : 'client';
        $coach_id = auth()->user()->role === 'coach' ? auth()->id() : $request->coach_id;

        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'coach_id' => $coach_id,
            'training_info' => $request->training_info,
            'profile_photo_path' => $profilePhotoPath,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }
        $coaches = User::where('role', 'coach')->get();
        return view('admin.users.edit', compact('user', 'coaches'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'training_info' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['role'] = 'required|string|in:coach,client,usuario,admin';
            $rules['coach_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        $role = auth()->user()->role === 'admin' ? $request->role : 'client';
        $coach_id = auth()->user()->role === 'coach' ? auth()->id() : $request->coach_id;

        $userData = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'coach_id' => $coach_id,
            'training_info' => $request->training_info,
        ];

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $userData['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update($userData);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function progress(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver el progreso de este usuario.');
        }

        $progressLogs = $user->progressLogs()->latest('recorded_at')->get();

        return view('admin.users.progress', compact('user', 'progressLogs'));
    }

    public function nutritionPlans(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para ver los planes de este usuario.');
        }

        // Utilizar la relación de NutritionPlan donde el usuario es el cliente
        $nutritionPlans = \App\Models\NutritionPlan::where('client_id', $user->id)->latest()->get();

        return view('admin.users.nutrition-plans', compact('user', 'nutritionPlans'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role === 'coach' && ($user->role !== 'client' || $user->coach_id !== auth()->id())) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
