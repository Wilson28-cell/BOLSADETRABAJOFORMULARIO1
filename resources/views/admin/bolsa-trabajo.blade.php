@extends('admin.layout')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-0">Bolsa de Trabajo</h2>
        <div class="btn-group mt-3" role="group" aria-label="Filtrar publicaciones">
            <a href="{{ url('admin/bolsa-trabajo') }}" class="btn btn-primary">Publicado</a>
            <a href="{{ url('admin/publicaciones-desactivadas') }}" class="btn btn-outline-secondary">Lista eliminados</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<style>
    .admin-bolsa-card .card-body {
        padding: 0.65rem;
    }
    .admin-bolsa-card .card-title {
        margin-bottom: 0.35rem;
        font-size: 0.95rem;
    }
    .admin-bolsa-card .text-muted,
    .admin-bolsa-card .text-secondary,
    .admin-bolsa-card p {
        margin-bottom: 0.25rem !important;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    .admin-bolsa-card .card-text {
        min-height: 42px;
    }
    .admin-bolsa-card .mb-3 {
        margin-bottom: 0.35rem !important;
    }
    .admin-bolsa-card .btn-sm {
        padding: 0.3rem 0.55rem;
        font-size: 0.78rem;
    }
</style>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">

            @forelse($empresas as $empresa)

            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden admin-bolsa-card">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $empresa->modalidad }}</span>
                        </div>
                        <h5 class="card-title mb-2">{{ $empresa->titulo_puesto }}</h5>
                        <p class="text-muted mb-3">{{ $empresa->nombre_empresa ?? 'N/A' }}</p>
                        <p class="card-text text-secondary mb-3">
                            {{ Str::limit($empresa->descripcion_puesto, 100) }}
                        </p>
                        @if(!empty($empresa->requisitos))
                        <div class="mb-3">
                            <p class="mb-1"><strong>Requisitos:</strong></p>
                            <p class="mb-0 text-truncate" title="{{ $empresa->requisitos }}">{{ Str::limit($empresa->requisitos, 80) }}</p>
                        </div>
                        @endif
                        <div class="mb-3">
                            <p class="mb-1"><strong>Ubicación:</strong></p>
                            <p class="mb-0">{{ $empresa->ubicacion }}</p>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1"><strong>Salario:</strong></p>
                            <p class="mb-0">S/ {{ number_format($empresa->salario ?? 0, 2) }}</p>
                        </div>
                        @if(!empty($empresa->created_at) || !empty($empresa->fecha_limite_postulacion))
                            <div class="mb-3">
                                @if(!empty($empresa->created_at))
                                    <p class="mb-1"><strong>Fecha aprobación:</strong></p>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($empresa->created_at)->format('d/m/Y') }}</p>
                                @endif
                                @if(!empty($empresa->fecha_limite_postulacion))
                                    <p class="mb-1 mt-3"><strong>Fecha límite:</strong></p>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($empresa->fecha_limite_postulacion)->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        @endif
                        @php
                            $imgExt = !empty($empresa->imagen_trabajo) ? strtolower(pathinfo($empresa->imagen_trabajo, PATHINFO_EXTENSION)) : null;
                            $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                        @endphp
                        @if(!empty($empresa->imagen_trabajo) || !empty($empresa->documento_validacion) || !empty($empresa->documento_aprobacion_pdf))
                            <div class="mb-3 d-flex flex-wrap gap-2">
                                @if(!empty($empresa->imagen_trabajo))
                                    @if(in_array($imgExt, $imgAllowed))
                                        <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-success btn-sm">🖼️ Imagen</a>
                                    @elseif($imgExt === 'pdf')
                                        <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-primary btn-sm">📄 PDF</a>
                                    @else
                                        <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-secondary btn-sm">📎 Archivo</a>
                                    @endif
                                @endif
                                @if(!empty($empresa->documento_validacion))
                                    <a href="{{ asset($empresa->documento_validacion) }}" target="_blank" class="btn btn-outline-warning btn-sm">🧾 Doc. empresa</a>
                                @endif
                                @if(!empty($empresa->documento_aprobacion_pdf))
                                    <a href="{{ asset($empresa->documento_aprobacion_pdf) }}" target="_blank" class="btn btn-outline-primary btn-sm">📄 Doc. aprobado</a>
                                @endif
                            </div>
                        @endif
                        <div class="mt-auto d-grid gap-2">
                            <button
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal{{ $empresa->id_aprobado }}"
                            >
                                Ver detalles
                            </button>
                            <form action="{{ url('admin/eliminar-publicacion/'.$empresa->id_aprobado) }}" method="POST" class="confirm-password-action" data-confirm-message="Eliminar esta publicación? Esta acción no se puede deshacer.">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar publicación</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @empty

            <div class="col">
                <div class="alert alert-info mb-0">
                    No hay empresas aprobadas para mostrar.
                </div>
            </div>

            @endforelse

        </div>

    </div>

