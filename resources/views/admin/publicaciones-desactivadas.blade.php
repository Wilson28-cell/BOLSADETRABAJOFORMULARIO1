<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Publicaciones desactivadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="container-fluid py-5">
    <div class="page-header">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="page-title mb-1">Publicaciones desactivadas</h2>
                <p class="text-muted mb-0">Revisa y restaura las publicaciones archivadas de la Bolsa de Trabajo.</p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">Volver</button>
                <a href="{{ url('admin/bolsa-trabajo') }}" class="btn btn-secondary">Regresar a la bolsa</a>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('admin/bolsa-trabajo') }}" class="btn btn-outline-primary">Publicadas</a>
            <a href="{{ url('admin/publicaciones-desactivadas') }}" class="btn btn-primary">Eliminadas</a>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-4">
            @if($empresas->isEmpty())
                <p class="text-muted">No hay publicaciones desactivadas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Empresa / Título</th>
                            <th>Categoría</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($empresas as $e)
                            <tr>
                                <td>{{ $e->id_publicidad ?? $e->id_aprobado ?? $e->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $e->nombre_empresa ?? ($e->titulo_puesto ?? 'Sin nombre') }}</div>
                                    <div class="text-muted small">{{ $e->titulo_puesto ?? '' }}</div>
                                </td>
                                <td>{{ $e->categoria ?? '-' }}</td>
                                <td>{{ $e->ubicacion ?? '-' }}</td>
                                <td><span class="badge bg-secondary">Desactivado</span></td>
                                <td>
                                    <a href="{{ url('admin/publicaciones-desactivadas/'.($e->id_publicidad ?? $e->id)) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    <form action="{{ url('admin/publicaciones/restaurar/'.($e->id_publicidad ?? $e->id_aprobado ?? $e->id)) }}" method="POST" class="confirm-password-action" data-confirm-message="Restaurar esta publicación?" style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Restaurar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

    <div id="adminToastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 2000; min-width: 320px;"></div>

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
    var confirmPasswordModal = new bootstrap.Modal(document.getElementById('confirmPasswordModal'));
    var pendingForm = null;
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
        var toastElement = document.createElement('div');
        toastElement.className = 'toast align-items-center text-bg-' + type + ' border-0';
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.innerHTML = '\n            <div class="d-flex">\n                <div class="toast-body">' + message + '</div>\n                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>\n            </div>';

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
        passwordInput.value = '';
        passwordInput.classList.remove('is-invalid');
        passwordError.textContent = '';
        confirmPasswordModalMessage.textContent = (message || 'Confirmar esta acción') + ' Ingresa tu contraseña para continuar.';
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

        protectedForms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
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

        @if(session('success'))
            showAdminToast('{{ session('success') }}', 'success');
        @endif
        @if(session('warning'))
            showAdminToast('{{ session('warning') }}', 'warning');
        @endif
        @if(session('error'))
            showAdminToast('{{ session('error') }}', 'danger');
        @endif
    });
</script>
</html>
