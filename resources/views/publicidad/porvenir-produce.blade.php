@extends('layouts.app')

@section('content')

<!-- Header Institucional -->
<header class="porvenir-header">
    <div class="header-container">
        <div class="header-left">
            <div class="logo-municipalidad">
                <div class="logo-circle">🏛️</div>
                <div class="logo-text">
                    <span class="logo-label">Municipalidad Distrital</span>
                    <span class="logo-name">El Porvenir</span>
                </div>
            </div>
        </div>
        <div class="header-divider"></div>
        <div class="header-right">
            <div class="logo-porvenir">
                <span class="porvenir-label">Porvenir</span>
                <span class="produce-label">Produce</span>
            </div>
        </div>
    </div>
</header>

<!-- Hero Principal -->
<div class="porvenir-hero">
    <div class="hero-background"></div>
    <div class="hero-overlay"></div>
    
    <div class="porvenir-content">
        <div class="porvenir-grid">
            <div class="porvenir-left">
                <div class="porvenir-copy">
                    <div class="badge-superior">🚀 BOLSA DE TRABAJO • PRODUCTOS • SERVICIOS</div>
                    <h1 class="porvenir-title">Porvenir Produce</h1>
                    <p class="porvenir-subtitle">
                        Conecta oportunidades de empleo, productos y servicios con un diseño profesional, claro y confiable.
                    </p>
                </div>

                <div class="porvenir-actions">
                    <a href="{{ url('/publicidad/bolsa-trabajo') }}" class="porvenir-card bolsa">
                        <div class="card-top">
                            <div class="card-icon"> 
                                <!-- icon -->
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6"></path>
                                    <path d="M23 11h-6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="card-divider"></div>
                        <div class="card-content">
                            <div class="card-label">Bolsa de Trabajo</div>
                            <div class="card-description">Encuentra vacantes y ofertas laborales verificadas.</div>
                        </div>
                        <div class="card-action">
                            <button class="btn-explorar">Explorar →</button>
                        </div>
                    </a>

                    <a href="{{ url('/publicidad/productos') }}" class="porvenir-card productos">
                        <div class="card-top">
                            <div class="card-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="card-divider"></div>
                        <div class="card-content">
                            <div class="card-label">Productos</div>
                            <div class="card-description">Explora el catálogo de productos disponibles.</div>
                        </div>
                        <div class="card-action">
                            <button class="btn-explorar">Explorar →</button>
                        </div>
                    </a>

                    <a href="{{ url('/publicidad/servicios') }}" class="porvenir-card servicios">
                        <div class="card-top">
                            <div class="card-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20M2 12h20"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                        </div>
                        <div class="card-divider"></div>
                        <div class="card-content">
                            <div class="card-label">Servicios</div>
                            <div class="card-description">Descubre proveedores y soluciones profesionales.</div>
                        </div>
                        <div class="card-action">
                            <button class="btn-explorar">Explorar →</button>
                        </div>
                    </a>
                    
                    
                </div>

                <div class="porvenir-stats">
                        <div class="stat-item empresas">
                            <div class="stat-value">+120</div>
                            <div class="stat-label">Empresas Registradas</div>
                        </div>
                        <div class="stat-item vacantes">
                            <div class="stat-value">+540</div>
                            <div class="stat-label">Vacantes Publicadas</div>
                        </div>
                        <div class="stat-item productos">
                            <div class="stat-value">+320</div>
                            <div class="stat-label">Productos Disponibles</div>
                        </div>
                        <div class="stat-item servicios">
                            <div class="stat-value">+180</div>
                            <div class="stat-label">Servicios Ofrecidos</div>
                        </div>
                </div>

                <div class="porvenir-footer">
                    <p>
                        Accede a oportunidades reales de la región, diseñadas para empresas, emprendedores y buscadores de empleo.
                    </p>
                </div>
            </div>

            <div class="porvenir-right">
                <div class="hero-image" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</div>

@endsection
