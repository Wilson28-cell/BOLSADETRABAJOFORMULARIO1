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
            <select name="categoria" class="form-control">
                <option value="">Todas</option>
                <option value="Servicios Profesionales" {{ request('categoria') === 'Servicios Profesionales' ? 'selected' : '' }}>Servicios Profesionales</option>
                <option value="Servicios de Transporte" {{ request('categoria') === 'Servicios de Transporte' ? 'selected' : '' }}>Servicios de Transporte</option>
                <option value="Servicios de Salud y Bienestar" {{ request('categoria') === 'Servicios de Salud y Bienestar' ? 'selected' : '' }}>Servicios de Salud y Bienestar</option>
                <option value="Servicios Financieros" {{ request('categoria') === 'Servicios Financieros' ? 'selected' : '' }}>Servicios Financieros</option>
                <option value="Servicios de Telecomunicaciones" {{ request('categoria') === 'Servicios de Telecomunicaciones' ? 'selected' : '' }}>Servicios de Telecomunicaciones</option>
                <option value="Servicios de Mantenimiento y Reparación" {{ request('categoria') === 'Servicios de Mantenimiento y Reparación' ? 'selected' : '' }}>Servicios de Mantenimiento y Reparación</option>
                <option value="Servicios de Limpieza y Domésticos" {{ request('categoria') === 'Servicios de Limpieza y Domésticos' ? 'selected' : '' }}>Servicios de Limpieza y Domésticos</option>
                <option value="Servicios de Hostelería y Turismo" {{ request('categoria') === 'Servicios de Hostelería y Turismo' ? 'selected' : '' }}>Servicios de Hostelería y Turismo</option>
                <option value="Servicios de Alimentación" {{ request('categoria') === 'Servicios de Alimentación' ? 'selected' : '' }}>Servicios de Alimentación</option>
                <option value="Servicios Públicos" {{ request('categoria') === 'Servicios Públicos' ? 'selected' : '' }}>Servicios Públicos</option>
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
