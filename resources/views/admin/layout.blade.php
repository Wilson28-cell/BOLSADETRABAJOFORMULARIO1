<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel Administrativo - PORVENIR PRODUCE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
            color: var(--color-text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f7fafc 100%);
            border-right: 1px solid rgba(0,0,0,0.04);
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(12, 74, 153, 0.04);
            position: sticky;
            top: 0;
        }

        .sidebar h2 {
            color: var(--color-primary);
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar .brand-bullet {
            width: 8px;
            height: 28px;
            background: linear-gradient(180deg, var(--color-accent), var(--color-primary));
            border-radius: 3px;
        }

        .sidebar hr {
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.04), transparent);
            margin: 1rem 0;
        }

        .sidebar-section { margin-bottom: 1rem; }

        .sidebar-section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 700;
            color: rgba(0,74,153,0.9);
            margin-bottom: 0.6rem;
        }

        .section-links { transition: max-height 0.25s ease; }

        .sidebar-section.collapsed .section-links { display: none; }

        .toggle-section-btn {
            font-size: 0.85rem;
            padding: 0.15rem 0.45rem;
            line-height: 1;
            border: none;
            background: transparent;
            color: rgba(0,0,0,0.45);
        }

        .sidebar-link {
            display: block;
            margin-bottom: 0.5rem;
            padding: 0.55rem 0.9rem;
            text-decoration: none;
            color: rgba(0,0,0,0.75);
            border-radius: 8px;
            transition: all 0.18s ease;
            border-left: 3px solid transparent;
            margin-left: 0.25rem;
            font-weight: 600;
        }

        .sidebar-link:hover {
            background-color: rgba(102,126,234,0.06);
            border-left-color: var(--color-accent);
            color: var(--color-primary);
            transform: translateX(6px);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(102,126,234,0.08), rgba(118,75,162,0.03));
            color: var(--color-primary) !important;
            border-left-color: var(--color-primary);
            box-shadow: inset 3px 0 0 rgba(0,74,153,0.06);
        }

        .content-wrapper {
            flex: 1;
            padding: 2rem;
            background: var(--color-secondary);
        }

        .page-header { margin-bottom: 2rem; }

        /* Scrollbar for sidebar */
        .sidebar { overflow-y: auto; }
        .sidebar::-webkit-scrollbar { width: 8px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 6px; }
    </style>

</head>

<body>

