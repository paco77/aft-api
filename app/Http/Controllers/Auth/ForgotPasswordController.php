<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.')
            : back()->withErrors(['email' => $this->translateStatus($status)]);
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
                Password::INVALID_USER => 'No encontramos un usuario con ese correo electrónico.',
                Password::RESET_THROTTLED => 'Por favor espera antes de intentar de nuevo.',
                default => 'Ocurrió un error. Inténtalo de nuevo.',
            };
    }
}
