@extends('layouts.app')

@section('title', 'Detalle de Servicio')

@section('content')
<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $servicio->nombre_servicio ?? 'Detalle del servicio' }}</h2>
            <p class="text-muted mb-0">{{ $servicio->nombre_empresa ?? 'Empresa no disponible' }}</p>
        </div>
        <a href="{{ url('publicidad/servicios') }}" class="btn btn-outline-secondary">Volver a servicios</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary"><i class="bi bi-building me-1"></i> {{ $servicio->nombre_empresa ?? 'N/A' }}</span>
                        <span class="badge bg-secondary"><i class="bi bi-tags me-1"></i> {{ $servicio->categoria ?? 'No especificado' }}</span>
                    </div>

                    @php
                        $imgExt = !empty($servicio->imagen_servicio) ? strtolower(pathinfo($servicio->imagen_servicio, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($servicio->imagen_servicio) && in_array($imgExt, $imgAllowed))
                        <div class="mb-4 rounded-4 shadow-sm" style="background:#f8f9fa; max-height:340px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset($servicio->imagen_servicio) }}" class="img-fluid" alt="Imagen del servicio" style="max-height:340px; width:auto; max-width:100%; object-fit:contain;">
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="fw-semibold mb-3">Descripción del servicio</h4>
                        <p class="text-secondary">{{ $servicio->descripcion ?? 'No hay descripción disponible.' }}</p>
                    </div>

                    @if(!empty($servicio->requisitos))
                        <div class="border-top pt-4">
                            <h5 class="fw-semibold mb-3">Requisitos</h5>
                            @php $lines = preg_split('/\r\n|\r|\n/', trim($servicio->requisitos)); @endphp
                            <ul class="mb-0">
                                @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
                            <p class="fw-semibold mb-0">{{ $servicio->nombre_empresa ?? 'No especificado' }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-envelope me-1"></i> Correo</p>
                            <p class="fw-semibold mb-0">
                                <a href="mailto:{{ $servicio->correo_contacto }}" class="text-decoration-none">
                                    {{ $servicio->correo_contacto ?? 'No disponible' }}
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-telephone me-1"></i> Teléfono</p>
                            <p class="fw-semibold mb-0">
                                <a href="tel:{{ $servicio->telefono_contacto }}" class="text-decoration-none">
                                    {{ $servicio->telefono_contacto ?? 'No disponible' }}
                                </a>
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-tags me-1"></i> Categoría</p>
                            <p class="fw-semibold mb-0">{{ $servicio->categoria ?? 'No especificado' }}</p>
                        </div>

                        @if(!empty($servicio->redes_sociales))
                            <div class="mb-4 border-top pt-3">
                                <p class="text-uppercase text-muted small mb-3">Redes sociales</p>
                                @include('publicidad.partials.social-links', ['texto' => $servicio->redes_sociales])
                            </div>
                        @endif
                    </div>

                    <a href="mailto:{{ $servicio->correo_contacto }}" class="btn btn-primary btn-lg rounded-4 w-100 mt-3">Contactar ahora</a>
                </div>
            </div>
        </div>
    </div>

</div>
