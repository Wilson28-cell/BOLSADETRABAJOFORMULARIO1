<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Panel Admin</title>

    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- ICONOS -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- ESTILO INSTITUCIONAL -->
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->

    <aside class="sidebar">

            <div class="brand">Municipalidad / Bolsa de Trabajo</div>
        <hr>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Bolsa de Trabajo</div>
            <div class="section-links">
                <a href="{{ url('admin/validacion-formularios') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-check"></i>
                    Validación Formularios
                </a>
                <a href="{{ url('admin/rechazados') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-x"></i>
                    Rechazados
                </a>
                <a href="{{ url('admin/bolsa-trabajo') }}" class="sidebar-link">
                    <i class="bi bi-briefcase"></i>
                    Publicaciones
                </a>
                <a href="{{ url('admin/publicaciones-desactivadas') }}" class="sidebar-link">
                    <i class="bi bi-archive"></i>
                    Publicaciones Eliminadas
                </a>
                <a href="{{ url('admin/postulantes') }}" class="sidebar-link">
                    <i class="bi bi-people"></i>
                    Postulantes
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Productos</div>
            <div class="section-links">
                <a href="{{ url('admin/formularios-productos') }}" class="sidebar-link">
                    <i class="bi bi-box-seam"></i>
                    Formularios
                </a>
                <a href="{{ url('admin/productos') }}" class="sidebar-link">
                    <i class="bi bi-newspaper"></i>
                    Publicaciones
                </a>
                <a href="{{ url('admin/productos-rechazados') }}" class="sidebar-link">
                    <i class="bi bi-x-circle"></i>
                    Rechazados
                </a>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Servicios</div>
            <div class="section-links">
                <a href="{{ url('admin/formularios-servicios') }}" class="sidebar-link">
                    <i class="bi bi-card-checklist"></i>
                    Formularios
                </a>
                <a href="{{ url('admin/publicaciones-servicios') }}" class="sidebar-link">
                    <i class="bi bi-tools"></i>
                    Publicaciones
                </a>
                <a href="{{ url('admin/servicios-rechazados') }}" class="sidebar-link">
                    <i class="bi bi-x-circle"></i>
                    Rechazados
                </a>
            </div>
        </div>
    </aside>

    <!-- CONTENIDO -->

    <main class="main-content">

        @yield('content')

    </main>

</div>


<!-- BOOTSTRAP JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentPath = window.location.pathname.replace(/\/$/, '');
        document.querySelectorAll('.sidebar-link').forEach(function (link) {
            var linkPath = link.getAttribute('href');
            if (linkPath && currentPath === linkPath.replace(/\/$/, '')) {
                link.classList.add('active');
            }
        });
    });
</script>

</body>

</html>