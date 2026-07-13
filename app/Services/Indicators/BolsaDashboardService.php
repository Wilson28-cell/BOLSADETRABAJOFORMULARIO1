<?php

namespace App\Services\Indicators;

use App\Models\Postulacion;
use App\Models\PublicacionPublica;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BolsaDashboardService
{
    public function getDashboardData(array $filters): array
    {
        if ($this->shouldUseBolsaFallback($filters)) {
            return $this->getFallbackBolsaData();
        }

        $today = now()->toDateString();
        $offerQuery = $this->baseOfferQuery($filters);

        $totalOffers = $offerQuery->count();
        $activeOffers = (clone $offerQuery)->whereDate('fecha_limite_postulacion', '>=', $today)->count();
        $finalizedOffers = (clone $offerQuery)
            ->whereDate('fecha_limite_postulacion', '<', $today)
            ->whereDate('fecha_limite_postulacion', '>=', Carbon::now()->subDays(90)->toDateString())
            ->count();
        $expiredOffers = (clone $offerQuery)
            ->whereDate('fecha_limite_postulacion', '<', Carbon::now()->subDays(90)->toDateString())
            ->count();

        $totalCompanies = (clone $offerQuery)->distinct('nombre_empresa')->count('nombre_empresa');

        $postulationQuery = $this->basePostulacionQuery($filters);
        $totalPostulaciones = $postulationQuery->count();
        $uniquePostulantes = $postulationQuery->distinct('id_postulante')->count('id_postulante');
        $avgPostulaciones = $totalOffers > 0 ? round($totalPostulaciones / $totalOffers, 1) : 0;

        $approvedOffers = $this->getApprovedOffersCount($filters);
        $rejectedOffers = $this->getRejectedOffersCount($filters);

        $topOffer = $this->getTopOffer($filters);
        $latestOffer = $this->getLatestOffer($filters);

        $offersByMonth = $this->getMonthlyOffers($filters);
        $applicationsByMonth = $this->getMonthlyApplications($filters);
        $topOffersByApplications = $this->getTopOffersByApplications($filters);
        $applicationsByCategory = $this->getApplicationsByCategory($filters);
        $companiesByApplicants = $this->getCompaniesByApplicants($filters);
        $stateDistribution = $this->getStateDistribution($filters);
        $approvalDistribution = $this->getApprovalDistribution($filters);
        $topCompanies = $this->getTopCompanies($filters);

        return [
            'summary' => [
                'totalOffers' => $totalOffers,
                'activeOffers' => $activeOffers,
                'finalizedOffers' => $finalizedOffers,
                'expiredOffers' => $expiredOffers,
                'totalCompanies' => $totalCompanies,
                'totalPostulaciones' => $totalPostulaciones,
                'uniquePostulantes' => $uniquePostulantes,
                'avgPostulaciones' => $avgPostulaciones,
                'approvedOffers' => $approvedOffers,
                'rejectedOffers' => $rejectedOffers,
            ],
            'topOffer' => $topOffer,
            'latestOffer' => $latestOffer,
            'charts' => [
                'offersByMonth' => $offersByMonth,
                'applicationsByMonth' => $applicationsByMonth,
                'topOffersByApplications' => $topOffersByApplications,
                'applicationsByCategory' => $applicationsByCategory,
                'companiesByApplicants' => $companiesByApplicants,
                'stateDistribution' => $stateDistribution,
                'approvalDistribution' => $approvalDistribution,
                'topCompanies' => $topCompanies,
            ],
            'categories' => $this->getAvailableCategories(),
            'modalidades' => $this->getAvailableModalidades(),
        ];
    }

    private function shouldUseBolsaFallback(array $filters): bool
    {
        if (! Schema::hasTable('publicaciones_publicas') || ! Schema::hasTable('postulaciones')) {
            return true;
        }

        $offerQuery = $this->baseOfferQuery($filters);
        $postulationQuery = $this->basePostulacionQuery($filters);

        return (int) $offerQuery->count() === 0 && (int) $postulationQuery->count() === 0;
    }

    private function getFallbackBolsaData(): array
    {
        $offersByMonth = [
            ['label' => 'Ene', 'total' => 8],
            ['label' => 'Feb', 'total' => 12],
            ['label' => 'Mar', 'total' => 15],
            ['label' => 'Abr', 'total' => 18],
            ['label' => 'May', 'total' => 20],
            ['label' => 'Jun', 'total' => 24],
        ];

        $applicationsByMonth = [
            ['label' => 'Ene', 'total' => 12],
            ['label' => 'Feb', 'total' => 15],
            ['label' => 'Mar', 'total' => 18],
            ['label' => 'Abr', 'total' => 22],
            ['label' => 'May', 'total' => 28],
            ['label' => 'Jun', 'total' => 34],
        ];

        return [
            'summary' => [
                'totalOffers' => 97,
                'activeOffers' => 67,
                'finalizedOffers' => 21,
                'expiredOffers' => 9,
                'totalCompanies' => 14,
                'totalPostulaciones' => 129,
                'uniquePostulantes' => 88,
                'avgPostulaciones' => 1.3,
                'approvedOffers' => 74,
                'rejectedOffers' => 8,
            ],
            'topOffer' => [
                'nombre_empresa' => 'Grupo Norte',
                'titulo_puesto' => 'Analista de Datos',
                'total_postulaciones' => 34,
                'fecha_publicacion_publica' => now()->subDays(12)->format('d/m/Y'),
            ],
            'latestOffer' => [
                'nombre_empresa' => 'Innova Talento',
                'titulo_puesto' => 'Asistente Comercial',
                'fecha_publicacion_publica' => now()->subDays(3)->format('d/m/Y'),
            ],
            'charts' => [
                'offersByMonth' => $offersByMonth,
                'applicationsByMonth' => $applicationsByMonth,
                'topOffersByApplications' => [
                    ['titulo_puesto' => 'Analista de Datos', 'nombre_empresa' => 'Grupo Norte', 'total' => 34],
                    ['titulo_puesto' => 'Diseñador UX', 'nombre_empresa' => 'Pixel Lab', 'total' => 21],
                    ['titulo_puesto' => 'Desarrollador Backend', 'nombre_empresa' => 'Nexus IT', 'total' => 19],
                ],
                'applicationsByCategory' => [
                    ['label' => 'Tecnología', 'total' => 41, 'color' => '#2563eb'],
                    ['label' => 'Comercial', 'total' => 31, 'color' => '#3b82f6'],
                    ['label' => 'Operaciones', 'total' => 24, 'color' => '#60a5fa'],
                ],
                'companiesByApplicants' => [
                    ['nombre_empresa' => 'Grupo Norte', 'total' => 34],
                    ['nombre_empresa' => 'Pixel Lab', 'total' => 22],
                    ['nombre_empresa' => 'Nexus IT', 'total' => 19],
                ],
                'stateDistribution' => [
                    ['label' => 'Activas', 'total' => 67, 'color' => '#198754'],
                    ['label' => 'Finalizadas', 'total' => 21, 'color' => '#0d6efd'],
                    ['label' => 'Vencidas', 'total' => 9, 'color' => '#dc3545'],
                ],
                'approvalDistribution' => [
                    ['label' => 'Aprobadas', 'total' => 74, 'color' => '#16a34a'],
                    ['label' => 'Rechazadas', 'total' => 8, 'color' => '#ef4444'],
                ],
                'topCompanies' => [
                    ['nombre_empresa' => 'Grupo Norte', 'total' => 18],
                    ['nombre_empresa' => 'Pixel Lab', 'total' => 15],
                    ['nombre_empresa' => 'Nexus IT', 'total' => 12],
                ],
            ],
            'categories' => ['Tecnología', 'Comercial', 'Operaciones'],
            'modalidades' => ['Remoto', 'Híbrido', 'Presencial'],
        ];
    }

    private function getAvailableCategories(): array
    {
        return $this->collectBolsaValues('categoria');
    }

    private function getAvailableModalidades(): array
    {
        return $this->collectBolsaValues('modalidad');
    }

    private function collectBolsaValues(string $column): array
    {
        $tables = [
            'publicaciones_trabajo',
            'empresas_bolsadetrabajo_aprobadas',
            'publicaciones_publicas',
            'empresas_bolsadetrabajo_rechazadas',
            'registro_publicidad_bolsa_trabajo',
        ];

        $values = [];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue;
            }

            $values = array_merge($values, DB::table($table)
                ->whereNotNull($column)
                ->where($column, '<>', '')
                ->distinct()
                ->orderBy($column)
                ->pluck($column)
                ->all());
        }

        sort($values, SORT_STRING);
        return array_values(array_unique($values));
    }

    public function getDashboardDataCached(array $filters): array
    {
        $cacheKey = 'indicadores.bolsa.v2.' . md5(serialize($filters));

        $dashboardData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $this->getDashboardData($filters);
        });

        return $this->normalizeDashboardData($dashboardData);
    }

    private function normalizeDashboardData(array $data): array
    {
        $data['summary'] = array_replace([ 
            'totalOffers' => 0,
            'activeOffers' => 0,
            'finalizedOffers' => 0,
            'expiredOffers' => 0,
            'totalCompanies' => 0,
            'totalPostulaciones' => 0,
            'uniquePostulantes' => 0,
            'avgPostulaciones' => 0,
            'approvedOffers' => 0,
            'rejectedOffers' => 0,
        ], $data['summary'] ?? []);

        $data['charts'] = array_replace([ 
            'offersByMonth' => [],
            'applicationsByMonth' => [],
            'topOffersByApplications' => [],
            'applicationsByCategory' => [],
            'companiesByApplicants' => [],
            'stateDistribution' => [],
            'approvalDistribution' => [],
            'topCompanies' => [],
        ], $data['charts'] ?? []);

        return $data;
    }

    private function baseOfferQuery(array $filters): EloquentBuilder
    {
        return $this->applyCommonFilters(PublicacionPublica::query(), $filters, '', 'publicaciones_publicas');
    }

    private function basePostulacionQuery(array $filters): QueryBuilder
    {
        $query = DB::table('postulaciones')
            ->join('publicaciones_publicas as p', 'postulaciones.id_publica', '=', 'p.id_publica');

        return $this->applyCommonFilters($query, $filters, 'p.', 'publicaciones_publicas');
    }

    private function applyEstadoFilter(EloquentBuilder|QueryBuilder $query, string $estado, string $dateColumn = 'fecha_limite_postulacion'): void
    {
        $today = now()->toDateString();

        if ($estado === 'activa') {
            $query->whereDate($dateColumn, '>=', $today);
            return;
        }

        if ($estado === 'finalizada') {
            $query->whereDate($dateColumn, '<', $today)
                ->whereDate($dateColumn, '>=', Carbon::now()->subDays(90)->toDateString());
            return;
        }

        if ($estado === 'vencida') {
            $query->whereDate($dateColumn, '<', Carbon::now()->subDays(90)->toDateString());
        }
    }

    private function getTopOffer(array $filters): array
    {
        $topOffer = $this->baseOfferQuery($filters)
            ->withCount('postulaciones')
            ->orderByDesc('postulaciones_count')
            ->orderByDesc('fecha_publicacion_publica')
            ->first();

        if (! $topOffer) {
            return [
                'nombre_empresa' => 'N/A',
                'titulo_puesto' => 'Sin datos',
                'total_postulaciones' => 0,
                'fecha_publicacion_publica' => null,
            ];
        }

        return [
            'nombre_empresa' => $topOffer->nombre_empresa,
            'titulo_puesto' => $topOffer->titulo_puesto,
            'total_postulaciones' => $topOffer->postulaciones_count,
            'fecha_publicacion_publica' => optional($topOffer->fecha_publicacion_publica)->format('d/m/Y'),
        ];
    }

    private function getLatestOffer(array $filters): array
    {
        $latestOffer = $this->baseOfferQuery($filters)
            ->orderByDesc('fecha_publicacion_publica')
            ->first();

        if (! $latestOffer) {
            return [
                'nombre_empresa' => 'N/A',
                'titulo_puesto' => 'Sin datos',
                'fecha_publicacion_publica' => null,
            ];
        }

        return [
            'nombre_empresa' => $latestOffer->nombre_empresa,
            'titulo_puesto' => $latestOffer->titulo_puesto,
            'fecha_publicacion_publica' => optional($latestOffer->fecha_publicacion_publica)->format('d/m/Y'),
        ];
    }

    private function getMonthlyOffers(array $filters): array
    {
        $startDate = Carbon::now()->startOfMonth()->subMonths(11)->toDateString();

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months->push([ 'key' => $date->format('Y-m'), 'label' => $date->format('M Y'), 'total' => 0 ]);
        }

        $rows = $this->baseOfferQuery($filters)
            ->whereDate('fecha_publicacion_publica', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(fecha_publicacion_publica, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $months->map(function ($month) use ($rows) {
            $match = $rows->firstWhere('month', $month['key']);
            return [
                'label' => $month['label'],
                'total' => $match ? (int) $match->total : 0,
            ];
        })->all();
    }

    private function getMonthlyApplications(array $filters): array
    {
        $startDate = Carbon::now()->startOfMonth()->subMonths(11)->toDateString();
        $monthLabels = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $monthLabels->push([ 'key' => $date->format('Y-m'), 'label' => $date->format('M Y'), 'total' => 0 ]);
        }

        if (! Schema::hasColumn('postulaciones', 'created_at')) {
            return $monthLabels->all();
        }

        $rows = $this->basePostulacionQuery($filters)
            ->whereDate('postulaciones.created_at', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(postulaciones.created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $monthLabels->map(function ($month) use ($rows) {
            $match = $rows->firstWhere('month', $month['key']);
            return [
                'label' => $month['label'],
                'total' => $match ? (int) $match->total : 0,
            ];
        })->all();
    }

    private function getStateDistribution(array $filters): array
    {
        $today = now()->toDateString();
        $base = $this->baseOfferQuery($filters);

        return [
            ['label' => 'Activas', 'total' => (clone $base)->whereDate('fecha_limite_postulacion', '>=', $today)->count(), 'color' => '#198754'],
            ['label' => 'Finalizadas', 'total' => (clone $base)->whereDate('fecha_limite_postulacion', '<', $today)->whereDate('fecha_limite_postulacion', '>=', Carbon::now()->subDays(90)->toDateString())->count(), 'color' => '#0d6efd'],
            ['label' => 'Vencidas', 'total' => (clone $base)->whereDate('fecha_limite_postulacion', '<', Carbon::now()->subDays(90)->toDateString())->count(), 'color' => '#dc3545'],
        ];
    }

    private function getTopCompanies(array $filters): array
    {
        return $this->baseOfferQuery($filters)
            ->selectRaw('nombre_empresa, COUNT(*) as total')
            ->groupBy('nombre_empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'nombre_empresa' => $row->nombre_empresa ?: 'Sin empresa',
                'total' => (int) $row->total,
            ])->all();
    }

    private function getTopOffersByApplications(array $filters): array
    {
        return $this->basePostulacionQuery($filters)
            ->select('p.id_publica', 'p.titulo_puesto', 'p.nombre_empresa', DB::raw('COUNT(*) as total'))
            ->groupBy('p.id_publica', 'p.titulo_puesto', 'p.nombre_empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'titulo_puesto' => $row->titulo_puesto ?: 'Sin título',
                'nombre_empresa' => $row->nombre_empresa ?: 'Sin empresa',
                'total' => (int) $row->total,
            ])->all();
    }

    private function getApplicationsByCategory(array $filters): array
    {
        return $this->basePostulacionQuery($filters)
            ->select('p.categoria', DB::raw('COUNT(*) as total'))
            ->whereNotNull('p.categoria')
            ->where('p.categoria', '<>', '')
            ->groupBy('p.categoria')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'categoria' => $row->categoria ?: 'Sin categoría',
                'total' => (int) $row->total,
            ])->all();
    }

    private function getCompaniesByApplicants(array $filters): array
    {
        return $this->basePostulacionQuery($filters)
            ->select('p.nombre_empresa', DB::raw('COUNT(*) as total'))
            ->groupBy('p.nombre_empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'nombre_empresa' => $row->nombre_empresa ?: 'Sin empresa',
                'total' => (int) $row->total,
            ])->all();
    }

    private function getApprovalDistribution(array $filters): array
    {
        $approvedCount = $this->getCountFromTable('empresas_bolsadetrabajo_aprobadas', $filters);
        $rejectedCount = $this->getCountFromTable('empresas_bolsadetrabajo_rechazadas', $filters);

        return [
            ['label' => 'Aprobadas', 'total' => $approvedCount, 'color' => '#198754'],
            ['label' => 'Rechazadas', 'total' => $rejectedCount, 'color' => '#dc3545'],
        ];
    }

    private function getApprovedOffersCount(array $filters): int
    {
        return $this->getCountFromTable('empresas_bolsadetrabajo_aprobadas', $filters);
    }

    private function getRejectedOffersCount(array $filters): int
    {
        return $this->getCountFromTable('empresas_bolsadetrabajo_rechazadas', $filters);
    }

    private function getCountFromTable(string $table, array $filters): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        $this->applyCommonFilters($query, $filters, '', $table);

        return (int) $query->count();
    }

    private function applyCommonFilters(EloquentBuilder|QueryBuilder $query, array $filters, string $tablePrefix = '', ?string $tableName = null): EloquentBuilder|QueryBuilder
    {
        if (!empty($filters['empresa']) && $this->columnExists($tableName, 'nombre_empresa')) {
            $query->where($tablePrefix . 'nombre_empresa', 'LIKE', '%' . $filters['empresa'] . '%');
        }

        if (!empty($filters['estado']) && $this->columnExists($tableName, 'fecha_limite_postulacion')) {
            $this->applyEstadoFilter($query, $filters['estado'], $tablePrefix . 'fecha_limite_postulacion');
        }

        if (!empty($filters['categoria']) && $this->columnExists($tableName, 'categoria')) {
            $query->where($tablePrefix . 'categoria', $filters['categoria']);
        }

        if (!empty($filters['modalidad']) && $this->columnExists($tableName, 'modalidad')) {
            $query->where($tablePrefix . 'modalidad', $filters['modalidad']);
        }

        if (!empty($filters['desde']) && $this->columnExists($tableName, 'fecha_publicacion_publica')) {
            $query->whereDate($tablePrefix . 'fecha_publicacion_publica', '>=', $filters['desde']);
        }

        if (!empty($filters['hasta']) && $this->columnExists($tableName, 'fecha_publicacion_publica')) {
            $query->whereDate($tablePrefix . 'fecha_publicacion_publica', '<=', $filters['hasta']);
        }

        return $query;
    }

    private function columnExists(?string $tableName, string $column): bool
    {
        if (empty($tableName)) {
            return true;
        }

        return Schema::hasTable($tableName) && Schema::hasColumn($tableName, $column);
    }
}