<div class="d-flex align-items-start">

    <!-- SIDEBAR -->
    <div class="sidebar">

        @php
            $rol = session('usuario')->rol ?? null;
        @endphp

        <h2>
            🚀 PORVENIR PRODUCE
        </h2>

        <div class="mb-3 text-muted small">
            Rol: {{ $rol ?? 'Usuario' }}
        </div>

        <form action="{{ url('/logout') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Salir</button>
        </form>

        <hr>

        @if($rol === 'Analista')
            <div class="sidebar-section" id="indicatorsSection">
                <div class="d-flex align-items-center justify-content-between sidebar-section-title">
                    <div>📊 Indicadores</div>
                    <button type="button" data-target="indicatorLinks" class="btn btn-sm btn-outline-secondary toggle-section-btn" aria-expanded="true" aria-controls="indicatorLinks">▾</button>
                </div>
                <div id="indicatorLinks" class="section-links">
                    <a href="{{ url('admin/indicadores-bolsa') }}" class="sidebar-link">📈 Indicadores Bolsa de Trabajo</a>
                    <a href="{{ url('admin/indicadores-productos') }}" class="sidebar-link">📊 Indicadores Productos</a>
                    <a href="{{ url('admin/indicadores-servicios') }}" class="sidebar-link">🛠 Indicadores Servicios</a>
                </div>
            </div>
        @endif
        @if($rol !== 'Analista')
            <div class="sidebar-section" id="bolsaSection">
                <div class="d-flex align-items-center justify-content-between sidebar-section-title">
                    <div>💼 Bolsa de Trabajo</div>
                    <button type="button" data-target="bolsaLinks" class="btn btn-sm btn-outline-secondary toggle-section-btn" aria-expanded="true" aria-controls="bolsaLinks">▾</button>
                </div>
                <div id="bolsaLinks" class="section-links">
                    <a href="{{ url('admin/validacion-formularios') }}" class="sidebar-link">📄 Validación Formularios</a>
                    <a href="{{ url('admin/rechazados') }}" class="sidebar-link">❌ Rechazados</a>
                    <a href="{{ url('admin/bolsa-trabajo') }}" class="sidebar-link">💼 Publicaciones</a>
                    <a href="{{ url('admin/publicaciones-desactivadas') }}" class="sidebar-link">🗑️ Publicaciones Eliminadas</a>
                    <a href="{{ url('admin/postulantes') }}" class="sidebar-link">👥 Postulantes</a>
                    @if($rol === 'Super_Administrador' || $rol === 'SuperAdministrador' || $rol === 'Super Administrador')
                        <a href="{{ url('admin/usuarios/crear') }}" class="sidebar-link">👤 Crear Usuario Admin</a>
                    @endif
                </div>
            </div>

            <!-- PRODUCTOS -->

            <div class="sidebar-section mb-4" id="productosSection">
                <div class="d-flex align-items-center justify-content-between sidebar-section-title">
                    <div>🛍 Productos</div>
                    <button type="button" data-target="productosLinks" class="btn btn-sm btn-outline-secondary toggle-section-btn" aria-expanded="true" aria-controls="productosLinks">▾</button>
                </div>
                <div id="productosLinks" class="section-links ps-3">
                    <a href="{{ url('admin/formularios-productos') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">📦 Formularios Productos</a>
                    <a href="{{ url('admin/productos') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">📰 Publicaciones</a>
                    <a href="{{ url('admin/productos-rechazados') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">❌ Productos Rechazados</a>
                </div>
            </div>

            <!-- SERVICIOS -->

            <div class="sidebar-section mb-4" id="serviciosSection">
                <div class="d-flex align-items-center justify-content-between sidebar-section-title">
                    <div>🛠 Servicios</div>
                    <button type="button" data-target="serviciosLinks" class="btn btn-sm btn-outline-secondary toggle-section-btn" aria-expanded="true" aria-controls="serviciosLinks">▾</button>
                </div>
                <div id="serviciosLinks" class="section-links ps-3">
                    <a href="{{ url('admin/formularios-servicios') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">📄 Formularios Servicios</a>
                    <a href="{{ url('admin/publicaciones-servicios') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">📰 Publicaciones Servicios</a>
                    <a href="{{ url('admin/servicios-rechazados') }}" class="d-block mb-3 text-decoration-none text-dark sidebar-link">❌ Servicios Rechazados</a>
                </div>
            </div>
        @endif

        
    </div>


    <!-- CONTENIDO -->

    <div class="content-wrapper">

        @yield('content')

    </div>

