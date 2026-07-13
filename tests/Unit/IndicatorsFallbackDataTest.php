<?php

namespace Tests\Unit;

use App\Services\Indicators\BolsaDashboardService;
use App\Services\Indicators\ProductoDashboardService;
use App\Services\Indicators\ServicioDashboardService;
use Tests\TestCase;

class IndicatorsFallbackDataTest extends TestCase
{
    public function test_bolsa_dashboard_returns_realistic_monthly_fallback_data(): void
    {
        $service = new BolsaDashboardService();

        $data = $service->getDashboardData([]);

        $this->assertGreaterThan(0, $data['summary']['totalOffers']);
        $this->assertGreaterThan(0, $data['summary']['totalPostulaciones']);
        $this->assertNotEmpty($data['charts']['offersByMonth']);
        $this->assertNotEmpty($data['charts']['applicationsByMonth']);
    }

    public function test_productos_dashboard_returns_realistic_monthly_fallback_data(): void
    {
        $service = new ProductoDashboardService();

        $data = $service->getDashboardData([]);

        $this->assertGreaterThan(0, $data['summary']['totalProducts']);
        $this->assertGreaterThan(0, $data['summary']['totalViews']);
        $this->assertNotEmpty($data['charts']['viewsByMonth']);
    }

    public function test_servicios_dashboard_returns_realistic_monthly_fallback_data(): void
    {
        $service = new ServicioDashboardService();

        $data = $service->getDashboardData([]);

        $this->assertGreaterThan(0, $data['summary']['totalServices']);
        $this->assertGreaterThan(0, $data['summary']['totalViews']);
        $this->assertNotEmpty($data['charts']['servicesByMonth']);
        $this->assertNotEmpty($data['charts']['viewsByMonth']);
    }
}
