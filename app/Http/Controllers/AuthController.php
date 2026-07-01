<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\UseCases\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function validar(LoginRequest $request, AuthService $authService)
    {
        $usuario = $authService->authenticate($request->correo, $request->password);

        if (!$usuario) {
            return back()->withInput()->withErrors(['correo' => 'Credenciales incorrectas'])->with('error', 'Credenciales incorrectas');
        }

        $request->session()->regenerate();
        Session::put('usuario', $usuario);

        $allowedRoles = ['Super_Administrador', 'Analista', 'Gestor_Operativo', 'SuperAdministrador', 'Super Administrador'];

        if (! isset($usuario->rol) || ! in_array($usuario->rol, $allowedRoles, true)) {
            // Invalidate session for unauthorized users
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Session::forget('usuario');

            return back()->withInput()->withErrors(['correo' => 'No tienes permiso para acceder al panel administrativo'])->with('error', 'No tienes permiso para acceder al panel administrativo');
        }

        return match ($usuario->rol ?? '') {
            'Super_Administrador' => redirect('admin/validacion-formularios'),
            'Analista' => redirect('admin/indicadores-bolsa'),
            'Gestor_Operativo' => redirect('admin/validacion-formularios'),
            default => redirect('admin/validacion-formularios'),
        };
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();

        return redirect('/login');
    }

    public function confirmPassword(Request $request, AuthService $authService)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $usuario = Session::get('usuario');

        if (! $usuario || ! $authService->matchesPassword($request->password, $usuario->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta',
            ], 422);
        }

        return response()->json(['success' => true]);
    }
}
