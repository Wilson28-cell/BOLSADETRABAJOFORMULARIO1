@extends('admin.layout')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow rounded-4">

        <div class="card-body p-5">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold mb-1">

                        {{ $producto->nombre_producto }}

                    </h2>

                    <span class="badge bg-success text-white px-3 py-2">

                        Aprobado

                    </span>

                </div>

                @php
                    $rol = session('usuario')->rol ?? null;
                @endphp
                <div class="d-flex gap-2">

                    <a
                        href="{{ url('admin/productos') }}"
                        class="btn btn-outline-secondary"
                    >

                        Volver

                    </a>

                    @if($rol !== 'Analista')
                        <form action="{{ url('admin/eliminar-producto/'.$producto->id_aprobado) }}" method="POST" class="confirm-password-action">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar Publicación</button>
                        </form>
                    @endif

                </div>

            </div>


            <div class="row g-5">

                <div class="col-md-5">

                    <div class="border rounded-4 p-3 bg-light">

                        <img
                            src="{{ asset($producto->imagen_producto) }}"
                            class="img-fluid rounded-4"
                            style="width:100%; height:350px; object-fit:cover;"
                        >

                    </div>

                </div>


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

                            <strong>Categoría</strong>
                            <br>
                            {{ $producto->categoria }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Ubicación</strong>
                            <br>
                            {{ $producto->ubicacion_ciudad }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Teléfono</strong>
                            <br>
                            {{ $producto->telefono_contacto }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Correo</strong>
                            <br>
                            {{ $producto->correo_contacto }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Redes Sociales</strong>
                            <br>
                            {{ $producto->redes_sociales }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Dirección Atención</strong>
                            <br>
                            {{ $producto->direccion_atencion }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Fecha Inicio</strong>
                            <br>
                            {{ $producto->fecha_inicio }}

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Fecha Fin</strong>
                            <br>
                            {{ $producto->fecha_fin }}

                        </div>

                    </div>

                    <hr>

                    <div class="mb-4">

                        <strong>Documento Validación</strong>
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

        </div>

    </div>

</div>


@endsection
