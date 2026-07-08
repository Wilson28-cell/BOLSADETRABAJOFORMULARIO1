@extends('layouts.app')

@section('title', $producto->nombre_producto ?? 'Detalle de producto')

@section('content')
@php
    use Illuminate\Support\Str;

    $productName = trim((string) ($producto->nombre_producto ?? ''));
    $companyName = trim((string) ($producto->nombre_empresa ?? ''));
    $category = trim((string) ($producto->categoria ?? ''));
    $description = trim((string) ($producto->descripcion ?? ''));

    $rawImages = $producto->imagen_producto ?? null;
    $images = [];
    if (!empty($rawImages)) {
        if (is_array($rawImages)) {
            $images = array_values(array_filter(array_map(static fn ($item) => is_string($item) ? trim($item) : '', $rawImages)));
        } elseif (is_string($rawImages)) {
            $decoded = json_decode($rawImages, true);
            if (is_array($decoded)) {
                $images = array_values(array_filter(array_map(static fn ($item) => is_string($item) ? trim($item) : '', $decoded)));
            } else {
                $parts = preg_split('/\s*(?:,|;|\|)\s*/', trim($rawImages));
                $images = array_values(array_filter($parts, static fn ($item) => trim((string) $item) !== ''));
            }
        }
    }

    $imageUrls = [];
    $buildImageUrl = static function ($path) {
        $value = trim((string) $path);
        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://', 'data:image/'])) {
            return $value;
        }

        return asset($value);
    };

    foreach ($images as $image) {
        $url = $buildImageUrl($image);
        if ($url) {
            $imageUrls[] = $url;
        }
    }

    $primaryImage = $imageUrls[0] ?? null;
    $galleryImages = array_values($imageUrls);
    $hasGallery = count($galleryImages) > 1;

    $rawSocial = $producto->redes_sociales ?? null;
    $socialLinks = [];
    if (!empty($rawSocial)) {
        $socialEntries = is_array($rawSocial)
            ? $rawSocial
            : preg_split('/\r\n|\r|\n|,|;/', trim((string) $rawSocial));

        foreach ($socialEntries as $entry) {
            $value = trim((string) $entry);
            if ($value === '') {
                continue;
            }

            $lowerValue = mb_strtolower($value);
            if (Str::contains($lowerValue, 'facebook')) {
                $socialLinks[] = [
                    'label' => 'Facebook',
                    'url' => Str::startsWith($value, ['http://', 'https://']) ? $value : 'https://facebook.com/' . ltrim($value, '/'),
                ];
            } elseif (Str::contains($lowerValue, 'instagram')) {
                $socialLinks[] = [
                    'label' => 'Instagram',
                    'url' => Str::startsWith($value, ['http://', 'https://']) ? $value : 'https://instagram.com/' . ltrim($value, '/'),
                ];
            } elseif (Str::contains($lowerValue, 'tiktok')) {
                $socialLinks[] = [
                    'label' => 'TikTok',
                    'url' => Str::startsWith($value, ['http://', 'https://']) ? $value : 'https://www.tiktok.com/@' . ltrim($value, '/'),
                ];
            } elseif (Str::contains($lowerValue, 'web') || Str::contains($lowerValue, '.com') || Str::contains($lowerValue, '.pe') || Str::contains($lowerValue, '.org') || Str::contains($lowerValue, 'www.')) {
                $socialLinks[] = [
                    'label' => 'Página web',
                    'url' => Str::startsWith($value, ['http://', 'https://']) ? $value : 'https://' . ltrim($value, '/'),
                ];
            }
        }
    }

    $detailItems = [];
    $appendDetailItem = static function ($field, $label, $icon, $formatter = null) use (&$detailItems, $producto) {
        $value = data_get($producto, $field);
        if ($value === null) {
            return;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return;
        }

        $displayValue = $formatter ? $formatter($value) : $value;
        $detailItems[] = ['label' => $label, 'icon' => $icon, 'value' => $displayValue];
    };

    $appendDetailItem('ubicacion_ciudad', 'Ubicación', 'map-pin');
    $appendDetailItem('horario_atencion', 'Horario de atención', 'clock');
    $appendDetailItem('metodos_pago', 'Métodos de pago', 'credit-card');
    $appendDetailItem('cobertura', 'Cobertura', 'globe');
    $appendDetailItem('fecha_publicacion', 'Fecha de publicación', 'calendar', static fn ($value) => \Carbon\Carbon::parse($value)->format('d/m/Y'));
    $appendDetailItem('visitas', 'Número de visitas', 'eye');

    $contactEmail = trim((string) ($producto->correo_contacto ?? $producto->correo_electronico ?? ''));
    $contactPhone = trim((string) ($producto->telefono_contacto ?? $producto->telefono ?? ''));

    $whatsappValue = trim((string) (data_get($producto, 'whatsapp') ?? data_get($producto, 'whatsapp_contacto') ?? data_get($producto, 'numero_whatsapp') ?? data_get($producto, 'whatsapp_numero') ?? ''));
    if ($whatsappValue === '' && $contactPhone !== '') {
        $whatsappValue = $contactPhone;
    }

    $whatsappUrl = '';
    if ($whatsappValue !== '') {
        $whatsappUrl = 'https://wa.me/' . rawurlencode(str_replace([' ', '-', '(', ')', '+'], '', $whatsappValue));
    }
