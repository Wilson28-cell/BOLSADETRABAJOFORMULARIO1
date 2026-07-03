<script id="dashboardStateData" type="application/json">@json(['charts' => $charts])</script>

<div class="dashboard-panel mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <h4 class="dashboard-title mb-2">Bolsa de Trabajo — Indicadores</h4>
            <p class="dashboard-subtitle mb-0">Análisis de movilidad laboral con filtros, KPIs y visualizaciones ejecutivas.</p>
        </div>
        <div class="dashboard-badges d-flex flex-wrap gap-2">
            <span class="badge badge-pill badge-surface">Analista</span>
            <span class="badge badge-pill badge-surface">Últimos 12 meses</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="filter-card p-4">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
                <div>
                    <h5 class="filter-card-title mb-1">Filtros</h5>
                    <p>Refina la vista por empresa, estado, categoría, modalidad y rango de fechas.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button id="dashboardResetFilters" type="button" class="btn btn-outline-gray">Limpiar filtros</button>
                    <button form="dashboardFiltersForm" type="submit" class="btn btn-primary">Aplicar filtros</button>
                </div>
            </div>

            <form id="dashboardFiltersForm" method="GET" action="{{ url('admin/indicadores-bolsa') }}">
                <div class="row gx-3 gy-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Empresa</label>
                        <input type="text" name="empresa" value="{{ $filters['empresa'] }}" class="form-control form-control-solid" placeholder="Filtrar por empresa" />
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Estado</label>
                        <select name="estado" class="form-select form-select-solid">
                            <option value="">Todos</option>
                            <option value="activa" {{ $filters['estado'] === 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="finalizada" {{ $filters['estado'] === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            <option value="vencida" {{ $filters['estado'] === 'vencida' ? 'selected' : '' }}>Vencida</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Categoría</label>
                        <select name="categoria" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ $filters['categoria'] === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Modalidad</label>
                        <select name="modalidad" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            @foreach($modalidades as $modalidad)
                                <option value="{{ $modalidad }}" {{ $filters['modalidad'] === $modalidad ? 'selected' : '' }}>{{ $modalidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Desde</label>
                        <input type="date" name="desde" value="{{ $filters['desde'] }}" class="form-control form-control-solid" />
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label text-secondary">Hasta</label>
                        <input type="date" name="hasta" value="{{ $filters['hasta'] }}" class="form-control form-control-solid" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total ofertas" value="{{ number_format($summary['totalOffers'] ?? 0) }}" meta="Publicaciones activas y pasadas." icon="bi-briefcase-fill" colorClass="text-primary" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total postulaciones" value="{{ number_format($summary['totalPostulaciones'] ?? 0) }}" meta="Candidatos interesados." icon="bi-people-fill" colorClass="text-success" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Postulantes únicos" value="{{ number_format($summary['uniquePostulantes'] ?? 0) }}" meta="Candidatos distintos." icon="bi-person-lines-fill" colorClass="text-info" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Promedio por oferta" value="{{ number_format($summary['avgPostulaciones'] ?? 0, 1) }}" meta="Interés promedio por oferta." icon="bi-graph-up" colorClass="text-warning" />
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Postulaciones por mes</h3>
                <p>Comparativo mensual del interés de los candidatos.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="applicationsByMonthChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="insight-card h-100">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <p class="insight-title">Insight destacado</p>
                    <h4 class="insight-value">{{ number_format(data_get($topOffer, 'total_postulaciones', 0)) }} postulaciones</h4>
                </div>
                <span class="insight-badge">Oferta top</span>
            </div>
            <p class="insight-meta">La oferta con mayor número de postulaciones en el período analizado.</p>
            <div class="mb-3">
                <p class="text-uppercase text-secondary mb-1" style="font-size:0.76rem; letter-spacing:0.12em;">Oferta</p>
                <h5 class="mb-2">{{ data_get($topOffer, 'titulo_puesto', 'No disponible') }}</h5>
            </div>
            <div class="mb-3">
                <p class="text-uppercase text-secondary mb-1" style="font-size:0.76rem; letter-spacing:0.12em;">Empresa</p>
                <p class="mb-0" style="color:#475569; font-weight:600;">{{ data_get($topOffer, 'nombre_empresa', 'No disponible') }}</p>
            </div>
            <div>
                <p class="text-uppercase text-secondary mb-1" style="font-size:0.76rem; letter-spacing:0.12em;">Publicada</p>
                <p class="mb-0" style="color:#475569;">{{ data_get($latestOffer, 'fecha_publicacion_publica', 'N/A') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Top 10 ofertas por postulaciones</h3>
                <p>Ofertas con mayor tracción entre los candidatos.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="topOffersByApplicationsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Postulaciones por categoría</h3>
                <p>Distribución del interés por sector laboral.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="applicationsByCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Empresas con más postulantes</h3>
                <p>Empresas cuyas vacantes atraen mayor número de candidatos.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="companiesByApplicantsChart"></canvas>
            </div>
        </div>
    </div>
</div>
