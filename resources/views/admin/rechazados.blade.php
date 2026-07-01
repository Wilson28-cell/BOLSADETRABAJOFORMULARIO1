@extends('admin.layout')

@section('content')

    <h1 class="fw-bold mb-4">Rechazados Bolsa de Trabajo</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <table class="table align-middle table-sm">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>RUC</th>
                        <th>Teléfono</th>
                        <th>Oferta</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Documento</th>
                        <th>Fecha Rechazo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empresas as $empresa)
                        <tr>
                            <td>
                                <strong>{{ $empresa->nombre_empresa ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $empresa->correo_electronico ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $empresa->ruc ?? 'N/A' }}</td>
                            <td>{{ $empresa->telefono ?? 'N/A' }}</td>
                            <td>{{ $empresa->titulo_puesto }}</td>
                            <td>{{ $empresa->categoria ?? 'N/A' }}</td>
                            <td>{{ $empresa->ubicacion ?? 'N/A' }}</td>
                            <td>
                                @if(!empty($empresa->documento_validacion))
                                    <a href="{{ asset($empresa->documento_validacion) }}" target="_blank" class="btn btn-outline-primary btn-sm">Ver</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $fecha = $empresa->fecha_rechazo ?? null;
                                @endphp
                                {{ $fecha ? \Carbon\Carbon::parse($fecha)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>
                                <form action="{{ url('admin/restaurar/' . ($empresa->id_rechazado ?? $empresa->id_empresa)) }}" method="POST" class="confirm-password-action" data-confirm-message="Restaurar este registro?" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay ofertas rechazadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