@endphp

<div class="service-detail-shell">
    <div class="container py-5">
        <a href="{{ url('publicidad/productos') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 mb-4">
            <span class="me-2">←</span> Volver a productos
        </a>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card service-card main-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                            <div>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge service-badge">Plataforma de productos</span>
                                    @if($category !== '')
                                        <span class="badge text-bg-light text-primary rounded-pill px-3 py-2">{{ e($category) }}</span>
                                    @endif
                                </div>
                                <h1 class="service-title mb-2">{{ $productName !== '' ? e($productName) : 'Producto sin nombre' }}</h1>
                                <p class="service-subtitle mb-0">{{ $companyName !== '' ? e($companyName) : 'Empresa no registrada' }}</p>
                            </div>
                        </div>

                        @if($primaryImage)
                            <div class="gallery-card mb-4">
                                @if($hasGallery)
                                    <div id="gallery-stage" class="gallery-stage" data-images='@json($galleryImages)'>
                                        <img id="gallery-main-image" src="{{ $primaryImage }}" alt="{{ e($productName) }}">
                                        <button type="button" class="gallery-nav prev" id="gallery-prev" aria-label="Anterior">
                                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                        </button>
                                        <button type="button" class="gallery-nav next" id="gallery-next" aria-label="Siguiente">
                                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="gallery-thumbnails mt-3">
                                        @foreach($galleryImages as $index => $image)
                                            <button type="button" class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" data-gallery-thumb="{{ $index }}" aria-label="Ver imagen {{ $index + 1 }}">
                                                <img src="{{ $image }}" alt="Miniatura {{ $index + 1 }}">
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="gallery-stage single-image">
                                        <img src="{{ $primaryImage }}" alt="{{ e($productName) }}">
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="gallery-placeholder mb-4">
                                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v10H4z"/><path d="M4 17l4-4 3 3 4-5 5 6"/></svg>
                                <p class="mb-0">Este producto aún no tiene imágenes registradas.</p>
                            </div>
                        @endif

                        @if($description !== '')
                            <div class="section-block">
                                <h2 class="section-title">Descripción</h2>
                                <p class="section-copy">{!! nl2br(e($description)) !!}</p>
                            </div>
                        @endif

                        @if(!empty($detailItems))
                            <div class="section-block">
                                <h2 class="section-title">Información adicional</h2>
                                <div class="row g-3">
                                    @foreach($detailItems as $detail)
                                        <div class="col-12 col-md-6">
                                            <div class="info-pill">
                                                <div class="info-icon">
                                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                                                        @switch($detail['icon'])
                                                            @case('map-pin')
                                                                <path d="M12 21s-6-5.4-6-10a6 6 0 1 1 12 0c0 4.6-6 10-6 10z"/><circle cx="12" cy="11" r="2.5"/>
                                                                @break
                                                            @case('clock')
                                                                <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                                                                @break
                                                            @case('credit-card')
                                                                <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/>
                                                                @break
                                                            @case('globe')
                                                                <circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18"/><path d="M12 3a15 15 0 0 0 0 18"/>
                                                                @break
                                                            @case('calendar')
                                                                <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                                                                @break
                                                            @default
                                                                <path d="M12 4v16"/><path d="M4 12h16"/>
                                                        @endswitch
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="info-label mb-1">{{ $detail['label'] }}</p>
                                                    <p class="info-value mb-0">{{ $detail['value'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card service-card sidebar-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <p class="text-uppercase small text-muted mb-2">Información de contacto</p>
                            <h3 class="sidebar-title mb-0">{{ $companyName !== '' ? e($companyName) : 'Empresa' }}</h3>
                        </div>

                        @if($companyName !== '')
                            <div class="contact-row">
                                <div class="contact-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M3 7l9 6 9-6"/></svg>
                                </div>
                                <div>
                                    <p class="contact-label">Empresa</p>
                                    <p class="contact-value mb-0">{{ e($companyName) }}</p>
                                </div>
                            </div>
                        @endif

                        @if($contactEmail !== '')
                            <a href="mailto:{{ $contactEmail }}" class="contact-link">
                                <div class="contact-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16v12H4z"/><path d="m4 8 8 6 8-6"/></svg>
                                </div>
                                <div>
                                    <p class="contact-label">Correo electrónico</p>
                                    <p class="contact-value mb-0">{{ e($contactEmail) }}</p>
                                </div>
                            </a>
                        @endif

                        @if($contactPhone !== '')
                            <a href="tel:{{ e($contactPhone) }}" class="contact-link">
                                <div class="contact-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h4l2 5-2.5 1.5a16 16 0 0 0 6.5 6.5L14 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 7a2 2 0 0 1 2-2"/></svg>
                                </div>
                                <div>
                                    <p class="contact-label">Teléfono</p>
                                    <p class="contact-value mb-0">{{ e($contactPhone) }}</p>
                                </div>
                            </a>
                        @endif

                        @if($category !== '')
                            <div class="contact-row">
                                <div class="contact-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16"/><path d="M7 3h10"/><path d="M6 11h12"/><path d="M5 15h14"/><path d="M3 19h18"/></svg>
                                </div>
                                <div>
                                    <p class="contact-label">Categoría</p>
                                    <p class="contact-value mb-0">{{ e($category) }}</p>
                                </div>
                            </div>
                        @endif

                        @if(!empty($socialLinks) || $whatsappUrl !== '' || $contactEmail !== '')
                            <div class="mt-4">
                                <p class="text-uppercase small text-muted mb-3">Redes sociales</p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($socialLinks as $social)
                                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                            <span>{{ $social['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($whatsappUrl !== '')
                                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="social-link">
                                            <span>WhatsApp</span>
                                        </a>
                                    @endif
                                    @if($contactEmail !== '')
                                        <a href="mailto:{{ $contactEmail }}" class="social-link">
                                            <span>Correo</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($whatsappUrl !== '')
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn whatsapp-btn mt-4">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 19l1.2-4.3a8 8 0 1 1 3.2 3.2z"/><path d="M14 15l-1.7-1-2.8 1.2-1.1-1.3 2.2-2.5-2.1-2.1-1.5 1.2-1.4-1.4 2.8-2.8 5.4 5.4-2 2.4z"/></svg>
                                Contactar por WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="institutional-footer mt-4">
            <p class="mb-0">Este producto pertenece a la Plataforma de Promoción de Productos y Servicios de la Municipalidad Distrital de El Porvenir.</p>
        </div>
    </div>
</div>

<style>
    .service-detail-shell {
        background: linear-gradient(135deg, #f6fbff 0%, #f8fafc 100%);
        min-height: 100%;
    }

    .service-card {
        border: 0;
        border-radius: 28px;
        box-shadow: 0 18px 45px rgba(15, 76, 129, 0.08);
        background: #ffffff;
    }

    .service-badge {
        background: #e8f2ff;
        color: #1d4ed8;
        border-radius: 999px;
        padding: 0.55rem 0.9rem;
        font-weight: 600;
    }

    .service-title {
        font-size: clamp(1.7rem, 3.1vw, 2.5rem);
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
    }

    .service-subtitle {
        color: #475569;
        font-size: 1.05rem;
    }

    .gallery-card {
        border-radius: 24px;
        overflow: hidden;
        background: #f8fbff;
        padding: 1rem;
    }

    .gallery-stage {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        min-height: 320px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.95);
        border: 0;
        border-radius: 999px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        transition: transform 0.2s ease;
    }

    .gallery-nav:hover {
        transform: translateY(-50%) scale(1.04);
    }

    .gallery-nav.prev {
        left: 1rem;
    }

    .gallery-nav.next {
        right: 1rem;
    }

    .gallery-thumbnails {
        display: flex;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .gallery-thumb {
        width: 72px;
        height: 72px;
        padding: 0;
        border: 2px solid transparent;
        border-radius: 14px;
        overflow: hidden;
        background: transparent;
        transition: all 0.2s ease;
    }

    .gallery-thumb.active {
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-placeholder {
        border-radius: 24px;
        min-height: 260px;
        display: grid;
        place-items: center;
        text-align: center;
        color: #64748b;
        background: linear-gradient(135deg, #f8fbff 0%, #edf4ff 100%);
        border: 1px dashed #bfdbfe;
    }

    .section-block {
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 1.5rem;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.85rem;
    }

    .section-copy {
        color: #475569;
        line-height: 1.75;
        margin-bottom: 0;
    }

    .info-pill {
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fcfdff;
    }

    .info-icon,
    .contact-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #eff6ff;
        color: #2563eb;
        flex-shrink: 0;
    }

    .info-label,
    .contact-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.2rem;
    }

    .info-value,
    .contact-value {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0;
        line-height: 1.45;
    }

    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .contact-row,
    .contact-link {
        display: flex;
        gap: 0.8rem;
        align-items: flex-start;
        padding: 0.9rem 0.95rem;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 0.8rem;
        color: #0f172a;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .contact-link:hover {
        transform: translateY(-2px);
        border-color: #93c5fd;
        background: #f0f7ff;
        color: #0f172a;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.7rem 0.95rem;
        border-radius: 999px;
        background: #f8fafc;
        color: #0f172a;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .social-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .whatsapp-btn {
        width: 100%;
        justify-content: center;
        gap: 0.55rem;
        background: #25D366;
        color: #ffffff;
        border-radius: 999px;
        padding: 0.85rem 1rem;
        font-weight: 700;
        box-shadow: 0 10px 24px rgba(37, 211, 102, 0.24);
    }

    .whatsapp-btn:hover {
        color: #ffffff;
        background: #1fb95a;
    }

    .institutional-footer {
        border-radius: 24px;
        padding: 1rem 1.25rem;
        background: linear-gradient(90deg, #0f4c81 0%, #2563eb 100%);
        color: #ffffff;
        text-align: center;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .gallery-stage {
            min-height: 250px;
        }

        .gallery-thumb {
            width: 60px;
            height: 60px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stage = document.getElementById('gallery-stage');
        if (!stage) {
            return;
        }

        const images = JSON.parse(stage.dataset.images || '[]');
        if (!images.length) {
            return;
        }

        const mainImage = document.getElementById('gallery-main-image');
        const prevButton = document.getElementById('gallery-prev');
        const nextButton = document.getElementById('gallery-next');
        const thumbs = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
        let currentIndex = 0;

        const renderImage = function (index) {
            currentIndex = (index + images.length) % images.length;
            mainImage.src = images[currentIndex];
            thumbs.forEach((thumb, thumbIndex) => {
                thumb.classList.toggle('active', thumbIndex === currentIndex);
            });
        };

        prevButton?.addEventListener('click', function () {
            renderImage(currentIndex - 1);
        });

        nextButton?.addEventListener('click', function () {
            renderImage(currentIndex + 1);
        });

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', function () {
                renderImage(Number(thumb.dataset.galleryThumb || 0));
            });
        });
    });
</script>
@endsection