</div>

@foreach($empresas as $empresa)

<div class="modal fade" id="modal{{ $empresa->id_aprobado }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm">
            <div class="modal-header border-0">
                <div>
                    <h5 class="modal-title">{{ $empresa->titulo_puesto }}</h5>
                    <p class="text-muted mb-0">{{ $empresa->nombre_empresa ?? 'N/A' }} • {{ $empresa->modalidad }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-4 gy-3">
                    <div class="col-xl-7 col-lg-8">
                        <div class="row gx-3 gy-3">
                            <div class="col-12 col-md-6">
                                <div class="bg-light rounded-4 p-3 h-100">
                                    <h6 class="fw-semibold">Descripción</h6>
                                    <p class="mb-0">{{ $empresa->descripcion_puesto }}</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="bg-light rounded-4 p-3 h-100">
                                    <h6 class="fw-semibold">Requisitos</h6>
                                    @if(!empty($empresa->requisitos))
                                        @php $lines = preg_split('/\r\n|\r|\n/', trim($empresa->requisitos)); @endphp
                                        <ul class="mb-0 ps-3">
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
                    <div class="col-xl-5 col-lg-4">
                        <div class="bg-white rounded-4 p-3 h-100 border">
                            <p class="text-uppercase text-secondary small mb-2">Detalles</p>
                            <p class="mb-2"><strong>Modalidad:</strong> {{ $empresa->modalidad }}</p>
                            <p class="mb-2"><strong>Ubicación:</strong> {{ $empresa->ubicacion }}</p>
                            <p class="mb-2"><strong>Categoría:</strong> {{ $empresa->categoria }}</p>
                            <p class="mb-2"><strong>Salario:</strong> S/ {{ number_format($empresa->salario ?? 0, 2) }}</p>
                            @if(!empty($empresa->created_at))
                                <p class="mb-2"><strong>Fecha aprobación:</strong> {{ \Carbon\Carbon::parse($empresa->created_at)->format('d/m/Y') }}</p>
                            @endif
                            @if(!empty($empresa->fecha_limite_postulacion))
                                <p class="mb-2"><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($empresa->fecha_limite_postulacion)->format('d/m/Y') }}</p>
                            @endif
                            @php
                                $imgExt = !empty($empresa->imagen_trabajo) ? strtolower(pathinfo($empresa->imagen_trabajo, PATHINFO_EXTENSION)) : null;
                                $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                            @endphp
                            <div class="mt-3">
                                @if(!empty($empresa->imagen_trabajo) && in_array($imgExt, $imgAllowed))
                                    <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-success btn-sm w-100 mb-2">🖼️ Ver imagen</a>
                                @elseif(!empty($empresa->imagen_trabajo) && $imgExt === 'pdf')
                                    <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 mb-2">📄 Abrir PDF</a>
                                @elseif(!empty($empresa->imagen_trabajo))
                                    <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-outline-secondary btn-sm w-100 mb-2">📎 Abrir archivo</a>
                                @endif
                                @if(!empty($empresa->documento_validacion))
                                    <a href="{{ asset($empresa->documento_validacion) }}" target="_blank" class="btn btn-outline-warning btn-sm w-100 mb-2">🧾 Doc. empresa</a>
                                @endif
                                @if(!empty($empresa->documento_aprobacion_pdf))
                                    <a href="{{ asset($empresa->documento_aprobacion_pdf) }}" target="_blank" class="btn btn-primary btn-sm w-100 mb-2">📄 Doc. aprobado</a>
                                    <form action="{{ url('admin/eliminar-documento/'.$empresa->id_aprobado) }}" method="POST" class="confirm-password-action d-grid" data-confirm-message="Eliminar el documento aprobado? Esta acción no se puede deshacer.">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar documento</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white border rounded-4 p-4">
                            <h6 class="fw-semibold mb-3">Subir documento de validación</h6>
                            <form action="{{ url('admin/subir-documento/'.$empresa->id_aprobado) }}" method="POST" enctype="multipart/form-data" class="confirm-password-action" data-confirm-message="Subir o reemplazar el documento de validación?">
                                @csrf
                                <div class="mb-3">
                                    <input type="file" name="documento_pdf" class="form-control" accept="application/pdf" required>
                                </div>
                                <button class="btn btn-success">Subir / Reemplazar documento</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection