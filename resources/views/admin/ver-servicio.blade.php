@extends('admin.layout')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow rounded-4">

        <div class="card-body p-5">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold mb-1">{{ $servicio->nombre_servicio }}</h2>

                    <span class="badge bg-warning text-dark px-3 py-2">{{ $servicio->estado_servicio ?? 'Pendiente de Validación' }}</span>

                </div>

                <a href="{{ url('admin/formularios-servicios') }}" class="btn btn-outline-secondary">Volver</a>

            </div>

            <div class="row g-5">

                <div class="col-md-5">

                    <div class="border rounded-4 p-3 bg-light">

                        @if($servicio->imagen_servicio)
                            <img src="{{ asset($servicio->imagen_servicio) }}" class="img-fluid rounded-4" style="width:100%; height:350px; object-fit:cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-4" style="height:350px;">
                                <span class="text-muted">Sin imagen de servicio</span>
                            </div>
                        @endif

                    </div>

                </div>

                <div class="col-md-7">

                    <h4 class="fw-bold text-primary mb-3">{{ $servicio->nombre_empresa ?? 'N/A' }}</h4>

                    <p class="text-muted">{{ $servicio->descripcion }}</p>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Categoría</strong><br>{{ $servicio->categoria }}</div>
                        <div class="col-md-6 mb-3"><strong>Ubicación</strong><br>{{ $servicio->ubicacion_ciudad }}</div>
                        <div class="col-md-6 mb-3"><strong>Teléfono</strong><br>{{ $servicio->telefono_contacto }}</div>
                        <div class="col-md-6 mb-3"><strong>Correo</strong><br>{{ $servicio->correo_contacto }}</div>
                        <div class="col-md-6 mb-3"><strong>Redes Sociales</strong><br>{{ $servicio->redes_sociales }}</div>
                        <div class="col-md-6 mb-3"><strong>Dirección Atención</strong><br>{{ $servicio->direccion_atencion }}</div>
                        <div class="col-md-6 mb-3"><strong>Horario de Atención</strong><br>{{ $servicio->horario_atencion }}</div>
                        <div class="col-md-6 mb-3"><strong>Fecha Inicio</strong><br>{{ $servicio->fecha_inicio }}</div>
                        <div class="col-md-6 mb-3"><strong>Fecha Fin</strong><br>{{ $servicio->fecha_fin }}</div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <strong>Documento de Validación</strong><br><br>
                        @if($servicio->documento_validacion)
                            <a href="{{ asset($servicio->documento_validacion) }}" target="_blank" class="btn btn-outline-primary btn-sm">Ver Documento</a>
                        @else
                            <span class="text-muted">No hay documento cargado.</span>
                        @endif
                    </div>

                </div>

            </div>

            <hr class="my-5">

            @php
                $rol = session('usuario')->rol ?? null;
            @endphp
            <div class="row">
                @if($rol !== 'Analista')
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-body">
                                <h5 class="fw-bold text-success mb-3">Aprobar Servicio</h5>
                                <form action="{{ url('admin/aprobar-servicio/'.$servicio->id_servicio) }}" method="POST" enctype="multipart/form-data" class="confirm-password-action" data-confirm-message="Aprobar este servicio?">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Documento de Validación (PDF)</label>
                                        <input type="file" name="documento_validacion_subgerencia" class="form-control" required accept="application/pdf">
                                    </div>
                                    <button class="btn btn-success w-100">Aprobar Servicio</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-danger mb-3">
                            <div class="card-body">
                                <h5 class="fw-bold text-danger mb-3">Rechazar Servicio</h5>
                                <form action="{{ url('admin/rechazar-servicio/'.$servicio->id_servicio) }}" method="POST" class="confirm-password-action" data-confirm-message="Rechazar este servicio?">
                                    @csrf
                                    <button class="btn btn-danger w-100">Rechazar Servicio</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

@endsection
