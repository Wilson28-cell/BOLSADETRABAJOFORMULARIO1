@extends('layouts.app')

@section('content')

<div class="publicidad-page">
    <div class="hero-banner compact-hero">
        <div class="hero-content">
            <span class="badge-custom">Bolsa de Trabajo</span>
            <h1>Conecta con empleos cerca de ti</h1>
            <p>Oportunidades laborales verificadas con el sello premium de Porvenir Produce.</p>
        </div>
    </div>

    @php
    $estadoSeleccionado = request('estado', 'publicadas');
    $query = request()->except('estado');
    $baseUrl = url('/publicidad/bolsa-trabajo');
    $publicadasUrl = $baseUrl . (count($query) ? '?' . http_build_query(array_merge($query, ['estado' => 'publicadas'])) : '?estado=publicadas');
    $vencidasUrl = $baseUrl . (count($query) ? '?' . http_build_query(array_merge($query, ['estado' => 'vencidas'])) : '?estado=vencidas');
    $tituloSeccion = $estadoSeleccionado === 'vencidas' ? 'Ofertas vencidas' : 'Ofertas publicadas';
    $descripcionSeccion = $estadoSeleccionado === 'vencidas'
        ? 'Estas ofertas ya han pasado su fecha límite de postulación.'
        : 'Estas ofertas están abiertas y disponibles para postulación.';
@endphp

<div class="section-heading text-center">
    <span class="section-label">Ofertas laborales activas</span>
</div>

@if($ofertas->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>{{ $estadoSeleccionado === 'vencidas' ? 'No hay ofertas vencidas' : 'No hay ofertas disponibles' }}</h3>
        <p>
            {{ $estadoSeleccionado === 'vencidas'
                ? 'No hay publicaciones vencidas en este momento.'
                : 'En este momento no hay publicaciones activas de bolsa de trabajo. Vuelve pronto para ver nuevas oportunidades.'
            }}
        </p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4">
        @foreach($ofertas as $oferta)
            <div class="col">
                <div class="card card-trabajo">
                    @php
                        $imgExt = !empty($oferta->imagen_trabajo) ? strtolower(pathinfo($oferta->imagen_trabajo, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($oferta->imagen_trabajo) && in_array($imgExt, $imgAllowed))
                        <div class="card-header-img">
                            <img src="{{ asset($oferta->imagen_trabajo) }}" alt="Imagen de la oferta">
                        </div>
                    @endif

                    <div class="card-body">
                        @php
                            $isVencida = \Carbon\Carbon::parse($oferta->fecha_limite_postulacion)->isPast();
                        @endphp
                        <div class="mb-2">
                            <span class="badge {{ $isVencida ? 'bg-danger' : 'bg-success' }} text-white px-3 py-2">
                                {{ $isVencida ? 'Vencida' : 'Activa' }}
                            </span>
                        </div>
                        <h5 class="titulo-oferta">{{ $oferta->titulo_puesto }}</h5>
                        <p class="empresa-nombre">{{ $oferta->nombre_empresa ?? 'Empresa' }}</p>

                        <div class="badges-container">
                            <span class="badge-custom badge-modalidad">{{ $oferta->modalidad }}</span>
                            <span class="badge-custom badge-categoria">{{ $oferta->categoria }}</span>
                        </div>

                        <p class="descripcion-oferta">
                            {{ Str::limit($oferta->descripcion_puesto, 120) }}
                        </p>

                        <div class="info-footer">
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
                                        $tiempoRestante = $dias . ' día' . ($dias === 1 ? '' : 's');
                                        if ($horas > 0) {
                                            $tiempoRestante .= ' ' . $horas . 'h';
                                        }
                                    } else {
                                        $tiempoRestante = $horas . ' hora' . ($horas === 1 ? '' : 's');
                                    }
                                    $tiempoClass = 'tiempo-activo';
                                }
                            @endphp
                            <span class="ubicacion-badge">📍 {{ $oferta->ubicacion }}</span>
                            <span class="tiempo-badge {{ $tiempoClass }}">⏰ {{ $tiempoRestante }}</span>
                            <span class="salario-badge">S/ {{ number_format($oferta->salario ?? 0, 2) }}</span>
                        </div>

                        <a href="{{ url('detalle-oferta/'.$oferta->id_aprobado) }}" class="btn-detalle">Ver Detalles →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
</div>

@endsection