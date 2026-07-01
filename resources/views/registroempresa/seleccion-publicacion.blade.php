@extends('layouts.app')

@section('content')

<div class="registro-empresa-page">
    <div class="registro-card">
        <div class="registro-card-header">
            <div class="registro-card-title">
                <span class="registro-card-icon">🏛️</span>
                <div>
                    <div class="registro-badge">Registro de Empresa</div>
                    <h1>Registro de Empresa</h1>
                </div>
            </div>
            @include('components.stepper', ['paso' => 1])
        </div>

        <div class="registro-card-body">
            <div class="registro-intro">
                <h2>¿Qué deseas publicar?</h2>
                <p>Selecciona el tipo de publicación que deseas realizar.</p>
            </div>

            <div class="row gx-4 gy-4">

                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ url('registro/productos') }}" class="registro-option-card text-decoration-none text-reset">
                        <div>
                            <div class="option-icon">📦</div>
                            <h3>Productos</h3>
                            <p>Publica productos de tu empresa con una presentación clara y corporativa.</p>
                        </div>
                        <div class="option-card-divider"></div>
                        <span class="option-btn">Seleccionar →</span>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ url('registro/servicios') }}" class="registro-option-card text-decoration-none text-reset">
                        <div>
                            <div class="option-icon">🛠️</div>
                            <h3>Servicios</h3>
                            <p>Publica servicios profesionales para conectar con la comunidad y el sector municipal.</p>
                        </div>
                        <div class="option-card-divider"></div>
                        <span class="option-btn">Seleccionar →</span>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ url('registro/bolsa-trabajo') }}" class="registro-option-card text-decoration-none text-reset">
                        <div>
                            <div class="option-icon">💼</div>
                            <h3>Bolsa de Trabajo</h3>
                            <p>Publica ofertas laborales para impulsar empleo local y facilitar el contacto con talento.</p>
                        </div>
                        <div class="option-card-divider"></div>
                        <span class="option-btn">Seleccionar →</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection