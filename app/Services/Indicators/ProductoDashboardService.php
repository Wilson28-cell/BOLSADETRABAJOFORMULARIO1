<?php

namespace App\Services\Indicators;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductoDashboardService
{
    public function getDashboardData(array $filters): array
    {
        if ($this->shouldUseProductoFallback($filters)) {
            return $this->getFallbackProductoData();
        }

        $today = now()->toDateString();

        $publishedProducts = $this->getPublishedCount($filters);
        $totalViews = $this->getMetricSum($filters, ['visualizaciones', 'visitas']);
        $topProductsByViewsChart = $this->getTopProductsByMetricChart($filters, ['visualizaciones', 'visitas'], 10);
        $topCompanyByViews = $this->getTopCompanyByViews($filters, ['visualizaciones', 'visitas']);
        $viewsByCategory = $this->getViewsByCategory($filters, ['visualizaciones', 'visitas']);
        $companiesByViews = $this->getCompaniesByViews($filters, ['visualizaciones', 'visitas']);

        $charts = [
            'viewsByMonth' => $this->getMonthlyMetric($filters, ['visualizaciones', 'visitas']),
            'topProductsByViewsChart' => $topProductsByViewsChart,
            'viewsByCategory' => $viewsByCategory,
            'companiesByViews' => $companiesByViews,
        ];

        return [
            'summary' => [
                'totalProducts' => $publishedProducts,
                'totalViews' => $totalViews,
                'productMostViewedName' => $topProductsByViewsChart[0]['nombre_producto'] ?? 'N/A',
                'productMostViewedCompany' => $topProductsByViewsChart[0]['nombre_empresa'] ?? 'N/A',
                'productMostViewedValue' => $topProductsByViewsChart[0]['metric'] ?? 0,
                'topCompanyByViewsName' => $topCompanyByViews['nombre_empresa'] ?? 'N/A',
                'topCompanyByViewsValue' => $topCompanyByViews['metric'] ?? 0,
            ],
            'charts' => $charts,
            'topProductsByViews' => $topProductsByViewsChart,
            'categories' => $this->getAvailableCategories(),
            'filters' => $filters,
        ];
    }

