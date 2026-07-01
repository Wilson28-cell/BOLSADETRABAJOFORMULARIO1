<?php

namespace App\Services\Indicators;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServicioDashboardService
{
    public function getDashboardData(array $filters): array
    {
        $today = now()->toDateString();

        $publishedServices = $this->getPublishedCount($filters);
        $activePublishedServices = $this->getActivePublishedCount($filters, $today);
        $expiredPublishedServices = $this->getExpiredPublishedCount($filters, $today);
        $approvedServices = $this->getApprovedServicesCount($filters);
        $rejectedServices = $this->getRejectedServicesCount($filters);
        $deletedServices = $this->getDeletedServicesCount($filters);
        $featuredServices = $this->getFeaturedServicesCount($filters);
        $companiesCount = $this->getCompaniesCount($filters);
        $categoriesWithPublications = $this->getCategoriesWithPublicationsCount($filters);
        $totalViews = $this->getMetricSum($filters, ['visualizaciones', 'visitas']);
        $totalClicks = $this->getMetricSum($filters, ['clics', 'clicks']);
        $totalContacts = $this->getMetricSum($filters, ['contactos', 'contacto']);
        $servicesCreatedDuringPeriod = $this->getServicesCreatedDuringPeriod($filters);
        $expiringServices = $this->getExpiringServicesCount($filters, $today);

        $charts = [
            'servicesByMonth' => $this->getMonthlyServices($filters),
            'viewsByMonth' => $this->getMonthlyMetric($filters, ['visualizaciones', 'visitas']),
            'publicationsByCategory' => $this->getPublicationsByCategory($filters),
            'publicationsByCompany' => $this->getPublicationsByCompany($filters),
            'stateDistribution' => $this->getStateDistribution($filters, $today),
            'topCompanies' => $this->getTopCompanies($filters),
            'topCategories' => $this->getTopCategories($filters),
        ];

        return [
            'summary' => [
                'totalServices' => $publishedServices,
                'activeServices' => $activePublishedServices,
                'expiredServices' => $expiredPublishedServices,
                'featuredServices' => $featuredServices,
                'totalCompanies' => $companiesCount,
                'categoriesWithPublications' => $categoriesWithPublications,
                'totalViews' => $totalViews,
                'totalClicks' => $totalClicks,
                'totalContacts' => $totalContacts,
                'createdDuringPeriod' => $servicesCreatedDuringPeriod,
                'expiringServices' => $expiringServices,
                'approvedServices' => $approvedServices,
                'rejectedServices' => $rejectedServices,
                'deletedServices' => $deletedServices,
            ],
            'charts' => $charts,
            'topServicesByViews' => $this->getTopServices($filters, ['visualizaciones', 'visitas'], 5),
            'topServicesByClicks' => $this->getTopServices($filters, ['clics', 'clicks'], 5),
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
        $cacheKey = 'indicadores.servicios.v2.' . md5(serialize($filters));

        $dashboardData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $this->getDashboardData($filters);
        });

