<script id="dashboardStateData" type="application/json">@json(['charts' => $charts])</script>

<div class="dashboard-panel mb-4">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div>
            <h4 class="dashboard-title mb-2">Bolsa de Trabajo — Indicadores</h4>
            <p class="dashboard-subtitle mb-0">Indicadores clave para análisis ejecutivo de la bolsa laboral.</p>
        </div>
        <div class="dashboard-badges d-flex flex-wrap gap-2">
            <span class="badge badge-pill badge-surface">Dashboard Bolsa</span>
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
                    <p class="text-muted mb-0">Aplica filtros para ajustar los indicadores y gráficos.</p>
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
                        <input type="text" name="empresa" value="{{ $filters['empresa'] }}" class="form-control form-control-solid" placeholder="Buscar empresa" />
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
        <x-admin.dashboard-indicator-card title="Total ofertas" value="{{ number_format($summary['totalOffers'] ?? 0) }}" meta="Ofertas registradas." icon="bi-briefcase-fill" colorClass="text-primary" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Aprobadas" value="{{ number_format($summary['approvedOffers'] ?? 0) }}" meta="Ofertas aprobadas." icon="bi-check2-circle" colorClass="text-success" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Rechazadas" value="{{ number_format($summary['rejectedOffers'] ?? 0) }}" meta="Ofertas rechazadas." icon="bi-x-circle" colorClass="text-danger" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Ofertas vencidas" value="{{ number_format($summary['expiredOffers'] ?? 0) }}" meta="Ofertas vencidas." icon="bi-clock-history" colorClass="text-warning" />
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Empresas registradas" value="{{ number_format($summary['totalCompanies'] ?? 0) }}" meta="Empresas con publicaciones." icon="bi-building" colorClass="text-info" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total postulaciones" value="{{ number_format($summary['totalPostulaciones'] ?? 0) }}" meta="Postulaciones totales." icon="bi-people-fill" colorClass="text-purple" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Postulantes únicos" value="{{ number_format($summary['uniquePostulantes'] ?? 0) }}" meta="Candidatos distintos." icon="bi-person-lines-fill" colorClass="text-orange" />
    </div>
    <div class="col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Promedio por oferta" value="{{ number_format($summary['avgPostulaciones'] ?? 0, 1) }}" meta="Media de postulaciones por oferta." icon="bi-graph-up" colorClass="text-primary" />
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="info-card p-4 h-100">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <p class="text-uppercase text-muted small mb-1">Insight clave</p>
                    <h5 class="mb-1">Oferta con más postulaciones</h5>
                </div>
                <span class="badge badge-pill badge-primary-alt">Top</span>
            </div>
            <h4 class="mb-2">{{ $topOffer['titulo_puesto'] }}</h4>
            <p class="text-muted mb-2">{{ $topOffer['nombre_empresa'] }}</p>
            <p class="mb-0 text-muted">Postulaciones: <strong>{{ number_format($topOffer['total_postulaciones']) }}</strong></p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="info-card p-4 h-100">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <p class="text-uppercase text-muted small mb-1">Última oferta</p>
                    <h5 class="mb-1">Última oferta publicada</h5>
                </div>
                <span class="badge badge-pill badge-success-alt">Reciente</span>
            </div>
            <h4 class="mb-2">{{ $latestOffer['titulo_puesto'] }}</h4>
            <p class="text-muted mb-2">{{ $latestOffer['nombre_empresa'] }}</p>
            <p class="mb-0 text-muted">Publicada: <strong>{{ $latestOffer['fecha_publicacion_publica'] ?? 'N/A' }}</strong></p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Ofertas publicadas por mes</h3>
                <p>Volumen mensual de nuevas ofertas.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="offersByMonthChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Postulaciones por mes</h3>
                <p>Tendencia de postulaciones en los últimos 12 meses.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="applicationsByMonthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Distribución por estado</h3>
                <p>Activas, finalizadas y vencidas.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="stateDistributionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Aprobadas vs Rechazadas</h3>
                <p>Publicaciones revisadas por estado de validación.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="approvalDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Top empresas con más ofertas</h3>
                <p>Ranking de las 10 empresas más activas.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="topCompaniesChart"></canvas>
            </div>
        </div>
    </div>
</div>