</div>

    <div id="adminToastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 2000; min-width: 320px;">
    </div>

    <!-- MODAL PARA INGRESAR MOTIVO DE RECHAZO -->
    <div class="modal fade" id="motivoRechazModal" tabindex="-1" aria-labelledby="motivoRechazLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title" id="motivoRechazLabel">⚠️ Ingresa el motivo del rechazo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Por favor, describe detalladamente por qué se rechaza este producto:</p>
                    <div class="mb-3">
                        <label for="motivoRechazInput" class="form-label fw-semibold">Motivo del rechazo</label>
                        <textarea 
                            id="motivoRechazInput" 
                            class="form-control" 
                            rows="4" 
                            placeholder="Ej: No cumple con los estándares de calidad..."
                            maxlength="500"
                        ></textarea>
                        <small class="text-muted d-block mt-2">
                            <span id="motivoCharCount">0</span>/500 caracteres
                        </small>
                    </div>
                    <div id="motivoError" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="motivoRechazSubmit">Continuar con rechazo</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="confirmPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="confirmPasswordModalLabel">Confirmar contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmPasswordModalMessage" class="mb-3">Para continuar con esta acción, ingresa tu contraseña.</p>
                    <div class="mb-3">
                        <label for="confirmPasswordInput" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" id="confirmPasswordInput" class="form-control" placeholder="Contraseña" autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePasswordVisibility" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="confirmPasswordError" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmPasswordSubmit">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function() {
        var buttons = document.querySelectorAll('.toggle-section-btn');
        var storageKey = 'adminSidebarSectionState';

        function loadState() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '{}');
            } catch (e) {
                return {};
            }
        }

        function saveState(state) {
            try {
                localStorage.setItem(storageKey, JSON.stringify(state));
            } catch (e) {
                // ignore storage errors
            }
        }

        var state = loadState();

        buttons.forEach(function(btn) {
            var section = btn.closest('.sidebar-section');
            var sectionId = section ? section.id : null;

            function updateButton(collapsed) {
                btn.setAttribute('aria-expanded', (!collapsed).toString());
                btn.textContent = collapsed ? '▸' : '▾';
            }

            if (sectionId && state[sectionId]) {
                section.classList.add('collapsed');
                updateButton(true);
            }

            btn.addEventListener('click', function(event) {
                event.stopPropagation();
                if (!section || !sectionId) {
                    return;
                }

                var collapsed = section.classList.toggle('collapsed');
                state[sectionId] = collapsed;
                saveState(state);
                updateButton(collapsed);
            });
        });
    })();

    var confirmPasswordModal = new bootstrap.Modal(document.getElementById('confirmPasswordModal'));
    var pendingForm = null;
    var pendingActionMessage = null;
    var passwordInput = document.getElementById('confirmPasswordInput');
    var passwordError = document.getElementById('confirmPasswordError');
    var confirmPasswordSubmit = document.getElementById('confirmPasswordSubmit');
    var confirmPasswordModalMessage = document.getElementById('confirmPasswordModalMessage');
    var togglePasswordVisibility = document.getElementById('togglePasswordVisibility');

    function getCsrfToken() {
        var tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') : '';
    }

    function showAdminToast(message, type) {
        var toastId = 'admin-toast-' + Date.now();
        var toastElement = document.createElement('div');
        toastElement.className = 'toast align-items-center text-bg-' + type + ' border-0';
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.id = toastId;
        toastElement.innerHTML = '<div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button></div>';

        var container = document.getElementById('adminToastContainer');
        if (!container) {
            return;
        }

        container.appendChild(toastElement);
        var toast = new bootstrap.Toast(toastElement, { delay: 4500 });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function () {
            toastElement.remove();
        });
    }

    function openPasswordModal(message) {
        pendingActionMessage = message || 'Confirmar esta acción';
        passwordInput.value = '';
        passwordInput.classList.remove('is-invalid');
        passwordError.textContent = '';
        confirmPasswordModalMessage.textContent = pendingActionMessage + ' Ingresa tu contraseña para continuar.';
        confirmPasswordModal.show();
        setTimeout(function () {
            passwordInput.focus();
        }, 300);
    }

    function validatePasswordAndSubmit() {
        if (!pendingForm) {
            return;
        }

        var password = passwordInput.value.trim();
        if (!password) {
            passwordInput.classList.add('is-invalid');
            passwordError.textContent = 'Ingresa tu contraseña.';
            return;
        }

        confirmPasswordSubmit.disabled = true;
        passwordInput.classList.remove('is-invalid');
        passwordError.textContent = '';

        fetch('{{ url('admin/confirm-password') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        })
            .then(function (response) {
                confirmPasswordSubmit.disabled = false;
                if (!response.ok) {
                    return response.json().then(function (data) {
                        throw new Error(data.message || 'Contraseña incorrecta');
                    });
                }
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    confirmPasswordModal.hide();
                    pendingForm.submit();
                    return;
                }
                throw new Error(data.message || 'Contraseña incorrecta');
            })
            .catch(function (error) {
                passwordInput.classList.add('is-invalid');
                passwordError.textContent = error.message || 'Contraseña incorrecta';
                showAdminToast(error.message || 'Contraseña incorrecta', 'danger');
            });
    }

    function setupPasswordProtectedForms() {
        var protectedForms = document.querySelectorAll('form.confirm-password-action');
        var motivoRechazModal = new bootstrap.Modal(document.getElementById('motivoRechazModal'));
        var motivoRechazInput = document.getElementById('motivoRechazInput');
        var motivoCharCount = document.getElementById('motivoCharCount');
        var motivoError = document.getElementById('motivoError');
        var pendingRejectForm = null;

        // Actualizar contador de caracteres
        motivoRechazInput.addEventListener('input', function() {
            motivoCharCount.textContent = motivoRechazInput.value.length;
        });

        // Manejar clic en botón de continuar con rechazo
        document.getElementById('motivoRechazSubmit').addEventListener('click', function() {
            var motivo = motivoRechazInput.value.trim();

            if (motivo.length === 0) {
                motivoError.textContent = 'El motivo del rechazo es obligatorio.';
                motivoError.classList.remove('d-none');
                motivoRechazInput.focus();
                return;
            }

            if (motivo.length < 10) {
                motivoError.textContent = 'El motivo debe tener al menos 10 caracteres.';
                motivoError.classList.remove('d-none');
                motivoRechazInput.focus();
                return;
            }

            motivoError.classList.add('d-none');

            // Guardar el motivo en el input hidden del formulario
            if (pendingRejectForm) {
                var motivoInputHidden = pendingRejectForm.querySelector('input[name="motivo"]');
                if (motivoInputHidden) {
                    motivoInputHidden.value = motivo;
                }
            }

            // Cerrar modal de motivo
            motivoRechazModal.hide();

            // Mostrar modal de contraseña
            setTimeout(function() {
                if (pendingRejectForm) {
                    var description = pendingRejectForm.dataset.confirmMessage || 'Confirmar rechazo del producto';
                    openPasswordModal(description);
                }
            }, 300);
        });

        // Event listener para cuando se cierra el modal de motivo sin guardar
        document.getElementById('motivoRechazModal').addEventListener('hide.bs.modal', function() {
            if (pendingRejectForm) {
                // Solo limpiar si el usuario canceló (no hizo clic en continuar)
                motivoRechazInput.value = '';
                motivoCharCount.textContent = '0';
                motivoError.classList.add('d-none');
                pendingRejectForm = null;
            }
        });

        protectedForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                // Si el formulario es de rechazo, mostrar modal de motivo
                if (form.classList.contains('reject-form')) {
                    var motivoInputHidden = form.querySelector('input[name="motivo"]');
                    if (!motivoInputHidden || !motivoInputHidden.value || motivoInputHidden.value.trim().length === 0) {
                        pendingRejectForm = form;
                        motivoRechazInput.value = '';
                        motivoCharCount.textContent = '0';
                        motivoError.classList.add('d-none');
                        motivoRechazModal.show();
                        setTimeout(function() {
                            motivoRechazInput.focus();
                        }, 300);
                        return;
                    }
                }

                pendingForm = form;
                var description = form.dataset.confirmMessage || form.querySelector('button, input[type="submit"]')?.textContent?.trim() || 'Confirmar acción';
                openPasswordModal(description);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupPasswordProtectedForms();

        confirmPasswordSubmit.addEventListener('click', validatePasswordAndSubmit);
        togglePasswordVisibility.addEventListener('click', function () {
            var type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePasswordVisibility.innerHTML = type === 'password'
                ? '<i class="bi bi-eye"></i>'
                : '<i class="bi bi-eye-slash"></i>';
        });

        @php
            $flashData = null;
            if (session()->has('success')) {
                $flashData = ['message' => session('success'), 'type' => 'success'];
            } elseif (session()->has('warning')) {
                $flashData = ['message' => session('warning'), 'type' => 'warning'];
            } elseif (session()->has('error')) {
                $flashData = ['message' => session('error'), 'type' => 'danger'];
            }
        @endphp
        var flashData = @json($flashData);
        if (flashData) {
            showAdminToast(flashData.message, flashData.type);
        }
        // Marcar enlace activo en sidebar según la ruta actual
        try {
            var currentPath = window.location.pathname.replace(/(^\/|\/$)/g, '');
            var links = document.querySelectorAll('.sidebar-link');
            links.forEach(function (link) {
                var href = link.getAttribute('href') || '';
                var linkPath = href.replace(window.location.origin, '').replace(/(^\/|\/$)/g, '');
                if (linkPath && currentPath.indexOf(linkPath) === 0) {
                    link.classList.add('active');
                    var parentSection = link.closest('.sidebar-section');
                    if (parentSection) parentSection.classList.remove('collapsed');
                }
            });
        } catch (e) {
            // no-op
        }
    });
</script>

</html>