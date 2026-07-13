<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBolsaTrabajoRequest;
use App\Http\Requests\StorePostulacionRequest;
use App\UseCases\Public\BolsaTrabajoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BolsaTrabajoController extends Controller
{
    public function __construct(private BolsaTrabajoService $bolsaTrabajoService)
    {
    }

    public function guardar(StoreBolsaTrabajoRequest $request)
    {
        try {
            $this->bolsaTrabajoService->createPublication(
                $request->validated(),
                $request->file('documento_validacion'),
                $request->file('imagen_trabajo')
            );
        } catch (\Illuminate\Database\QueryException $exception) {
            $code = $exception->errorInfo[1] ?? null;
            if ($code === 1062) {
                return back()->withInput()->withErrors(['ruc' => 'El RUC ya está registrado.'])->with('error', 'Error al guardar: datos duplicados.');
            }

            return back()->withInput()->withErrors(['database' => 'Error al guardar la empresa.'])->with('error', 'Error al guardar la publicación.');
        }

        return back()->with('success', 'Publicación registrada correctamente. La aprobación puede demorar 1-2 días hábiles.');
    }

    public function inicio(Request $request)
    {
        $ofertas = $this->bolsaTrabajoService->listPublicJobs($request->only(['buscar', 'modalidad', 'categoria']));
        $categorias = $this->bolsaTrabajoService->getPublicCategories();

        return view('bolsa-trabajo.index', compact('ofertas', 'categorias'));
    }

    public function publicidadBolsaTrabajo(Request $request)
    {
        $filters = $request->only(['buscar', 'modalidad', 'categoria', 'salario_min', 'estado']);
        $filters['estado'] = in_array($filters['estado'] ?? null, ['publicadas', 'vencidas'], true)
            ? $filters['estado']
            : 'publicadas';

        $ofertas = $this->bolsaTrabajoService->listPublicJobs($filters);
        $categorias = $this->bolsaTrabajoService->getPublicCategories();

        return view('publicidad.bolsa-trabajo', compact('ofertas', 'filters', 'categorias'));
    }

    public function detalle(int $id)
    {
        $oferta = $this->bolsaTrabajoService->getPublicJobById($id);

        return view('bolsa-trabajo.detalle', compact('oferta'));
    }

    public function postular(int $id)
    {
        $oferta = $this->bolsaTrabajoService->getPublicJobById($id);

        return view('bolsa-trabajo.postular', compact('oferta'));
    }

    public function guardarPostulacion(StorePostulacionRequest $request, int $id)
    {
        $data = $request->validated();
        $data['password'] = Str::random(10);

        $this->bolsaTrabajoService->submitPostulacion($id, $data, $request->file('curriculum_pdf'));

        return redirect('/')->with('success', 'Postulación enviada correctamente');
    }

    public function detalleServicio(int $id)
    {
        $servicio = \Illuminate\Support\Facades\DB::table('servicios_publicos')
            ->select('servicios_publicos.*', 'id_publico_servicio as id')
            ->where('id_publico_servicio', $id)
            ->where('estado', 'Publicado')
            ->whereDate('fecha_fin', '>=', now()->format('Y-m-d'))
            ->firstOrFail();

        return view('publicidad.detalle-servicio', compact('servicio'));
    }
}
