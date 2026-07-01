@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">Ver Servicio Rechazado</h2>

<div class="mb-4">
    <a href="{{ url('/admin/servicios-rechazados') }}" class="btn btn-secondary">Volver a servicios rechazados</a>
</div>

<div class="card border-0 shadow rounded-4">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-8">
                <h4>{{ $servicio->nombre_servicio }}</h4>
                <p class="text-muted mb-1">{{ $servicio->nombre_empresa ?? 'Empresa no disponible' }}</p>
                <p>{{ $servicio->descripcion }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-danger text-white px-3 py-2">
                    {{ $servicio->estado ?? 'Rechazado' }}
                </span>
                <p class="mb-1"><strong>Fecha rechazo:</strong> {{ $servicio->fecha_rechazo }}</p>
                <p class="mb-0"><strong>Motivo:</strong> {{ $servicio->motivo_rechazo }}</p>
            </div>
        </div>

        <div class="row gy-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <h6 class="mb-1">Categoría</h6>
                    <p>{{ $servicio->categoria }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="mb-1">Ubicación</h6>
                    <p>{{ $servicio->ubicacion_ciudad }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="mb-1">Teléfono</h6>
                    <p>{{ $servicio->telefono_contacto }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <h6 class="mb-1">Correo</h6>
                    <p>{{ $servicio->correo_contacto }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="mb-1">Dirección de atención</h6>
                    <p>{{ $servicio->direccion_atencion }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="mb-1">Redes sociales</h6>
                    <p>{{ $servicio->redes_sociales }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection