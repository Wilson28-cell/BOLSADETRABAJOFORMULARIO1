@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="login-page d-flex align-items-center justify-content-center min-vh-100 py-5" style="background: linear-gradient(135deg, #0D6EFD 0%, #1E3A5F 100%);">
    <div class="card login-card shadow-lg border-0">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h1 class="login-title">Portal Administrativo</h1>
                <p class="login-subtitle mb-0">Acceda con su cuenta institucional a la Municipalidad y Bolsa de Trabajo.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-control form-control-lg rounded-4" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg rounded-4" required>
                </div>

                <button class="btn btn-primary btn-lg w-100 rounded-4">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection
