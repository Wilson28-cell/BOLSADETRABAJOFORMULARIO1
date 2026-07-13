<script id="dashboardStateData" type="application/json">@json(['charts' => $charts])</script>

<div class="products-filter-card mb-4">
    <form id="dashboardFiltersForm" method="GET" action="{{ url('admin/indicadores-productos') }}">
        <div class="row gx-3 gy-3 align-items-end">
            <div class="col-12 col-lg-4">
                <label class="form-label">Empresa anunciante</label>
                <input type="text" name="empresa" value="{{ $filters['empresa'] ?? '' }}" class="form-control form-control-solid" placeholder="Buscar anunciante" />
            </div>
            <div class="col-12 col-lg-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-select form-select-solid">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ ($filters['categoria'] ?? '') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-lg-2">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" value="{{ !empty(data_get($filters, 'desde', '')) ? \Illuminate\Support\Carbon::parse(data_get($filters, 'desde', ''))->format('Y-m-d') : '' }}" class="form-control form-control-solid" autocomplete="off" />
            </div>
            <div class="col-6 col-lg-2">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" value="{{ !empty(data_get($filters, 'hasta', '')) ? \Illuminate\Support\Carbon::parse(data_get($filters, 'hasta', ''))->format('Y-m-d') : '' }}" class="form-control form-control-solid" autocomplete="off" />
            </div>
            <div class="col-12 col-lg-1 text-lg-end">
                <button type="submit" class="btn btn-primary w-100">Aplicar</button>
            </div>
        </div>
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="products-kpi-card h-100">
            <div class="products-kpi-title">Visualizaciones</div>
            <div class="products-kpi-value">{{ number_format($summary['totalViews'] ?? 0) }}</div>
            <div class="products-kpi-note">Total de visualizaciones acumuladas en el período.</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="products-kpi-card h-100">
            <div class="products-kpi-title">Productos publicados</div>
            <div class="products-kpi-value">{{ number_format($summary['totalProducts'] ?? 0) }}</div>
            <div class="products-kpi-note">Total de productos publicados activos.</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="products-kpi-card h-100">
            <div class="products-kpi-title">Producto más visto</div>
            <div class="products-kpi-value">{{ number_format($summary['productMostViewedValue'] ?? 0) }}</div>
            <div class="products-kpi-note">{{ $summary['productMostViewedName'] ?? 'No disponible' }}</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="products-kpi-card h-100">
            <div class="products-kpi-title">Empresa líder en visualizaciones</div>
            <div class="products-kpi-value">{{ number_format($summary['topCompanyByViewsValue'] ?? 0) }}</div>
            <div class="products-kpi-note">{{ $summary['topCompanyByViewsName'] ?? 'No disponible' }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="products-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Visualizaciones por mes</h3>
                <p>Tendencia mensual de visualizaciones para productos publicitarios.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="viewsByMonthChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="products-insight-card h-100">
            <div class="products-insight-title">Insight</div>
            <div class="products-insight-name">{{ $summary['productMostViewedName'] ?? 'Producto no disponible' }}</div>
            <div class="products-insight-meta">Empresa anunciante: <strong>{{ $summary['productMostViewedCompany'] ?? 'N/A' }}</strong></div>
            <div class="products-insight-value">{{ number_format($summary['productMostViewedValue'] ?? 0) }}</div>
            <div class="products-insight-label">Visualizaciones del producto más visto en el período.</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="products-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Top 10 productos más vistos</h3>
                <p>Productos que concentran el mayor volumen de visualizaciones.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="topProductsByViewsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="products-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Visualizaciones por categoría</h3>
                <p>Distribución del interés por categoría de producto.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="viewsByCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="products-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Empresas con mayor número de visualizaciones</h3>
                <p>Empresas cuyos productos reciben la mayor atención de audiencia.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="companiesByViewsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="products-insight-card h-100">
            <div class="products-insight-title">Producto más visto</div>
            <div class="products-insight-name">{{ $summary['productMostViewedName'] ?? 'Sin datos' }}</div>
            <div class="products-insight-meta mb-4">Empresa anunciante: <strong>{{ $summary['productMostViewedCompany'] ?? 'No disponible' }}</strong></div>
            <div class="products-insight-value">{{ number_format($summary['productMostViewedValue'] ?? 0) }}</div>
            <div class="products-insight-label">Visualizaciones totales del producto principal.</div>
        </div>
    </div>
</div>

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
