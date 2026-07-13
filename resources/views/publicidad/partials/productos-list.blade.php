@if($productos->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <h3>No hay productos disponibles</h3>
        <p>En este momento no hay productos publicados. Vuelve pronto para ver nuevas ofertas.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4">
        @foreach($productos as $producto)
            <div class="col">
                <div class="card card-producto">
                    @php
                        $imgExt = !empty($producto->imagen_producto) ? strtolower(pathinfo($producto->imagen_producto, PATHINFO_EXTENSION)) : null;
                        $imgAllowed = ['jpg','jpeg','png','gif','webp','svg'];
                    @endphp
                    @if(!empty($producto->imagen_producto) && in_array($imgExt, $imgAllowed))
                        <div class="card-header-img">
                            <img src="{{ asset($producto->imagen_producto) }}" alt="Imagen del producto">
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="titulo-producto">{{ $producto->nombre_producto }}</h5>
                        <p class="empresa-nombre">{{ $producto->nombre_empresa ?? 'Empresa' }}</p>

                        <div class="badges-container">
                            <span class="badge-custom badge-categoria">{{ $producto->categoria }}</span>
                        </div>

                        <p class="descripcion-producto">
                            {{ Str::limit($producto->descripcion, 120) }}
                        </p>

                        <div class="info-footer">
                            @php
                                $fechaFin = \Carbon\Carbon::parse($producto->fecha_fin);
                                $ahora = \Carbon\Carbon::now();
                                if ($ahora->greaterThan($fechaFin)) {
                                    $tiempoRestante = 'Vencido';
                                    $tiempoClass = 'tiempo-vencido';
                                } else {
                                    $segundosRestantes = $fechaFin->getTimestamp() - $ahora->getTimestamp();
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
                            <span class="contacto-badge">📧 {{ Str::limit($producto->correo_contacto, 30) }}</span>
                            <span class="tiempo-badge {{ $tiempoClass }}">⏰ {{ $tiempoRestante }}</span>
                        </div>

                        <a href="{{ url('publicidad/productos/'.$producto->id_publico_producto) }}" class="btn-detalle">Ver Detalles →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
