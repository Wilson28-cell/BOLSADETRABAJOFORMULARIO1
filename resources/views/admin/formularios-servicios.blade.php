@extends('admin.layout')

@section('content')

@php
    $rol = $rol ?? session('usuario')->rol ?? null;
@endphp

<h2 class="fw-bold mb-4">Formularios de Servicios</h2>

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
                        <th width="200">Acciones</th>
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
                            <span class="badge bg-warning text-dark px-3 py-2">{{ $servicio->estado_servicio ?? 'Pendiente' }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ url('admin/ver-servicio/'.$servicio->id_servicio) }}" class="btn btn-primary btn-sm">Ver</a>
                                <a href="{{ url('admin/ver-servicio/'.$servicio->id_servicio) }}" class="btn btn-success btn-sm">Ver / Aprobar</a>
                                @if($rol !== 'Analista')
                                    <form action="{{ url('admin/rechazar-servicio/'.$servicio->id_servicio) }}" method="POST" class="confirm-password-action reject-form" data-confirm-message="¿Deseas rechazar este servicio?" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="motivo" value="">
                                        <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">No existen formularios de servicios registrados.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
