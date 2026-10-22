<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            if ($request->filled('redirect')) {
                return redirect($request->input('redirect'));
            }
            if (Auth::user()->role === 'client') {
                return redirect()->route('admin.plans.index');
            }
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->login)
            ->orWhere('email', $request->login)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->is_active) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => 'Usuario Inactivo, favor de contactar al Administrador'
                    ], 422);
                }
                throw ValidationException::withMessages([
                    'login' => ['Usuario Inactivo, favor de contactar al Administrador'],
                ]);
            }

            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            $target = $request->input('redirect');
            if (!$target) {
                if ($user->role === 'client') {
                    $target = route('admin.plans.index');
                } else {
                    $target = route('admin.dashboard');
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $target,
                ]);
            }

            if ($request->filled('redirect')) {
                return redirect($request->input('redirect'));
            }

            return redirect()->intended($target);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Las credenciales proporcionadas son incorrectas.'
            ], 422);
        }

        throw ValidationException::withMessages([
            'login' => ['Las credenciales proporcionadas son incorrectas.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->filled('redirect')) {
            return redirect($request->input('redirect'));
        }

        return redirect('/');
    }
}
