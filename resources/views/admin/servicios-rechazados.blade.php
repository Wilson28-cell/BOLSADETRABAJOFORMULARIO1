@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">Servicios Rechazados</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
                        <th class="ps-4">Empresa</th>
                        <th>Servicio</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Fecha Rechazo</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $servicio->nombre_empresa ?? 'N/A' }}</td>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->categoria }}</td>
                        <td>{{ $servicio->ubicacion_ciudad }}</td>
                        <td>
                            <span class="badge bg-danger text-white px-3 py-2">{{ $servicio->estado ?? 'Rechazado' }}</span>
                        </td>
                        <td>{{ $servicio->fecha_registro }}</td>
                        <td>{{ $servicio->motivo_rechazo ?? '-' }}</td>
                        <td>
                            <a href="{{ url('admin/ver-servicio-rechazado/' . $servicio->id_rechazado) }}" class="btn btn-outline-primary btn-sm mb-1">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">No hay servicios rechazados registrados.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
