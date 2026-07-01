<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Ver Empresa
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="container-fluid py-5">

    <div class="page-header mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <h2 class="page-title mb-1">{{ $empresa->nombre_empresa ?? 'Detalle de Empresa' }}</h2>
                <p class="text-muted mb-0">Resumen administrativo de la publicación y datos de la empresa.</p>
            </div>
            <a href="{{ url('admin/validacion-formularios') }}" class="btn btn-outline-secondary">Volver a validación</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-semibold mb-4">Datos de la empresa</h4>
                    <div class="row gy-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">RUC</div>
                                <div class="fw-semibold">{{ $empresa->ruc ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Correo</div>
                                <div class="fw-semibold">{{ $empresa->correo_electronico ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Teléfono</div>
                                <div class="fw-semibold">{{ $empresa->telefono ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Responsable</div>
                                <div class="fw-semibold">{{ $empresa->responsable_representante ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Dirección</div>
                                <div class="fw-semibold">{{ $empresa->direccion ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Estado</div>
                                <div class="fw-semibold">{{ $empresa->estado ?? 'Pendiente' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Categoría</div>
                                <div class="fw-semibold">{{ $empresa->categoria ?? 'No especificado' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div>
                            <h4 class="fw-semibold mb-3">Oferta laboral</h4>
                            <h5 class="mb-2">{{ $empresa->titulo_puesto ?? 'Sin título' }}</h5>
                        </div>
                        <div class="text-end text-muted">ID: {{ $empresa->id_empresa ?? 'N/A' }}</div>
                    </div>

                    <p class="text-secondary mb-4">{{ $empresa->descripcion_puesto ?? 'No hay descripción disponible.' }}</p>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Modalidad</div>
                                <div class="fw-semibold">{{ $empresa->modalidad ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Ubicación</div>
                                <div class="fw-semibold">{{ $empresa->ubicacion ?? 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Salario</div>
                                <div class="fw-semibold">{{ $empresa->salario ? 'S/ ' . number_format($empresa->salario, 2, ',', '.') : 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Inicio convocatoria</div>
                                <div class="fw-semibold">{{ !empty($empresa->fecha_inicio_convocatoria) ? \Carbon\Carbon::parse($empresa->fecha_inicio_convocatoria)->format('d/m/Y') : 'No especificado' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-4 p-3 bg-white">
                                <div class="text-muted small">Límite postulación</div>
                                <div class="fw-semibold">{{ !empty($empresa->fecha_limite_postulacion) ? \Carbon\Carbon::parse($empresa->fecha_limite_postulacion)->format('d/m/Y') : 'No especificado' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border rounded-4 p-3 bg-white">
                        <div class="text-muted small mb-2">Requisitos</div>
                        @if(!empty($empresa->requisitos))
                            @php $lines = preg_split('/\r\n|\r|\n/', trim($empresa->requisitos)); @endphp
                            <ul class="mb-0">
                                @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                        <li>{{ $line }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No especificado.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 g-3 mt-3">
                @if(!empty($empresa->imagen_trabajo))
                    @php
                        $imgExt = strtolower(pathinfo($empresa->imagen_trabajo, PATHINFO_EXTENSION));
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="mb-1">Imagen / archivo de la publicación</h6>
                                        <p class="text-muted small mb-0">{{ strtoupper($imgExt) }}</p>
                                    </div>
                                    <a href="{{ asset($empresa->imagen_trabajo) }}" target="_blank" class="btn btn-sm btn-outline-success">Abrir</a>
                                </div>
                                @if(in_array($imgExt, $imgAllowed))
                                    <div class="rounded-4 shadow-sm overflow-hidden" style="background:#f8f9fa; min-height:180px; display:flex; align-items:center; justify-content:center;">
                                        <img src="{{ asset($empresa->imagen_trabajo) }}" alt="Imagen publicación" class="img-fluid" style="max-height:220px; width:auto; max-width:100%; object-fit:contain;">
                                    </div>
                                @elseif($imgExt === 'pdf')
                                    <div class="ratio ratio-4x3">
                                        <iframe src="{{ asset($empresa->imagen_trabajo) }}" frameborder="0"></iframe>
                                    </div>
                                @else
                                    <p class="text-muted">Archivo disponible para abrir en nueva pestaña.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($empresa->documento_validacion))
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="mb-1">Documento de validación</h6>
                                        <p class="text-muted small mb-0">Subido por la empresa</p>
                                    </div>
                                    <a href="{{ asset($empresa->documento_validacion) }}" target="_blank" class="btn btn-sm btn-outline-warning">Abrir</a>
                                </div>
                                <div class="ratio ratio-4x3">
                                    <iframe src="{{ asset($empresa->documento_validacion) }}" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($empresa->documento_aprobacion_pdf))
                    <div class="col">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="mb-1">Documento aprobado</h6>
                                        <p class="text-muted small mb-0">PDF administrativo</p>
                                    </div>
                                    <a href="{{ asset($empresa->documento_aprobacion_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                                </div>
                                <div class="ratio ratio-4x3">
                                    <iframe src="{{ asset($empresa->documento_aprobacion_pdf) }}" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $rol = session('usuario')->rol ?? null;
    @endphp
    <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row gap-2">
                @if($rol !== 'Analista')
                    <form action="{{ url('admin/aprobar/'.$empresa->id_empresa) }}" method="POST" class="w-100 confirm-password-action" data-confirm-message="Aprobar esta solicitud?">
                        @csrf
                        <button class="btn btn-success w-100">Aprobar</button>
                    </form>
                    <form action="{{ url('admin/rechazar/'.$empresa->id_empresa) }}" method="POST" class="w-100 confirm-password-action" data-confirm-message="Rechazar esta solicitud?">
                        @csrf
                        <button class="btn btn-danger w-100">Rechazar</button>
                    </form>
                @endif
                <a href="{{ url('admin/validacion-formularios') }}" class="btn btn-secondary w-100">Volver</a>
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

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
    });
</script>

</html>