@extends('admin.layout')

@section('content')

<div class="page-header mb-4">
    <h2>Dashboard de Indicadores - Productos</h2>
    <p class="text-muted">Métricas ejecutivas y análisis visual de la publicidad de productos con filtros dinámicos.</p>
</div>

<div id="dashboardContent" data-dashboard-url="{{ url('admin/indicadores-productos') }}">
    @include('admin.partials.dashboard.productos-content')
</div>

@include('admin.partials.dashboard.dashboard-scripts')

@endsection
