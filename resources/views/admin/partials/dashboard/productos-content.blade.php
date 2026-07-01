<script id="dashboardStateData" type="application/json">@json([ 'charts' => $charts ])</script>

<div class="dashboard-header-card mb-4 p-4 p-xl-5">
    <div class="d-flex flex-column flex-xl-row justify-content-between gap-4 align-items-start align-items-xl-center">
        <div class="dashboard-header-copy">
            <p class="dashboard-eyebrow">Dashboard Ejecutivo</p>
            <h2 class="dashboard-heading">Publicidad de Productos</h2>
            <p class="dashboard-description mb-0">Visión estratégica para analizar rendimiento, publicaciones y comportamiento comercial de los productos publicitarios.</p>
        </div>
        <div class="dashboard-header-controls p-4">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <h5 class="mb-1">Filtros activos</h5>
                    <p class="text-muted small mb-0">Ajusta el rango de fechas, empresa, categoría y estado para actualizar todas las tarjetas y gráficos sin recargar.</p>
                </div>
                <button id="dashboardResetFilters" type="button" class="btn btn-sm btn-outline-secondary">Limpiar</button>
            </div>
            <form id="dashboardFiltersForm" method="GET" action="{{ url('admin/indicadores-productos') }}">
                <div class="row gx-3 gy-3">
                    <div class="col-12 col-xl-5">
                        <label class="form-label">Empresa anunciante</label>
                        <input type="text" name="empresa" value="{{ $filters['empresa'] ?? '' }}" class="form-control form-control-solid" placeholder="Buscar empresa o marca" />
                    </div>
                    <div class="col-6 col-xl-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select form-select-solid">
                            <option value="">Todos</option>
                            @foreach($states as $state)
                                <option value="{{ $state }}" {{ ($filters['estado'] ?? '') === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-xl-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select form-select-solid">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ ($filters['categoria'] ?? '') === $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-xl-3">
                        <label class="form-label">Desde</label>
                        <input type="date" name="desde" value="{{ $filters['desde'] ?? '' }}" class="form-control form-control-solid" />
                    </div>
                    <div class="col-6 col-xl-3">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="hasta" value="{{ $filters['hasta'] ?? '' }}" class="form-control form-control-solid" />
                    </div>
                    <div class="col-12 col-xl-6 text-xl-end">
                        <button type="submit" class="btn btn-primary btn-lg">Aplicar filtros</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Total de publicaciones" value="{{ number_format($summary['totalProducts'] ?? 0) }}" meta="Total de productos publicados." icon="bi-bar-chart-fill" colorClass="text-primary" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Aprobadas" value="{{ number_format($summary['approvedProducts'] ?? 0) }}" meta="Productos aprobados." icon="bi-check2-circle" colorClass="text-success" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Rechazadas" value="{{ number_format($summary['rejectedProducts'] ?? 0) }}" meta="Productos rechazados." icon="bi-x-circle" colorClass="text-danger" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Eliminadas" value="{{ number_format($summary['deletedProducts'] ?? 0) }}" meta="Productos desactivados." icon="bi-trash-fill" colorClass="text-secondary" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Publicaciones destacadas" value="{{ number_format($summary['featuredProducts'] ?? 0) }}" meta="Publicaciones con mayor visibilidad." icon="bi-star-fill" colorClass="text-warning" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Empresas anunciantes" value="{{ number_format($summary['totalCompanies'] ?? 0) }}" meta="Empresas activas en productos." icon="bi-building" colorClass="text-info" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Categorías con publicaciones" value="{{ number_format($summary['categoriesWithPublications'] ?? 0) }}" meta="Categorías representadas en el período." icon="bi-tags-fill" colorClass="text-purple" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Visualizaciones" value="{{ number_format($summary['totalViews'] ?? 0) }}" meta="Total de visualizaciones estimadas." icon="bi-eye-fill" colorClass="text-success" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Clics" value="{{ number_format($summary['totalClicks'] ?? 0) }}" meta="Interacciones de clics en productos." icon="bi-mouse-fill" colorClass="text-primary" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Contactos generados" value="{{ number_format($summary['totalContacts'] ?? 0) }}" meta="Consultas y contactos recibidos." icon="bi-telephone-fill" colorClass="text-info" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Creadas en el período" value="{{ number_format($summary['createdDuringPeriod'] ?? 0) }}" meta="Publicaciones registradas en el rango seleccionado." icon="bi-calendar-fill" colorClass="text-primary" />
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <x-admin.dashboard-indicator-card title="Próximas a vencer" value="{{ number_format($summary['expiringProducts'] ?? 0) }}" meta="Publicaciones con vencimiento cercano." icon="bi-exclamation-triangle-fill" colorClass="text-danger" />
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-7">
        <x-admin.dashboard-chart-card id="productsByMonthChart" title="Evolución mensual de publicaciones" description="Publicaciones activas en el período seleccionado." />
    </div>
    <div class="col-12 col-xl-5">
        <div class="row g-4">
            <div class="col-12">
                <x-admin.dashboard-chart-card id="viewsByMonthChart" title="Evolución de visualizaciones" description="Tendencia mensual de interacción." />
            </div>
            <div class="col-12">
                <div class="chart-card h-100">
                    <div class="chart-header">
                        <h3>Distribución por estado</h3>
                        <p>Comparación proporcional de estados de publicaciones.</p>
                    </div>
                    <div class="chart-container" style="min-height: 340px;">
                        <canvas id="stateDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Publicaciones por categoría</h3>
                <p>Comparativa de volumen por categoría de producto.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Ranking de empresas</h3>
                <p>Empresas con mayor número de publicaciones.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="companyRankingChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h3>Categorías de mayor rendimiento</h3>
                <p>Identifica las categorías más relevantes por publicaciones.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="topCategoriesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="row g-4">
            <div class="col-12">
                <div class="info-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Ranking</p>
                            <h5 class="mb-1">Productos más vistos</h5>
                        </div>
                        <span class="badge badge-pill badge-primary-alt">Top 5</span>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topProductsByViews as $product)
                            <div class="list-group-item px-0 py-3 border-0">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold text-white">{{ $product['nombre_producto'] }}</div>
                                        <small class="text-muted">{{ $product['nombre_empresa'] }}</small>
                                    </div>
                                    <span class="badge badge-pill badge-surface">{{ number_format($product['metric']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="info-card h-100 p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Ranking</p>
                            <h5 class="mb-1">Productos con más clics</h5>
                        </div>
                        <span class="badge badge-pill badge-success-alt">Top 5</span>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topProductsByClicks as $product)
                            <div class="list-group-item px-0 py-3 border-0">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold text-white">{{ $product['nombre_producto'] }}</div>
                                        <small class="text-muted">{{ $product['nombre_empresa'] }}</small>
                                    </div>
                                    <span class="badge badge-pill badge-surface">{{ number_format($product['metric']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="info-card h-100 p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-muted small mb-1">Estrategia</p>
                    <h5 class="mb-1">Empresas más activas</h5>
                </div>
            </div>
            <div class="list-group list-group-flush">
                @foreach($topCompaniesRanking as $company)
                    <div class="list-group-item px-0 py-3 border-0">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold text-white">{{ $company['nombre_empresa'] }}</div>
                                <small class="text-muted">{{ $company['total'] }} publicaciones</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="info-card h-100 p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-muted small mb-1">Estrategia</p>
                    <h5 class="mb-1">Categorías más populares</h5>
                </div>
            </div>
            <div class="list-group list-group-flush">
                @foreach($topCategoriesRanking as $category)
                    <div class="list-group-item px-0 py-3 border-0">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold text-white">{{ $category['categoria'] }}</div>
                                <small class="text-muted">{{ $category['total'] }} publicaciones</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="info-card h-100 p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div>
                    <p class="text-uppercase text-muted small mb-1">Alertas</p>
                    <h5 class="mb-1">Publicaciones próximas a vencer</h5>
                </div>
            </div>
            <div class="list-group list-group-flush">
                @foreach($expiringPublications as $publication)
                    <div class="list-group-item px-0 py-3 border-0">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold text-white">{{ $publication['nombre_producto'] }}</div>
                                <small class="text-muted">{{ $publication['nombre_empresa'] }} · {{ $publication['categoria'] }}</small>
                            </div>
                            <span class="badge badge-pill badge-surface">{{ $publication['fecha_fin'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
