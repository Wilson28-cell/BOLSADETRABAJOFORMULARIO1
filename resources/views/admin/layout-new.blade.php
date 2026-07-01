<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - PORVENIR PRODUCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-theme.css') }}">

    <style>
        :root {
            --color-primary: #004a99;
            --color-secondary: #f4f7f6;
            --color-accent: #d4af37;
            --color-text: #333333;
        }

        body {
            background: var(--color-secondary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, var(--color-secondary) 100%);
            border-right: 3px solid var(--color-primary);
            padding: 2rem 1.5rem;
            box-shadow: 2px 0 8px rgba(0, 74, 153, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar h2 {
            color: var(--color-primary);
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar h2::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(180deg, var(--color-accent), var(--color-primary));
            border-radius: 2px;
        }

        .sidebar hr {
            border-color: var(--color-accent);
            border-width: 2px;
            margin: 1rem 0;
        }

        .sidebar-section {
            margin-bottom: 1.5rem;
        }

        .sidebar-section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--color-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--color-accent);
        }

        .sidebar-link {
            display: block;
            margin-bottom: 0.8rem;
            padding: 0.6rem 0.8rem;
            text-decoration: none;
            color: var(--color-text);
            border-radius: 6px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-left: 0.5rem;
            font-size: 0.95rem;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 74, 153, 0.08);
            border-left-color: var(--color-accent);
            color: var(--color-primary);
            transform: translateX(5px);
        }

        .content-wrapper {
            margin-left: 280px;
            padding: 2rem;
            background: var(--color-secondary);
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 74, 153, 0.08);
            margin-bottom: 1.5rem;
        }

        .table thead {
            background: linear-gradient(135deg, var(--color-primary) 0%, #003370 100%);
            color: white;
        }

        .btn-primary {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 74, 153, 0.15);
        }

        .btn-primary:hover {
            background-color: #003370 !important;
            box-shadow: 0 4px 12px rgba(0, 74, 153, 0.3);
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: var(--color-accent) !important;
            border-color: var(--color-accent) !important;
            color: var(--color-primary) !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.15);
        }

        .btn-warning:hover {
            background-color: #c9a125 !important;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
            transform: translateY(-2px);
        }

        .btn-success:hover,
        .btn-danger:hover {
            transform: translateY(-2px);
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--color-primary);
            font-weight: 700;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 153, 0.15) !important;
        }

        .badge {
            padding: 0.5rem 0.85rem;
            font-weight: 600;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 1.2rem 1.5rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 5px solid #10b981;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 3px solid var(--color-primary);
            }

            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🚀 PORVENIR PRODUCE</h2>
        <hr>

        <!-- BOLSA DE TRABAJO -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">💼 Bolsa de Trabajo</div>
            <div>
                <a href="{{ url('admin/validacion-formularios') }}" class="sidebar-link">📄 Validación Formularios</a>
                <a href="{{ url('admin/rechazados') }}" class="sidebar-link">❌ Rechazados</a>
                <a href="{{ url('admin/bolsa-trabajo') }}" class="sidebar-link">💼 Publicaciones</a>
                <a href="{{ url('admin/postulantes') }}" class="sidebar-link">👥 Postulantes</a>
            </div>
        </div>

        <!-- PRODUCTOS -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">🛍 Productos</div>
            <div>
                <a href="{{ url('admin/formularios-productos') }}" class="sidebar-link">📦 Formularios</a>
                <a href="{{ url('admin/productos') }}" class="sidebar-link">📰 Publicaciones</a>
                <a href="{{ url('admin/productos-rechazados') }}" class="sidebar-link">❌ Rechazados</a>
            </div>
        </div>

        <!-- SERVICIOS -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">🛠 Servicios</div>
            <div>
                <a href="{{ url('admin/formularios-servicios') }}" class="sidebar-link">📄 Formularios</a>
                <a href="{{ url('admin/publicaciones-servicios') }}" class="sidebar-link">📰 Publicaciones</a>
                <a href="{{ url('admin/servicios-rechazados') }}" class="sidebar-link">❌ Rechazados</a>
            </div>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
