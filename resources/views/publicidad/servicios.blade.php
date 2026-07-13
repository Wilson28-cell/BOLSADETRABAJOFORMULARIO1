@extends('layouts.app')

@section('content')

<div class="publicidad-page">
    <div class="hero-banner compact-hero">
        <div class="hero-content">
            <span class="badge-custom">Servicios</span>
            <h1>Proveedores profesionales cerca de ti</h1>
            <p>Encuentra servicios confiables con la presentación visual institucional de Porvenir Produce.</p>
        </div>
    </div>

<div class="section-heading text-center">
    <span class="section-label">Servicios disponibles</span>
</div>
<div class="filters-container mb-4">
    <form id="servicios-filters" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="search" name="q" class="form-control" placeholder="Buscar servicios, empresa o descripción" value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="categoria" class="form-control" placeholder="Categoría" value="{{ request('categoria') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="ubicacion_ciudad" class="form-control" placeholder="Ubicación / Ciudad" value="{{ request('ubicacion_ciudad') }}">
        </div>
        <div class="col-md-1 d-grid">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
</div>

<div id="servicios-container">
    @include('publicidad.partials.servicios-list')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('servicios-filters');
    const container = document.getElementById('servicios-container');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const data = new FormData(form);
        const params = new URLSearchParams();
        for (const pair of data.entries()) {
            if (pair[1]) params.append(pair[0], pair[1]);
        }

        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json()).then(json => {
            if (json.html !== undefined) {
                container.innerHTML = json.html;
            }
        }).catch(err => console.error(err));
    });
});
</script>
</div>

@endsection
