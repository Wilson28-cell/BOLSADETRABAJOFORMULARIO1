@extends('admin.layout')

@section('content')

<div class="page-header mb-4">
    <h2>Dashboard de Indicadores - Servicios</h2>
    <p class="text-muted">Métricas ejecutivas y análisis visual de la publicidad de servicios con filtros dinámicos.</p>
</div>

<div id="dashboardContent" data-dashboard-url="{{ url('admin/indicadores-servicios') }}">
    @include('admin.partials.dashboard.servicios-content')
</div>

@include('admin.partials.dashboard.dashboard-scripts')

@endsection
