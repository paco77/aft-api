<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro para coach, independiente o gimnasio.
     */
    public function showRegistrationForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $allowedRoles = ['coach', 'independiente', 'gimnasio'];
        $role = $request->query('role', 'coach');
        if (!in_array($role, $allowedRoles)) {
            $role = 'coach';
        }

        return view('auth.register', compact('role'));
    }

    /**
     * Procesa la creación de la nueva cuenta.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['coach', 'independiente', 'gimnasio'])],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'username.unique' => 'Este nombre de usuario ya está registrado en la plataforma.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor introduce un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'role.required' => 'Debes seleccionar un perfil válido.',
            'role.in' => 'El perfil seleccionado no es válido.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('status', '¡Bienvenido a AF Training! Tu cuenta ha sido creada exitosamente.');
    }
}