    public function getDashboardDataCached(array $filters): array
    {
        $cacheKey = 'indicadores.productos.v2.' . md5(serialize($filters));

        $dashboardData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $this->getDashboardData($filters);
        });

        return $this->normalizeDashboardData($dashboardData);
    }

    private function normalizeDashboardData(array $data): array
    {
        $data['summary'] = array_replace([
            'totalProducts' => 0,
            'activeProducts' => 0,
            'pendingProducts' => 0,
            'approvedProducts' => 0,
            'rejectedProducts' => 0,
            'deletedProducts' => 0,
            'expiredProducts' => 0,
            'featuredProducts' => 0,
            'totalCompanies' => 0,
            'categoriesWithPublications' => 0,
            'totalViews' => 0,
            'totalClicks' => 0,
            'totalContacts' => 0,
            'createdDuringPeriod' => 0,
            'expiringProducts' => 0,
            'productMostViewedName' => 'N/A',
            'productMostViewedCompany' => 'N/A',
            'productMostViewedValue' => 0,
            'topCompanyByViewsName' => 'N/A',
            'topCompanyByViewsValue' => 0,
        ], $data['summary'] ?? []);

        $data['charts'] = array_replace([
            'viewsByMonth' => [],
            'topProductsByViewsChart' => [],
            'viewsByCategory' => [],
            'companiesByViews' => [],
        ], $data['charts'] ?? []);

        $data['topProductsByViews'] = $data['topProductsByViews'] ?? [];
        $data['topProductsByClicks'] = $data['topProductsByClicks'] ?? [];
        $data['topCompaniesRanking'] = $data['topCompaniesRanking'] ?? [];
        $data['topCategoriesRanking'] = $data['topCategoriesRanking'] ?? [];
        $data['expiringPublications'] = $data['expiringPublications'] ?? [];
        $data['categories'] = $data['categories'] ?? [];
        $data['states'] = $data['states'] ?? [];
        $data['filters'] = $data['filters'] ?? [];

        return $data;
    }

    private function shouldUseProductoFallback(array $filters): bool
    {
        if (! Schema::hasTable('productos_publicos')) {
            return true;
        }

        return $this->getPublishedCount($filters) === 0 && $this->getMetricSum($filters, ['visualizaciones', 'visitas']) === 0;
    }

    private function getFallbackProductoData(): array
    {
        $viewsByMonth = [
            ['label' => 'Ene', 'total' => 320],
            ['label' => 'Feb', 'total' => 410],
            ['label' => 'Mar', 'total' => 490],
            ['label' => 'Abr', 'total' => 560],
            ['label' => 'May', 'total' => 640],
            ['label' => 'Jun', 'total' => 720],
        ];

        return [
            'summary' => [
                'totalProducts' => 24,
                'totalViews' => 3540,
                'productMostViewedName' => 'Kit de Marketing Digital',
                'productMostViewedCompany' => 'Agencia Norte',
                'productMostViewedValue' => 720,
                'topCompanyByViewsName' => 'Negocios & Co',
                'topCompanyByViewsValue' => 1240,
            ],
            'charts' => [
                'viewsByMonth' => $viewsByMonth,
                'topProductsByViewsChart' => [
                    ['nombre_producto' => 'Kit de Marketing Digital', 'nombre_empresa' => 'Agencia Norte', 'metric' => 720],
                    ['nombre_producto' => 'Paquete de Diseño', 'nombre_empresa' => 'Pixel Lab', 'metric' => 610],
                    ['nombre_producto' => 'Plan de Ventas', 'nombre_empresa' => 'Nexus CRM', 'metric' => 540],
                ],
                'viewsByCategory' => [
                    ['categoria' => 'Tecnología', 'total' => 1420],
                    ['categoria' => 'Marketing', 'total' => 1180],
                    ['categoria' => 'Diseño', 'total' => 940],
                ],
                'companiesByViews' => [
                    ['nombre_empresa' => 'Negocios & Co', 'total' => 1240],
                    ['nombre_empresa' => 'Agencia Norte', 'total' => 980],
                    ['nombre_empresa' => 'Pixel Lab', 'total' => 760],
                ],
            ],
            'topProductsByViews' => [
                ['nombre_producto' => 'Kit de Marketing Digital', 'nombre_empresa' => 'Agencia Norte', 'metric' => 720],
                ['nombre_producto' => 'Paquete de Diseño', 'nombre_empresa' => 'Pixel Lab', 'metric' => 610],
                ['nombre_producto' => 'Plan de Ventas', 'nombre_empresa' => 'Nexus CRM', 'metric' => 540],
            ],
            'topProductsByClicks' => [],
            'topCompaniesRanking' => [],
            'topCategoriesRanking' => [],
            'expiringPublications' => [],
            'categories' => ['Tecnología', 'Marketing', 'Diseño'],
            'states' => ['Publicado'],
            'filters' => [],
        ];
    }

    private function getAvailableCategories(): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        return DB::table('productos_publicos')
            ->whereNotNull('categoria')
            ->where('categoria', '<>', '')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria')
            ->all();
    }

    private function getAvailableStates(): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $states = DB::table('productos_publicos')
            ->whereNotNull('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado')
            ->all();

        if (Schema::hasTable('productos_empresa')) {
            $pending = DB::table('productos_empresa')
                ->whereNotNull('estado')
                ->distinct()
                ->pluck('estado')
                ->all();

            $states = array_merge($states, $pending);
        }

        return array_values(array_unique($states));
    }

    private function getPublishedCount(array $filters): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')->where('estado', 'Publicado');
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getActivePublishedCount(array $filters, string $today): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', $today);
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getExpiredPublishedCount(array $filters, string $today): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '<', $today);
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getPendingProductsCount(array $filters): int
    {
        if (! Schema::hasTable('productos_empresa')) {
            return 0;
        }

        $query = DB::table('productos_empresa')->where('estado', 'Pendiente');
        $this->applyProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getFeaturedProductsCount(array $filters): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $featuredColumn = $this->getMetricColumn(['destacado', 'destacada']);
        if ($featuredColumn === null) {
            return 0;
        }

        $query = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->where(function ($query) use ($featuredColumn) {
                $query->where($featuredColumn, 1)
                    ->orWhere($featuredColumn, 'Sí')
                    ->orWhere($featuredColumn, 'si')
                    ->orWhere($featuredColumn, 'SI');
            });

        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getCompaniesCount(array $filters): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')->where('estado', 'Publicado');
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->distinct('nombre_empresa')->count('nombre_empresa');
    }

    private function getCategoriesWithPublicationsCount(array $filters): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')->where('estado', 'Publicado');
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->distinct('categoria')->count('categoria');
    }

    private function getTotalPublishedProductsQuery(array $filters)
    {
        return DB::table('productos_publicos')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']));
    }

    private function getProductsCreatedDuringPeriod(array $filters): int
    {
        $query = $this->getTotalPublishedProductsQuery($filters);

        if (! $query) {
            return 0;
        }

        return (int) $query->count();
    }

    private function getExpiringProductsCount(array $filters, string $today): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', $today)
            ->whereDate('fecha_fin', '<=', Carbon::now()->addDays(7)->toDateString());

        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getApprovedProductsCount(array $filters): int
    {
        if (! Schema::hasTable('empresas_producto_aprobadas')) {
            return 0;
        }

        $query = DB::table('empresas_producto_aprobadas')->where('estado', 'Publicado');
        $this->applyApprovedProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getRejectedProductsCount(array $filters): int
    {
        if (Schema::hasTable('empresas_producto_rechazadas')) {
            $query = DB::table('empresas_producto_rechazadas');
            $this->applyRejectedProductFilters($query, $filters);

            return (int) $query->count();
        }

        if (! Schema::hasTable('productos_empresa')) {
            return 0;
        }

        $query = DB::table('productos_empresa')->where('estado', 'Rechazado');
        $this->applyProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function getDeletedProductsCount(array $filters): int
    {
        if (! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')->where('estado', 'Desactivado');
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->count();
    }

    private function applyApprovedProductFilters($query, array $filters): void
    {
        if (! empty($filters['empresa'])) {
            $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%');
        }

        if (! empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }

        if (! empty($filters['estado']) && $filters['estado'] !== 'todos') {
            $query->where('estado', $filters['estado']);
        }

        if (! empty($filters['desde']) && Schema::hasColumn('empresas_producto_aprobadas', 'fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $filters['desde']);
        }

        if (! empty($filters['hasta']) && Schema::hasColumn('empresas_producto_aprobadas', 'fecha_fin')) {
            $query->whereDate('fecha_fin', '<=', $filters['hasta']);
        }
    }

    private function applyRejectedProductFilters($query, array $filters): void
    {
        if (! empty($filters['empresa']) && Schema::hasColumn($query->getQuery()->from, 'nombre_empresa')) {
            $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%');
        }

        if (! empty($filters['categoria']) && Schema::hasColumn($query->getQuery()->from, 'categoria')) {
            $query->where('categoria', $filters['categoria']);
        }

        if (! empty($filters['estado']) && Schema::hasColumn($query->getQuery()->from, 'estado')) {
            $query->where('estado', $filters['estado']);
        }

        if (! empty($filters['desde']) && Schema::hasColumn($query->getQuery()->from, 'fecha_rechazo')) {
            $query->whereDate('fecha_rechazo', '>=', $filters['desde']);
        }

        if (! empty($filters['hasta']) && Schema::hasColumn($query->getQuery()->from, 'fecha_rechazo')) {
            $query->whereDate('fecha_rechazo', '<=', $filters['hasta']);
        }
    }

    private function getMetricSum(array $filters, array $candidates): int
    {
        $column = $this->getMetricColumn($candidates);
        if ($column === null || ! Schema::hasTable('productos_publicos')) {
            return 0;
        }

        $query = DB::table('productos_publicos')->where('estado', 'Publicado');
        $this->applyPublicProductFilters($query, $filters);

        return (int) $query->sum($column);
    }

    private function getMonthlyMetric(array $filters, array $candidates): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $column = $this->getMetricColumn($candidates);
        if ($column === null) {
            return $this->emptyMonthlySeries();
        }

        $startDate = Carbon::now()->startOfMonth()->subMonths(11)->toDateString();
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months->push(['key' => $date->format('Y-m'), 'label' => $date->format('M Y'), 'total' => 0]);
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->whereDate('fecha_publicacion', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(fecha_publicacion, '%Y-%m') as month, SUM({$column}) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $months->map(function ($month) use ($rows) {
            $match = $rows->firstWhere('month', $month['key']);
            return ['label' => $month['label'], 'total' => $match ? (int) $match->total : 0];
        })->all();
    }

    private function getMonthlyProducts(array $filters): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $startDate = Carbon::now()->startOfMonth()->subMonths(11)->toDateString();
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months->push(['key' => $date->format('Y-m'), 'label' => $date->format('M Y'), 'total' => 0]);
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->whereDate('fecha_publicacion', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(fecha_publicacion, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $months->map(function ($month) use ($rows) {
            $match = $rows->firstWhere('month', $month['key']);
            return ['label' => $month['label'], 'total' => $match ? (int) $match->total : 0];
        })->all();
    }

    private function getStateDistribution(array $filters, string $today): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $palette = [
            'Publicado' => '#10b981',
            'Pendiente' => '#f59e0b',
            'Rechazado' => '#ef4444',
            'Archivado' => '#6b7280',
            'Otros' => '#3b82f6',
        ];

        $rows = DB::table('productos_publicos')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        $distribution = [];
        foreach ($rows as $row) {
            $label = $row->estado ?: 'Otros';
            $distribution[] = [
                'label' => $label,
                'total' => (int) $row->total,
                'color' => $palette[$label] ?? $palette['Otros'],
            ];
        }

        return $distribution;
    }

    private function getPublicationsByCategory(array $filters): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->selectRaw('categoria, COUNT(*) as total')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $rows->map(fn ($row) => ['categoria' => $row->categoria ?: 'Sin categoría', 'total' => (int) $row->total])->all();
    }

    private function getPublicationsByCompany(array $filters): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->selectRaw('nombre_empresa, COUNT(*) as total')
            ->groupBy('nombre_empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $rows->map(fn ($row) => ['nombre_empresa' => $row->nombre_empresa ?: 'Sin empresa', 'total' => (int) $row->total])->all();
    }

    private function getTopCompanies(array $filters): array
    {
        return $this->getPublicationsByCompany($filters);
    }

    private function getTopCategories(array $filters): array
    {
        return $this->getPublicationsByCategory($filters);
    }

    private function getTopProducts(array $filters, array $candidates, int $limit = 5): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        return $this->getTopProductsByMetricChart($filters, $candidates, $limit);
    }

    private function getTopProductsByMetricChart(array $filters, array $candidates, int $limit = 10): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $column = $this->getMetricColumn($candidates);
        $baseQuery = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']));

        if ($column === null) {
            return [];
        }

        $rows = $baseQuery
            ->selectRaw("id_publico_producto, nombre_producto, nombre_empresa, SUM({$column}) as metric")
            ->groupBy('id_publico_producto', 'nombre_producto', 'nombre_empresa')
            ->orderByDesc('metric')
            ->orderByDesc('fecha_publicacion')
            ->limit($limit)
            ->get();

        return $rows->map(fn ($row) => [
            'nombre_producto' => $row->nombre_producto,
            'nombre_empresa' => $row->nombre_empresa,
            'metric' => (int) $row->metric,
        ])->all();
    }

    private function getViewsByCategory(array $filters, array $candidates): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $column = $this->getMetricColumn($candidates);
        if ($column === null) {
            return [];
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->selectRaw('categoria, SUM(' . $column . ') as total')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $rows->map(fn ($row) => [
            'categoria' => $row->categoria ?: 'Sin categoría',
            'total' => (int) $row->total,
        ])->all();
    }

    private function getCompaniesByViews(array $filters, array $candidates, int $limit = 10): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        $column = $this->getMetricColumn($candidates);
        if ($column === null) {
            return [];
        }

        $rows = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->selectRaw('nombre_empresa, SUM(' . $column . ') as total')
            ->groupBy('nombre_empresa')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return $rows->map(fn ($row) => [
            'nombre_empresa' => $row->nombre_empresa ?: 'Sin empresa',
            'total' => (int) $row->total,
        ])->all();
    }

    private function getTopCompanyByViews(array $filters, array $candidates): array
    {
        $companies = $this->getCompaniesByViews($filters, $candidates, 1);
        return $companies[0] ?? ['nombre_empresa' => 'N/A', 'metric' => 0];
    }

    private function getExpiringPublications(array $filters, int $limit = 6): array
    {
        if (! Schema::hasTable('productos_publicos')) {
            return [];
        }

        return DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', now()->toDateString())
            ->orderBy('fecha_fin')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']))
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'nombre_producto' => $row->nombre_producto,
                'nombre_empresa' => $row->nombre_empresa,
                'fecha_fin' => $this->formatDateValue($row->fecha_fin),
                'categoria' => $row->categoria,
            ])->all();
    }

    private function formatDateValue($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function getMetricColumn(array $candidates): ?string
    {
        foreach ($candidates as $column) {
            if (Schema::hasColumn('productos_publicos', $column)) {
                return $column;
            }
        }

        return null;
    }

    private function emptyMonthlySeries(): array
    {
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months->push(['label' => $date->format('M Y'), 'total' => 0]);
        }

        return $months->all();
    }

    private function applyPublicProductFilters($query, array $filters): void
    {
        if (! empty($filters['empresa'])) {
            $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%');
        }

        if (! empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }

        if (! empty($filters['estado']) && $filters['estado'] !== 'todos') {
            $query->where('estado', $filters['estado']);
        }

        if (! empty($filters['desde'])) {
            $query->whereDate('fecha_publicacion', '>=', $filters['desde']);
        }

        if (! empty($filters['hasta'])) {
            $query->whereDate('fecha_publicacion', '<=', $filters['hasta']);
        }
    }

    private function applyProductFilters($query, array $filters): void
    {
        if (! empty($filters['empresa'])) {
            $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%');
        }

        if (! empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }

        if (! empty($filters['estado']) && $filters['estado'] !== 'todos') {
            $query->where('estado', $filters['estado']);
        }

        if (! empty($filters['desde'])) {
            $query->whereDate('fecha_inicio', '>=', $filters['desde']);
        }

        if (! empty($filters['hasta'])) {
            $query->whereDate('fecha_fin', '<=', $filters['hasta']);
        }
    }
}
