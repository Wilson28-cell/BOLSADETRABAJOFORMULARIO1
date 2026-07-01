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
        $today = now()->toDateString();

        $publishedProducts = $this->getPublishedCount($filters);
        $activePublishedProducts = $this->getActivePublishedCount($filters, $today);
        $expiredPublishedProducts = $this->getExpiredPublishedCount($filters, $today);
        $pendingProducts = $this->getPendingProductsCount($filters);
        $approvedProducts = $this->getApprovedProductsCount($filters);
        $rejectedProducts = $this->getRejectedProductsCount($filters);
        $deletedProducts = $this->getDeletedProductsCount($filters);
        $featuredProducts = $this->getFeaturedProductsCount($filters);
        $companiesCount = $this->getCompaniesCount($filters);
        $categoriesWithPublications = $this->getCategoriesWithPublicationsCount($filters);
        $totalViews = $this->getMetricSum($filters, ['visualizaciones', 'visitas']);
        $totalClicks = $this->getMetricSum($filters, ['clics', 'clicks']);
        $totalContacts = $this->getMetricSum($filters, ['contactos', 'contacto']);
        $productsCreatedDuringPeriod = $this->getProductsCreatedDuringPeriod($filters);
        $expiringProducts = $this->getExpiringProductsCount($filters, $today);

        $charts = [
            'productsByMonth' => $this->getMonthlyProducts($filters),
            'viewsByMonth' => $this->getMonthlyMetric($filters, ['visualizaciones', 'visitas']),
            'publicationsByCategory' => $this->getPublicationsByCategory($filters),
            'publicationsByCompany' => $this->getPublicationsByCompany($filters),
            'stateDistribution' => $this->getStateDistribution($filters, $today),
            'topCompanies' => $this->getTopCompanies($filters),
            'topCategories' => $this->getTopCategories($filters),
        ];

        return [
            'summary' => [
                'totalProducts' => $publishedProducts,
                'activeProducts' => $activePublishedProducts,
                'pendingProducts' => $pendingProducts,
                'expiredProducts' => $expiredPublishedProducts,
                'featuredProducts' => $featuredProducts,
                'totalCompanies' => $companiesCount,
                'categoriesWithPublications' => $categoriesWithPublications,
                'totalViews' => $totalViews,
                'totalClicks' => $totalClicks,
                'totalContacts' => $totalContacts,
                'createdDuringPeriod' => $productsCreatedDuringPeriod,
                'expiringProducts' => $expiringProducts,
                'approvedProducts' => $approvedProducts,
                'rejectedProducts' => $rejectedProducts,
                'deletedProducts' => $deletedProducts,
            ],
            'charts' => $charts,
            'topProductsByViews' => $this->getTopProducts($filters, ['visualizaciones', 'visitas'], 5),
            'topProductsByClicks' => $this->getTopProducts($filters, ['clics', 'clicks'], 5),
            'topCompaniesRanking' => $charts['topCompanies'],
            'topCategoriesRanking' => $charts['topCategories'],
            'expiringPublications' => $this->getExpiringPublications($filters, 6),
            'categories' => $this->getAvailableCategories(),
            'states' => $this->getAvailableStates(),
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
        ], $data['summary'] ?? []);

        $data['charts'] = array_replace([
            'productsByMonth' => [],
            'viewsByMonth' => [],
            'publicationsByCategory' => [],
            'publicationsByCompany' => [],
            'stateDistribution' => [],
            'topCompanies' => [],
            'topCategories' => [],
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

        $column = $this->getMetricColumn($candidates);
        $baseQuery = DB::table('productos_publicos')
            ->where('estado', 'Publicado')
            ->when(! empty($filters['empresa']), fn ($query) => $query->where('nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%'))
            ->when(! empty($filters['categoria']), fn ($query) => $query->where('categoria', $filters['categoria']))
            ->when(! empty($filters['estado']) && $filters['estado'] !== 'todos', fn ($query) => $query->where('estado', $filters['estado']))
            ->when(! empty($filters['desde']), fn ($query) => $query->whereDate('fecha_publicacion', '>=', $filters['desde']))
            ->when(! empty($filters['hasta']), fn ($query) => $query->whereDate('fecha_publicacion', '<=', $filters['hasta']));

        if ($column !== null) {
            $rows = $baseQuery
                ->selectRaw("id_publico_producto, nombre_producto, nombre_empresa, {$column} as metric")
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

        $rows = $baseQuery
            ->orderByDesc('fecha_fin')
            ->limit($limit)
            ->get();

        return $rows->map(fn ($row) => [
            'nombre_producto' => $row->nombre_producto,
            'nombre_empresa' => $row->nombre_empresa,
            'metric' => 0,
        ])->all();
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
