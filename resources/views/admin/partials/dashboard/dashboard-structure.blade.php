<div class="dashboard-header-card mb-4 p-4 p-xl-5">
    <div class="d-flex flex-column flex-xl-row justify-content-between gap-4 align-items-start align-items-xl-center">
        <div class="dashboard-header-copy">
            <p class="dashboard-eyebrow">Dashboard Ejecutivo</p>
            <h2 class="dashboard-heading">Indicadores de Bolsa de Trabajo</h2>
            <p class="dashboard-description mb-0">Visión estratégica para entender rápidamente la actividad de ofertas, postulaciones y desempeño del mercado laboral.</p>
        </div>
        <div class="dashboard-header-controls p-4">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <h5 class="mb-1">Filtros activos</h5>
                    <p class="text-muted small mb-0">Aplica criterios para analizar el dashboard desde varios ángulos.</p>
                </div>
                <button id="dashboardResetFilters" type="button" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            </div>
            <form id="dashboardFiltersForm" method="GET" action="{{ url('admin/indicadores-bolsa') }}">
                <div class="row gx-3 gy-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Empresa / palabra clave</label>
                        <input type="text" name="empresa" value="{{ $filters['empresa'] }}" class="form-control form-control-solid" placeholder="Buscar empresa o texto" />
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            <option value="activa" {{ $filters['estado'] === 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="finalizada" {{ $filters['estado'] === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            <option value="vencida" {{ $filters['estado'] === 'vencida' ? 'selected' : '' }}>Vencida</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            @foreach($categories as $categoria)
                                <option value="{{ $categoria }}" {{ $filters['categoria'] === $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Modalidad</label>
                        <select name="modalidad" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            @foreach($modalidades as $modalidad)
                                <option value="{{ $modalidad }}" {{ $filters['modalidad'] === $modalidad ? 'selected' : '' }}>{{ $modalidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Desde</label>
                        <input type="date" name="desde" value="{{ $filters['desde'] }}" class="form-control form-control-solid" />
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="hasta" value="{{ $filters['hasta'] }}" class="form-control form-control-solid" />
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-primary btn-lg">Aplicar filtros</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total ofertas" value="{{ number_format($summary['totalOffers']) }}" meta="Ofertas registradas." icon="bi-list-check" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Ofertas activas" value="{{ number_format($summary['activeOffers']) }}" meta="Ofertas con vigencia actual." icon="bi-check-circle" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total postulaciones" value="{{ number_format($summary['totalPostulaciones']) }}" meta="Postulaciones totales." icon="bi-person-lines-fill" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Postulantes únicos" value="{{ number_format($summary['uniquePostulantes']) }}" meta="Candidatos distintos." icon="bi-people-fill" />
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-7">
        <x-admin.dashboard-chart-card id="offersByMonthChart" title="Tendencia de ofertas" description="Evolución mensual de publicaciones." />
    </div>
    <div class="col-12 col-xl-5">
        <div class="row g-4">
            <div class="col-12">
                <x-admin.dashboard-chart-card id="applicationsByMonthChart" title="Tendencia de postulaciones" description="Aporte mensual de postulaciones." />
            </div>
            <div class="col-12">
                <div class="chart-card h-100">
                    <div class="chart-header">
                        <h3>Distribución por estado</h3>
                        <p>Porcentaje de ofertas activas, finalizadas y vencidas.</p>
                    </div>
                    <div class="chart-container" style="min-height: 340px;">
                        <canvas id="stateDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-12 col-xl-5">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Top empresas con más ofertas</h3>
                <p>Ranking de las empresas más activas en la bolsa.</p>
            </div>
            <div class="chart-container" style="min-height: 400px;">
                <canvas id="topCompaniesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-7">
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="info-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Insight clave</p>
                            <h5 class="mb-1">Oferta más postulada</h5>
                        </div>
                        <span class="badge badge-pill badge-primary-alt">Top</span>
                    </div>
                    <h4 class="mb-2">{{ $topOffer['titulo_puesto'] }}</h4>
                    <p class="text-muted mb-2">{{ $topOffer['nombre_empresa'] }}</p>
                    <p class="mb-0 text-muted">Postulaciones: <strong>{{ number_format($topOffer['total_postulaciones']) }}</strong></p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="info-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Actualizado</p>
                            <h5 class="mb-1">Última oferta</h5>
                        </div>
                        <span class="badge badge-pill badge-success-alt">Reciente</span>
                    </div>
                    <h4 class="mb-2">{{ $latestOffer['titulo_puesto'] }}</h4>
                    <p class="text-muted mb-2">{{ $latestOffer['nombre_empresa'] }}</p>
                    <p class="mb-0 text-muted">Fecha: <strong>{{ $latestOffer['fecha_publicacion_publica'] ?? 'N/A' }}</strong></p>
                </div>
            </div>
            <div class="col-12">
                <div class="info-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Ranking</p>
                            <h5 class="mb-1">Empresas más activas</h5>
                        </div>
                        <span class="badge badge-pill badge-surface">Top 10</span>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topCompanies as $company)
                            <div class="list-group-item px-0 py-3 border-0">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="rank-badge">{{ $loop->iteration }}</span>
                                        <div>
                                            <div class="fw-semibold text-white">{{ $company['nombre_empresa'] }}</div>
                                            <small class="text-muted">{{ $company['total'] }} ofertas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