        return $this->normalizeDashboardData($dashboardData);
    }

    private function normalizeDashboardData(array $data): array
    {
        $data['summary'] = array_replace([
            'totalServices' => 0,
            'activeServices' => 0,
            'approvedServices' => 0,
            'rejectedServices' => 0,
            'deletedServices' => 0,
            'expiredServices' => 0,
            'featuredServices' => 0,
            'totalCompanies' => 0,
            'categoriesWithPublications' => 0,
            'totalViews' => 0,
            'totalClicks' => 0,
            'totalContacts' => 0,
            'createdDuringPeriod' => 0,
            'expiringServices' => 0,
        ], $data['summary'] ?? []);

        $data['charts'] = array_replace([
            'servicesByMonth' => [],
            'viewsByMonth' => [],
            'publicationsByCategory' => [],
            'publicationsByCompany' => [],
            'stateDistribution' => [],
            'topCompanies' => [],
            'topCategories' => [],
        ], $data['charts'] ?? []);

        $data['topServicesByViews'] = $data['topServicesByViews'] ?? [];
        $data['topServicesByClicks'] = $data['topServicesByClicks'] ?? [];
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
        if (! Schema::hasTable('servicios_publicos')) {
            return [];
        }

        return DB::table('servicios_publicos')
            ->whereNotNull('categoria')
            ->where('categoria', '<>', '')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria')
            ->all();
    }

    private function getAvailableStates(): array
    {
        if (! Schema::hasTable('servicios_publicos')) {
            return [];
        }

        return DB::table('servicios_publicos')
            ->whereNotNull('estado')
            ->distinct()
            ->orderBy('estado')
            ->pluck('estado')
            ->all();
    }

    private function getPublishedCount(array $filters): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->count();
    }

    private function getActivePublishedCount(array $filters, string $today): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', $today)
            ->count();
    }

    private function getExpiredPublishedCount(array $filters, string $today): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '<', $today)
            ->count();
    }

    private function getApprovedServicesCount(array $filters): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->count();
    }

    private function getRejectedServicesCount(array $filters): int
    {
        return 0;
    }

    private function getDeletedServicesCount(array $filters): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Desactivado')
            ->count();
    }

    private function getFeaturedServicesCount(array $filters): int
    {
        return 0;
    }

    private function getCompaniesCount(array $filters): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->distinct()
            ->count('nombre_empresa');
    }

    private function getCategoriesWithPublicationsCount(array $filters): int
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereNotNull('categoria')
            ->distinct()
            ->count('categoria');
    }

    private function getMetricSum(array $filters, array $columnOptions): int
    {
        $query = $this->baseQuery($filters)->where('estado', 'Publicado');

        foreach ($columnOptions as $column) {
            if (Schema::hasColumn('servicios_publicos', $column)) {
                return $query->sum($column) ?? 0;
            }
        }

        return 0;
    }

    private function getServicesCreatedDuringPeriod(array $filters): int
    {
        $query = $this->baseQuery($filters);

        if (!empty($filters['desde'])) {
            $query->whereDate('fecha_publicacion', '>=', $filters['desde']);
        }
        if (!empty($filters['hasta'])) {
            $query->whereDate('fecha_publicacion', '<=', $filters['hasta']);
        }

        return $query->count();
    }

    private function getExpiringServicesCount(array $filters, string $today): int
    {
        $sevenDaysFromNow = now()->addDays(7)->toDateString();

        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '<=', $sevenDaysFromNow)
            ->whereDate('fecha_fin', '>=', $today)
            ->count();
    }

    private function getMonthlyServices(array $filters): array
    {
        $query = $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->select(
                DB::raw('DATE_FORMAT(fecha_publicacion, "%Y-%m-01") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month');

        return $query->get()->map(fn ($item) => [
            'month' => $item->month,
            'total' => $item->total,
        ])->toArray();
    }

    private function getMonthlyMetric(array $filters, array $columnOptions): array
    {
        $column = null;
        foreach ($columnOptions as $col) {
            if (Schema::hasColumn('servicios_publicos', $col)) {
                $column = $col;
                break;
            }
        }

        if (!$column) {
            return [];
        }

        $query = $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->select(
                DB::raw('DATE_FORMAT(fecha_publicacion, "%Y-%m-01") as month'),
                DB::raw("SUM($column) as total")
            )
            ->groupBy('month')
            ->orderBy('month');

        return $query->get()->map(fn ($item) => [
            'month' => $item->month,
            'total' => $item->total ?? 0,
        ])->toArray();
    }

    private function getPublicationsByCategory(array $filters): array
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereNotNull('categoria')
            ->select('categoria', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'categoria' => $item->categoria,
                'total' => $item->total,
            ])
            ->toArray();
    }

    private function getPublicationsByCompany(array $filters): array
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->select('nombre_empresa', DB::raw('COUNT(*) as total'))
            ->groupBy('nombre_empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($item) => [
                'nombre_empresa' => $item->nombre_empresa,
                'total' => $item->total,
            ])
            ->toArray();
    }

    private function getStateDistribution(array $filters, string $today): array
    {
        $states = [];

        $active = $this->getActivePublishedCount($filters, $today);
        if ($active > 0) {
            $states[] = ['state' => 'Activos', 'count' => $active];
        }

        $expired = $this->getExpiredPublishedCount($filters, $today);
        if ($expired > 0) {
            $states[] = ['state' => 'Vencidos', 'count' => $expired];
        }

        $rejected = $this->getRejectedServicesCount($filters);
        if ($rejected > 0) {
            $states[] = ['state' => 'Rechazados', 'count' => $rejected];
        }

        $deleted = $this->getDeletedServicesCount($filters);
        if ($deleted > 0) {
            $states[] = ['state' => 'Desactivados', 'count' => $deleted];
        }

        return $states;
    }

    private function getTopCompanies(array $filters): array
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->select('nombre_empresa', DB::raw('COUNT(*) as total'))
            ->groupBy('nombre_empresa')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'nombre_empresa' => $item->nombre_empresa,
                'total' => $item->total,
            ])
            ->toArray();
    }

    private function getTopCategories(array $filters): array
    {
        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereNotNull('categoria')
            ->select('categoria', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'categoria' => $item->categoria,
                'total' => $item->total,
            ])
            ->toArray();
    }

    private function getTopServices(array $filters, array $columnOptions, int $limit): array
    {
        $column = null;
        foreach ($columnOptions as $col) {
            if (Schema::hasColumn('servicios_publicos', $col)) {
                $column = $col;
                break;
            }
        }

        if (!$column) {
            return [];
        }

        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->select('nombre_servicio', 'nombre_empresa', DB::raw("$column as metric"))
            ->orderByDesc('metric')
            ->limit($limit)
            ->get()
            ->map(fn ($item) => [
                'nombre_servicio' => $item->nombre_servicio,
                'nombre_empresa' => $item->nombre_empresa,
                'metric' => $item->metric,
            ])
            ->toArray();
    }

    private function getExpiringPublications(array $filters, int $daysLimit): array
    {
        $expiringDate = now()->addDays($daysLimit)->toDateString();

        return $this->baseQuery($filters)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '<=', $expiringDate)
            ->whereDate('fecha_fin', '>=', now()->toDateString())
            ->select('nombre_servicio', 'nombre_empresa', 'fecha_fin')
            ->orderBy('fecha_fin')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function baseQuery(array $filters)
    {
        $query = DB::table('servicios_publicos');

        if (!empty($filters['empresa'])) {
            $query->where('nombre_empresa', 'like', '%' . $filters['empresa'] . '%');
        }

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['categoria'])) {
            $query->where('categoria', $filters['categoria']);
        }

        if (!empty($filters['desde'])) {
            $query->whereDate('fecha_publicacion', '>=', $filters['desde']);
        }

        if (!empty($filters['hasta'])) {
            $query->whereDate('fecha_publicacion', '<=', $filters['hasta']);
        }

        return $query;
    }
}
