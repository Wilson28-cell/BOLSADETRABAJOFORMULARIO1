@extends('layouts.app')

@section('content')

<h1 class="fw-bold mb-4">Publicidad</h1>

<p class="text-muted mb-4">
    Elige un área de publicidad para mostrar tu empresa y tus servicios en un sitio separado del portal general.
</p>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card h-100 shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h4 class="fw-bold">💼 Bolsa de Trabajo</h4>
                <p class="text-muted">
                    Publica ofertas laborales y recibe postulaciones de candidatos.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ url('publicidad/bolsa-trabajo') }}" class="btn btn-primary w-100">
                        Ver publicidad de bolsa de trabajo
                    </a>
                    <!-- Botón 'Ver ofertas vencidas' eliminado -->
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h4 class="fw-bold">📦 Productos</h4>
                <p class="text-muted">
                    Publica los productos de tu empresa para que los clientes te contacten.
                </p>
                <a href="{{ url('publicidad/productos') }}" class="btn btn-primary w-100">
                    Ver publicidad de productos
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h4 class="fw-bold">🛠 Servicios</h4>
                <p class="text-muted">
                    Publica los servicios de tu empresa y genera nuevas oportunidades.
                </p>
                <a href="{{ url('publicidad/servicios') }}" class="btn btn-primary w-100">
                    Ver publicidad de servicios
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
