@extends('admin.layout')

@section('content')

<style>
    .services-dashboard-page {
        background: #f4f6fb;
    }

    .services-dashboard-header {
        margin-bottom: 1.75rem;
    }

    .services-dashboard-header h2 {
        font-size: 2.25rem;
        font-weight: 700;
        color: #102a43;
    }

    .services-dashboard-header p {
        color: #52606d;
        font-size: 1rem;
    }

    .services-filter-card,
    .services-kpi-card,
    .services-chart-card,
    .services-insight-card {
        background: #ffffff;
        border: 1px solid rgba(16, 42, 67, 0.1);
        border-radius: 1.5rem;
        box-shadow: 0 18px 36px rgba(16, 42, 67, 0.08);
    }

    .services-filter-card {
        padding: 1.6rem;
    }

    .services-filter-card .form-label {
        font-weight: 600;
        color: #334e68;
    }

    .form-control-solid,
    .form-select-solid {
        background: #f8fbff;
        border: 1px solid #caced9;
        border-radius: 0.95rem;
        color: #102a43;
        min-height: 50px;
        box-shadow: inset 0 1px 3px rgba(16, 42, 67, 0.08);
    }

    .form-control-solid:focus,
    .form-select-solid:focus {
        border-color: #2064f4;
        box-shadow: 0 0 0 0.14rem rgba(32, 100, 244, 0.14);
    }

    .btn-primary {
        background: #2064f4;
        border-color: #2064f4;
        box-shadow: 0 10px 24px rgba(32, 100, 244, 0.18);
    }

    .btn-primary:hover {
        background: #1647c0;
        border-color: #1647c0;
    }

    .services-kpi-card {
        padding: 1.6rem;
        min-height: 160px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .services-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 40px rgba(16, 42, 67, 0.12);
    }

    .services-kpi-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334e68;
        margin-bottom: 0.75rem;
    }

    .services-kpi-value {
        font-size: 2.4rem;
        font-weight: 800;
        color: #102a43;
        margin-bottom: 0.5rem;
    }

    .services-kpi-note {
        color: #627d98;
        font-size: 0.95rem;
    }

    .services-chart-card {
        padding: 1.5rem;
        min-height: 430px;
    }

    .services-chart-card .chart-header h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #102a43;
    }

    .services-chart-card .chart-header p {
        color: #627d98;
        margin-top: 0.35rem;
        font-size: 0.95rem;
    }

    .services-insight-card {
        padding: 1.6rem;
        min-height: 430px;
    }

    .services-insight-title {
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #2064f4;
        margin-bottom: 1rem;
    }

    .services-insight-name {
        font-size: 1.65rem;
        font-weight: 800;
        color: #102a43;
        margin-bottom: 0.8rem;
    }

    .services-insight-meta {
        color: #627d98;
        font-size: 0.95rem;
        margin-bottom: 1.2rem;
    }

    .services-insight-value {
        font-size: 3rem;
        font-weight: 800;
        color: #2064f4;
        margin-bottom: 0.5rem;
    }

    .services-insight-label {
        color: #334e68;
        font-size: 0.95rem;
    }

    .services-insight-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.65rem 0.9rem;
        background: rgba(32, 100, 244, 0.13);
        color: #1647c0;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    @media (max-width: 991px) {
        .services-chart-card,
        .services-insight-card,
        .services-kpi-card,
        .services-filter-card {
            min-height: auto;
        }
    }
</style>

<div class="services-dashboard-page">
    <div class="services-dashboard-header mb-4">
        <h2>Publicidad de Servicios</h2>
        <p>Panel ejecutivo para analizar las visualizaciones y el rendimiento de servicios publicitarios.</p>
    </div>

    <div id="dashboardContent" data-dashboard-url="{{ url('admin/indicadores-servicios') }}">
        @include('admin.partials.dashboard.servicios-content')
    </div>
</div>

@include('admin.partials.dashboard.dashboard-scripts')

@endsection
