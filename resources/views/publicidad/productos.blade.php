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
            <select name="categoria" class="form-control">
                <option value="">Todas</option>
                <option value="Alimentos y Bebidas" {{ request('categoria') === 'Alimentos y Bebidas' ? 'selected' : '' }}>Alimentos y Bebidas</option>
                <option value="Moda y Calzado" {{ request('categoria') === 'Moda y Calzado' ? 'selected' : '' }}>Moda y Calzado</option>
                <option value="Tecnología y Electrónica" {{ request('categoria') === 'Tecnología y Electrónica' ? 'selected' : '' }}>Tecnología y Electrónica</option>
                <option value="Cuidado Personal y Belleza" {{ request('categoria') === 'Cuidado Personal y Belleza' ? 'selected' : '' }}>Cuidado Personal y Belleza</option>
                <option value="Hogar y Decoración" {{ request('categoria') === 'Hogar y Decoración' ? 'selected' : '' }}>Hogar y Decoración</option>
                <option value="Salud y Bienestar" {{ request('categoria') === 'Salud y Bienestar' ? 'selected' : '' }}>Salud y Bienestar</option>
                <option value="Juguetes y Niños" {{ request('categoria') === 'Juguetes y Niños' ? 'selected' : '' }}>Juguetes y Niños</option>
                <option value="Deportes y Aire Libre" {{ request('categoria') === 'Deportes y Aire Libre' ? 'selected' : '' }}>Deportes y Aire Libre</option>
                <option value="Limpieza y Mascotas" {{ request('categoria') === 'Limpieza y Mascotas' ? 'selected' : '' }}>Limpieza y Mascotas</option>
                <option value="Oficina y Papelería" {{ request('categoria') === 'Oficina y Papelería' ? 'selected' : '' }}>Oficina y Papelería</option>
            </select>
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
