<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (!auth()->user()->is_active) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['login' => 'Tu cuenta ha sido desactivada. Favor de contactar al Administrador.']);
            }

            if (in_array(auth()->user()->role, ['admin', 'super_admin', 'coach', 'gimnasio', 'independiente', 'client'])) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}
