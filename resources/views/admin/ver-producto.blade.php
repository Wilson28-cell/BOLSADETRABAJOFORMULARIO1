@extends('admin.layout')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow rounded-4">

        <div class="card-body p-5">

            <!-- TITULO -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold mb-1">

                        {{ $producto->nombre_producto }}

                    </h2>

                    <span class="badge bg-warning text-dark px-3 py-2">

                        Pendiente de Validación

                    </span>

                </div>

                <a
                    href="{{ url('admin/formularios-productos') }}"
                    class="btn btn-outline-secondary"
                >

                    Volver

                </a>

            </div>


            <div class="row g-5">

                <!-- IMAGEN -->

                <div class="col-md-5">

                    <div class="border rounded-4 p-3 bg-light">

                        <img
                            src="{{ asset($producto->imagen_producto) }}"
                            class="img-fluid rounded-4"
                            style="
                                width:100%;
                                height:350px;
                                object-fit:cover;
                            "
                        >

                    </div>

                </div>


                <!-- INFORMACION -->

                <div class="col-md-7">

                    <h4 class="fw-bold text-primary mb-3">

                        {{ $producto->nombre_empresa ?? 'N/A' }}

                    </h4>

                    <p class="text-muted">

                        {{ $producto->descripcion }}

                    </p>

                    <hr>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <strong>

                                Categoría

                            </strong>

                            <br>

                            {{ $producto->categoria }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Ubicación

                            </strong>

                            <br>

                            {{ $producto->ubicacion_ciudad }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Teléfono

                            </strong>

                            <br>

                            {{ $producto->telefono_contacto }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Correo

                            </strong>

                            <br>

                            {{ $producto->correo_contacto }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Redes Sociales

                            </strong>

                            <br>

                            {{ $producto->redes_sociales }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Dirección Atención

                            </strong>

                            <br>

                            {{ $producto->direccion_atencion }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Fecha Inicio

                            </strong>

                            <br>

                            {{ $producto->fecha_inicio }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>

                                Fecha Fin

                            </strong>

                            <br>

                            {{ $producto->fecha_fin }}

                        </div>

                    </div>


                    <!-- DOCUMENTO EMPRESA -->

                    <hr>

                    <div class="mb-4">

                        <strong>

                            Documento Empresa

                        </strong>

                        <br><br>

                        <a
                            href="{{ asset($producto->documento_validacion) }}"
                            target="_blank"
                            class="btn btn-outline-primary btn-sm"
                        >

                            Ver Documento

                        </a>

                    </div>

                </div>

            </div>


            <!-- ACCIONES -->

            <hr class="my-5">

            @php
                $rol = session('usuario')->rol ?? null;
            @endphp

            @if($rol !== 'Analista')
                <div class="row">

                <!-- APROBAR -->

                <div class="col-md-6">

                    <div class="card border-success">

                        <div class="card-body">

                            <h5 class="fw-bold text-success mb-3">

                                Aprobar Producto

                            </h5>

                            <form
                                action="{{ url('admin/aprobar-producto/'.$producto->id_empresa_producto) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="confirm-password-action"
                                data-confirm-message="Aprobar este producto?"
                            >

                                @csrf

                                <label class="fw-bold mb-2">

                                    Documento Validación PDF

                                </label>

                                <input
                                    type="file"
                                    name="documento_validacion_subgerencia"
                                    class="form-control mb-4"
                                    required
                                >

                                <button
                                    class="btn btn-success w-100"
                                >

                                    Aprobar Producto

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                    <!-- RECHAZAR -->

                    <div class="col-md-6">

                        <div class="card border-danger">

                            <div class="card-body">

                                <h5 class="fw-bold text-danger mb-3">

                                    Rechazar Producto

                                </h5>

                                <form
                                    action="{{ url('admin/rechazar-producto/'.$producto->id_empresa_producto) }}"
                                    method="POST"
                                    class="confirm-password-action reject-form"
                                    data-confirm-message="Rechazar este producto?"
                                >

                                    @csrf
                                    <input type="hidden" name="motivo" value="">

                                    <button
                                        class="btn btn-danger w-100"
                                    >

                                        Rechazar Producto

                                    </button>

                                </form>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
            @endif

        </div>

    </div>

</div>

@endsection