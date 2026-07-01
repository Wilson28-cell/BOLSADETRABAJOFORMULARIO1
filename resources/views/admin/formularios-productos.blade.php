@extends('admin.layout')

@section('content')

@php
    $rol = session('usuario')->rol ?? null;
@endphp

<h2 class="fw-bold mb-4">

    Formularios de Productos

</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card border-0 shadow rounded-4">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-4">

                            Empresa

                        </th>

                        <th>

                            Producto

                        </th>

                        <th>

                            Categoría

                        </th>

                        <th>

                            Ubicación

                        </th>

                        <th>

                            Estado

                        </th>

                        <th width="260">

                            Acciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($productos as $producto)

                    <tr>

                        <!-- EMPRESA -->

                        <td class="ps-4 fw-semibold">

                            {{ $producto->nombre_empresa ?? 'N/A' }}

                        </td>


                        <!-- PRODUCTO -->

                        <td>

                            {{ $producto->nombre_producto }}

                        </td>


                        <!-- CATEGORIA -->

                        <td>

                            {{ $producto->categoria }}

                        </td>


                        <!-- UBICACION -->

                        <td>

                            {{ $producto->ubicacion_ciudad }}

                        </td>


                        <!-- ESTADO -->

                        <td>

                            <span class="badge bg-warning text-dark px-3 py-2">

                                Pendiente

                            </span>

                        </td>


                        <!-- ACCIONES -->

                        <td>

                            <div class="d-flex gap-2 flex-wrap">

                                <!-- VER -->

                                <a
                                    href="{{ url('admin/ver-producto/'.$producto->id_empresa_producto) }}"
                                    class="btn btn-primary btn-sm"
                                >

                                    Ver

                                </a>


                                <!-- APROBAR -->

                                <a
                                    href="{{ url('admin/ver-producto/'.$producto->id_empresa_producto) }}"
                                    class="btn btn-success btn-sm"
                                >

                                    Ver / Aprobar

                                </a>

                                @if($rol !== 'Analista')
                                    <!-- RECHAZAR -->

                                        <form
                                            action="{{ url('admin/rechazar-producto/'.$producto->id_empresa_producto) }}"
                                            method="POST"
                                            class="confirm-password-action reject-form"
                                            data-confirm-message="¿Deseas rechazar este producto?"
                                            style="display:inline;"
                                        >

                                            @csrf
                                            <input type="hidden" name="motivo" value="">

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >

                                                Rechazar

                                            </button>

                                        </form>
                                @endif

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

                            <div class="text-muted">

                                No existen formularios registrados.

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.reject-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var motivo = prompt('Ingrese el motivo del rechazo:');
                if (motivo === null) return; // cancel
                motivo = motivo.trim();
                if (motivo.length === 0) {
                    alert('El motivo no puede estar vacío.');
                    return;
                }
                var input = form.querySelector('input[name="motivo"]');
                if (input) input.value = motivo;
                form.submit();
            });
        });
    });
</script>
@endsection