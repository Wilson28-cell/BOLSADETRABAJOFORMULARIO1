@extends('layouts.app')

@section('content')

<div class="registration-container">
    <div class="container-fluid">
        <div class="registration-card">
            <div class="registration-header">
                <h1>🏪 Registra tu Producto</h1>
                <p>Completa el formulario para publicar tu producto en el directorio</p>
            </div>

            <div class="registration-body">
                <div class="step-indicator">
                    <div class="step active" id="step1-indicator">
                        <div class="step-circle">1</div>
                        <div class="step-label">Empresa</div>
                    </div>
                    <div class="divider"></div>
                    <div class="step" id="step2-indicator">
                        <div class="step-circle">2</div>
                        <div class="step-label">Producto</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-custom alert-success-custom">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-custom alert-danger-custom">
                        <strong>⚠️ Errores en el formulario:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('guardar-producto') }}" method="POST" enctype="multipart/form-data" id="mainForm">
                    @csrf

                    <div id="formEmpresa" class="form-section">
                        <div class="section-title">
                            <span class="section-number">1</span>
                            Información de la Empresa
                        </div>

                        <div class="row-custom">
                            <div class="form-group-custom">
                                <label class="form-label-custom">🏢 Nombre Empresa <span class="required-mark">*</span></label>
                                <input type="text" name="nombre_empresa" class="form-control-custom" required placeholder="Ej: Empresa ABC S.A.">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📋 RUC <span class="required-mark">*</span></label>
                                <input type="text" name="ruc" class="form-control-custom" maxlength="11" pattern="\d{11}" title="Ingrese 11 dígitos de RUC" required placeholder="Ej: 12345678901">
                                <div class="helper-text">11 dígitos sin guiones</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📧 Correo <span class="required-mark">*</span></label>
                                <input type="email" name="correo_electronico" class="form-control-custom" required placeholder="empresa@ejemplo.com">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">☎️ Teléfono <span class="required-mark">*</span></label>
                                <input type="text" name="telefono" class="form-control-custom" required placeholder="Ej: 044 123456">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">👤 Responsable Representante <span class="required-mark">*</span></label>
                                <input type="text" name="responsable_representante" class="form-control-custom" required placeholder="Nombre completo">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🗺️ Dirección <span class="required-mark">*</span></label>
                                <input type="text" name="direccion" class="form-control-custom" required placeholder="Calle, número, distrito...">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📄 Documentos requeridos (Ficha de SUNAT y SUNARP) <span class="required-mark">*</span></label>
                                <input type="file" name="documento_validacion" class="form-control-custom" accept=".pdf" required>
                                <div class="helper-text">Formato: PDF. Máx: 10MB</div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-continue" onclick="mostrarProducto()">
                                ▶ Continuar →
                            </button>
                        </div>
                    </div>

                    <div id="formProducto" class="form-section hidden-section">
                        <div class="section-title">
                            <span class="section-number">2</span>
                            Información del Producto
                        </div>

                        <div class="row-custom">
                            <div class="form-group-custom">
                                <label class="form-label-custom">🛍️ Nombre del Producto <span class="required-mark">*</span></label>
                                <input type="text" name="nombre_producto" class="form-control-custom" required placeholder="Ej: Jabón artesanal de rosas">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🏷️ Categoría <span class="required-mark">*</span></label>
                                <select name="categoria" class="form-control-custom" required>
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="Alimentos y Bebidas" {{ old('categoria') === 'Alimentos y Bebidas' ? 'selected' : '' }}>Alimentos y Bebidas</option>
                                    <option value="Moda y Calzado" {{ old('categoria') === 'Moda y Calzado' ? 'selected' : '' }}>Moda y Calzado</option>
                                    <option value="Tecnología y Electrónica" {{ old('categoria') === 'Tecnología y Electrónica' ? 'selected' : '' }}>Tecnología y Electrónica</option>
                                    <option value="Cuidado Personal y Belleza" {{ old('categoria') === 'Cuidado Personal y Belleza' ? 'selected' : '' }}>Cuidado Personal y Belleza</option>
                                    <option value="Hogar y Decoración" {{ old('categoria') === 'Hogar y Decoración' ? 'selected' : '' }}>Hogar y Decoración</option>
                                    <option value="Salud y Bienestar" {{ old('categoria') === 'Salud y Bienestar' ? 'selected' : '' }}>Salud y Bienestar</option>
                                    <option value="Juguetes y Niños" {{ old('categoria') === 'Juguetes y Niños' ? 'selected' : '' }}>Juguetes y Niños</option>
                                    <option value="Deportes y Aire Libre" {{ old('categoria') === 'Deportes y Aire Libre' ? 'selected' : '' }}>Deportes y Aire Libre</option>
                                    <option value="Limpieza y Mascotas" {{ old('categoria') === 'Limpieza y Mascotas' ? 'selected' : '' }}>Limpieza y Mascotas</option>
                                    <option value="Oficina y Papelería" {{ old('categoria') === 'Oficina y Papelería' ? 'selected' : '' }}>Oficina y Papelería</option>
                                </select>
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📝 Descripción <span class="required-mark">*</span></label>
                                <textarea name="descripcion" class="form-control-custom textarea-custom" rows="4" required placeholder="Describe tu producto, sus características y beneficios..."></textarea>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📍 Ciudad</label>
                                <input type="text" name="ubicacion_ciudad" class="form-control-custom" placeholder="Ciudad donde se ofrece el producto">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">☎️ Teléfono de Contacto</label>
                                <input type="text" name="telefono_contacto" class="form-control-custom" placeholder="Teléfono para consultas">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🌐 Redes Sociales</label>
                                <input type="text" name="redes_sociales" class="form-control-custom" placeholder="Enlaces o nombres de redes sociales">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📧 Correo de Contacto</label>
                                <input type="email" name="correo_contacto" class="form-control-custom" placeholder="correo@contacto.com">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">🏠 Dirección de Atención</label>
                                <input type="text" name="direccion_atencion" class="form-control-custom" placeholder="Dirección de retiro o entrega">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🖼️ Imagen del Producto <span class="required-mark">*</span></label>
                                <input type="file" name="imagen_producto" class="form-control-custom" required>
                                <div class="helper-text">Formato recomendado JPG/PNG. Tamaño máximo 5MB</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📅 Fecha Inicio <span class="required-mark">*</span></label>
                                <input type="date" name="fecha_inicio" class="form-control-custom" value="{{ old('fecha_inicio', \Carbon\Carbon::now()->format('Y-m-d')) }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addYear()->format('Y-m-d') }}" required>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📅 Fecha Fin <span class="required-mark">*</span></label>
                                <input type="date" name="fecha_fin" class="form-control-custom" value="{{ old('fecha_fin', \Carbon\Carbon::now()->addMonth()->format('Y-m-d')) }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addYear()->format('Y-m-d') }}" required>
                                <div class="helper-text">Por defecto la publicación termina en un mes. Puedes extenderla hasta un año.</div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-back" onclick="volverAlPaso1()">
                                ← Atrás
                            </button>
                            <button type="submit" class="btn-custom btn-submit">
                                ✓ Registrar Producto
                            </button>
                        </div>
                        <div class="helper-text helper-text-center">
                            ℹ️ Tu producto será revisado antes de publicarse. El proceso puede tardar 1-2 días hábiles.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom confirmation modal -->
