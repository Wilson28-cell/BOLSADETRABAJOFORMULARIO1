<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct(private AdminServiceService $adminServiceService)
    {
    }

    public function formulariosServicios()
    {
        $servicios = $this->adminServiceService->listPendingServices();

        return view('admin.formularios-servicios', compact('servicios'));
    }

    public function verServicio($id)
    {
        $servicio = $this->adminServiceService->getPendingService($id);

        if (!$servicio) {
            return redirect('/admin/formularios-servicios')->with('error', 'Servicio no encontrado');
        }

        return view('admin.ver-servicio', compact('servicio'));
    }

    public function verServicioRechazado($id)
    {
        $servicio = $this->adminServiceService->getRejectedService($id);

        if (!$servicio) {
            return back()->with('error', 'Servicio rechazado no encontrado.');
        }

        return view('admin.ver-servicio-rechazado', compact('servicio'));
    }

    public function aprobarServicio(Request $request, $id)
    {
        $request->validate([
            'documento_validacion_subgerencia' => 'required|mimes:pdf|max:10240',
        ]);

        $this->adminServiceService->approveService(
            $id,
            $request->file('documento_validacion_subgerencia'),
            Auth::id() ?? 1
        );

        return redirect('/admin/formularios-servicios')->with('success', 'Solicitud aprobada correctamente');
    }

    public function rechazarServicio(Request $request, $id)
    {
        $validated = $request->validate([
            'motivo' => 'required|string|min:5',
        ], [
            'motivo.required' => 'El motivo de rechazo es obligatorio.',
            'motivo.min' => 'El motivo de rechazo debe tener al menos 5 caracteres.',
        ]);

        $this->adminServiceService->rejectService(
            $id,
            $validated['motivo'],
            Auth::id() ?? 1
        );

        return redirect('/admin/formularios-servicios')->with('warning', 'Solicitud rechazada correctamente');
    }

    public function restaurarServicio($id)
    {
        $this->adminServiceService->restoreService($id);

        return back()->with('success', 'Registro restaurado correctamente');
    }

    public function verPublicacionServicio($id)
    {
        $servicio = $this->adminServiceService->getPublicService($id);

        if (!$servicio) {
            return back()->with('error', 'Publicación de servicio no encontrada.');
        }

        return view('admin.ver-publicacion-servicio', compact('servicio'));
    }

    public function desactivarPublicacionServicio($id)
    {
        $this->adminServiceService->deactivatePublishedService($id);

        return back()->with('success', 'Publicación desactivada correctamente.');
    }

    public function reactivarPublicacionServicio($id)
    {
        $this->adminServiceService->reactivatePublishedService($id);

        return back()->with('success', 'Publicación reactivada correctamente.');
    }

    public function borrarPublicacionServicio($id)
    {
        $this->adminServiceService->deletePublishedService($id);

        return back()->with('success', 'Publicación eliminada correctamente.');
    }

    public function publicacionesServicios(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'estado' => $request->input('estado'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
        ];

        $servicios = $this->adminServiceService->listPublicServices($filters);

        return view('admin.publicaciones-servicios', compact('servicios', 'filters'));
    }

    public function rechazados()
    {
        $servicios = $this->adminServiceService->listRejectedServices();

        return view('admin.servicios-rechazados', compact('servicios'));
    }
}
