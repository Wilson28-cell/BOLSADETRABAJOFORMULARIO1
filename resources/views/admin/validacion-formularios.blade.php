@extends('admin.layout')

@section('content')

    @php
        $rol = session('usuario')->rol ?? null;
    @endphp

    <h1 class="fw-bold mb-4">
        Validación de Formularios de Bolsa de Trabajo
    </h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
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
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
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
                                <span class="badge bg-warning text-dark">{{ $empresa->estado }}</span>
                            </td>
                            <td>
                                <a href="{{ url('admin/ver/'.$empresa->id_empresa) }}" class="btn btn-primary btn-sm">Ver</a>
                                @if($rol !== 'Analista')
                                    <form action="{{ url('admin/aprobar/'.$empresa->id_empresa) }}" method="POST" style="display:inline-block;" class="confirm-password-action" data-confirm-message="Aprobar esta solicitud?">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Aprobar</button>
                                    </form>
                                    <form action="{{ url('admin/rechazar/'.$empresa->id_empresa) }}" method="POST" style="display:inline-block;" class="confirm-password-action" data-confirm-message="Rechazar esta solicitud?">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Rechazar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="modal{{ $empresa->id_empresa }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4">
                                    <div class="modal-header">
                                        <div>
                                            <h4 class="fw-bold mb-1">{{ $empresa->nombre_empresa ?? 'N/A' }}</h4>
                                            <small class="text-muted">RUC: {{ $empresa->ruc ?? 'N/A' }}</small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 bg-light rounded-3">
                                                    <small class="text-muted">Responsable</small><br>
                                                    <strong>{{ $empresa->responsable_representante }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 bg-light rounded-3">
                                                    <small class="text-muted">Correo</small><br>
                                                    <strong>{{ $empresa->correo_electronico }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 bg-light rounded-3">
                                                    <small class="text-muted">Teléfono</small><br>
                                                    <strong>{{ $empresa->telefono }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 bg-light rounded-3">
                                                    <small class="text-muted">Dirección</small><br>
                                                    <strong>{{ $empresa->direccion }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="fw-bold mb-3">Publicación Laboral</h5>
                                        <div class="border rounded-4 p-4 bg-light">
                                            <h5 class="fw-bold">{{ $empresa->titulo_puesto }}</h5>
                                            <p>{{ $empresa->descripcion_puesto }}</p>
                                            <div>
                                                <strong>Requisitos:</strong>
                                                @if(!empty($empresa->requisitos))
                                                    @php $lines = preg_split('/\r\n|\r|\n/', trim($empresa->requisitos)); @endphp
                                                    <ul class="mb-0">
                                                        @foreach($lines as $line)
                                                            @if(trim($line) !== '')
                                                                <li>{{ $line }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted mb-0">No especificado.</p>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-3 flex-wrap mt-3">
                                                <span class="badge bg-primary">{{ $empresa->modalidad }}</span>
                                                <span class="badge bg-success">{{ $empresa->categoria }}</span>
                                                <span class="badge bg-dark">{{ $empresa->ubicacion }}</span>
                                            </div>
                                            <p class="mt-3"><strong>Salario:</strong> S/ {{ $empresa->salario ?? 0 }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if($rol !== 'Analista')
                                            <form method="POST" action="{{ url('admin/aprobar/'.$empresa->id_empresa) }}" class="confirm-password-action" data-confirm-message="Aprobar esta solicitud?">
                                                @csrf
                                                <button class="btn btn-success">Aprobar</button>
                                            </form>
                                            <form method="POST" action="{{ url('admin/rechazar/'.$empresa->id_empresa) }}" class="confirm-password-action" data-confirm-message="Rechazar esta solicitud?">
                                                @csrf
                                                <button class="btn btn-danger">Rechazar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
