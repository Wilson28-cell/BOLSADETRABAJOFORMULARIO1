@extends('layouts.app')

@section('title', 'Postular')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-5">
            <h2 class="fw-bold mb-4">Formulario de Postulación</h2>
            <div class="alert alert-primary">Postulando a: <strong>{{ $oferta->titulo_puesto }}</strong></div>

            <form action="{{ url('guardar-postulacion/'.$oferta->id_publica) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="fw-bold">Nombres <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Máximo 150 caracteres</small>
                        <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan" maxlength="150" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Apellidos <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Máximo 150 caracteres</small>
                        <input type="text" name="apellidos" class="form-control" placeholder="Ej: Pérez García" maxlength="150" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">DNI <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: 8-12 dígitos sin puntos ni guiones</small>
                        <input type="text" name="dni" class="form-control" placeholder="Ej: 12345678" pattern="[0-9]{8,12}" maxlength="12" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Correo válido (ej: usuario@dominio.com)</small>
                        <input type="email" name="correo_electronico" class="form-control" placeholder="usuario@example.com" maxlength="150" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Teléfono <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Mínimo 7 dígitos</small>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej: 987654321" pattern="[0-9]{7,20}" maxlength="20" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Dirección <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Dirección completa (calle, número, distrito)</small>
                        <input type="text" name="direccion" class="form-control" placeholder="Ej: Calle Principal 123, Lima" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Fecha Nacimiento <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Debe ser mayor de edad (18 años)</small>
                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Género <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Seleccione una opción</small>
                        <select name="genero" class="form-select" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold">Mensaje de Postulación</label>
                        <small class="d-block text-muted mb-2">Opcional: Cuéntanos por qué eres el candidato ideal para este puesto. Máximo 500 caracteres.</small>
                        <textarea name="mensaje_postulacion" class="form-control" rows="4" placeholder="Comparte tus habilidades y experiencia relevante..." maxlength="500"></textarea>
                        <small class="text-muted">0/500 caracteres</small>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold">Currículum PDF <span class="text-danger">*</span></label>
                        <small class="d-block text-muted mb-2">Requisito: Solo archivos PDF. Tamaño máximo: 5MB. Incluye experiencia, educación y habilidades.</small>
                        <input type="file" name="curriculum_pdf" class="form-control" accept=".pdf" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary px-5">Enviar Postulación</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="mensaje_postulacion"]');
        if (textarea) {
            const countDisplay = textarea.parentElement.querySelector('small.text-muted');
            textarea.addEventListener('input', function() {
                if (countDisplay) {
                    countDisplay.textContent = this.value.length + '/500 caracteres';
                }
            });
        }
    });
</script>
@endpush
@endsection