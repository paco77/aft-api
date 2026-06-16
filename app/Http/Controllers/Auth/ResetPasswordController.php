<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', '¡Contraseña actualizada correctamente! Inicia sesión con tu nueva contraseña.')
            : back()->withErrors(['email' => $this->translateStatus($status)]);
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
                Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
                Password::INVALID_TOKEN => 'El enlace de recuperación es inválido o ha expirado.',
                Password::RESET_THROTTLED => 'Por favor espera antes de intentar de nuevo.',
                default => 'Ocurrió un error. Inténtalo de nuevo.',
            };
    }
}
