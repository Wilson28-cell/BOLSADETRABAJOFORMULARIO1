@extends('admin.layout')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Productos Desactivados</h1>
    <a href="{{ url('admin/productos') }}" class="btn btn-secondary">
        ← Volver a Productos
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($productos->isEmpty())
    <div class="alert alert-info">No hay productos desactivados.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Empresa</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td class="fw-bold">{{ $producto->nombre_empresa ?? 'N/A' }}</td>
                        <td>{{ $producto->nombre_producto }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $producto->categoria }}</span>
                        </td>
                        <td>{{ $producto->ubicacion_ciudad }}</td>
                        <td>{{ isset($producto->fecha_fin) ? \Carbon\Carbon::parse($producto->fecha_fin)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ url('admin/ver-producto-aprobado/'.$producto->id_aprobado) }}" class="btn btn-sm btn-primary">
                                    Ver Detalle
                                </a>
                                <form action="{{ url('admin/productos/reactivar/'.$producto->id_aprobado) }}" method="POST" class="confirm-password-action" data-confirm-message="Reactivar esta publicación? Volverá a ser visible en el portal público.">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Reactivar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
