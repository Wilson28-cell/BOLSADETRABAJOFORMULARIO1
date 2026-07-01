<?php

namespace App\Http\Controllers\Analista;

use App\Http\Controllers\Controller;
use App\Services\Indicators\BolsaDashboardService;
use App\Services\Indicators\ProductoDashboardService;
use App\Services\Indicators\ServicioDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndicatorsController extends Controller
{
    public function bolsa(Request $request, BolsaDashboardService $dashboardService)
    {
        $filters = [
            'empresa' => trim($request->query('empresa', '')),
            'estado' => trim($request->query('estado', '')),
            'categoria' => trim($request->query('categoria', '')),
            'modalidad' => trim($request->query('modalidad', '')),
            'desde' => $request->query('desde'),
            'hasta' => $request->query('hasta'),
        ];

        $dashboardData = $dashboardService->getDashboardDataCached($filters);
        $viewData = array_merge($dashboardData, [
            'filters' => $filters,
            'topCompanies' => $dashboardData['charts']['topCompanies'] ?? [],
        ]);

        if ($request->ajax()) {
            return view('admin.partials.dashboard.bolsa-content', $viewData);
        }

        return view('admin.indicadores-bolsa', $viewData);
    }

    public function productos(Request $request, ProductoDashboardService $dashboardService)
    {
        $filters = [
            'empresa' => trim($request->query('empresa', '')),
            'estado' => trim($request->query('estado', '')),
            'categoria' => trim($request->query('categoria', '')),
            'desde' => $request->query('desde'),
            'hasta' => $request->query('hasta'),
        ];

        $dashboardData = $dashboardService->getDashboardDataCached($filters);
        $viewData = array_merge($dashboardData, [
            'filters' => $filters,
        ]);

        if ($request->ajax()) {
            return view('admin.partials.dashboard.productos-content', $viewData);
        }

        return view('admin.indicadores-productos', $viewData);
    }

    public function servicios(Request $request, ServicioDashboardService $dashboardService)
    {
        $filters = [
            'empresa' => trim($request->query('empresa', '')),
            'estado' => trim($request->query('estado', '')),
            'categoria' => trim($request->query('categoria', '')),
            'desde' => $request->query('desde'),
            'hasta' => $request->query('hasta'),
        ];

        $dashboardData = $dashboardService->getDashboardDataCached($filters);
        $viewData = array_merge($dashboardData, [
            'filters' => $filters,
        ]);

        if ($request->ajax()) {
            return view('admin.partials.dashboard.servicios-content', $viewData);
        }

        return view('admin.indicadores-servicios', $viewData);
    }
}
