@extends('layouts.app')

@section('content')

<div class="publicidad-page">
    <div class="hero-banner compact-hero">
        <div class="hero-content">
            <span class="badge-custom">Productos</span>
            <h1>Catálogo de productos destacados</h1>
            <p>Explora ofertas de productos con la misma identidad premium de Porvenir Produce.</p>
        </div>
    </div>

<div class="section-heading text-center">
    <span class="section-label">Productos disponibles</span>
</div>
<div class="filters-container mb-4">
    <form id="productos-filters" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="search" name="q" class="form-control" placeholder="Buscar productos, empresa o descripción" value="{{ request('q') }}">
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

<div id="productos-container">
    @include('publicidad.partials.productos-list')
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('productos-filters');
    const container = document.getElementById('productos-container');

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
