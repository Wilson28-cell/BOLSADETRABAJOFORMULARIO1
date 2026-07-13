<script id="dashboardStateData" type="application/json">@json(['charts' => $charts])</script>

<div class="services-filter-card mb-4">
    <form id="dashboardFiltersForm" method="GET" action="{{ url('admin/indicadores-servicios') }}">
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
        <div class="services-kpi-card h-100">
            <div class="services-kpi-title">Total de visualizaciones</div>
            <div class="services-kpi-value">{{ number_format($summary['totalViews'] ?? 0) }}</div>
            <div class="services-kpi-note">Visualizaciones acumuladas de servicios publicados.</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="services-kpi-card h-100">
            <div class="services-kpi-title">Servicios publicados</div>
            <div class="services-kpi-value">{{ number_format($summary['totalServices'] ?? 0) }}</div>
            <div class="services-kpi-note">Total de servicios publicados en el período.</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="services-kpi-card h-100">
            <div class="services-kpi-title">Servicio más visto</div>
            <div class="services-kpi-value">{{ number_format($summary['serviceMostViewedValue'] ?? 0) }}</div>
            <div class="services-kpi-note">{{ $summary['serviceMostViewedName'] ?? 'No disponible' }}</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="services-kpi-card h-100">
            <div class="services-kpi-title">Empresa líder en visualizaciones</div>
            <div class="services-kpi-value">{{ number_format($summary['topCompanyByViewsValue'] ?? 0) }}</div>
            <div class="services-kpi-note">{{ $summary['topCompanyByViewsName'] ?? 'No disponible' }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="services-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Visualizaciones por mes</h3>
                <p>Tendencia mensual de visualizaciones de servicios.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="viewsByMonthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="services-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Top 10 servicios más vistos</h3>
                <p>Los servicios con mayor interés de la audiencia.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="topServicesByViewsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6">
        <div class="services-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Visualizaciones por categoría de servicio</h3>
                <p>Distribución del interés por categoría.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="viewsByCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="services-chart-card h-100">
            <div class="chart-header mb-4">
                <h3>Empresas con mayor número de visualizaciones de servicios</h3>
                <p>Empresas cuyos servicios reciben mayor atención de la audiencia.</p>
            </div>
            <div class="chart-container" style="min-height: 420px;">
                <canvas id="companiesByViewsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="services-insight-card h-100">
            <div class="services-insight-title">Insight</div>
            <div class="services-insight-name">{{ $summary['serviceMostViewedName'] ?? 'No disponible' }}</div>
            <div class="services-insight-meta">Empresa anunciante: <strong>{{ $summary['serviceMostViewedCompany'] ?? 'No disponible' }}</strong></div>
            <div class="services-insight-value">{{ number_format($summary['serviceMostViewedValue'] ?? 0) }}</div>
            <div class="services-insight-label">Servicio más visto del período.</div>
        </div>
    </div>
</div>
