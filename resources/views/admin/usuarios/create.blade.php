@extends('admin.layout')

@section('content')
<div class="container">
    <h3>Crear usuario administrativo</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('admin/usuarios') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre_completo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="Analista">Analista</option>
                <option value="Gestor_Operativo">Gestor Operativo</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <button class="btn btn-primary">Crear usuario</button>
    </form>
</div>
@endsection
