@extends('admin.layout')

@section('content')

<style>
    .page-header {
        margin-bottom: 1.75rem;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212529;
    }

    .dashboard-subtitle {
        color: #6c757d;
    }

    .dashboard-panel {
        padding: 1rem 0;
    }

    .filter-card,
    .info-card,
    .chart-card,
    .indicator-card {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.04);
    }

    .filter-card {
        padding: 1.5rem;
    }

    .filter-card-title {
        font-weight: 600;
        color: #212529;
    }

    .form-control-solid,
    .form-select-solid {
        background: #ffffff;
        border: 1px solid #ced4da;
        color: #495057;
        border-radius: 0.75rem;
        min-height: 46px;
    }

    .form-control-solid:focus,
    .form-select-solid:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.1rem rgba(13, 110, 253, 0.15);
        outline: none;
        background: #ffffff;
    }

    .btn-outline-gray {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #ced4da;
    }

    .btn-outline-gray:hover {
        background: #e9ecef;
    }

    .badge-surface {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .badge-primary-alt {
        background: #0d6efd;
        color: #fff;
    }

    .badge-success-alt {
        background: #198754;
        color: #fff;
    }

    .indicator-card {
        padding: 1.35rem;
        min-height: 150px;
        border-radius: 1rem;
        transition: box-shadow 0.2s ease;
    }

    .indicator-card:hover {
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
    }

    .indicator-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.75rem;
        text-transform: none;
        letter-spacing: 0;
        font-size: 0.92rem;
    }

    .indicator-value {
        font-size: 2.1rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 0.25rem;
    }

    .indicator-meta {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .indicator-icon {
        width: 2.5rem;
        height: 2.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.85rem;
        background: #e9ecef;
        color: #0d6efd;
        font-size: 1.15rem;
    }

    .chart-card {
        padding: 1.5rem;
        min-height: 360px;
    }

    .chart-header {
        margin-bottom: 1rem;
    }

    .chart-header h3 {
        margin: 0;
        color: #212529;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .chart-header p {
        margin: 0.45rem 0 0;
        color: #6c757d;
        font-size: 0.95rem;
    }

    .dashboard-badges .badge {
        font-size: 0.85rem;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
    }

    @media (max-width: 991px) {
        .chart-card,
        .info-card,
        .filter-card {
            min-height: auto;
        }
    }

    @media (max-width: 767px) {
        .dashboard-panel,
        .filter-card {
            padding: 1rem;
        }

        .indicator-card {
            min-height: auto;
        }
    }
</style>

<div class="page-header mb-4">
    <h2>Dashboard Bolsa de Trabajo</h2>
    <p class="text-muted">Visión ejecutiva y análisis de las ofertas laborales con métricas, filtros y gráficos interactivos.</p>
</div>

<div id="dashboardContent" data-dashboard-url="{{ url('admin/indicadores-bolsa') }}">
    @include('admin.partials.dashboard.bolsa-content')
</div>

@include('admin.partials.dashboard.dashboard-scripts')

@endsection
