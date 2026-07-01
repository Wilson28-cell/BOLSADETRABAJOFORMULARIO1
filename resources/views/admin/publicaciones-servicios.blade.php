@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">Publicaciones Servicios</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
    $query = request()->except('estado');
    $baseUrl = url('/admin/publicaciones-servicios');
    $allUrl = $baseUrl . (count($query) ? '?' . http_build_query($query) : '');
    $publishedUrl = $baseUrl . (count($query) ? '?' . http_build_query(array_merge($query, ['estado' => 'Publicado'])) : '?estado=Publicado');
    $disabledUrl = $baseUrl . (count($query) ? '?' . http_build_query(array_merge($query, ['estado' => 'Desactivado'])) : '?estado=Desactivado');
    $currentEstado = $filters['estado'] ?? '';
@endphp

<!-- Quick filter buttons removed: now filtering is handled only via the filter form below -->

<form method="GET" action="{{ url('/admin/publicaciones-servicios') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Buscar empresa o servicio</label>
        <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ $filters['search'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <option value="" {{ (empty($filters['estado'])) ? 'selected' : '' }}>Todos</option>
            <option value="Publicado" {{ (isset($filters['estado']) && $filters['estado'] === 'Publicado') ? 'selected' : '' }}>Publicado</option>
            <option value="Desactivado" {{ (isset($filters['estado']) && $filters['estado'] === 'Desactivado') ? 'selected' : '' }}>Desactivado</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Desde</label>
        <input type="date" name="fecha_inicio" class="form-control" value="{{ $filters['fecha_inicio'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Hasta</label>
        <input type="date" name="fecha_fin" class="form-control" value="{{ $filters['fecha_fin'] ?? '' }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

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
                        <th>Fecha Publicación</th>
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
                            @if($servicio->estado === 'Desactivado')
                                <span class="badge bg-secondary text-white px-3 py-2">Desactivado</span>
                            @else
                                <span class="badge bg-success text-white px-3 py-2">Publicado</span>
                            @endif
                        </td>
                        <td>{{ $servicio->fecha_registro }}</td>
                        <td>
                            <a href="{{ url('admin/ver-publicacion-servicio/' . $servicio->id_publico_servicio) }}" class="btn btn-outline-primary btn-sm mb-1">Ver</a>

                            @if($servicio->estado === 'Desactivado')
                                <form action="{{ url('admin/publicaciones-servicios/reactivar/' . $servicio->id_publico_servicio) }}" method="POST" class="confirm-password-action" data-confirm-message="Reactivar esta publicación?" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm mb-1">Reactivar</button>
                                </form>
                            @else
                                <form action="{{ url('admin/publicaciones-servicios/desactivar/' . $servicio->id_publico_servicio) }}" method="POST" class="confirm-password-action" data-confirm-message="Desactivar esta publicación?" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm mb-1">Desactivar</button>
                                </form>
                            @endif

                            <form action="{{ url('admin/publicaciones-servicios/borrar/' . $servicio->id_publico_servicio) }}" method="POST" class="confirm-password-action" data-confirm-message="Eliminar permanentemente esta publicación?" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">No hay publicaciones de servicios disponibles.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
