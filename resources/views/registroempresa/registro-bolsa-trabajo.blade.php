@extends('layouts.app')

@section('content')

<div class="registration-container">
    <div class="container-fluid">
        <div class="registration-card">
            <!-- Header -->
            <div class="registration-header">
                <h1>💼 Registra tu Oferta de Trabajo</h1>
                <p>Completa el formulario para publicar tu oferta de empleo</p>
            </div>

            <!-- Body -->
            <div class="registration-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1-indicator">
                        <div class="step-circle">1</div>
                        <div class="step-label">Empresa</div>
                    </div>
                    <div class="divider"></div>
                    <div class="step" id="step2-indicator">
                        <div class="step-circle">2</div>
                        <div class="step-label">Publicación</div>
                    </div>
                </div>

                <form action="{{ url('guardar-bolsa-trabajo') }}" method="POST" enctype="multipart/form-data" id="mainForm">
                    @csrf

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

                    <!-- STEP 1: INFORMACIÓN EMPRESA -->
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
                                <input type="text" name="ruc" class="form-control-custom" maxlength="11" required placeholder="Ej: 12345678901">
                                <div class="helper-text">11 dígitos sin guiones</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📧 Correo Electrónico <span class="required-mark">*</span></label>
                                <input type="email" name="correo_electronico" class="form-control-custom" required placeholder="empresa@ejemplo.com">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">☎️ Teléfono <span class="required-mark">*</span></label>
                                <input type="text" name="telefono" class="form-control-custom" required placeholder="Ej: 044 123456">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">👤 Representante Responsable <span class="required-mark">*</span></label>
                                <input type="text" name="responsable_representante" class="form-control-custom" required placeholder="Nombre completo">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📄 Documentos requeridos (Ficha de SUNAT y SUNARP) <span class="required-mark">*</span></label>
                                <input type="file" name="documento_validacion" class="form-control-custom" required>
                                <div class="helper-text">Formato: PDF, JPG, PNG. Máx: 5MB</div>
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">🗺️ Dirección <span class="required-mark">*</span></label>
                                <textarea name="direccion" class="form-control-custom textarea-custom" required placeholder="Calle, número, distrito, provincia..."></textarea>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-continue" onclick="mostrarBolsaTrabajo()">
                                ▶ Continuar →
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: INFORMACIÓN PUBLICACIÓN -->
                    <div id="formBolsaTrabajo" class="form-section hidden-section">
                        <div class="section-title">
                            <span class="section-number">2</span>
                            Detalles de la Oferta de Trabajo
                        </div>

                        <div class="row-custom">
                            <div class="form-group-custom">
                                <label class="form-label-custom">💼 Título del Puesto <span class="required-mark">*</span></label>
                                <input type="text" name="titulo_puesto" class="form-control-custom" required placeholder="Ej: Ingeniero de Software">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🏷️ Categoría <span class="required-mark">*</span></label>
                                <input type="text" name="categoria" class="form-control-custom" required placeholder="Ej: Tecnología, Administrativo">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📍 Modalidad <span class="required-mark">*</span></label>
                                <select name="modalidad" class="form-control-custom" required>
                                    <option value="">Seleccionar modalidad...</option>
                                    <option value="Presencial">🏢 Presencial</option>
                                    <option value="Virtual">💻 Virtual</option>
                                    <option value="Hibrido">🔄 Híbrido</option>
                                </select>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📌 Ubicación <span class="required-mark">*</span></label>
                                <select name="ubicacion" class="form-control-custom" required>
                                    <option value="">Seleccionar ciudad...</option>
                                    <option>🏙️ Trujillo Centro</option>
                                    <option>🌳 La Esperanza</option>
                                    <option>🏛️ El Porvenir</option>
                                    <option>🌺 Florencia de Mora</option>
                                    <option>🏖️ Huanchaco</option>
                                    <option>🏘️ Laredo</option>
                                    <option>🏞️ Moche</option>
                                    <option>⛱️ Salaverry</option>
                                    <option>🏡 Victor Larco</option>
                                    <option>🏗️ Alto Trujillo</option>
                                </select>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">💵 Salario (S/) <span class="required-mark">*</span></label>
                                <input type="number" step="0.01" name="salario" class="form-control-custom" required placeholder="0.00">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📅 Fecha Inicio <span class="required-mark">*</span></label>
                                <input type="date" name="fecha_inicio_convocatoria" class="form-control-custom" value="{{ old('fecha_inicio_convocatoria', \Carbon\Carbon::now()->addDay()->format('Y-m-d')) }}" readonly required>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">⏰ Fecha Límite <span class="required-mark">*</span></label>
                                <input type="date" name="fecha_limite_postulacion" class="form-control-custom" value="{{ old('fecha_limite_postulacion', \Carbon\Carbon::now()->addMonth()->format('Y-m-d')) }}" readonly required>
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📝 Descripción del Puesto <span class="required-mark">*</span></label>
                                <textarea name="descripcion_puesto" class="form-control-custom textarea-custom" required placeholder="Describe las funciones principales, responsabilidades y tareas del puesto..."></textarea>
                                <div class="helper-text">Sé detallado para atraer candidatos calificados</div>
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">✅ Requisitos <span class="required-mark">*</span></label>
                                <textarea name="requisitos" class="form-control-custom textarea-custom" required placeholder="Experiencia, estudios, certificaciones, habilidades requeridas..."></textarea>
                                <div class="helper-text">Ejemplo: 2 años de experiencia en ventas, título técnico, conocimiento de Excel avanzado</div>
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">🖼️ Imagen de la Oferta <span class="required-mark">*</span></label>
                                <input type="file" name="imagen_trabajo" class="form-control-custom" required>
                                <div class="helper-text">Formato: JPG, PNG, WebP. Tamaño recomendado: 400x300px. Máx: 5MB</div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-back" onclick="volverAlPaso1()">
                                ← Atrás
                            </button>
                            <button type="submit" class="btn-custom btn-submit">
                                ✓ Registrar Publicación
                            </button>
                        </div>
                        <div class="helper-text helper-text-center">
                            ℹ️ La publicación será revisada por nuestro equipo. La aprobación puede demorar 1-2 días hábiles.
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarBolsaTrabajo() {
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
            const confirmed = confirm('¿Los datos ingresados son correctos?');
            if (!confirmed) {
                return;
            }

            document.getElementById('formEmpresa').style.display = 'none';
            document.getElementById('formBolsaTrabajo').style.display = 'block';
            document.getElementById('step1-indicator').classList.remove('active');
            document.getElementById('step2-indicator').classList.add('active');
            window.scrollTo(0, 0);
        }
    }

    function volverAlPaso1() {
        document.getElementById('formBolsaTrabajo').style.display = 'none';
        document.getElementById('formEmpresa').style.display = 'block';
        document.getElementById('step1-indicator').classList.add('active');
        document.getElementById('step2-indicator').classList.remove('active');
        window.scrollTo(0, 0);
    }
</script>

@endsection
