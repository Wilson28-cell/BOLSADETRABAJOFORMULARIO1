@extends('layouts.app')

@section('content')

<div class="publicidad-page">
    <div class="hero-banner compact-hero">
        <div class="hero-content">
            <span class="badge-custom">Servicios</span>
            <h1>Proveedores profesionales cerca de ti</h1>
            <p>Encuentra servicios confiables con la presentación visual institucional de Porvenir Produce.</p>
        </div>
    </div>

<div class="section-heading text-center">
    <span class="section-label">Servicios disponibles</span>
</div>

@if(isset($servicios) && !$servicios->isEmpty())
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4">
        @foreach($servicios as $servicio)
            <div class="col">
                <div class="card card-servicio">
                    @php
                        $imgExt = !empty($servicio->imagen_servicio) ? strtolower(pathinfo($servicio->imagen_servicio, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($servicio->imagen_servicio) && in_array($imgExt, $imgAllowed))
                        <div class="card-header-img">
                            <img src="{{ asset($servicio->imagen_servicio) }}" alt="{{ $servicio->nombre_servicio }}">
                        </div>
                    @else
                        <div class="card-header-color">🔧</div>
                    @endif

                    <div class="card-body">
                        <h5 class="titulo-servicio">{{ $servicio->nombre_servicio }}</h5>
                        <p class="empresa-nombre">{{ $servicio->nombre_empresa ?? 'Empresa' }}</p>

                        <div class="badges-container">
                            <span class="badge-custom badge-categoria">{{ $servicio->categoria }}</span>
                        </div>

                        <p class="descripcion-servicio">
                            {{ Str::limit($servicio->descripcion, 120) }}
                        </p>

                        <div class="info-footer">
                            <span class="contacto-badge">📧 {{ Str::limit($servicio->correo_contacto ?? 'No disponible', 30) }}</span>
                            <span class="contacto-badge">☎️ {{ $servicio->telefono_contacto ?? 'No disponible' }}</span>
                        </div>

                        @if(!empty($servicio->requisitos))
                            <div class="requisitos-note">
                                <strong>ℹ️ Requisitos:</strong> {{ Str::limit($servicio->requisitos, 80) }}
                            </div>
                        @endif

                        <a href="{{ url('detalle-servicio/'.$servicio->id_publico_servicio) }}" class="btn-detalle">Ver Detalles →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">🌟</div>
        <h3>No hay servicios disponibles</h3>
        <p>En este momento estamos preparando el catálogo de servicios. Pronto tendremos disponibles los mejores proveedores de servicios en tu área.</p>
    </div>
@endif
</div>

@endsection
