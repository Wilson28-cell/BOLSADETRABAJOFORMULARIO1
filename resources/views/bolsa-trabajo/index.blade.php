@extends('layouts.app')

@section('title', 'Bolsa de Trabajo')

@section('content')
<section class="hero-page text-white" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('fondoPrincipal/fondo.jpg') }}');">
    <div class="container text-center hero-content">
        <h1 class="fw-bold display-4">El Porvenir Produce</h1>
        <p class="fs-5 mt-3">Encuentra oportunidades laborales, productos y servicios.</p>
        <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/') }}" class="btn btn-light btn-lg rounded-4">Bolsa de Trabajo</a>
            <a href="{{ url('registro/productos') }}" class="btn btn-outline-light btn-lg rounded-4">Productos</a>
            <a href="{{ url('registro/servicios') }}" class="btn btn-outline-light btn-lg rounded-4">Servicios</a>
        </div>
    </div>
</section>

<div class="container py-5">
    <form method="GET">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control rounded-4" placeholder="Buscar empleo..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="modalidad" class="form-select rounded-4">
                            <option value="">Todas las modalidades</option>
                            <option value="Presencial" {{ request('modalidad') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="Virtual" {{ request('modalidad') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                            <option value="Hibrido" {{ request('modalidad') == 'Hibrido' ? 'selected' : '' }}>Hibrido</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select rounded-4">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 rounded-4">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @foreach($ofertas as $oferta)
            <div class="col">
                <div class="card card-trabajo h-100">
                    @php
                        $imgExt = !empty($oferta->imagen_trabajo) ? strtolower(pathinfo($oferta->imagen_trabajo, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($oferta->imagen_trabajo) && in_array($imgExt, $imgAllowed))
                        <div class="card-header-img mb-2">
                            <img src="{{ asset($oferta->imagen_trabajo) }}" class="img-fluid" alt="Imagen de la oferta">
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <h5 class="titulo-oferta mb-1">{{ $oferta->titulo_puesto }}</h5>
                            <p class="empresa-nombre mb-2">{{ $oferta->nombre_empresa ?? 'N/A' }}</p>
                            <div class="badges-container">
                                <span class="badge-custom badge-modalidad">{{ $oferta->modalidad }}</span>
                                <span class="badge-custom badge-categoria">{{ $oferta->categoria }}</span>
                            </div>
                        </div>
                        <p class="descripcion-oferta mb-3">{{ Str::limit($oferta->descripcion_puesto, 100) }}</p>
                        @php
                            $fechaLimite = \Carbon\Carbon::parse($oferta->fecha_limite_postulacion);
                            $ahora = \Carbon\Carbon::now();
                            if ($ahora->greaterThan($fechaLimite)) {
                                $tiempoRestante = 'Publicación vencida';
                                $tiempoClass = 'tiempo-vencido';
                            } else {
                                $segundosRestantes = $fechaLimite->getTimestamp() - $ahora->getTimestamp();
                                $dias = (int) floor($segundosRestantes / 86400);
                                $horas = (int) floor(($segundosRestantes % 86400) / 3600);
                                if ($dias > 0) {
                                    $tiempoRestante = 'Quedan ' . $dias . ' día' . ($dias === 1 ? '' : 's');
                                    if ($horas > 0) {
                                        $tiempoRestante .= ' y ' . $horas . ' hora' . ($horas === 1 ? '' : 's');
                                    }
                                } else {
                                    $tiempoRestante = 'Quedan ' . $horas . ' hora' . ($horas === 1 ? '' : 's');
                                }
                                $tiempoClass = 'tiempo-activo';
                            }
                        @endphp
                        <div class="info-footer mt-auto">
                            <span class="ubicacion-badge">{{ $oferta->ubicacion }}</span>
                            <span class="tiempo-badge {{ $tiempoClass }}">{{ $tiempoRestante }}</span>
                            <span class="salario-badge">S/ {{ number_format($oferta->salario ?? 0, 2) }}</span>
                        </div>
                        <a href="{{ url('detalle-oferta/'.$oferta->id_aprobado) }}" class="btn btn-outline-primary w-100 mt-4">Ver detalle</a>
                        <a href="{{ url('postular/'.$oferta->id_aprobado) }}" class="btn btn-primary w-100 mt-2">Postular</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
