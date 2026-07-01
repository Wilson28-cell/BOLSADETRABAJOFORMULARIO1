@extends('layouts.app')

@section('title', 'Detalle de Producto')

@section('content')
<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $producto->nombre_producto ?? 'Detalle del producto' }}</h2>
            <p class="text-muted mb-0">{{ $producto->nombre_empresa ?? 'Empresa no disponible' }}</p>
        </div>
        <a href="{{ url('publicidad/productos') }}" class="btn btn-outline-secondary">Volver a productos</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary"><i class="bi bi-building me-1"></i> {{ $producto->nombre_empresa ?? 'N/A' }}</span>
                        <span class="badge bg-secondary"><i class="bi bi-tags me-1"></i> {{ $producto->categoria ?? 'No especificado' }}</span>
                        <span class="badge bg-info text-dark"><i class="bi bi-geo-alt me-1"></i> {{ $producto->ubicacion_ciudad ?? 'No especificado' }}</span>
                    </div>

                    @php
                        $imgExt = !empty($producto->imagen_producto) ? strtolower(pathinfo($producto->imagen_producto, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($producto->imagen_producto) && in_array($imgExt, $imgAllowed))
                        <div class="mb-4 rounded-4 shadow-sm" style="background:#f8f9fa; max-height:340px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset($producto->imagen_producto) }}" class="img-fluid" alt="Imagen del producto" style="max-height:340px; width:auto; max-width:100%; object-fit:contain;">
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="fw-semibold mb-3">Descripción del producto</h4>
                        <p class="text-secondary">{{ $producto->descripcion ?? 'No hay descripción disponible.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted small mb-3">Información de contacto</p>
                        
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-person-badge me-1"></i> Empresa</p>
                            <p class="fw-semibold mb-0">{{ $producto->nombre_empresa ?? 'No especificado' }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-envelope me-1"></i> Correo</p>
                            <p class="fw-semibold mb-0">
                                <a href="mailto:{{ $producto->correo_contacto }}" class="text-decoration-none">
                                    {{ $producto->correo_contacto ?? 'No disponible' }}
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-telephone me-1"></i> Teléfono</p>
                            <p class="fw-semibold mb-0">
                                <a href="tel:{{ $producto->telefono_contacto }}" class="text-decoration-none">
                                    {{ $producto->telefono_contacto ?? 'No disponible' }}
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-geo-alt me-1"></i> Ubicación</p>
                            <p class="fw-semibold mb-0">{{ $producto->ubicacion_ciudad ?? 'No especificado' }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-building me-1"></i> Dirección atención</p>
                            <p class="fw-semibold mb-0">{{ $producto->direccion_atencion ?? 'No especificado' }}</p>
                        </div>

                        @if(!empty($producto->redes_sociales))
                            <div class="mb-4 border-top pt-3">
                                <p class="text-uppercase text-muted small mb-3">Redes sociales</p>
                                @include('publicidad.partials.social-links', ['texto' => $producto->redes_sociales])
                            </div>
                        @endif
                    </div>

                    <a href="mailto:{{ $producto->correo_contacto }}" class="btn btn-primary btn-lg rounded-4 w-100 mt-3">Contactar ahora</a>
                </div>
            </div>
        </div>
    </div>

</div>
