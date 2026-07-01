<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = Session::get('usuario');

        if (! $usuario) {
            return redirect()->route('login')
                ->with('error', 'Por favor inicia sesión para acceder al panel administrativo.');
        }

        $allowedRoles = ['Super_Administrador', 'Analista', 'Gestor_Operativo', 'SuperAdministrador', 'Super Administrador'];

        if (! isset($usuario->rol) || ! in_array($usuario->rol, $allowedRoles, true)) {
            return redirect()->route('login')
                ->with('error', 'No tienes permiso para acceder a esta área.');
        }

        if ($usuario->rol === 'Analista') {
            // Analista: only read-only access — allow GET requests across admin routes
            if (! $request->isMethod('GET')) {
                return redirect()->route('login')
                    ->with('error', 'No tienes permiso para realizar esta acción.');
            }
        }

        if ($usuario->rol === 'Gestor_Operativo' && $request->is('admin/indicadores*')) {
            return redirect()->route('login')
                ->with('error', 'No tienes permiso para acceder a los indicadores.');
        }

        return $next($request);
    }
}
