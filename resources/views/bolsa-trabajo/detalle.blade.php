@extends('layouts.app')

@section('title', 'Detalle de Oferta')

@section('content')
<div class="container py-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $oferta->titulo_puesto ?? 'Detalle de la oferta' }}</h2>
            <p class="text-muted mb-0">{{ $oferta->nombre_empresa ?? 'Empresa no disponible' }}</p>
        </div>
        <a href="{{ url('publicidad/bolsa-trabajo') }}" class="btn btn-outline-secondary">Volver a la bolsa</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary"><i class="bi bi-building me-1"></i> {{ $oferta->nombre_empresa ?? 'N/A' }}</span>
                        <span class="badge bg-secondary"><i class="bi bi-tags me-1"></i> {{ $oferta->categoria ?? 'No especificado' }}</span>
                        <span class="badge bg-info text-dark"><i class="bi bi-geo-alt me-1"></i> {{ $oferta->ubicacion ?? 'No especificado' }}</span>
                        <span class="badge bg-success"><i class="bi bi-briefcase me-1"></i> {{ $oferta->modalidad ?? 'No especificado' }}</span>
                    </div>

                    @php
                        $imgExt = !empty($oferta->imagen_trabajo) ? strtolower(pathinfo($oferta->imagen_trabajo, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($oferta->imagen_trabajo) && in_array($imgExt, $imgAllowed))
                        <div class="mb-4 rounded-4 shadow-sm" style="background:#f8f9fa; max-height:340px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset($oferta->imagen_trabajo) }}" class="img-fluid" alt="Imagen de la oferta" style="max-height:340px; width:auto; max-width:100%; object-fit:contain;">
                        </div>
                    @elseif(!empty($oferta->imagen_trabajo) && $imgExt === 'pdf')
                        <div class="mb-4">
                            <a href="{{ asset($oferta->imagen_trabajo) }}" target="_blank" class="btn btn-outline-primary mb-3">📄 Ver documento de la oferta</a>
                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
                                <iframe src="{{ asset($oferta->imagen_trabajo) }}" frameborder="0"></iframe>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="fw-semibold mb-3">Descripción del puesto</h4>
                        <p class="text-secondary">{{ $oferta->descripcion_puesto ?? 'No hay descripción disponible.' }}</p>
                    </div>

                    <div class="border-top pt-4">
                        <h5 class="fw-semibold mb-3">Requisitos</h5>
                        @if(!empty($oferta->requisitos))
                            @php $lines = preg_split('/\r\n|\r|\n/', trim($oferta->requisitos)); @endphp
                            <ul class="mb-0">
                                @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No especificado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-uppercase text-muted small mb-3">Oferta</p>
                        <div class="mb-4">
                            <p class="mb-1 text-muted"><i class="bi bi-currency-dollar me-1"></i> Salario</p>
                            <h4 class="fw-bold mb-0">{{ !empty($oferta->salario) ? 'S/ ' . number_format($oferta->salario, 2) : 'No especificado' }}</h4>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-calendar-event me-1"></i> Fecha inicio</p>
                            <p class="fw-semibold mb-0">{{ !empty($oferta->fecha_inicio_convocatoria) ? \Carbon\Carbon::parse($oferta->fecha_inicio_convocatoria)->format('d/m/Y') : 'No especificado' }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-calendar-x me-1"></i> Fecha límite</p>
                            <p class="fw-semibold mb-0">{{ !empty($oferta->fecha_limite_postulacion) ? \Carbon\Carbon::parse($oferta->fecha_limite_postulacion)->format('d/m/Y') : 'No especificado' }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-person-badge me-1"></i> Empresa</p>
                            <p class="fw-semibold mb-0">{{ $oferta->nombre_empresa ?? 'No especificado' }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-geo-alt me-1"></i> Ubicación</p>
                            <p class="fw-semibold mb-0">{{ $oferta->ubicacion ?? 'No especificado' }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 text-muted"><i class="bi bi-briefcase me-1"></i> Modalidad</p>
                            <p class="fw-semibold mb-0">{{ $oferta->modalidad ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    <a href="{{ url('postular/'.$oferta->id_aprobado) }}" class="btn btn-primary btn-lg rounded-4 w-100 mt-3">Postular ahora</a>
                </div>
            </div>
        </div>
    </div>

</div>
