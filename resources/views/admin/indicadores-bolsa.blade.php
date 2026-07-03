@extends('admin.layout')

@section('content')

<style>
    body {
        background: #f3f6fb;
    }

    .page-header {
        margin-bottom: 1.75rem;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
    }

    .dashboard-subtitle {
        color: #475569;
    }

    .dashboard-panel {
        padding: 1rem 0;
    }

    .filter-card,
    .indicator-card,
    .chart-card,
    .insight-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
    }

    .filter-card {
        padding: 1.75rem;
    }

    .filter-card-title {
        font-weight: 700;
        color: #0f172a;
    }

    .filter-card p {
        color: #64748b;
    }

    .form-control-solid,
    .form-select-solid {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        border-radius: 0.9rem;
        min-height: 48px;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .form-control-solid:focus,
    .form-select-solid:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.12rem rgba(37, 99, 235, 0.18);
        outline: none;
        background: #ffffff;
    }

    .btn-primary {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.18);
    }

    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-outline-gray {
        background: rgba(15, 23, 42, 0.04);
        color: #0f172a;
        border: 1px solid rgba(15, 23, 42, 0.08);
    }

    .indicator-card {
        padding: 1.5rem;
        min-height: 154px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .indicator-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 42px rgba(15, 23, 42, 0.08);
    }

    .indicator-title {
        font-size: 0.91rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.85rem;
        letter-spacing: 0.01em;
    }

    .indicator-value {
        font-size: 2.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .indicator-meta {
        color: #64748b;
        font-size: 0.95rem;
    }

    .indicator-note {
        color: #2563eb;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .chart-card {
        padding: 1.5rem;
        min-height: 420px;
    }

    .chart-header {
        margin-bottom: 1rem;
    }

    .chart-header h3 {
        margin: 0;
        color: #0f172a;
        font-weight: 700;
        font-size: 1.15rem;
    }

    .chart-header p {
        margin: 0.45rem 0 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .insight-card {
        padding: 1.8rem;
    }

    .insight-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.8rem;
    }

    .insight-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .insight-meta {
        color: #475569;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .insight-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 0.85rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.14);
        color: #1d4ed8;
        font-size: 0.85rem;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .chart-card,
        .indicator-card,
        .filter-card,
        .insight-card {
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
