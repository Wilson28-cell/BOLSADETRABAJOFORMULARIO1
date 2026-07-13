<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndicatorsDashboardPartialTest extends TestCase
{
    public function test_bolsa_dashboard_partial_renders_with_date_filters(): void
    {
        $html = view('admin.partials.dashboard.bolsa-content', [
            'summary' => [
                'totalOffers' => 2,
                'totalPostulaciones' => 0,
                'uniquePostulantes' => 0,
                'avgPostulaciones' => 0,
            ],
            'topOffer' => [
                'nombre_empresa' => 'Test',
                'titulo_puesto' => 'Analista',
                'total_postulaciones' => 0,
                'fecha_publicacion_publica' => '01/01/2026',
            ],
            'latestOffer' => [
                'nombre_empresa' => 'Test',
                'titulo_puesto' => 'Analista',
                'fecha_publicacion_publica' => '02/01/2026',
            ],
            'charts' => [
                'offersByMonth' => [['label' => 'Jan 2026', 'total' => 1]],
                'applicationsByMonth' => [['label' => 'Jan 2026', 'total' => 0]],
                'topOffersByApplications' => [],
                'applicationsByCategory' => [],
                'companiesByApplicants' => [],
                'stateDistribution' => [],
                'approvalDistribution' => [],
                'topCompanies' => [],
            ],
            'filters' => ['empresa' => '', 'estado' => '', 'categoria' => '', 'modalidad' => '', 'desde' => '2026-06-28', 'hasta' => '2026-06-30'],
            'categories' => [],
            'modalidades' => [],
            'topCompanies' => [],
        ])->render();

        $this->assertStringContainsString('name="desde"', $html);
        $this->assertStringContainsString('name="hasta"', $html);
    }

    public function test_productos_dashboard_partial_renders_with_date_filters(): void
    {
        $html = view('admin.partials.dashboard.productos-content', [
            'summary' => [
                'totalProducts' => 1,
                'totalViews' => 10,
                'productMostViewedName' => 'Producto',
                'productMostViewedCompany' => 'Empresa',
                'productMostViewedValue' => 10,
                'topCompanyByViewsName' => 'Empresa',
                'topCompanyByViewsValue' => 10,
            ],
            'charts' => [
                'viewsByMonth' => [['label' => 'Jan 2026', 'total' => 10]],
                'topProductsByViewsChart' => [],
                'viewsByCategory' => [],
                'companiesByViews' => [],
            ],
            'topProductsByViews' => [],
            'expiringPublications' => [],
            'filters' => ['empresa' => '', 'categoria' => '', 'desde' => '2026-06-28', 'hasta' => '2026-06-30'],
            'categories' => [],
        ])->render();

        $this->assertStringContainsString('name="desde"', $html);
        $this->assertStringContainsString('name="hasta"', $html);
    }

    public function test_servicios_dashboard_partial_renders_with_date_filters(): void
    {
        $html = view('admin.partials.dashboard.servicios-content', [
            'summary' => [
                'totalServices' => 1,
                'activeServices' => 1,
                'expiredServices' => 0,
                'featuredServices' => 0,
                'totalCompanies' => 1,
                'categoriesWithPublications' => 1,
                'totalViews' => 10,
                'totalClicks' => 2,
                'totalContacts' => 1,
                'createdDuringPeriod' => 1,
                'expiringServices' => 0,
                'approvedServices' => 1,
                'rejectedServices' => 0,
                'deletedServices' => 0,
                'serviceMostViewedName' => 'Servicio',
                'serviceMostViewedCompany' => 'Empresa',
                'serviceMostViewedValue' => 10,
                'topCompanyByViewsName' => 'Empresa',
                'topCompanyByViewsValue' => 10,
            ],
            'charts' => [
                'servicesByMonth' => [['label' => 'Jan 2026', 'total' => 1]],
                'viewsByMonth' => [['label' => 'Jan 2026', 'total' => 10]],
                'publicationsByCategory' => [],
                'publicationsByCompany' => [],
                'stateDistribution' => [],
                'topCompanies' => [],
                'topCategories' => [],
            ],
            'topServicesByViews' => [],
            'expiringPublications' => [],
            'filters' => ['empresa' => '', 'categoria' => '', 'desde' => '2026-06-28', 'hasta' => '2026-06-30'],
            'categories' => [],
            'states' => [],
        ])->render();

        $this->assertStringContainsString('name="desde"', $html);
        $this->assertStringContainsString('name="hasta"', $html);
    }
}