<div id="confirmModal" class="custom-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="custom-confirm-modal">
        <div id="confirmTitle" class="custom-confirm-title">Confirma los datos</div>
        <div class="custom-confirm-text">¿Los datos ingresados son correctos?</div>
        <div class="custom-confirm-actions">
            <button id="confirmYes" class="btn-yes">Sí</button>
            <button id="confirmNo" class="btn-no">No</button>
        </div>
    </div>
</div>

<script>
    function mostrarProducto() {
        const container = document.getElementById('formEmpresa');
        const controls = container.querySelectorAll('input, textarea, select');

        let isValid = true;
        for (let i = 0; i < controls.length; i++) {
            const el = controls[i];
            if (!el.checkValidity()) {
                el.reportValidity();
                isValid = false;
                break;
            }
        }

        if (isValid) {
            // show custom modal
            document.getElementById('confirmModal').classList.add('open');
        }
    }

    function hideConfirmModal() {
        document.getElementById('confirmModal').classList.remove('open');
    }

    document.getElementById('confirmYes').addEventListener('click', function () {
        hideConfirmModal();
        document.getElementById('formEmpresa').style.display = 'none';
        document.getElementById('formProducto').style.display = 'block';
        document.getElementById('step1-indicator').classList.remove('active');
        document.getElementById('step2-indicator').classList.add('active');
        window.scrollTo(0, 0);
    });

    document.getElementById('confirmNo').addEventListener('click', function () {
        hideConfirmModal();
    });

    function volverAlPaso1() {
        document.getElementById('formProducto').style.display = 'none';
        document.getElementById('formEmpresa').style.display = 'block';
        document.getElementById('step1-indicator').classList.add('active');
        document.getElementById('step2-indicator').classList.remove('active');
        window.scrollTo(0, 0);
    }
</script>

@endsection
