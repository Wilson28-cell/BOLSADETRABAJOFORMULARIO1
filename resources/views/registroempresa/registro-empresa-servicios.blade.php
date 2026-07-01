@extends('layouts.app')

@section('content')

<div class="registration-container">

    <div class="container-fluid">
        <div class="registration-card">
            <div class="registration-header">
                <h1>🔧 Registra tu Servicio</h1>
                <p>Completa el formulario para publicar tu servicio en el directorio</p>
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
                        <div class="step-label">Servicio</div>
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

                @php
                    $fechaHoy = \Carbon\Carbon::today();
                    $fechaFinDefault = $fechaHoy->copy()->addMonth();
                    $fechaFinMax = $fechaHoy->copy()->addYear();
                @endphp

                <form action="{{ url('guardar-servicio') }}" method="POST" enctype="multipart/form-data" id="mainForm">
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
                                <label class="form-label-custom">👤 Responsable / Representante</label>
                                <input type="text" name="responsable_representante" class="form-control-custom" placeholder="Nombre del responsable">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📍 Dirección</label>
                                <input type="text" name="direccion" class="form-control-custom" placeholder="Dirección de la empresa">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📄 Documento de Validación (PDF) <span class="required-mark">*</span></label>
                                <input type="file" name="documento_validacion" class="form-control-custom" accept="application/pdf" required>
                                <div class="helper-text">Requerido. PDF máximo 10MB.</div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-continue" onclick="mostrarServicio()">
                                ▶ Continuar →
                            </button>
                        </div>
                    </div>

                    <div id="formServicio" class="form-section hidden-section">
                        <div class="section-title">
                            <span class="section-number">2</span>
                            Información del Servicio
                        </div>

                        <div class="row-custom">
                            <div class="form-group-custom">
                                <label class="form-label-custom">🔧 Nombre del Servicio <span class="required-mark">*</span></label>
                                <input type="text" name="nombre_servicio" class="form-control-custom" required placeholder="Ej: Reparación de computadoras">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🏷️ Categoría <span class="required-mark">*</span></label>
                                <input type="text" name="categoria" class="form-control-custom" required placeholder="Ej: Tecnología, Limpieza, etc.">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📝 Descripción <span class="required-mark">*</span></label>
                                <textarea name="descripcion" class="form-control-custom textarea-custom" required placeholder="Describe detalladamente tu servicio, qué incluye, en qué zonas atiende, horarios..."></textarea>
                                <div class="helper-text">Sé específico para atraer más clientes interesados en tus servicios</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📌 Ubicación / Ciudad</label>
                                <input type="text" name="ubicacion_ciudad" class="form-control-custom" placeholder="Ej: Lima">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">☎️ Teléfono de Contacto</label>
                                <input type="text" name="telefono_contacto" class="form-control-custom" placeholder="Teléfono de contacto">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🌐 Redes Sociales</label>
                                <input type="text" name="redes_sociales" class="form-control-custom" placeholder="URLs o redes sociales">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📧 Correo de Contacto</label>
                                <input type="email" name="correo_contacto" class="form-control-custom" placeholder="correo@contacto.com">
                            </div>

                            <div class="form-group-custom row-full">
                                <label class="form-label-custom">📍 Dirección de Atención</label>
                                <input type="text" name="direccion_atencion" class="form-control-custom" placeholder="Dirección donde se presta el servicio">
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">🖼️ Imagen del Servicio</label>
                                <input type="file" name="imagen_servicio" class="form-control-custom" accept="image/*">
                                <div class="helper-text">Opcional. Imagen representativa del servicio (max 5MB).</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">⏰ Horario de Atención</label>
                                <input type="text" name="horario_atencion" class="form-control-custom" placeholder="Ej: Lun-Vie 9:00-18:00">
                            </div>

                            <input type="hidden" name="fecha_inicio" value="{{ $fechaHoy->format('Y-m-d') }}">
                            <div class="form-group-custom row-full">
                                <div class="helper-text helper-text-strong">Fecha de inicio automática: {{ $fechaHoy->format('d/m/Y') }}</div>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label-custom">📅 Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control-custom" value="{{ old('fecha_fin', $fechaFinDefault->format('Y-m-d')) }}" min="{{ $fechaHoy->format('Y-m-d') }}" max="{{ $fechaFinMax->format('Y-m-d') }}">
                                <div class="helper-text">La fecha fin será dentro de 1 mes por defecto y como máximo hasta {{ $fechaFinMax->format('d/m/Y') }}.</div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-custom btn-back" onclick="volverAlPaso1()">
                                ← Atrás
                            </button>
                            <button type="submit" class="btn-custom btn-submit">
                                ✓ Registrar Servicio
                            </button>
                        </div>
                        <div class="helper-text helper-text-center">
                            ℹ️ Tu servicio será revisado por nuestro equipo. La aprobación puede demorar 1-2 días hábiles.
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarServicio() {
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
            document.getElementById('formServicio').style.display = 'block';
            document.getElementById('step1-indicator').classList.remove('active');
            document.getElementById('step2-indicator').classList.add('active');
            window.scrollTo(0, 0);
        }
    }

    function volverAlPaso1() {
        document.getElementById('formServicio').style.display = 'none';
        document.getElementById('formEmpresa').style.display = 'block';
        document.getElementById('step1-indicator').classList.add('active');
        document.getElementById('step2-indicator').classList.remove('active');
        window.scrollTo(0, 0);
    }
</script>

@endsection
