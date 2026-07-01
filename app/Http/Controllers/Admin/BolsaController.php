<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminBolsaService;
use Illuminate\Http\Request;

class BolsaController extends Controller
{
    public function __construct(private AdminBolsaService $adminBolsaService)
    {
    }
    public function validacion()
    {
        $empresas = $this->adminBolsaService->listPendingValidations();

        return view('admin.validacion-formularios', compact('empresas'));
    }

    public function bolsaTrabajo()
    {
        $empresas = $this->adminBolsaService->listApprovedJobs();

        return view('admin.bolsa-trabajo', compact('empresas'));
    }

    public function rechazados()
    {
        $empresas = $this->adminBolsaService->listRejectedJobs();

        return view('admin.rechazados', compact('empresas'));
    }

    public function ver($id)
    {
        $empresa = $this->adminBolsaService->getValidationDetail($id);

        if (!$empresa) {
            return redirect('admin/validacion-formularios')->with('error', 'Oferta no encontrada o ya fue procesada.');
        }

        $empresa = $this->adminBolsaService->normalizeValidationDocumentPath($empresa);

        return view('admin.ver', compact('empresa'));
    }

    public function eliminarDocumento($id)
    {
        $this->adminBolsaService->deleteApprovalDocument($id);

        return back();
    }

    public function eliminarPublicacion($id)
    {
        $this->adminBolsaService->deactivatePublication($id);

        return back()->with('success', 'La publicación se ha desactivado y movido al registro correctamente.');
    }

    /**
     * Listar publicaciones desactivadas en el panel admin
     */
    public function desactivados()
    {
        $empresas = $this->adminBolsaService->listDeactivatedPublications();

        return view('admin.publicaciones-desactivadas', compact('empresas'));
    }

    /**
     * Ver detalles de una publicación desactivada
     */
    public function verDesactivada($id)
    {
        $empresa = $this->adminBolsaService->getDeactivatedPublication($id);

        if (!$empresa) {
            return redirect('admin/publicaciones-desactivadas')->with('error', 'Publicación no encontrada.');
        }

        return view('admin.ver', compact('empresa'));
    }

    /**
     * Restaurar una publicación desactivada
     */
    public function restaurarPublicacion($id)
    {
        $this->adminBolsaService->restorePublication($id);

        return back()->with('success', 'Registro restaurado correctamente');
    }
}
