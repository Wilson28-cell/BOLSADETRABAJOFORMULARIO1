<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo',
            'rol' => 'required|in:Analista,Gestor_Operativo',
            'password' => 'required|string|min:6',
        ]);

        DB::table('usuarios')->insert([
            'nombre_completo' => $data['nombre_completo'],
            'correo' => $data['correo'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'fecha_registro' => now(),
        ]);

        return redirect('admin/validacion-formularios')->with('success', 'Usuario creado correctamente.');
    }
}
