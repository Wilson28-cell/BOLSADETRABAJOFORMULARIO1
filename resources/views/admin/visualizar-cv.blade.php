@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">Visualizar Currículum</h2>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-1"><strong>Postulación:</strong> #{{ $cv->id_postulacion }}</p>
                <p class="mb-0"><strong>Archivo:</strong> {{ basename($cv->curriculum_pdf) }}</p>
            </div>
            <a href="{{ url('admin/postulante/descargar/'.$cv->id_postulacion) }}" class="btn btn-success btn-sm">
                Descargar documento
            </a>
        </div>

        <div style="height: 80vh; min-height: 500px;">
            <iframe
                src="{{ url('admin/postulante/cv/'.$cv->id_postulacion) }}"
                width="100%"
                height="100%"
                style="border: 1px solid #ddd; border-radius: 8px;"
            >
                Este navegador no soporta ver PDF en línea.
            </iframe>
        </div>
    </div>
</div>

@endsection