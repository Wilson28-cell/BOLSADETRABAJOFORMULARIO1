@extends('admin.layout')

@section('content')

<style>
    .admin-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.15);
    }

    .admin-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 900;
    }

    .filter-card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .filter-card .card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .filter-card .card-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .filter-card .card-body {
        padding: 25px;
    }

    .alert-success {
        background: #d4edda;
        border: 2px solid #28a745;
        color: #155724;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background: #f8d7da;
        border: 2px solid #dc3545;
        color: #721c24;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        font-weight: 600;
        border-radius: 10px;
    }

    .btn-primary-custom:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
    }

    .table-card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table {
        margin: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .table thead th {
        font-weight: 700;
        border: none;
        padding: 15px;
        vertical-align: middle;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: #f9f9f9;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
    }

    .badge-estado {
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-publicado {
        background: #d4edda;
        color: #155724;
    }

    .badge-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .badge-rechazado {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-desactivado {
        background: #e2e3e5;
        color: #383d41;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-sm-custom {
        padding: 8px 15px;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-ver {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-ver:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-desactivar {
        background: #ffeaa7;
        color: #d63031;
    }

    .btn-desactivar:hover {
        background: #fdcb6e;
        color: #d63031;
    }

    .btn-reactivar {
        background: #74b9ff;
        color: white;
    }

    .btn-reactivar:hover {
        background: #0984e3;
        color: white;
    }

    .btn-borrar {
        background: #e74c3c;
        color: white;
    }

    .btn-borrar:hover {
        background: #c0392b;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .filter-card .card-body {
            padding: 15px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-sm-custom {
            width: 100%;
        }

        .table {
            font-size: 0.85rem;
        }

        .table thead th {
            padding: 10px;
        }

        .table tbody td {
            padding: 10px;
        }
    }
</style>

<div class="admin-header">
    <h2>🛍️ Publicaciones Productos</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✓ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ⚠️ {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- FILTROS -->
<div class="filter-card">
    <div class="card-header">
        <h5>🔍 Filtrar Publicaciones</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('admin/productos') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-600">Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Empresa o producto..." value="{{ request('buscar') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-600">Estado</label>
                <select name="estado" class="form-control">
                    <option value="todos">-- Todos --</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-600">Desde</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-600">Hasta</label>
                <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary-custom w-100">
                    🔍 Filtrar
                </button>
            </div>

            <div class="col-md-12">
                <a href="{{ url('admin/productos') }}" class="btn btn-outline-secondary btn-sm">
                    Limpiar Filtros
                </a>
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE PRODUCTOS -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($productos->isEmpty())
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">📦</div>
                                <p>No hay publicaciones que coincidan con los filtros</p>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($productos as $producto)
                    <tr>
                        <td>
                            <span class="fw-bold" style="color: #f5576c;">{{ $producto->nombre_empresa ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $producto->nombre_producto }}</td>
                        <td>
                            <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 15px; font-size: 0.85rem;">
                                {{ $producto->categoria }}
                            </span>
                        </td>
                        <td>📍 {{ $producto->ubicacion_ciudad }}</td>
                        <td>
                            @php
                                $fechaFin = isset($producto->fecha_fin) ? \Carbon\Carbon::parse($producto->fecha_fin) : null;
                                $ahora = \Carbon\Carbon::now();
                                if ($fechaFin && $ahora->greaterThan($fechaFin)) {
                                    echo '<span style="color: #e74c3c; font-weight: 600;">❌ Vencido</span>';
                                } else {
                                    echo isset($producto->fecha_fin) ? \Carbon\Carbon::parse($producto->fecha_fin)->format('d/m/Y') : 'N/A';
                                }
                            @endphp
                        </td>
                        <td>
                            @php
                                $estado = strtolower($producto->estado ?? '');
                                $badgeClass = match($estado) {
                                    'pendiente' => 'badge-pendiente',
                                    'rechazado' => 'badge-rechazado',
                                    'desactivado' => 'badge-desactivado',
                                    default => 'badge-publicado',
                                };
                                $estadoText = match($estado) {
                                    'pendiente' => '⏳ Pendiente',
                                    'rechazado' => '❌ Rechazado',
                                    'desactivado' => '🚫 Desactivado',
                                    default => '✓ Publicado',
                                };
                            @endphp
                            <span class="badge-estado {{ $badgeClass }}">{{ $estadoText }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ url('admin/ver-producto-aprobado/'.$producto->id_aprobado) }}" class="btn-sm-custom btn-ver">
                                    👁️ Ver
                                </a>
                                @if(strtolower($producto->estado ?? '') !== 'desactivado')
                                    <form action="{{ url('admin/eliminar-producto/'.$producto->id_aprobado) }}" method="POST" class="confirm-password-action" data-confirm-message="Desactivar esta publicación?" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm-custom btn-desactivar">🔒 Desactivar</button>
                                    </form>
                                @else
                                    <form action="{{ url('admin/productos/reactivar/'.$producto->id_aprobado) }}" method="POST" class="confirm-password-action" data-confirm-message="Reactivar esta publicación?" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-sm-custom btn-reactivar">🔓 Reactivar</button>
                                    </form>
                                @endif
                                <form action="{{ url('admin/borrar-producto/'.$producto->id_aprobado) }}" method="POST" class="confirm-password-action" data-confirm-message="Eliminar permanentemente esta publicación?" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm-custom btn-borrar">🗑️ Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection