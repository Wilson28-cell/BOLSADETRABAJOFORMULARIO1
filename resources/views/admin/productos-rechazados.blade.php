@extends('admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Productos Rechazados</h1>
    <a href="{{ url('admin/formularios-productos') }}" class="btn btn-secondary">
        ← Volver a Pendientes
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($productos->isEmpty())
    <div class="alert alert-info">No hay productos rechazados.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Empresa</th>
                    <th>RUC</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Motivo</th>
                    <th>Fecha Rechazo</th>
                    <th>Documento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td class="fw-bold">{{ $producto->nombre_empresa }}</td>
                        <td>{{ $producto->ruc }}</td>
                        <td>{{ $producto->nombre_producto }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $producto->categoria }}</span>
                        </td>
                        <td>{{ $producto->ubicacion_ciudad }}</td>
                        <td style="max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $producto->motivo_rechazo ?? ($producto->motivo ?? 'N/A') }}</td>
                        <td>{{ isset($producto->fecha_rechazo) ? \Carbon\Carbon::parse($producto->fecha_rechazo)->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>
                            @if(!empty($producto->documento_validacion))
                                <a href="{{ asset($producto->documento_validacion) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    Ver
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <!-- Restaurar button hidden by request -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
